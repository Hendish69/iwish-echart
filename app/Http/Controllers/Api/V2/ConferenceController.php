<?php
namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Models\Api\TxCdmChat;
use App\Models\Api\TxCdmUser;
use App\Models\Api\TxCdmGroup;
use App\Models\Api\TxCdm;
use App\Models\Api\Volcano;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Managers\FileManager;

class ConferenceController
{
	public function group(Request $request)
	{
		// $allowedRooms = TxCdmUser::where('user_id', Auth::user()->user_id)->get()->pluck('cdm_id'); //PLG
		$allowedRooms = TxCdmUser::where('user_id', Auth::user()->id)->get()->pluck('cdm_id');

		$rooms = TxCdm::query()
			->with([
				'volcano' => function($query) {
					return $query->select('va_no', 'va_name');
				},
			])
			->whereIn('cdm_id', $allowedRooms)
			->orderBy('cdm_date', 'DESC')->paginate(10);

		foreach ($rooms as &$room) {
			$room->last_chat = (object) [];

			$chat = TxCdmChat::limit(1)->orderBy('chat_date', 'desc')->where('cdm_id', $room->cdm_id)->first();

			if (null !== $chat) {
				$room->last_chat->chat = $chat;
				// $room->last_chat->user = User::where('user_id', $chat->user_id)->first(); //PLG
				$room->last_chat->user = User::where('id', $chat->user_id)->first();
			} else {
				$room->last_chat = null;
			}
		}

		return ApiResponse::success($rooms);
	}

	public function chat(Request $request, string $id)
	{
		$chats = TxCdmChat::with('user')->where('cdm_id', $id)->orderBy('chat_id', 'desc')->paginate(10);

		return ApiResponse::success($chats);
	}

	public function types(Request $request)
	{
		$results = [];

		$types = DB::table('tb_reff')->where('reff_group', '0002')->get()->sortBy('reff_code');

		foreach ($types as $type) {
			$results[] = ['value' => $type->reff_code, 'label' => $type->reff_name];
		}

		return ApiResponse::success($results);
	}

	public function postChat(Request $request, FileManager $fm, string $id)
	{
		$cdm = TxCdm::with([
			'volcano' => function($query) {
				return $query->select('va_no', 'va_name');
			}
		])->where('cdm_id', $id)->first();

		if (null === $cdm) {
			return ApiResponse::error(ApiResponse::RESOURCE_NOT_FOUND);
		}

		$validator = Validator::make($request->all(), [
			'text' => 'string',
			'file' => 'file',
			'type' => 'required|string'
		]);

		if ($validator->fails()) {
			return ApiResponse::fail($validator->errors());	
		}

		$chat = new TxCdmChat();

		$last = DB::table('tx_cdm_chat')->orderBy('chat_id', 'desc')->first();

		if (null === $last) {
			$chat->chat_id = 1;
		} else {
			$chat->chat_id = $last->chat_id+1;
		}

		$chat->cdm_id = $cdm->cdm_id;
		$chat->va_no = $cdm->volcano->va_no;
		$chat->chat_content = $request->text;
		$chat->chat_type = $request->type;

		if ($request->has('file')) {
			$chat->chat_file_path = $request->file->getClientOriginalName();
			$chat->chat_file_name = $request->file->getClientOriginalName();
			$chat->chat_file_ext = $request->file->extension();
			$chat->chat_file_size = $request->file->getSize();

			$request->file->move(app()->basePath('public/upload/chat'), $request->file->getClientOriginalName());
		}

		$chat->save();

		$chat = TxCdmChat::with('user')->where('chat_id', $chat->chat_id)->first();

		return ApiResponse::success($chat);
	}
}