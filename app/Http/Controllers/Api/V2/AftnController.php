<?php
namespace App\Http\Controllers\Api\V2;  
use app\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Response; 
use App\ApiResponse;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator; 
use App\Models\Api\CecAftnlog as Aftnlog;
use App\Models\Api\CecFpl as Fpl;
use App\Models\Api\CecAirport;
use App\Models\Api\CecAcft;
use App\Models\Api\CecEmission;

use DateTime; 

class AftnController 
{      
	private $log_id, $param;

  	function __construct() {
	    $this->param = (object) [
			'findDepLess'=> 30,
	  		'findDepMore'=> 30,
	  		'longestFltDuration'=> 900
  		];
	}
  	public function isDomestic ($airport) {
		$pattern = "/WI|WA/i";
		return preg_match_all($pattern, $airport);
	}
	public function getDofToEobt($eobt, $other) {
		$string = strtoupper($other); 
  		$prefix = "DOF/";
		$index = strpos($string, $prefix) + strlen($prefix);
		$result = substr($string, $index,6);
		 
		$eobts = str_split($eobt, 2);
		$newEobt = $eobts[0].':'.$eobts[1].':00';

		$DTime = DateTime::createFromFormat('ymd',$result); 
		$newDateString = $DTime->format('Y-m-d'); 
		// make like this 2021-06-11 03:50:00
		return $newDateString.' '.$newEobt;
	}
	private function getDofToAtd($atd,$thedof) {
	 	$time = str_split($atd, 2);
		$newEobt = $time[0].':'.$time[1].':00';
		$newDateString=  gmdate("Y-m-d", $thedof); //convert unix time
		return $newDateString.' '.$newEobt;
	}
	public function store(Request $request)
	{ 
		// var_dump($request->all());die;
		$datas;
		try{
			$datas = $request->all()['data'];  
			// save to aftn log
			if(null !== $datas){
				$aftn = new Aftnlog;
		        $aftn->data = $datas;
		        $aftn->save();
			}
			$this->log_id = $aftn->id; 
		}catch(\Exception $e){
	    	// \Log::Info('error data json :'.$e->getMessage());
	     //    echo $e->getMessage();
	        return Response::json([
                    'error'     => 400 ,
                    'message'   => 'error data json : '.$e->getMessage(),
                ], 400 );
	    }
		// partition data
		foreach ($datas as $data) { 
			switch ($data['msgType']) {
				case 'FPL': $this->procFPL($data);
					break;
				case 'DEP': $this->procDEP($data);
					break; 
				case 'ARR': $this->procARR($data);
					break;
			} 
		}	
		
		
	}
	public function procFPL($data){
		$_acid 		= isset($data['acid']) ? $data['acid']:'';
		
		$_adep_id 	= isset($data['adep']) ? $data['adep']:''; 
		$_ades_id 	= isset($data['ades']) ? $data['ades']:'';

		$_acft_id   = isset($data['acType']) ? $data['acType']: '';

		if(false === CecAcft::where('icao',$_acft_id)->exists()){
			\Log::Info('FPL not exist -> aircraft (icao): '.$_acft_id);
			return ApiResponse::error('FPL not exist -> aircraft (icao): ',$_acft_id);
		}
		if(false === CecAirport::where('icao',$_adep_id)->exists()){
			\Log::Info('FPL log not exist -> departure airport (icao): '.$_adep_id);
			return ApiResponse::error('FPL log not exist -> departure airport (icao): ',$_adep_id);
		}
		if(false === CecAirport::where('icao',$_ades_id)->exists()){
			\Log::Info('FPL log not exist -> destination airport (icao): '.$_ades_id);
			return ApiResponse::error('FPL log not exist -> destination airport (icao): ',$_ades_id);
		}

		$_eobt 		= $this->getDofToEobt($data['eobt'],$data['other']);
		$range 		= $this->dateBetweenDep($_eobt);
		
		

		$logU = Aftnlog::find($this->log_id);
		// check if FPL is exist
		if(!$this->is_existFPL($_acid,$range[0],$range[1],$_adep_id,$_ades_id)['status']){
			$logU->status= 1; 
			try{
		       	$fpls = new Fpl;
				$fpls->acid 		= $_acid;
				$fpls->adep_id 		= $_adep_id;
				$fpls->ades_id 		= $_ades_id; 
				$fpls->ftype 		= $this->isDomestic($data['adep']) && $this->isDomestic($data['ades']) ? 'D' : 'I' ;
				$fpls->eobt 		= $_eobt;
				$fpls->eet 			= isset($data['eet']) ? (int)$data['eet'] : NULL;
				$fpls->atd 			= isset($data['atd']) ? $data['atd']: NULL;
				$fpls->ata 			= isset($data['ata']) ? $data['ata']: NULL;
				$fpls->acft_id 		= $_acft_id;
				
				$fpls->emission_id 	= isset($data['emission']) ? $data['emission']:NULL;
				$fpls->rfl 			= isset($data['level']) ? $data['level']:NULL;
				$fpls->route 		= isset($data['route']) ? $data['route']:NULL;
				$fpls->save();
			    }
			    catch(\Exception $e){
			    	$logU->status= NULL; 
			    	\Log::Info('error save FPL :'.$e->getMessage());
			        echo $e->getMessage();
			    }
			$logU->save();
		}else{
			 \Log::Info('FPL exist -> acid: '.$_acid.'- eobt: '.$_eobt.'- adep: '.$_adep_id.'- ades: '.$_ades_id );
			 return ApiResponse::error('FPL exist ACID -> ',$_acid);
		}
	}
	public function updateFPL($id,$data){
		$Fpls = FPL::find($id);
		try{
			foreach ($data as $field => $value){
				$Fpls->$field = $value;
			}
			$Fpls->save();

		} catch(\Exception $e){
			\Log::Info('error save FPL :'.$e->getMessage());
			// return abort(404,'error save FPL -> '.$e->getMessage());
			echo $e->getMessage();
		}

	}
	public function is_existFPL($acid,$from_time,$to_time,$adep,$ades){
		 
		$fpl['status'] = Fpl::where('acid', '=', $acid)
				->whereBetween('eobt', [$from_time, $to_time])
				->where('adep_id','=',$adep)
				->where('ades_id','=',$ades)
				->exists();
	 
		$acft = DB::table('cec_acft')->selectRaw('cec_acft.*');
		$arpt = DB::table('cec_airport')->selectRaw('cec_airport.*');

		// $fpl['data'] =  DB::table('cec_fpl')
		// 		->rightJoin('cec_acft', function ($join) {
		// 			$join->on('cec_acft.icao', '=', 'cec_fpl.acft_id');
		//         })
		//         ->rightJoin('cec_airport as dep', function ($join) {
		// 			$join->on('dep.icao', '=', 'cec_fpl.adep_id');
		//         })
		//         ->rightJoin('cec_airport as des', function ($join) {
		// 			$join->on('des.icao', '=', 'cec_fpl.ades_id');
		//         })
		// 		->where('acid', '=', $acid)
		// 		->whereBetween('eobt', [$from_time, $to_time])
		// 		->where('adep_id','=',$adep)
		// 		->where('ades_id','=',$ades)
		// 		->first();
		$fpl['data'] =  DB::table('cec_fpl')
						->leftJoinSub($acft,'acft', function($join){
							$join->on('cec_fpl.acft_id','=','acft.icao');
						})
						->leftJoinSub($arpt,'dep', function($join){
							$join->on('cec_fpl.adep_id','=','dep.icao');
						})
						->leftJoinSub($arpt,'des', function($join){
							$join->on('cec_fpl.ades_id','=','des.icao');
						})
						->selectRaw('cec_fpl.*, acft.*, dep.* , 
									des.icao as sicao,
									des.taxiout as staxiout,
									des.gndholding as sgndholding,
									des.arrholding as sarrholding,
									des.approach as sapproach,
									des.taxiin as staxiin,
									des.dep_features as sdep_features,
									des.arr_features as sarr_features,
									des.location as slocation')
						->where('acid', '=', $acid)
						->whereBetween('eobt', [$from_time, $to_time])
						->where('adep_id','=',$adep)
						->where('ades_id','=',$ades)
						->first();
		// $fpl['data'] = Fpl::where('acid', '=', $acid)
		// 					->whereBetween('eobt', [$from_time, $to_time])
		// 					->where('adep_id','=',$adep)
		// 					->where('ades_id','=',$ades)
		// 					->with(['acft','depAirport','desAirport'])
		// 					->get();
		
		return $fpl;
	}
	public function procDEP($data){
		
		$newAtd 	= $this->getDofToAtd($data['atd'],$data['dof']);
		$range 		= $this->dateBetweenDep($newAtd);
		 
		$_acid 		= isset($data['acid']) ? $data['acid']:'';
		$_adep_id 	= isset($data['adep']) ? $data['adep']:''; 
		$_ades_id 	= isset($data['ades']) ? $data['ades']:'';
		$currFPL 	= $this->is_existFPL($_acid,$range[0],$range[1],$_adep_id,$_ades_id); 
		if(!$currFPL['status']){
			\Log::Info('FPL for DEP not found: '.$_acid); 
			return ApiResponse::error('FPL for DEP not found: '.$_acid);
		}else{
			$id = $currFPL['data']->id;
			$data2Save = [ 'atd'=>$newAtd ];
			$this->updateFPL($id,$data2Save); 
		}

	}
	private function dateBetweenDep($date){
		// Convert datetime to Unix timestamp
		$datetime  = date($date);
		$timestamp = strtotime($datetime);
		// Subtract / add time 30 minute from datetime
		$_time = $timestamp - ($this->param->findDepLess * 60); 
		$time_ = $timestamp + ($this->param->findDepMore * 60);
		$from_time =  date("Y-m-d H:i:s", $_time);
		$to_time   =  date("Y-m-d H:i:s", $time_); 
		return [$from_time,$to_time];
	}
	public function procARR($data){
		$newAta 	= $this->His2fulldate($data['ata']);
		$beforeArr 	= $this->dateBeforeArr($newAta); 
		
		$_acid 		= isset($data['acid']) ? $data['acid']:'';
		$_adep_id 	= isset($data['adep']) ? $data['adep']:''; 
		$_ades_id 	= isset($data['ades']) ? $data['ades']:'';
		// var_dump($beforeArr);die;

		$currFPL 	= $this->is_existFPL($_acid,$beforeArr, $newAta ,$_adep_id,$_ades_id ); 
		
		if($currFPL['status'] === false){
			
			\Log::Info('FPL for ARR not found: '.$_acid); 
			// echo 'FPL for ARR not found: '.$_acid;
			return ApiResponse::error('FPL for ARR not found:', $_acid);
		}else{
			
			$id = $currFPL['data']->id;
			$data2Save = [ 'ata'=>$newAta ];

			if($currFPL['data']->ata != NULL || $currFPL['data']->ata !='') {  
				\Log::Info('FPL already have ATA: '.$_acid);
				return ApiResponse::error('FPL already have ATA:', $_acid);
			}
			$this->updateFPL($id,$data2Save); 
			if(null === $currFPL['data']->atd || $currFPL['data']->atd ==='') {  
				\Log::Info('FPL don\'t have ATD: '.$_acid); 
				return ApiResponse::error('FPL don\'t have ATD: ', $_acid);
			}
			// var_dump($currFPL['data']);die;
			$currFPL['data']->ata = $newAta;
			$_emission = $this->calculateEmission($currFPL['data']);
			if(is_object($_emission)){
				$this->updateFplEmission($_emission);	
			}else{
				\Log::Info('FPL can\'t Calculte emission: '.$_acid); 	
				return ApiResponse::error('FPL can\'t Calculte emission: ', $_acid);
			}
		} 
	}
	private function updateFplEmission($em){
		$fpl_id = $em->fpl_id;
		try {
			$emi = new CecEmission;
			$emi->emissionstart		= $em->emissionstart;
			$emi->emissiontaxiout	= $em->emissiontaxiout;
			$emi->emissiongndholding= $em->emissiongndholding;
			$emi->emissiontakeoff	= $em->emissiontakeoff;
			$emi->emissionclimb		= $em->emissionclimb;
			$emi->emissioncruise	= $em->emissioncruise;
			$emi->emissiondescend	= $em->emissiondescend;
			$emi->emissionholding	= $em->emissionholding;
			$emi->emissionapproach	= $em->emissionapproach;
			$emi->emissionlanding	= $em->emissionlanding;
			$emi->emissiontaxiin	= $em->emissiontaxiin;
			$emi->fpl_id			= $em->fpl_id;
			$emi->emissiontotal		= $em->emissiontotal;
			$emi->save();
		} catch(\Exception $e){
	    	\Log::Info('error save emission :'.$e->getMessage());
	        echo $e->getMessage();
		}

		if(!$emi->id){
		   \Log::Info('Emission data can\'t be save (FPL ID): '.$fpl_id); 
		}else{
			// update FPL data 
			$data2Save = [ 'emission_id'=>$emi->id ];
			$this->updateFPL($fpl_id,$data2Save); 
		}
	}
	private function calculateEmission($flt) {
		// var_dump($flt);
		$acft 	= $flt->acft_id;
	    $dep  	= $flt->adep_id;
	    $des  	= $flt->ades_id;
	    $atd  	= $flt->atd;
	    $ata  	= $flt->ata;
	    $em 	= (object)[];
	    // var_dump('$ata : '.$ata);
	    $tClimb 		= round(($flt->rfl * 100) / $flt->rateclimb,2); // $flt->rfl inputan cruise flight level
	    // var_dump('$tClimb :'.$tClimb);
	    $tTakeOff 		= $tClimb < $flt->ttakeoff ? $tClimb : $flt->ttakeoff;
	    // var_dump('$tTakeOff :'.$tTakeOff);
		$tCleanClimb	= $tClimb < $flt->ttakeoff ? 0 : $tClimb - $tTakeOff;
		// var_dump('$tCleanClimb :'.$tCleanClimb);
		$atReach 		= $this->addMinutesToDate($atd, $tClimb); // ~*
		// var_dump('$atReach :'.$atReach);
		$tDescend 		= round(($flt->rfl * 100) / $flt->ratedescend,2);
		// var_dump('$tDescend :'.$tDescend);
		$tCleanDescend 	= $tDescend < $flt->sapproach ? 0 : $tDescend - $flt->sapproach;
		// var_dump('$tCleanDescend :'.$tCleanDescend);
		// $minDescend 	= int()$tDescend * (-1);
		$minDescend = -1 * abs($tDescend);
		$atDescend 		= $this->addMinutesToDate($ata, $minDescend); // ~*
		// var_dump('$atDescend :'.$atDescend);
				
		$tCruise 		= $this->getDuration($atReach, $atDescend); // Route Distance / Speed 
		// var_dump('$tCruise :'.$tCruise);
		// Emissions
		$em->emissionstart 	 	= round($flt->tstartup * $flt->eridle,2);

		$em->emissiontaxiout 	= round($flt->taxiout * $flt->ertaxi,2);
		$em->emissiongndholding = round(($flt->gndholding + $flt->tidle) * $flt->eridle,2);
		$em->emissiontakeoff 	= round($flt->ttakeoff * $flt->erfull,2);
		$em->emissionclimb 		= round($tCleanClimb * $flt->erclimb,2);
		$em->emissioncruise 	= round($tCruise * $flt->ercruise,2);
		
		$em->emissiondescend 	= round($tCleanDescend * $flt->erdescend,2);
		$em->emissionholding 	= round($flt->sarrholding * $flt->erholding,2);
		$em->emissionapproach 	= round($flt->sapproach * $flt->erapch,2);
		$em->emissionlanding 	= round($flt->tlanding * $flt->erlanding,2);
		$em->emissiontaxiin 	= round($flt->staxiin * $flt->ertaxi,2);
		$em->emissiontotal 		= $em->emissionstart + $em->emissiontaxiout + $em->emissiongndholding + $em->emissiontakeoff + $em->emissionclimb + $em->emissioncruise + $em->emissiondescend + $em->emissionholding + $em->emissionapproach + $em->emissionlanding + $em->emissiontaxiin;
		$em->fpl_id = $flt->id; 
  		// echo json_encode($em);
  		return $em;

	}
	private function His2fulldate($time) {
		if($time!=''){
			$dt = date('Y-m-d'); 
			$date = str_split($time, 2);
			return $dt.' '.$date[0].':'.$date[1].':00';
		}
	}
	private function dateBeforeArr($date) {
		$time = strtotime($date);
		$time = $time - ((int)$this->param->longestFltDuration * 60);
		$retn = date("Y-m-d H:i:s", $time);
		return $retn ;
	}
	
	private function is_existACFT($id){
		return CecAcft::findOrFail($id);
	}
	private function is_existAIRPORT($id){
		return CecAirport::findOrFail($id);
	}	 	 
	private function addMinutesToDate($theDate, $theMinutes) {
	   $datetime = date($theDate);
		// Convert datetime to Unix timestamp
		$timestamp = strtotime($datetime);
		//  add time 
		$theMinutes = (int) $theMinutes;
		
		$time_ = $timestamp + ($theMinutes * 60); 
	
		$to_time   =  date("Y-m-d H:i:s", $time_);  
		return $to_time;
	}
	function getDuration($old, $lat){
		$date1 = date_create($old); 
		$date2 = date_create($lat); 
		$difference = date_diff($date1, $date2);     
	    $minutes = $difference->days * 24 * 60;
	    $minutes += $difference->h * 60;
	    $minutes += $difference->i;
	    return $minutes;
	}

}