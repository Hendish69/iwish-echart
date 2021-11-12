<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, View, Validator};
use App\Models\{User, FeedbackSubsection, FeedbackFaq, FeedbackIssue, FeedbackTopic, FeedbackPriority, FeedbackPart, FeedbackSection, FeedbackAttachment, FeedbackHistory};
use App\ApiResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendFeedbackNotification;
use App\Models\Api\CodEaip;

class CareController extends Controller
{
	public function summary(Request $request)
	{
		$menus = DB::table('cod_eaip')->where('level', 0)->orderBy('id')->get();
		$submenus = DB::table('cod_eaip')->where('level', 1)->orderby('seq')->get();
		$airports = DB::table('arpt')->where('ctry', 'ID')->orderBy('icao', 'ASC')->get();

		if ($request->query->has('year') && $request->query->has('month')) {
			$year = $request->query->get('year');
			$month = $request->query->get('month');

			if ($year == '' && $month == 'ALL') {
				$feeds = DB::table('feedback_issue')->select('section_id', 'subsection_id', 'airport_id')->get();
			} else if ($year != '' && $month == 'ALL') {
				$feeds = DB::table('feedback_issue')->select('section_id', 'subsection_id', 'airport_id')->whereYear('created_at', $request->query->get('year'))->get();
			} else {
				$feeds = DB::table('feedback_issue')->select('section_id', 'subsection_id', 'airport_id')->whereYear('created_at', $request->query->get('year'))->whereMonth('created_at', $request->query->get('month'))->get();
			}

		} else {
			$feeds = DB::table('feedback_issue')->select('section_id', 'subsection_id', 'airport_id')->get();
		}

		foreach ($menus as &$menu) {
			$menu->total = $feeds->where('section_id', $menu->id)->count();
			$detail = [];

			foreach ($submenus->where('parentid', $menu->id) as $submenu) {
				if ($submenu->id == 96) {
					$totalAirports = 0;
					foreach ($airports as &$airport) {
						$airport->total = $feeds->where('airport_id', $airport->arpt_ident)->where('subsection_id', $submenu->id)->count();
						$totalAirports = $totalAirports + $airport->total;
					}
					$submenu->total = $totalAirports;
					$submenu->airports = $airports;
					$submenu->has_airport = true;
					$detail[] = $submenu;
				} else {
					$submenu->total = $feeds->where('section_id', $menu->id)->where('subsection_id', $submenu->id)->count();
					$submenu->airports = [];
					$submenu->has_airport = false;
					$detail[] = $submenu;
				}
			}

			$menu->submenus = $detail;
		}

		$results = [
			'nondata' => ['label' => 'Non Data', 'total' => $feeds->whereNull('section_id')->whereNull('subsection_id')->count()],
			'data' => $menus,
		];

		return ApiResponse::success($results);
	}

	public function index(Request $request)
	{
		return View::make('pages.care.index');
	}

	public function ajaxIssueList(Request $request)
	{	
		if (Auth::user()->isAdmin()) {
			$issues = FeedbackIssue::orderBy('created_at', 'DESC')->get();
		} else {
			$issues = FeedbackIssue::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
		}

		return ApiResponse::success($issues);
	}

	public function ajaxAirportList(Request $request)
	{
		$results = [];

		foreach (DB::table('arpt')->where('ctry', 'ID')->orderBy('icao', 'ASC')->get() as $airport) {
			$results[] = [
				'value' => $airport->arpt_ident,
				'label' => $airport->icao.' - '.$airport->arpt_name.' - '.$airport->city_name,
			];
		}

		return ApiResponse::success($results);
	}

	public function ajaxTopicList(Request $request)
	{
		$topics = FeedbackTopic::all();

		return ApiResponse::success($topics);
	}

	public function ajaxPriorityList(Request $request)
	{
		$priorities = FeedbackPriority::all();

		return ApiResponse::success($priorities);
	}

	public function ajaxPartList(Request $request)
	{
		$parts = FeedbackPart::orderBy('sequence', 'asc')->get();

		return ApiResponse::success($parts);
	}

	public function ajaxSectionList(Request $request)
	{
		$menus = DB::table('cod_eaip')->where('level', 0)->orderby('id')->get();

		return ApiResponse::success($menus);
	}

	public function ajaxSubsectionList(Request $request, string $section)
	{
		$menus =DB::table('cod_eaip')->where('level', 1)->where('parentid', $section)
        ->orderby('seq')
        ->get();

        return ApiResponse::success($menus);
	}

	public function ajaxAttachmentList(Request $request, string $issue)
	{
		$attachments = FeedbackAttachment::where('issue_id', $issue)->get();	

		return ApiResponse::success($attachments);
	}

	public function ajaxHistoryList(Request $request, string $issue)
	{
		$histories = FeedbackHistory::where('issue_id', $issue)->orderBy('created_at', 'asc')->get();

		return ApiREsponse::success($histories);
	}

	public function ajaxRemoveAttachment(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => 'string|required',
		]);

		if ($validator->fails()) {
			return ApiResponse::fail($validator->errors());
		}

		$attachment = FeedbackAttachment::find($request->id);

		$attachment->forceDelete();

		return ApiResponse::success(null);
	}

	public function ajaxFaqCreate(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'required|string',
			'description' => 'required|string',
			'language' => 'required|string',
		]);

		if ($validator->fails()) {
			return ApiResponse::fail($validator->errors());
		}

		$faq = new FeedbackFaq();

		$faq->title = $request->title;
		$faq->description = $request->description;
		$faq->lang_id = $request->language;

		$faq->save();

		return ApiResponse::success($faq->fresh());
	}

	public function ajaxUploadAttachment(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'file' => 'file|required',
			'issue_id' => 'required|string',
		]);

		if ($validator->fails()) {
			return ApiResponse::fail($validator->errors());
		}

		$fa = new FeedbackAttachment();

		$fa->issue_id = $request->issue_id;
		$fa->attachment = $request->file->getClientOriginalName();

		$fa->save();

		$request->file->move(public_path('uploads'), $request->file->getClientOriginalName());

		return ApiResponse::success($fa->fresh()); 
	}

	public function ajaxFaqList(Request $request)
	{
		$faqs = FeedbackFaq::orderBy('sequence', 'asc')->get();

		return ApiResponse::success($faqs);
	}

	public function ajaxFaqUpdate(Request $request, string $faq)
	{
		$faq = FeedbackFaq::find($faq);

		if (null === $faq) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$validator = Validator::make($request->all(), [
			'title' => 'required|string',
			'description' => 'required|string',
		]);

		if ($validator->fails()) {
			return ApiResponse::fail($validator->errors());
		}

		$faq->title = $request->title;
		$faq->description = $request->description;

		$faq->save();

		return ApiResponse::success($faq->fresh());
	}

	public function ajaxCreateIssue(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'required|string',
			'description' => 'required|string',
			'topic_id' => 'required|string',
			'priority_id' => 'required|string',
		]);

		if ($validator->fails()) {
			return ApiResponse::fail($validator->errors());
		}

		try {
			$issue = new FeedbackIssue();

			$issue->title = $request->title;
			$issue->description = $request->description;
			$issue->topic_id = $request->topic_id;
			$issue->priority_id = $request->priority_id;
			$issue->part_id = 1;
			$issue->section_id = $request->section_id;
			$issue->subsection_id = $request->subsection_id;

			if ($request->has('airport_id')) {
				$issue->airport_id = $request->airport_id;
			}

			$issue->save();

			$log = new FeedbackHistory();

			$log->issue_id = $issue->id;
			$log->user_id = Auth::user()->id;
			$log->log = 'Feedback created by '.Auth::user()->name;

			$log->save();

			return ApiResponse::success($issue->fresh());
		} catch (Exception $e) {
			return ApiResponse::error($e->getMessage());
		}
	}

	public function ajaxUpdateIssue(Request $request, string $issue)
	{
		$issue = FeedbackIssue::find($issue);

		$all = $request->all();

		if ($request->has('data_confirmation_id')) {
			if ($request->data_confirmation_id == 1 || $request->data_confirmation_id == 2) {
				$all['progress_user_id'] = 6;

				$history = new FeedbackHistory();

				$history->issue_id = $issue->id;
				if ($request->data_confirmation_id == 1) {
					$history->log = Auth::user()->name.' confirmed this feedback as <strong>Data Problem</strong>';
				} else {
					$history->log = Auth::user()->name.' confirmed this feedback as <strong>Non-Data Problem</strong>';
				}

				$history->save();
			}
		}

		$issue->update($all);

		Notification::send(
			User::find($issue->user_id), 
			new SendFeedbackNotification([
				'title' => '',
				'descriptions' => 'Admin updated your feedback #'.$issue->no
			])
		);

		$changes = $issue->getChanges();
		$log = Auth::user()->name.' updated the feedback, detail:<br>';
		$updatelog = false;
		foreach ($changes as $field => $value) {	
			if ($field == 'title') {
				$updatelog = true;
				$log .= '- Title changed from: <strong>'.$issue->title.' > '.$value.'</strong><br>';
			}

			if ($field == 'topic_id') {	
				$value = FeedbackTopic::find($value);
				$updatelog = true;
				$log .= '- Topic changed from: <strong>'.$issue->topic->title.' > '.$value->title.'</strong><br>';
			}

			if ($field == 'priority_id') {
				$value = FeedbackPriority::find($value);
				$updatelog = true;
				$log .= '- Priority changed from: <strong>'.$issue->priority->name.' > '.$value->name.'</strong><br>';
			}

			if ($field == 'section_id') {
				$value = CodEaip::find($value);
				$updatelog = true;
				$log .= '- Section changed from: <strong>'.$issue->section->sub_id.' '.$issue->section->definition.' > '.$value->sub_id.' '.$value->definition.'</strong><br>';
			}

			if ($field == 'subsection_id') {
				$value = CodEaip::find($value);
				$updatelog = true;
				$log .= '- Subsection changed from: <strong>'.$issue->subsection->sub_id.' '.$issue->subsection->definition.' > '.$value->sub_id.' '.$value->definition.'</strong><br>';
			}

			if ($field == 'airport_id') {
				$value = Airport::find($value);
				$updatelog = true;
				$log .= '- Airport changed from: <strong>'.$issue->airport->arpt_name.' > '.$value->arpt_name.'</strong><br>';
			}

			if ($field == 'description') {
				$updatelog = true;
				$log .= '- Description changed from: <strong>'.$issue->description.' > '.$value. '</strong><br>';
			}
		}

		$history = new FeedbackHistory();

		$history->log = $log;
		$history->issue_id = $issue->id;

		if ($updatelog) {
			$history->save();
		}

		return ApiResponse::success($issue->fresh());
	}

	public function ajaxSolveIssue(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'issue' => 'required|string',
		]);

		$issue = FeedbackIssue::find($request->issue);

		$issue->progress_id = 8;
		$issue->progress_user_id = 7;

		$issue->save();

		Notification::send(
			User::find($issue->user_id), 
			new SendFeedbackNotification([
				'title' => '',
				'descriptions' => 'Admin solved your feedback #'.$issue->no
			])
		);

		$h = new FeedbackHistory();

		$h->issue_id = $issue->id;
		$h->log = Auth::user()->name.' marked this issue as <strong>Solved</strong';

		$h->save();

		return ApiResponse::success($issue->fresh());
	}
}

//ALTER TABLE public.feedback_issue ALTER COLUMN subsection_id DROP NOT NULL;
