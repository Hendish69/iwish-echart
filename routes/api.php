<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//puta
Route::get('/puta/airport', 'App\Http\Controllers\Api\AirportController@listputa');
Route::get('/puta/airspace', 'App\Http\Controllers\Api\AirspaceController@listputa');
Route::get('/puta/suas', 'App\Http\Controllers\Api\SuasController@listputa');
//pute
Route::get('/sysmenu', 'App\Http\Controllers\Api\EaipController@sysmenu');
Route::get('/nav/channel', 'App\Http\Controllers\Api\NavaidController@channel');
Route::get('/tblreff', 'App\Http\Controllers\Api\VolcanoController@tblreff');
Route::get('/ats/listall', 'App\Http\Controllers\Api\AtsController@listall');
Route::get('/getoffset/{pid}', 'App\Http\Controllers\Api\VolcanoController@getoffsetforecast');
Route::get('/getcenter/{pid}', 'App\Http\Controllers\Api\VolcanoController@getcenterforecast');
Route::get('/getcontainsarpt/{pid}', 'App\Http\Controllers\Api\AirportController@getairportorecast');
Route::get('/getcontainsats/{pid}', 'App\Http\Controllers\Api\AtsController@getatsforecast');
Route::get('/airports', 'App\Http\Controllers\Api\AirportController@index');
Route::get('/auth', 'App\Http\Controllers\Api\AirportController@auth');
Route::get('/airport/search', 'App\Http\Controllers\Api\AirportController@search');
Route::get('/airport/list', 'App\Http\Controllers\Api\AirportController@list');
Route::get('/airport/list/adc', 'App\Http\Controllers\Api\AirportController@listadc');
Route::get('/airport/adc', 'App\Http\Controllers\Api\AirportController@listadcaixm');
Route::get('/allchart', 'App\Http\Controllers\Api\AirportController@allchart');
Route::get('/airport/chart', 'App\Http\Controllers\Api\AirportController@airportchart');
Route::post('/airport/chart/save', 'App\Http\Controllers\Api\AirportController@airportchartsave');
Route::post('/airport/chart/remove/{id}', 'App\Http\Controllers\Api\AirportController@airportchartremove');
Route::post('/airport/save', 'App\Http\Controllers\Api\AirportController@save');
Route::post('/airport/update/{id}', 'App\Http\Controllers\Api\AirportController@update');
Route::post('/airport/save/tlta', 'App\Http\Controllers\Api\AirportController@savetlta');
Route::post('/airport/update/tlta/{id}', 'App\Http\Controllers\Api\AirportController@updatetlta');
Route::post('/airport/remove/{id}', 'App\Http\Controllers\Api\AirportController@remove');
Route::post('/auth/save', 'App\Http\Controllers\Api\AirportController@saveauth');
Route::post('/auth/update/{id}', 'App\Http\Controllers\Api\AirportController@updateauth');
Route::post('/auth/remove/{id}', 'App\Http\Controllers\Api\AirportController@removeauth');
Route::get('/arpt/search/{id}', 'App\Http\Controllers\Api\AirportController@airportsearch');
Route::get('/freq/list', 'App\Http\Controllers\Api\FreqController@list');
Route::get('/freq/temp/list', 'App\Http\Controllers\Api\FreqController@listtemp');
Route::get('/freq/value', 'App\Http\Controllers\Api\FreqController@getfreqvalue');
Route::get('/freq/usage', 'App\Http\Controllers\Api\FreqController@FreqUsage');
Route::get('/freq/temp/usage', 'App\Http\Controllers\Api\FreqController@FreqUsagetemp');
Route::get('/freq/search', 'App\Http\Controllers\Api\FreqController@search');
Route::get('/freq/list/seg/{pid}', 'App\Http\Controllers\Api\FreqController@listsegment');
Route::get('/freq/list/useon/{pid}', 'App\Http\Controllers\Api\FreqController@UseOn');
Route::get('/freq/code', 'App\Http\Controllers\Api\FreqController@listcode');
Route::get('/freq', 'App\Http\Controllers\Api\FreqController@getfreqonly');
Route::post('/freq/usage/save', 'App\Http\Controllers\Api\FreqController@insertfreqused');
Route::post('/freq/usage/update', 'App\Http\Controllers\Api\FreqController@updatefreqused');
Route::post('/freq/usage/remove', 'App\Http\Controllers\Api\FreqController@removefreqused');
Route::post('/freqchart/seq', 'App\Http\Controllers\Api\FreqController@changesequence');

Route::get('/freqarpt/{tbl}', 'App\Http\Controllers\Api\FreqController@AirportFreq');
Route::get('/freqarpt/temp/{tbl}', 'App\Http\Controllers\Api\FreqController@AirportFreqTemp');
Route::post('/freq/save', 'App\Http\Controllers\Api\FreqController@save');
Route::post('/freq/update/{id}', 'App\Http\Controllers\Api\FreqController@update');
Route::post('/freq/remove/{id}', 'App\Http\Controllers\Api\FreqController@remove');
Route::post('/freq/seg/save', 'App\Http\Controllers\Api\FreqController@saveseg');
Route::post('/freq/seg/update/{id}', 'App\Http\Controllers\Api\FreqController@updateseg');
Route::post('/freq/seg/remove/{id}', 'App\Http\Controllers\Api\FreqController@removeseg');
Route::post('/freq/value/save', 'App\Http\Controllers\Api\FreqController@savevalue');
Route::post('/freq/value/update/{id}', 'App\Http\Controllers\Api\FreqController@updatevalue');
Route::post('/freq/value/remove/{id}', 'App\Http\Controllers\Api\FreqController@removevalue');
Route::get('/chartfreq/temp/', 'App\Http\Controllers\Api\FreqController@chartfreqlist');
Route::get('/airspace/list', 'App\Http\Controllers\Api\AirspaceController@list');
Route::get('/airspace/temp/list', 'App\Http\Controllers\Api\AirspaceController@listtemp');
Route::get('/airspace/list/class', 'App\Http\Controllers\Api\AirspaceController@AspClass');
Route::get('/airspace/temp/list/class', 'App\Http\Controllers\Api\AirspaceController@AspClasstemp');
Route::get('/airspace/list/seg', 'App\Http\Controllers\Api\AirspaceController@AspSeg');
Route::get('/airspace/list/temp/seg', 'App\Http\Controllers\Api\AirspaceController@AspSegtemp');
Route::get('/airspace/list/freq', 'App\Http\Controllers\Api\AirspaceController@AspFreq');
Route::get('/airspace/temp/list/freq', 'App\Http\Controllers\Api\AirspaceController@AspFreqtemp');
Route::get('/airspace/list/remarks/{pid}', 'App\Http\Controllers\Api\AirspaceController@AspRemarks');
Route::get('/airspace/search', 'App\Http\Controllers\Api\AirspaceController@search');
Route::get('/airspace/ats', 'App\Http\Controllers\Api\AirspaceController@findingAts');
Route::get('/airspace/menu', 'App\Http\Controllers\Api\AirspaceController@AspMenu');
Route::post('/airspace/save', 'App\Http\Controllers\Api\AirspaceController@save');
Route::post('/airspace/update/{id}', 'App\Http\Controllers\Api\AirspaceController@update');
Route::post('/airspace/remove/{id}', 'App\Http\Controllers\Api\AirspaceController@remove');
Route::post('/airspace/seg/save', 'App\Http\Controllers\Api\AirspaceController@saveseg');
Route::post('/airspace/seg/update/{id}', 'App\Http\Controllers\Api\AirspaceController@updateseg');
Route::post('/airspace/seg/remove/{id}', 'App\Http\Controllers\Api\AirspaceController@removeseg');
Route::post('/airspace/class/save', 'App\Http\Controllers\Api\AirspaceController@saveclass');
Route::post('/airspace/class/update/{id}', 'App\Http\Controllers\Api\AirspaceController@updateclass');
Route::post('/airspace/class/remove/{id}', 'App\Http\Controllers\Api\AirspaceController@removeclass');
Route::post('/airspace/freq/remove/{id}', 'App\Http\Controllers\Api\AirspaceController@removefreq');
Route::post('/airspace/save/temp1', 'App\Http\Controllers\Api\AirspaceController@savetemp1');
Route::post('/airspace/remove/temp1/{id}', 'App\Http\Controllers\Api\AirspaceController@removetemp1');
Route::get('/airspace/temp1', 'App\Http\Controllers\Api\AirspaceController@asptemp1');
Route::get('/suas/list', 'App\Http\Controllers\Api\SuasController@list');
Route::get('/suas/temp/list', 'App\Http\Controllers\Api\SuasController@listtemp');
Route::get('/suas/list/remarks/{pid}', 'App\Http\Controllers\Api\SuasController@getRemarks');
Route::get('/suas/list/seg/{pid}', 'App\Http\Controllers\Api\SuasController@getBoundary');
Route::get('/suas/temp/list/seg', 'App\Http\Controllers\Api\SuasController@SuasSegtemp');

Route::post('/suas/save', 'App\Http\Controllers\Api\SuasController@save');
Route::post('/suas/update/{id}', 'App\Http\Controllers\Api\SuasController@update');
Route::post('/suas/segment/remove', 'App\Http\Controllers\Api\SuasController@removesegment');
Route::post('/suas/segment/save', 'App\Http\Controllers\Api\SuasController@savesegment');
Route::post('/suas/segment/update/{id}', 'App\Http\Controllers\Api\SuasController@updatesegment');
Route::post('/suas/remarks/save', 'App\Http\Controllers\Api\SuasController@saveremarks');
Route::post('/suas/remarks/update/{id}', 'App\Http\Controllers\Api\SuasController@updateremarks');
Route::get('/navaid', 'App\Http\Controllers\Api\NavaidController@index');
Route::get('/navaid/search', 'App\Http\Controllers\Api\NavaidController@navsearch');
Route::get('/navaid/temp', 'App\Http\Controllers\Api\NavaidController@indextemp');
Route::get('/navaid/list', 'App\Http\Controllers\Api\NavaidController@list');
Route::get('/navaid/temp/list', 'App\Http\Controllers\Api\NavaidController@listtemp');
Route::get('/navaid/list/aixm', 'App\Http\Controllers\Api\NavaidController@listaixm');
Route::get('/navaidall', 'App\Http\Controllers\Api\NavaidController@navaidlisttemp');

// Route::get('/navaid/search', 'App\Http\Controllers\Api\NavaidController@search');
Route::post('/navaid/save', 'App\Http\Controllers\Api\NavaidController@save');
Route::post('/navaid/update/{id}', 'App\Http\Controllers\Api\NavaidController@update');
Route::post('/navaid/remove/{id}', 'App\Http\Controllers\Api\NavaidController@remove');
Route::get('/navarpt', 'App\Http\Controllers\Api\NavaidController@getarptnav');
Route::get('/navarpt/temp', 'App\Http\Controllers\Api\NavaidController@getarptnavtemp');

Route::get('/navarpt/{tbl}', 'App\Http\Controllers\Api\NavaidController@AirportNavaid');
Route::post('/navarpt/update/{id}', 'App\Http\Controllers\Api\NavaidController@updatearptnav');
Route::post('/navarpt/save', 'App\Http\Controllers\Api\NavaidController@savearptnav');
Route::post('/navarpt/remove', 'App\Http\Controllers\Api\NavaidController@removearptnav');
Route::post('/navarpt/temp/remove', 'App\Http\Controllers\Api\NavaidController@removearptnavtemp');
Route::get('/ils/list', 'App\Http\Controllers\Api\IlsController@list');
Route::get('/ils/temp/list', 'App\Http\Controllers\Api\IlsController@listtemp');
Route::get('/ils/listaixm', 'App\Http\Controllers\Api\IlsController@listaixm');
Route::get('/ils', 'App\Http\Controllers\Api\IlsController@getils');
Route::get('/ils/temp', 'App\Http\Controllers\Api\IlsController@getilstemp');
Route::get('/ils/search', 'App\Http\Controllers\Api\IlsController@ilssearch');

Route::get('/marker/search', 'App\Http\Controllers\Api\IlsController@markersearch');
Route::get('/ils/temp/marker', 'App\Http\Controllers\Api\IlsController@getmarkertemp');
Route::post('/ils/save', 'App\Http\Controllers\Api\IlsController@save');
Route::post('/ils/update/{id}', 'App\Http\Controllers\Api\IlsController@update');
Route::post('/ils/remove/{id}', 'App\Http\Controllers\Api\IlsController@remove');
Route::post('/ils/marker/save', 'App\Http\Controllers\Api\IlsController@savemarker');
Route::post('/ils/marker/update/{id}', 'App\Http\Controllers\Api\IlsController@updatemarker');
Route::post('/ils/marker/remove/{id}', 'App\Http\Controllers\Api\IlsController@removemarker');
Route::get('/waypoint/temp', 'App\Http\Controllers\Api\WaypointController@indextemp');
Route::get('/waypoint/list', 'App\Http\Controllers\Api\WaypointController@list');
Route::get('/waypoint/temp/list', 'App\Http\Controllers\Api\WaypointController@listtemp');
Route::get('/waypoint/list/aixm/{id}', 'App\Http\Controllers\Api\WaypointController@listaixm');
Route::post('/waypoint/update/{id}', 'App\Http\Controllers\Api\WaypointController@update');
Route::post('/waypoint/save', 'App\Http\Controllers\Api\WaypointController@save');
Route::post('/waypoint/remove/{id}', 'App\Http\Controllers\Api\WaypointController@remove');
Route::get('/waypoint/search', 'App\Http\Controllers\Api\WaypointController@wptsearch');
Route::get('/waypoint/nearest/{id}', 'App\Http\Controllers\Api\WaypointController@getwaypointnearest');

Route::get('/ats/point/{pid}', 'App\Http\Controllers\Api\AtsController@getatsbypoint');
Route::get('/ats/point/temp/{pid}', 'App\Http\Controllers\Api\AtsController@getatsbypointtemp');

Route::get('/atsall', 'App\Http\Controllers\Api\AtsController@atsall');
Route::get('/atsremarkall', 'App\Http\Controllers\Api\AtsController@atsremarkall');

Route::get('/ats', 'App\Http\Controllers\Api\AtsController@index');
Route::get('/ats/temp', 'App\Http\Controllers\Api\AtsController@indextemp');
Route::get('/ats/list/{pid}', 'App\Http\Controllers\Api\AtsController@list');
Route::get('/ats/list/temp/{pid}', 'App\Http\Controllers\Api\AtsController@listtemp');
Route::get('/ats/aixm/{pid}', 'App\Http\Controllers\Api\AtsController@listaixm');
Route::get('/ats/data', 'App\Http\Controllers\Api\AtsController@listbyident');
Route::get('/ats/data/temp', 'App\Http\Controllers\Api\AtsController@listbyidenttemp');
Route::get('/ats/list/point/{pid}', 'App\Http\Controllers\Api\AtsController@getpoint');
Route::get('/ats/list/point/temp/{pid}', 'App\Http\Controllers\Api\AtsController@getpointtemp');
Route::get('/ats/next', 'App\Http\Controllers\Api\AtsController@nextdata');
Route::get('/ats/next/temp', 'App\Http\Controllers\Api\AtsController@nextdatatemp');
Route::get('/ats/list/remarks/{pid}', 'App\Http\Controllers\Api\AtsController@AtsRemarks');
Route::get('/ats/list/remarks/temp/{pid}', 'App\Http\Controllers\Api\AtsController@AtsRemarkstemp');
Route::get('/ats/search', 'App\Http\Controllers\Api\AtsController@searchByIdent');
Route::post('/ats/update/temp/{id}', 'App\Http\Controllers\Api\AtsController@updatetemp');
Route::post('/ats/update/{id}', 'App\Http\Controllers\Api\AtsController@update');
Route::post('/ats/updateseq/{id}', 'App\Http\Controllers\Api\AtsController@updateseq');
Route::post('/ats/save', 'App\Http\Controllers\Api\AtsController@save');
Route::post('/ats/save/temp', 'App\Http\Controllers\Api\AtsController@savetemp');
Route::post('/ats/remove/{id}', 'App\Http\Controllers\Api\AtsController@remove');
Route::post('/ats/remove/temp/{id}', 'App\Http\Controllers\Api\AtsController@removetemp');
Route::post('/ats/removeident', 'App\Http\Controllers\Api\AtsController@removeatsident');
Route::post('/ats/removeident/temp', 'App\Http\Controllers\Api\AtsController@removeatsidenttemp');
Route::post('/ats/update/remarks/{id}', 'App\Http\Controllers\Api\AtsController@UpdateRemarksaspid');
Route::post('/ats/save/remarks', 'App\Http\Controllers\Api\AtsController@SaveRemarksaspid');
Route::post('/ats/update/remarks/temp/{id}', 'App\Http\Controllers\Api\AtsController@UpdateRemarksaspidtemp');
Route::post('/ats/save/remarks/temp', 'App\Http\Controllers\Api\AtsController@SaveRemarksaspidtemp');
Route::get('/getpoint/ats/{pid}', 'App\Http\Controllers\Api\PointsUsedController@UseInATS');
Route::get('/getpoint/trans/{pid}', 'App\Http\Controllers\Api\PointsUsedController@UseInTrans');
Route::get('/getpoint/ats/temp/{pid}', 'App\Http\Controllers\Api\PointsUsedController@UseInATSTemp');
Route::get('/getpoint/trans/temp/{pid}', 'App\Http\Controllers\Api\PointsUsedController@UseInTransTemp');
Route::get('/getpoint/asp/{pid}', 'App\Http\Controllers\Api\PointsUsedController@UseInASP');
Route::get('/getpoint/asp/temp/{pid}', 'App\Http\Controllers\Api\PointsUsedController@UseInASPTemp');

Route::get('/chartminima', 'App\Http\Controllers\Api\ProcedureController@chartminima');
Route::get('/proc/list', 'App\Http\Controllers\Api\ProcedureController@list');
Route::get('/proc', 'App\Http\Controllers\Api\ProcedureController@procedure');
Route::get('/proc/list/seg/{pid}', 'App\Http\Controllers\Api\ProcedureController@listtrans');
Route::get('/msa/list', 'App\Http\Controllers\Api\MsaController@list');
Route::post('/msa/save', 'App\Http\Controllers\Api\MsaController@save');
Route::get('/holding/list/temp', 'App\Http\Controllers\Api\HoldingController@listtemp');
Route::get('/holding/list', 'App\Http\Controllers\Api\HoldingController@listcurr');
Route::get('/holding/temp', 'App\Http\Controllers\Api\HoldingController@indextemp');
Route::get('/holding', 'App\Http\Controllers\Api\HoldingController@index');
Route::post('/holding/save', 'App\Http\Controllers\Api\HoldingController@save');
////
Route::get('/eaip-gen-content/text/{subId}', 'App\Http\Controllers\Api\EaipGenContentController@text');
Route::get('/gen/locindicator', 'App\Http\Controllers\Api\EaipGenContentController@locindicator');
Route::get('/gen/locindicator/temp', 'App\Http\Controllers\Api\EaipGenContentController@locindicatortemp');
Route::post('/gen/locindicator/save', 'App\Http\Controllers\Api\EaipGenContentController@locindicatorsave');
Route::post('/gen/locindicator/remove/{id}', 'App\Http\Controllers\Api\EaipGenContentController@locindicatorremove');
Route::post('/rwy/save', 'App\Http\Controllers\Api\RunwayController@save');
Route::post('/rwy/temp/save', 'App\Http\Controllers\Api\RunwayController@savetemp');
Route::post('/rwy/update/{tbl}', 'App\Http\Controllers\Api\RunwayController@update');
Route::post('/rwy/physical/save', 'App\Http\Controllers\Api\RunwayController@savephysical');
Route::post('/rwy/physical/temp/save', 'App\Http\Controllers\Api\RunwayController@savephysicaltemp');
Route::post('/rwy/physical/update/{tbl}', 'App\Http\Controllers\Api\RunwayController@updatephysical');
Route::post('/rwy/lgt/save', 'App\Http\Controllers\Api\RunwayController@savelgt');
Route::post('/rwy/lgt/remove/{tbl}', 'App\Http\Controllers\Api\RunwayController@removelgt');
Route::post('/rwy/lgt/update/{tbl}', 'App\Http\Controllers\Api\RunwayController@updatelgt');
Route::get('/rwyarpt/{tbl}', 'App\Http\Controllers\Api\RunwayController@AirportRwy');
Route::get('/rwy', 'App\Http\Controllers\Api\RunwayController@index');
Route::get('/rwy/temp', 'App\Http\Controllers\Api\RunwayController@indextemp');
Route::post('/rwy/remove/{id}', 'App\Http\Controllers\Api\RunwayController@remove');
Route::get('/rwy/list', 'App\Http\Controllers\Api\RunwayController@list');
Route::get('/rwy/thr', 'App\Http\Controllers\Api\RunwayController@GetThr');
Route::get('/rwy/thr/temp', 'App\Http\Controllers\Api\RunwayController@GetThrTemp');
Route::get('/rwy/lgt', 'App\Http\Controllers\Api\RunwayController@GetRwyLgt');
Route::post('/rawdata/save', 'App\Http\Controllers\Api\EaipController@saveupdaterawdata');
Route::get('/abbr', 'App\Http\Controllers\Api\EaipController@Getabbr');
Route::post('/abbr/remove/{id}', 'App\Http\Controllers\Api\EaipController@removeabbr');
Route::post('/abbr/save', 'App\Http\Controllers\Api\EaipController@saveabbr');
Route::get('/abbr/temp', 'App\Http\Controllers\Api\EaipController@Getabbrtemp');
Route::get('/eaip/type', 'App\Http\Controllers\Api\EaipController@ambilarray');
Route::get('/eaip/menu', 'App\Http\Controllers\Api\EaipController@menu');
Route::get('/eaip/menu/all/{pid}', 'App\Http\Controllers\Api\EaipController@menuall');
Route::get('/eaip/menu/one', 'App\Http\Controllers\Api\EaipController@levelOneMenu');
Route::get('/eaip/menu/two/{pid}', 'App\Http\Controllers\Api\EaipController@levelTwoMenu');
Route::get('/eaip/menu/three/{pid}', 'App\Http\Controllers\Api\EaipController@levelThreeMenu');
Route::get('/eaip/menu/list/{pid}', 'App\Http\Controllers\Api\EaipController@levelTwoMenulist');
Route::get('/getnewid/{pid}', 'App\Http\Controllers\Api\EaipController@getnewid');
Route::get('/cod/list/{tbl}', 'App\Http\Controllers\Api\EaipController@CodTypes');
Route::get('/cod/freq', 'App\Http\Controllers\Api\EaipController@CheckFreq');
Route::get('/getdistance', 'App\Http\Controllers\Api\EaipController@Getdistance');
Route::get('/data/temp', 'App\Http\Controllers\Api\EaipController@Gettempupdate');
Route::get('/temp', 'App\Http\Controllers\Api\EaipController@tempupdate');
Route::get('/data/tempdetail', 'App\Http\Controllers\Api\EaipController@gettempupdatefilter');
Route::post('/data/temp/update/{id}', 'App\Http\Controllers\Api\EaipController@updatetempupdate');
Route::post('/data/tempdetail/update/{id}', 'App\Http\Controllers\Api\EaipController@updatetempupdatedetail');
Route::post('/data/temp/save', 'App\Http\Controllers\Api\EaipController@savetempupdate');
Route::post('/data/tempdetail/save', 'App\Http\Controllers\Api\EaipController@savetempupdatedetail');
Route::post('/data/temp/remove', 'App\Http\Controllers\Api\EaipController@removetempupdate');
Route::post('/data/temp/removeall', 'App\Http\Controllers\Api\EaipController@removetempupdateallident');
Route::get('/airport/content/{tbl}', 'App\Http\Controllers\Api\EaipController@GetContent');
Route::get('/eaip/content', 'App\Http\Controllers\Api\EaipController@GetEaipContent');
Route::get('/eaip/contenttemp', 'App\Http\Controllers\Api\EaipController@GetEaipContenttemp');
Route::get('/eaip/temp', 'App\Http\Controllers\Api\EaipController@eaipcontenttemp');
Route::get('/eaip', 'App\Http\Controllers\Api\EaipController@eaipcontent');
Route::post('/eaip/temp/update/{id}', 'App\Http\Controllers\Api\EaipController@eaipchartcontenttempupdate');
Route::post('/eaip/temp/save', 'App\Http\Controllers\Api\EaipController@eaipchartcontenttempsave');
Route::post('/eaip/update/{id}', 'App\Http\Controllers\Api\EaipController@eaipchartcontentupdate');
Route::post('/eaip/save', 'App\Http\Controllers\Api\EaipController@eaipchartcontentsave');
Route::post('/eaip/temp/remove/{id}', 'App\Http\Controllers\Api\EaipController@eaipchartcontenttempremove');
Route::post('/eaip/remove/{id}', 'App\Http\Controllers\Api\EaipController@eaipchartcontentremove');

Route::get('/eaip/gen', 'App\Http\Controllers\Api\EaipController@eaipgencontent');
Route::get('/eaip/gen/content', 'App\Http\Controllers\Api\EaipController@gencontent');
Route::post('/eaip/gen/update/{id}', 'App\Http\Controllers\Api\EaipController@eaipgencontentupdate');
Route::post('/eaip/gen/save', 'App\Http\Controllers\Api\EaipController@eaipgencontentsave');
Route::post('/eaip/gen/remove/{id}', 'App\Http\Controllers\Api\EaipController@eaipgencontentremove');
Route::get('/eaip/gen/temp', 'App\Http\Controllers\Api\EaipController@eaipgencontenttemp');
Route::post('/eaip/gen/temp/update/{id}', 'App\Http\Controllers\Api\EaipController@eaipgencontenttempupdate');
Route::post('/eaip/gen/temp/save', 'App\Http\Controllers\Api\EaipController@eaipgencontenttempsave');
Route::post('/eaip/gen/remove/{id}', 'App\Http\Controllers\Api\EaipController@eaipgencontenttempremove');

Route::get('/eaip/getrequest', 'App\Http\Controllers\Api\EaipController@getcontentrequest');
Route::get('/eaip/getrequestdetail/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestdetail');
Route::get('/eaip/getrequestapron/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestapron');
Route::get('/eaip/getrequestparkingstand/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestparkingstand');
Route::get('/eaip/getrequestpushback/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestpushback');
Route::get('/eaip/getrequestobstacle/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestobstacle');
Route::get('/eaip/getrequestrwy/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestrwy');
Route::get('/eaip/getrequestrwyphysical/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestrwyphysical');
Route::get('/eaip/getrequestrwylight/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestrwylight');
Route::get('/eaip/getrequestcomm/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestcomm');
Route::get('/eaip/getrequestnavaid/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestnavaid');
Route::get('/eaip/getrequestils/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestils');
Route::get('/eaip/getrequestmarker/{id}/{tbl}', 'App\Http\Controllers\Api\EaipController@getcontentrequestmarker');


Route::get('/eaip/getutc', 'App\Http\Controllers\Api\EaipController@getutctime');
Route::get('/eaip/getpaper', 'App\Http\Controllers\Api\EaipController@Getpaper');
Route::get('/eaip/codaip', 'App\Http\Controllers\Api\EaipController@Codaip');
Route::get('/eaip/codaipsub', 'App\Http\Controllers\Api\EaipController@Codaipsub');
Route::get('/eaip/codtableheader', 'App\Http\Controllers\Api\EaipController@Codtableheader');
Route::get('/cod/chart', 'App\Http\Controllers\Api\EaipController@Codcharttypes');
Route::get('/arpt/aprontwy', 'App\Http\Controllers\Api\EaipApronTwyController@ApronTwylist');
Route::get('/arpt/parkingstand', 'App\Http\Controllers\Api\EaipApronTwyController@ParkingStandlist');
Route::get('/arpt/temp/aprontwy', 'App\Http\Controllers\Api\EaipApronTwyController@ApronTwylisttemp');
Route::get('/arpt/temp/parkingstand', 'App\Http\Controllers\Api\EaipApronTwyController@ParkingStandlisttemp');
Route::post('/eaip/apron/save', 'App\Http\Controllers\Api\EaipApronTwyController@saveapron');
Route::post('/eaip/apron/update/{id}', 'App\Http\Controllers\Api\EaipApronTwyController@updateapron');
Route::post('/eaip/apron/remove/{id}', 'App\Http\Controllers\Api\EaipApronTwyController@removeapron');
Route::post('/eaip/parkingstand/save', 'App\Http\Controllers\Api\EaipApronTwyController@saveparkingstand');
Route::post('/eaip/parkingstand/update/{id}', 'App\Http\Controllers\Api\EaipApronTwyController@updateparkingstand');
Route::post('/eaip/parkingstand/remove/{id}', 'App\Http\Controllers\Api\EaipApronTwyController@removeparkingstand');
Route::get('/arpt/pushback', 'App\Http\Controllers\Api\EaipApronTwyController@Pushbacklist');
Route::post('/eaip/pushback/save', 'App\Http\Controllers\Api\EaipApronTwyController@savepushback');
Route::post('/eaip/pushback/update/{id}', 'App\Http\Controllers\Api\EaipApronTwyController@updatepushback');
Route::post('/eaip/pushback/remove/{id}', 'App\Http\Controllers\Api\EaipApronTwyController@removepushback');
Route::get('/arpt/temp/pushback', 'App\Http\Controllers\Api\EaipApronTwyController@Pushbacklisttemp');
Route::post('/eaip/temp/pushback/save', 'App\Http\Controllers\Api\EaipApronTwyController@savepushbacktemp');
Route::post('/eaip/temp/pushback/update/{id}', 'App\Http\Controllers\Api\EaipApronTwyController@updatepushbacktemp');
Route::post('/eaip/temp/pushback/remove/{id}', 'App\Http\Controllers\Api\EaipApronTwyController@removepushbacktemp');
Route::get('/eaip/obstacleaoc/{id}', 'App\Http\Controllers\Api\ObstacleController@getobstacleaoc');
Route::get('/eaip/obstacletemp', 'App\Http\Controllers\Api\ObstacleController@indextemp');
Route::get('/eaip/obstacle', 'App\Http\Controllers\Api\ObstacleController@index');
Route::post('/eaip/obstacle/save', 'App\Http\Controllers\Api\ObstacleController@save');
Route::post('/eaip/obstacle/remove/{id}', 'App\Http\Controllers\Api\ObstacleController@remove');
Route::get('/source', 'App\Http\Controllers\Api\DataSourceController@source');
Route::get('/eaip/datasource', 'App\Http\Controllers\Api\DataSourceController@index');
Route::get('/eaip/codeaip/{id}', 'App\Http\Controllers\Api\DataSourceController@codeaip');
Route::get('/eaip/list/datasource', 'App\Http\Controllers\Api\DataSourceController@list');
Route::post('/eaip/datasource/save', 'App\Http\Controllers\Api\DataSourceController@save');
Route::post('/eaip/datasource/update/{id}', 'App\Http\Controllers\Api\DataSourceController@update');
Route::post('/eaip/datasource/remove/{id}', 'App\Http\Controllers\Api\DataSourceController@remove');
Route::get('/eaip/datasource/section/{id}', 'App\Http\Controllers\Api\DataSourceController@section');
Route::get('/eaip/datasource/page/{id}', 'App\Http\Controllers\Api\DataSourceController@subsection');
Route::get('/publication', 'App\Http\Controllers\Api\PublicationController@index');
Route::post('/publication/save', 'App\Http\Controllers\Api\PublicationController@save');
Route::post('/publication/update/{id}', 'App\Http\Controllers\Api\PublicationController@update');
Route::post('/publication/remove/{id}', 'App\Http\Controllers\Api\PublicationController@remove');
Route::post('/publication/detail/save', 'App\Http\Controllers\Api\PublicationController@savedetail');
Route::post('/publication/detail/update/{id}', 'App\Http\Controllers\Api\PublicationController@updatedetail');
Route::post('/publication/detail/remove/{id}', 'App\Http\Controllers\Api\PublicationController@removedetail');
Route::get('notam', 'App\Http\Controllers\Api\PublicationController@getnotam');
Route::get('/airac', 'App\Http\Controllers\Api\PublicationController@airac');
Route::get('/user', 'App\Http\Controllers\Api\PublicationController@userindex');
Route::get('/user/org', 'App\Http\Controllers\Api\PublicationController@orglist');
Route::get('/user/group', 'App\Http\Controllers\Api\PublicationController@usergrouplist');
Route::post('/user/save', 'App\Http\Controllers\Api\PublicationController@saveuser');

Route::get('/tablereff', 'App\Http\Controllers\Api\PublicationController@tablereff');
Route::post('/user/update/{id}', 'App\Http\Controllers\Api\PublicationController@updateuser');
Route::get('/req/status', 'App\Http\Controllers\Api\PublicationController@codstatus');
Route::get('/pub/rawdata/onprocess', 'App\Http\Controllers\Api\PublicationController@rawdatadalamprosespublication');
Route::get('/pub/rawdata', 'App\Http\Controllers\Api\PublicationController@rawdataindex');
Route::get('/pub/rawdatadetail', 'App\Http\Controllers\Api\PublicationController@rawpubdetail');
Route::get('/pub/rawdatachart', 'App\Http\Controllers\Api\PublicationController@getchartaffect');
Route::post('/pub/rawdatadetail/save', 'App\Http\Controllers\Api\PublicationController@saverawpubdetail');
Route::post('/pub/rawdatadetail/update/{id}', 'App\Http\Controllers\Api\PublicationController@updaterawpubdetail');
Route::post('/pub/rawdata/save', 'App\Http\Controllers\Api\PublicationController@saverawdata');
Route::post('/pub/rawdata/update/{id}', 'App\Http\Controllers\Api\PublicationController@updaterawdata');
Route::post('/pub/rawdata/remove/{id}', 'App\Http\Controllers\Api\PublicationController@removerawdata');
Route::get('/pub/rawdataatt', 'App\Http\Controllers\Api\PublicationController@rawpubatt');
Route::post('/pub/rawdataatt/save', 'App\Http\Controllers\Api\PublicationController@saverawpubatt');
Route::post('/pub/uppload', 'App\Http\Controllers\Api\PublicationController@uploadfile');
Route::get('/rawpublication', 'App\Http\Controllers\Api\PublicationController@publication');
Route::post('/rawpublication/save', 'App\Http\Controllers\Api\PublicationController@savepublication');
Route::post('/rawpublication/update/{id}', 'App\Http\Controllers\Api\PublicationController@updatepublication');
//source nr
Route::get('/sourcenr', 'App\Http\Controllers\Api\PublicationController@getsourcenr');
Route::post('/sourcenr/save', 'App\Http\Controllers\Api\PublicationController@sourcenrsave');
Route::post('/sourcenrseg/save', 'App\Http\Controllers\Api\PublicationController@sourcenrsegsave');
//Volcano n CDM
Route::get('/volcano', 'App\Http\Controllers\Api\VolcanoController@index');
Route::post('/volcano/update/{id}', 'App\Http\Controllers\Api\VolcanoController@volcanoupdate');
Route::get('/vol/vona', 'App\Http\Controllers\Api\VolcanoController@lastvona');
Route::get('/vol/ashtam', 'App\Http\Controllers\Api\VolcanoController@lastashtam');
Route::get('/getsigmet', 'App\Http\Controllers\Api\AstamController@getsigmet');
Route::get('/getashtam', 'App\Http\Controllers\Api\AstamController@getData');
Route::get('/getnotam/{icao}', 'App\Http\Controllers\Api\AstamController@getnotam');
Route::get('/getmetar/{icao}', 'App\Http\Controllers\Api\AstamController@getmetar');
Route::get('/getspeci/{icao}', 'App\Http\Controllers\Api\AstamController@getspeci');
Route::get('/gettaf/{icao}', 'App\Http\Controllers\Api\AstamController@gettaf');
Route::post('/vol/vona/save', 'App\Http\Controllers\Api\VolcanoController@savevona');
Route::post('/vol/ashtam/save', 'App\Http\Controllers\Api\VolcanoController@saveashtam');

Route::get('/vol/request', 'App\Http\Controllers\Api\VolcanoController@lastrequest');
Route::get('/vol/publication', 'App\Http\Controllers\Api\VolcanoController@lastpublication');
Route::get('/vol/cdmlog', 'App\Http\Controllers\Api\VolcanoController@cdmlog');
Route::get('/vol/cdmchat', 'App\Http\Controllers\Api\VolcanoController@cdmchat');
Route::get('/vol/cdmuser', 'App\Http\Controllers\Api\VolcanoController@cdmuser');

Route::get('/vol/txcdm', 'App\Http\Controllers\Api\VolcanoController@txcdm');
Route::post('/vol/txcdm/save', 'App\Http\Controllers\Api\VolcanoController@txcdmsave');
Route::post('/vol/txcdm/update/{id}', 'App\Http\Controllers\Api\VolcanoController@txcdmupdate');
//utnuk json autocad
Route::get('/aeross/menu/{id}', 'App\Http\Controllers\Api\EchartController@getmenuawal');
Route::get('/userlog', 'App\Http\Controllers\Api\EchartController@getuserlog');
Route::post('/userlog', 'App\Http\Controllers\Api\EchartController@getuserlogsave');

//Transition
Route::get('/frame/chart', 'App\Http\Controllers\Api\ProcedureController@framechart');
Route::post('/chartprop/save', 'App\Http\Controllers\Api\ProcedureController@chartpropsave');
Route::post('/minima/save', 'App\Http\Controllers\Api\ProcedureController@saveminima');
Route::post('/frame/save', 'App\Http\Controllers\Api\ProcedureController@framechartsave');
Route::get('/proc/chart', 'App\Http\Controllers\Api\ProcedureController@chartprop');
Route::get('/arpt/proc', 'App\Http\Controllers\Api\ProcedureController@arptproc');
Route::get('/transition', 'App\Http\Controllers\Api\ProcedureController@transition');
Route::get('/procedures', 'App\Http\Controllers\Api\ProcedureController@procedures');
Route::get('/transition/temp', 'App\Http\Controllers\Api\ProcedureController@transitiontemp');
Route::get('/transition/seg/temp', 'App\Http\Controllers\Api\ProcedureController@transitionsegtemp');
Route::get('/procedures/temp', 'App\Http\Controllers\Api\ProcedureController@procedurestemp');
Route::get('/procedures/seg/temp', 'App\Http\Controllers\Api\ProcedureController@getnoteprocedurestemp');
Route::post('/procedure/temp/save', 'App\Http\Controllers\Api\ProcedureController@saveprocedure');
Route::post('/transition/temp/save', 'App\Http\Controllers\Api\ProcedureController@savetrans');
// cec
Route::get('/getfpl', 'App\Http\Controllers\Api\ProcedureController@getfpl');


Route::group(['prefix' => '/v2', 'namespace' => 'App\Http\Controllers\Api\V2'], function() {
    Route::post('/authenticate', 'SecurityController@authenticate');
    Route::group(['middleware' => 'mobile'], function() {
        Route::get('/me', 'SecurityController@me');
        Route::get('/conference/group', 'ConferenceController@group');
        Route::get('/conference/types', 'ConferenceController@types');
        Route::get('/conference/chat/{id}', 'ConferenceController@chat');
        Route::post('/conference/chat/{id}/post', 'ConferenceController@postChat');
    });
    Route::group(['middleware' => ['airnav', 'CORS']], function() {
    	Route::post('/aftn', 'AftnController@store');
    });
    Route::group(['middleware' => ['puta', 'CORS']], function() {
    	// Route::post('/aftn', 'AftnController@store');
    	Route::get('/puta/airport', 'PutaController@airport');
		Route::get('/puta/airspace', 'PutaController@airspace');
		Route::get('/puta/suas', 'PutaController@suas');
    });
});
