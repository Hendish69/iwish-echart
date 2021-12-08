<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicationsController;
use App\Models\User;
use App\Notifications\SendChangeEmail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/

// Homepage Route
Route::group(['middleware' => ['web','activity', 'checkblocked']], function () {
    // Route::get('/', 'App\Http\Controllers\WelcomeController@welcome')->name('welcome');
    Route::get('/',function() {
        return redirect()->route('login');
     });
     Route::get('setlocale/{locale}',function($lang){
        if (! in_array($lang, ['en', 'es', 'fr'])) {
            abort(400);
        }
        App::setLocale($lang);
        return redirect()->back();
    });
    Route::get('/terms', 'App\Http\Controllers\TermsController@terms')->name('terms');
});

//Route::get('/force-login/{user}', 'App\Http\Controllers\ApplicationController@forceLogin');
Route::get('/privacy',function(){
    return view('privacy');
});
// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'App\Http\Controllers\Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'App\Http\Controllers\Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'App\Http\Controllers\RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'App\Http\Controllers\Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'App\Http\Controllers\Auth\LoginController@logout'])->name('logout');
});

// Registered and Activated User Routes
// Route::group(['middleware' => ['auth', 'activated', 'activity', 'twostep', 'checkblocked']], function () {
// Route::group(['middleware' => ['auth']], function () {
Route::group(['middleware' => ['auth', 'activated', 'activity','checkblocked']], function () {
    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'App\Http\Controllers\UserController@index']);

    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@show',
    ]);
    // Notification route
      
    Route::get('/orgNotif', function () {
        $user = Auth::user();
        $notifs = array();
        $isExist = false;
         
        $details = [
                'notif_id'      => 'DefOrg',
                'from'          => 'Iwish System',
                'title'         => 'Changes Your Email',
                'descriptions'  => '<br><strong>Need Attention</strong>',
                'body'          => 'Hi '. $user->name.'<br><p>Please change your profile email to continue as the Originator</p>',
                'thanks'        => 'Thank you for your attention!'
        ];
        
        $notifs = $user->notifications;
         
        // dd($notifs);
        if(count($notifs) > 0){
                foreach ($notifs as $notif) {
                    if($notif->data['notif_id']=='DefOrg')
                        $isExist = true;
                } 
            }
        if(!$isExist){
            $user->notify(new \App\Notifications\SendChangeEmail($details));
            return redirect()->back();
        }else{
            return view('pages.user.home');
        }

        
        // return dd("Done");
    })->name('orgNotif');

    Route::get('/markAsRead', function(){

        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();

    })->name('mark');
    Route::get('/inbox.messages', 'App\Http\Controllers\InboxController@index');
    Route::get('/inbox.show/{id}', 'App\Http\Controllers\InboxController@show');

    // Volcano
    Route::get('/cdm/participants/{vano}', 'App\Http\Controllers\VolcanoController@participants');
    Route::get('/cdm/chat/refresh/{vaid}/{chatid}', 'App\Http\Controllers\VolcanoController@refresh');
    Route::get('/cdm/chat/older/{vaid}/{chatid}', 'App\Http\Controllers\VolcanoController@older');
    Route::get('/cdm/chat/{id}', 'App\Http\Controllers\VolcanoController@chatList');
    Route::post('/cdm/chat/{id}', 'App\Http\Controllers\VolcanoController@postChat');
    Route::get('volcanoes', 'App\Http\Controllers\VolcanoController@index');
    Route::get('cdm', 'App\Http\Controllers\VolcanoController@cdm');
    Route::get('cdmlogdetail/{id}', 'App\Http\Controllers\VolcanoController@cdmlog');
    Route::get('vol/report/{cdmid}/{id}', 'App\Http\Controllers\PdfController@reportcdm');
    Route::get('/cdm/editgrp/{cdm_id}', 'App\Http\Controllers\VolcanoController@editCdmGrp');
    Route::post('/cdm/editgrp/', 'App\Http\Controllers\VolcanoController@storeCdmGrp');
    
     // room tele
    Route::get("/room/create", "App\Http\Controllers\RoomController@showCreateForm");
    Route::post("/room/create", "App\Http\Controllers\RoomController@create");
    Route::get("/room/{room}", "App\Http\Controllers\RoomController@redirectToRoom");
    Route::get("/room/delete/{room}", "App\Http\Controllers\RoomController@delete");

    Route::get('interaktif/Airport', 'App\Http\Controllers\InteraktifController@airport');
    Route::get('interaktif/Airspace', 'App\Http\Controllers\InteraktifController@airspace');
    Route::get('interaktif/Airspace3d', 'App\Http\Controllers\InteraktifController@airspace3d');
    Route::get('interaktif/Enroute', 'App\Http\Controllers\InteraktifController@enroute');
    Route::get('interaktif/Navaid', 'App\Http\Controllers\InteraktifController@navaid');
    Route::get('interaktif/Waypoint', 'App\Http\Controllers\InteraktifController@waypoint');
    Route::get('gpsraim', 'App\Http\Controllers\InteraktifController@gpsraim');
    Route::get('navaidinfo/{id}', 'App\Http\Controllers\InteraktifController@navinfo');
    Route::get('waypointinfo/{id}', 'App\Http\Controllers\InteraktifController@wptinfo');
    Route::get('ilsinfo/{id}', 'App\Http\Controllers\InteraktifController@ilsinfo');
    Route::get('airportinfo/{id}', 'App\Http\Controllers\InteraktifController@arptinfo');
    Route::get('electronicaip', 'App\Http\Controllers\PublicationsController@eaiphtml');
    Route::get('aipsubmission/{id}', 'App\Http\Controllers\PublicationsController@aipsubmission');
    Route::get('DataRequest', 'App\Http\Controllers\PublicationsController@request');
    Route::post('DataRequest/save', 'App\Http\Controllers\PublicationsController@store');
    Route::post('DataRequest/remove', 'App\Http\Controllers\PublicationsController@remove');
    Route::get('enroutehtml/{id}', 'App\Http\Controllers\PublicationsController@enrhtml');
    Route::post('notam/save', 'App\Http\Controllers\PublicationsController@savenotam');
    Route::post('publication/upload', 'App\Http\Controllers\PublicationsController@savepublicationfile');
    Route::post('parkingstand/update', 'App\Http\Controllers\PublicationsController@parkingstandupdate');
    Route::post('aprontwy/update', 'App\Http\Controllers\PublicationsController@aprontwyupdate');
    Route::post('pushback/update', 'App\Http\Controllers\PublicationsController@pushbackupdate');
    // Route::resource('request', PublicationsController::class);
    Route::get('amdt/{page}', 'App\Http\Controllers\PublicationsController@amdtlist');

    Route::get('timeline/{id}', 'App\Http\Controllers\PublicationsController@timeline');
    Route::get('requestview/{id}', 'App\Http\Controllers\PublicationsController@requestview');
    Route::get('datahistory', 'App\Http\Controllers\PublicationsController@datahistory');
    Route::get('createpdf/{id}', 'App\Http\Controllers\PublicationsController@createpdf');
    // Route::get('pdf/{id}', 'App\Http\Controllers\PDFController@createPDF');
    Route::post('pdf', 'App\Http\Controllers\PdfController@createPDF');
    //AIXM
    Route::get('createaixm', 'App\Http\Controllers\AixmController@createaixm');
    Route::post('aixm', 'App\Http\Controllers\AixmController@generateaixm');
    //PIB
    Route::get('pib', 'App\Http\Controllers\AixmController@pib');
    //ats route
    Route::get('listats/{id}', 'App\Http\Controllers\PublicationsController@atslist');
    Route::get('atsdetail/{id}', 'App\Http\Controllers\PublicationsController@atsdetail');
    Route::get('editats/{id}', 'App\Http\Controllers\PublicationsController@atsedit');
    // Route::post('editats/{id}', 'App\Http\Controllers\PublicationsController@atsdetail');
    //airspace
    Route::get('listairpace', 'App\Http\Controllers\PublicationsController@airspacelist');
    Route::get('listsuas/{page}/{id}', 'App\Http\Controllers\PublicationsController@suaslist');
    Route::get('airspace/{id}', 'App\Http\Controllers\AipController@airspace');
    Route::get('suas/{id}', 'App\Http\Controllers\AipController@suas');
   //navaid
    Route::get('navaid/{id}', 'App\Http\Controllers\AipController@navaid');
    Route::get('navaid', 'App\Http\Controllers\PublicationsController@navaid');
    Route::get('ils/{id}', 'App\Http\Controllers\AipController@ils');
   //waypoint
    Route::get('waypoint/{id}', 'App\Http\Controllers\AipController@waypoint');
    Route::get('waypoint', 'App\Http\Controllers\PublicationsController@waypoint');
    Route::get('terminalwaypoint', 'App\Http\Controllers\PublicationsController@terminalwaypoint');
    //frequency
    Route::get('frequency/{id}', 'App\Http\Controllers\PublicationsController@frequency');
    //pia
    Route::get('pia', 'App\Http\Controllers\PublicationsController@pialist');
    // create html
    Route::get('text/html/{id}', 'App\Http\Controllers\PublicationsController@genfreetext');
    Route::get('listairpace/{id}', 'App\Http\Controllers\PublicationsController@airspacelist');
    // Route::post('obstacle/save', 'App\Http\Controllers\AipController@updateobstacle');
    //eaip
    Route::get('listairport/{id}', 'App\Http\Controllers\AipController@airportlist');
    Route::get('editairport/{id}', 'App\Http\Controllers\AipController@editairport');
    Route::get('show217/{page}/{id}', 'App\Http\Controllers\AipController@show217');
    Route::post('show217/{page}/{id}', 'App\Http\Controllers\AipController@updatedata');
    //heru
    Route::post('editairport/{id}', 'App\Http\Controllers\AipController@updatearpt');
    Route::get('aipedit/{page}/{id?}', 'App\Http\Controllers\AipController@aipedit');
    Route::post('aipedit/{page}/{id}', 'App\Http\Controllers\AipController@updatedata');
    //gen
    Route::get('gen.edit/{id}/{text}', 'App\Http\Controllers\GenController@index');
    Route::get('gen/{id}/{arpt}/{text}', 'App\Http\Controllers\GenController@adinfo');
    Route::post('gen/save/{id}', 'App\Http\Controllers\GenController@store');
    Route::post('gen.uploadCK','App\Http\Controllers\GenController@uploadImage')->name('uploadCK');

    Route::get('gen02/{id}/{code}', 'App\Http\Controllers\PublicationsController@gen02');
    Route::get('gen24/{id}', 'App\Http\Controllers\PublicationsController@gen24');
    Route::get('gen22/{id}', 'App\Http\Controllers\PublicationsController@abbr');
    Route::get('gen25/{id}', 'App\Http\Controllers\PublicationsController@gen25');
    Route::get('enr41/{id}', 'App\Http\Controllers\PublicationsController@enr41');
    //
    Route::get('edit22/{id}', 'App\Http\Controllers\AipController@edit22');
    Route::get('edit23/{id}', 'App\Http\Controllers\AipController@edit23');
    Route::get('edit24/{id}', 'App\Http\Controllers\AipController@edit24');
    Route::get('edit25/{id}', 'App\Http\Controllers\AipController@edit25');
    Route::get('edit26/{id}', 'App\Http\Controllers\AipController@edit26');
    Route::get('edit27/{id}', 'App\Http\Controllers\AipController@edit27');
    Route::get('edit28/{id}', 'App\Http\Controllers\AipController@edit28');
    Route::get('edit29/{id}', 'App\Http\Controllers\AipController@edit29');
    Route::get('edit210/{id}', 'App\Http\Controllers\AipController@edit210');
    Route::get('edit211/{id}', 'App\Http\Controllers\AipController@edit211');
    Route::get('edit212/{id}/{page}', 'App\Http\Controllers\AipController@edit212');
    Route::get('edit213/{id}/{page}', 'App\Http\Controllers\AipController@edit212');
    Route::get('edit214/{id}/{page}', 'App\Http\Controllers\AipController@edit212');
    Route::get('edit215/{id}', 'App\Http\Controllers\AipController@edit215');
    Route::get('edit216/{id}', 'App\Http\Controllers\AipController@edit216');
    Route::get('edit217/{id}', 'App\Http\Controllers\AipController@edit217');
    Route::get('edit218/{id}', 'App\Http\Controllers\AipController@edit218');
    Route::get('edit219/{id}', 'App\Http\Controllers\AipController@edit219');
    Route::get('edit220/{id}', 'App\Http\Controllers\AipController@edit220');
    Route::get('edit221/{id}', 'App\Http\Controllers\AipController@edit221');
    Route::get('edit222/{id}', 'App\Http\Controllers\AipController@edit222');
    Route::get('edit223/{id}', 'App\Http\Controllers\AipController@edit223');
    Route::get('edit224/{id}', 'App\Http\Controllers\AipController@edit224');
    Route::get('updatealldata/{id}', 'App\Http\Controllers\AipController@updatealldata');
    
    // procedure Transition
    Route::get('procedure/{id}/{chart}', 'App\Http\Controllers\AipController@listprocedure');
    Route::get('listtranssegment/{id}/{chart}', 'App\Http\Controllers\AipController@listtransitionsegment');
    Route::get('listprocsegment/{id}/{chart}', 'App\Http\Controllers\AipController@listproceduresegment');
    Route::get('holding/{id}', 'App\Http\Controllers\AipController@listholding');
    Route::get('msa', 'App\Http\Controllers\AipController@listmsa');
    Route::get('chartprop/{id}/{tbl}', 'App\Http\Controllers\AipController@listchart');
    Route::get('chartprop/edit/{id}/{arpt}', 'App\Http\Controllers\AipController@listchart_prop');
    Route::get('chartframe/edit/{id}/{arpt}', 'App\Http\Controllers\AipController@listchart_frame');
    
    Route::get('sourcenr', 'App\Http\Controllers\AipController@listsourcenr');
    Route::get('aoc/{id}/{tbl}', 'App\Http\Controllers\AipController@aoc');

    // vfr planning
    Route::get('vfr_planning', 'App\Http\Controllers\VfrController@index');
    Route::post('vfr_planning', 'App\Http\Controllers\VfrController@store');
    Route::post('get_route', 'App\Http\Controllers\VfrController@getRoute');
    Route::get('get_infoarpt/{id}', 'App\Http\Controllers\VfrController@getInfoArpt');
    Route::get('GeoHi/{lat}/{lon}', function($lat,$lon){
        $hi= GeoHi($lat,$lon);
        
        return $hi; 
    });
    Route::get('/care', 'App\Http\Controllers\CareController@index');
    Route::get('/ajax/feedback/summary', 'App\Http\Controllers\CareController@summary');
    Route::get('/ajax/issue/list', 'App\Http\Controllers\CareController@ajaxIssueList');
    Route::get('/ajax/topic/list', 'App\Http\Controllers\CareController@ajaxTopicList');
    Route::get('/ajax/priority/list', 'App\Http\Controllers\CareController@ajaxPriorityList');
    // Route::get('/ajax/part/list', 'App\Http\Controllers\CareController@ajaxPartList');
    // Route::get('/ajax/section/{part}/list', 'App\Http\Controllers\CareController@ajaxSectionList');
    Route::get('/ajax/attachment/{issue}/list', 'App\Http\Controllers\CareController@ajaxAttachmentList');
    Route::get('/ajax/history/{issue}/list', 'App\Http\Controllers\CareController@ajaxHistoryList');
    Route::get('/ajax/faq/list', 'App\Http\Controllers\CareController@ajaxFaqList');
    Route::post('/ajax/faq/create', 'App\Http\Controllers\CareController@ajaxFaqCreate');
    Route::post('/ajax/faq/{faq}/update', 'App\Http\Controllers\CareController@ajaxFaqUpdate');
    Route::post('/ajax/attachment/remove', 'App\Http\Controllers\CareController@ajaxRemoveAttachment');
    Route::post('/ajax/attachment/upload', 'App\Http\Controllers\CareController@ajaxUploadAttachment');
    Route::post('/ajax/issue/{issue}/update', 'App\Http\Controllers\CareController@ajaxUpdateIssue');
    Route::post('/ajax/issue/create', 'App\Http\Controllers\CareController@ajaxCreateIssue');
    Route::get('/ajax/section/list', 'App\Http\Controllers\CareController@ajaxSectionList');
    Route::get('/ajax/subsection/{section}/list', 'App\Http\Controllers\CareController@ajaxSubsectionList');
    Route::get('/ajax/airport/list', 'App\Http\Controllers\CareController@ajaxAirportList');
    Route::post('/ajax/issue/solve', 'App\Http\Controllers\CareController@ajaxSolveIssue');

    Route::get('/postflight', 'App\Http\Controllers\PostflightController@index');
    Route::get('/ajax/postflight/list', 'App\Http\Controllers\PostflightController@list');
    Route::post('/ajax/postflight/create', 'App\Http\Controllers\PostflightController@create');
    Route::get('/ajax/postflight/view/{id}', 'App\Http\Controllers\PostflightController@view');
    // tes html2pdf

    Route::get('teshtml2pdf', function(){    
    
        $html = '<table style="width:100%">
                        <tr>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Age</th>
                        </tr>
                        <tr>
                            <td>Jill</td>
                            <td>Smith</td>
                            <td>50</td>
                        </tr>
                        <tr>
                            <td>Eve</td>
                            <td>Jackson</td>
                            <td>94</td>
                        </tr>
                        </table> 
                        <br />
                        <br />
                        <b>1. Airport Regulations</b><br />
                        &emsp;1. Aerodrome Traffic Circuit Procedures<br />
                        &emsp;&emsp;a. Runway 08 : Take Off and Landing right hand circuit or as instructed by ATC<br />
                        &emsp;&emsp;b. Runway 26 : Take Off and Landing normal circuit or as instructed by ATC<br />
                        &emsp;2. Arrival and Departure Procedures<br />
                        &emsp;&emsp;Arriving and departing aircrafts are required to follow the current Standard Instrument Arrival (STAR) and Standard Instrument Departure (SID).<br /> ◀
                        &emsp;3. Freighter wide body aircraft are suggested not landing at Adi Soemarmo Airport / Solo Due to ground equipment (Ground deck Louder) not available.<br /> ◀
                        &emsp;4. Military Training Area 5-20 NM centre of &ldquo;SO&rdquo; NDB Ground Up to 6000ft<br />
                        <b>2. Taxiing To and From Stands</b><br />
                        &emsp;1. Taxi Procedures :<br />
                        &emsp;&emsp;a. Departure ACFT RWY 08/26 taxi out from North apron via TWY Alpha and then proceed to RWY 08/26.<br />
                        &emsp;&emsp;b. Arrival ACFT from RWY 08/26 taxi in to North apron via TWY Bravo.<br />
                        &emsp;&emsp;c. Or as instructed by ATC.<br />
                        &emsp;2. All aircraft are not allowed to make one wheel locked turn on turning areas.<br />
                        &nbsp;
                        ';
            $pdf = new createPDF(
                $html,  // html text to publish
                "Test", // article title
                "",    // article URL
                "Heru", // author name
                time()
            );
            $pdf->run();
    
            return $pdf; 
        });
    Route::get('/customer', function () {
        return view('pages.customers.index');
    });

    // INAVACEC
    Route::group(['prefix' => 'inavcec'], function()
    {
        // dashboard
        Route::resource('dashboard', 'App\Http\Controllers\CecDashController', [
            'only' => ['index']
        ]);
        Route::get('getLastData', 'App\Http\Controllers\CecDashController@getLastData')->name('dashboard.getLastData');
        Route::get('getCityPair', 'App\Http\Controllers\CecDashController@getCityPair')->name('dashboard.getCityPair');
        Route::get('getEmDomInter', 'App\Http\Controllers\CecDashController@getEmDomInter')->name('dashboard.getEmDomInter');
        Route::get('getLastEmTotal', 'App\Http\Controllers\CecDashController@getLastEmTotal')->name('dashboard.getLastEmTotal');
        
        // report periodic
        Route::resource('report', 'App\Http\Controllers\Report\CecEmPeriodicCtrl', [
            'only' => ['index']
        ]);
        Route::get('getEmChart', 'App\Http\Controllers\Report\CecEmPeriodicCtrl@getEmChart')->name('report.getEmChart');
        Route::get('emTablePie', 'App\Http\Controllers\Report\CecEmPeriodicCtrl@emTablePie')->name('report.emTablePie');
        Route::get('getPieTotal', 'App\Http\Controllers\Report\CecEmPeriodicCtrl@getPieTotal')->name('report.getPieTotal');
        Route::get('getData', 'App\Http\Controllers\Report\CecEmPeriodicCtrl@getData')->name('report.getData');
        // report city pair
        Route::resource('reportcity', 'App\Http\Controllers\Report\CecCityPairCtrl', [
            'only' => ['index']
        ]);
        Route::get('cpTabelPie', 'App\Http\Controllers\Report\CecCityPairCtrl@cpTabelPie')->name('reportcity.cpTabelPie');
        Route::get('cpPieChart', 'App\Http\Controllers\Report\CecCityPairCtrl@cpPieChart')->name('reportcity.cpPieChart');

        Route::get('cpgetBarChart', 'App\Http\Controllers\Report\CecCityPairCtrl@cpgetBarChart')->name('reportcity.cpgetBarChart');
        Route::get('cpgetData', 'App\Http\Controllers\Report\CecCityPairCtrl@cpgetData')->name('reportcity.cpgetData');
        // report dom in

        Route::resource('reportdomin', 'App\Http\Controllers\Report\CecDomInCtrl', [
            'only' => ['index']
        ]);
        Route::get('domTabelPie', 'App\Http\Controllers\Report\CecDomInCtrl@domTabelPie')->name('reportdomin.domTabelPie');
        Route::get('domPieChart', 'App\Http\Controllers\Report\CecDomInCtrl@domPieChart')->name('reportdomin.domPieChart');

        Route::get('domgetBarChart', 'App\Http\Controllers\Report\CecDomInCtrl@domgetBarChart')->name('reportdomin.domgetBarChart');
        Route::get('domgetData', 'App\Http\Controllers\Report\CecDomInCtrl@domgetData')->name('reportdomin.domgetData');

        // acft
        Route::resource('acft', 'App\Http\Controllers\CecAcftController', [
            'only' => ['index','update','destroy','delete']
        ]);
        Route::get('getAcft', 'App\Http\Controllers\CecAcftController@getAcft')->name('acft.getAcft');

        // Route::get('acft', 'App\Http\Controllers\CecAcftController@index');
        // Route::post('acft/create','App\Http\Controllers\CecAcftController@store');
        // Route::get('acft/edit/{id}','App\Http\Controllers\CecAcftController@edit');
        // Route::patch('acft/{id}','App\Http\Controllers\CecAcftController@update');
        // Route::delete('acft/{id}','App\Http\Controllers\CecAcftController@destroy');
        // Route::get('acft/{id}','App\Http\Controllers\CecAcftController@view');
        //  Dep Features
        Route::resource('depfeat', 'App\Http\Controllers\CecDepFeatController', [
            'only' => ['index','update','destroy','delete','create']
        ]);
        Route::post('depfeat/create','App\Http\Controllers\CecDepFeatController@store');
        Route::get('getDepFeat', 'App\Http\Controllers\CecDepFeatController@getDepFeat')->name('depfeat.getDepFeat');

        //  Arr Features
        Route::resource('arrfeat', 'App\Http\Controllers\CecArrFeatController', [
            'only' => ['index','update','destroy','delete','create']
        ]);
        Route::post('arrfeat/create','App\Http\Controllers\CecArrFeatController@store');
        Route::get('getArrFeat', 'App\Http\Controllers\CecArrFeatController@getArrFeat')->name('arrfeat.getArrFeat');
        
        // Airport
        Route::resource('airport', 'App\Http\Controllers\CecAirportController', [
            'only' => ['index','update','destroy','delete']
        ]);
        Route::post('airport/create','App\Http\Controllers\CecAirportController@store');
        Route::get('getAirport', 'App\Http\Controllers\CecAirportController@getAirport')->name('airport.getAirport');

        // predict tool
        Route::get('predictool', 'App\Http\Controllers\PredicToolCtrl@index');
        Route::post('predictool/create', 'App\Http\Controllers\PredicToolCtrl@store');
        Route::post('getRoute', 'App\Http\Controllers\PredicToolCtrl@getRoute')->name('predictool.getRoute');
        Route::post('calcEmission', 'App\Http\Controllers\PredicToolCtrl@calcEmission')->name('predictool.calcEmission');
    });


});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity', 'twostep', 'checkblocked']], function () {
// Route::group(['middleware' => ['auth']], function () {

    // User Profile and Account Routes
    Route::resource(
        'profile',
        \App\Http\Controllers\ProfilesController::class,
        [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'App\Http\Controllers\ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'App\Http\Controllers\ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/users/deleted', \App\Http\Controllers\SoftDeletesController::class, [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', \App\Http\Controllers\UsersManagementController::class, [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('search-users', 'App\Http\Controllers\UsersManagementController@search')->name('search-users');

    Route::resource('themes', \App\Http\Controllers\ThemesManagementController::class, [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'App\Http\Controllers\AdminDetailsController@listRoutes');
    Route::get('active-users', 'App\Http\Controllers\AdminDetailsController@activeUsers');
    
});

// Route::redirect('/php', '/phpinfo', 301);
