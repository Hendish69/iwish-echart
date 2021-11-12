<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiResponse;

class AstamController extends Controller
{
    private function _initCurl($url, $token=null){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$token
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
    public function getData(){
        $ret = $this->_getData();
        return $ret;
    }
    private function _getData(){
        $tgl = date('y-m-d');
        $url     = "http://36.67.210.229:8899/S041RN4V/WAAF/".$tgl;
        $response = $this->_initCurl($url);
        // print($response);
        // return ApiResponse::success($results);
        // $return = json_decode($response);
        return ApiResponse::success(json_decode($response));
        // $return = $response;
        // return $return;
    }

    public function getnotam($icao){
      $ret = $this->_getNotam($icao);
      return $ret;
    }
    private function _getNotam($icao){
       
        $url     = "http://aim-jakarta.co.id/searchpib/?link=view2&aero1=".$icao;
        $response = $this->_initCurl($url);
        // print($response);
        // return ApiResponse::success($results);
        // $return = json_decode($response);
        // return ApiResponse::success(json_decode($response));
        return ApiResponse::success($response);
        // $return = $response;
        // return $return;
    }
    public function getsigmet(){
      $ret = $this->_getsigmet();
      return $ret;
    }
    private function _getsigmet(){
        $url     = "https://rami.bmkg.go.id/api/siam/code/current/sigmet";
        $response = $this->_initCurl($url);
        // print($response);
        // return ApiResponse::success($results);
        $data = json_decode($response);
        // dd($data->data);
        return ApiResponse::success($data->data);
        // return ApiResponse::success($response);
        // $return = $response;
        // return $return;
    }
    public function getmetar($icao){
      $ret = $this->_getMetar($icao);
      return $ret;
    }
    private function _getMetar($icao){
      $yr = date('Y');
      $m = date('m');
      // dd($yr,$m);
        
        $url     = "http://aviation.bmkg.go.id/latest/metar.php?i=".$icao."&y=".$yr."&m=".$m;
        $response = $this->_initCurl($url);
        // print($response);
        // return ApiResponse::success($results);
        // $return = json_decode($response);
        // return ApiResponse::success(json_decode($response));
        return ApiResponse::success($response);
        // $return = $response;
        // return $return;
    }
    public function getspeci($icao){
      $ret = $this->_getSpeci($icao);
      return $ret;
    }
    private function _getSpeci($icao){
      $yr = date('Y');
      $m = date('m');
      // dd($yr,$m);
        
        $url     = "http://aviation.bmkg.go.id/latest/speci.php?i=".$icao."&y=".$yr."&m=".$m;
        $response = $this->_initCurl($url);
        // print($response);
        // return ApiResponse::success($results);
        // $return = json_decode($response);
        // return ApiResponse::success(json_decode($response));
        return ApiResponse::success($response);
        // $return = $response;
        // return $return;
    }

    public function gettaf($icao){
      $ret = $this->_getTaf($icao);
      return $ret;
    }
    private function _getTaf($icao){
      $yr = date('Y');
      $m = date('m');
      // dd($yr,$m);
        
        $url     = "http://aviation.bmkg.go.id/latest/taf.php?i=".$icao."&y=".$yr."&m=".$m;
        $response = $this->_initCurl($url);
        // print($response);
        // return ApiResponse::success($results);
        // $return = json_decode($response);
        // return ApiResponse::success(json_decode($response));
        return ApiResponse::success($response);
        // $return = $response;
        // return $return;
    }

}
