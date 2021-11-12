<?php
namespace App\Http\Controllers;

use Session;
use \Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Route;
use Illuminate\Http\Request as Req;
use App\Models\Api\Content;
use App\Models\Api\EaipChartContentTemp as etemp;
use App\Models\Api\RawdataPub as RawPub;

use Auth;
class GenController extends Controller
{
    public function index($id,$text)
    { 
        
        $originalInput=Request::input();
        $user = Auth::user();

        $data['id'] = $id;
        $data['text'] = $text; 
        $konten =  Content::where("section_id", $id)->first(); 
        $data['konten'] = isset($konten->body)?$konten->body : '';
        $data['arpt'] = '';
        $data['text'] = $text;
       
            $konten =  Content::where('section_id','=',$id)->first(); 
            
        
        // $konten =  Content::where("section_id", $id)->first(); 
        // dd($konten);
        if ($konten==null){
            $isi='';
            $cont= getDataApi($originalInput, 'api/eaip/gen/temp?sub_id='.$id.'&tbl=GEN&sort=seq:asc');
            if (!is_array($cont)){
                $isi='<b><i>Reserved</i></b>';
            }else{
                foreach ($cont as $key => $value) {
                    $tab='';
                    switch ($value->tab) {
                        case '30':
                            $tab='&emsp;';
                            break;
                        case '60':
                            $tab='&emsp;&emsp;';
                            break;
                        case '90':
                            $tab='&emsp;&emsp;&emsp;';
                            break;
                        default:
                        $tab='&nbsp;';
                            break;
                    }
                    if ($value->font=='B'){
                        $isi =$isi.'<br><b>'.$value->content.'</b>';
                    }else{
                        $isi =$isi.'<br>'.$tab.$value->content;
                    }
                }
                // dd( $isi);
            }
            $data['konten']=$isi;
        }else{
            
            $data['konten'] = isset($konten->body)?$konten->body : '';
        }
        return view("pages.publications.gen.index", $data);
    }

    public function adinfo($id,$arptident,$text)
    {  
        $originalInput=Request::input();
        $user = Auth::user();
        $data['id'] = $arptident;
        $data['arpt'] = $id;
        $data['text'] = $text;

            $konten =  Content::where('section_id','=',$arptident)->where('title','=',$text)->first(); 
      
        // $konten =  Content::where("section_id", $id)->first(); 
        // dd($konten,$text,$text);
        if ($konten==null){
            $isi='';
            $cont= getDataApi($originalInput, 'api/eaip/contenttemp?arpt_ident='.$arptident.'&category_id='.$id.'&sort=sequence:asc');
            // dd(count($cont));
            if (count($cont)==0){
                $isi='<b><i>Reserved</i></b>';
            }else{
                foreach ($cont as $key => $value) {
                    $tab='';
                    switch ($value->tab) {
                        case '30':
                            $tab='&emsp;';
                            break;
                        case '60':
                            $tab='&emsp;&emsp;';
                            break;
                        case '90':
                            $tab='&emsp;&emsp;&emsp;';
                            break;
                        default:
                        $tab='&nbsp;';
                            break;
                    }
                    if ($value->font=='B'){
                        $isi =$isi.'<br><b>'.$value->content.'</b>';
                    }else{
                        $isi =$isi.'<br>'.$tab.$value->content;
                    }
                    
                }
                
            }
            $data['konten']=$isi;
            // dd($data['konten']);
            // $konten =  etemp::where("category_id", $id)->where("arpt_ident", $arptident)->first();
            // $data['konten'] = isset($konten->content)?$konten->content : '';
        }else{
            
            $data['konten'] = isset($konten->body)?$konten->body : '';
        }
        return view("pages.publications.gen.index", $data);
    }

    public function uploadImage(Req $request) {    
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
        
            $request->file('upload')->move(public_path('images/gen'), $fileName);
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/gen/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }        
    public function store_rawpub($data){
        $ret=false;
        $exist = RawPub::where('tablename', '=', $data['tablename'])
                        ->where('fieldname','=', $data['fieldname'])
                        ->where('fieldid', '=', $data['fieldid'])
                        ->where('status_raw','<',100)->first();
        if ($exist === null) {
            $dat = new RawPub;        
            $dat->tablename         = $data['tablename'] ;
            $dat->fieldname         = $data['fieldname'] ;
            $dat->fieldid           = $data['fieldid'] ;
            $dat->status_raw        = $data['status_raw'] ;
            $dat->ori_change_pic    = $data['ori_change_pic'] ;
            $dat->save();
            $ret=true;
        }else{
            $exist->update($data); 
            $ret=true;
            // $upRawPub = RawPub::where('tablename', '=', $data['tablename'])->where('fieldid', '=', $data['fieldid'])->update($data);
        } 
        return $ret;
    }
    public function store(Req $request,$id) {
        $user = Auth::user();
        $next = false;
        $message = '';
        $status_save = false;
    //    dd($request,$id);
        $return='';
        $gen_number = explode(" ", $request->text);
        $fieldid = $gen_number[0]." ".$gen_number[1]; 
        // collect data to raw_pub -> $fieldid ;
        $data = [
                    'tablename'     =>'GEN',
                    'fieldname'     =>'sub_id',
                    'fieldid'       => $fieldid,
                    'status_raw'    =>0,
                    'ori_change_pic'=>$user->id
                ];
        //end update raw_pub
               // dd($request); 
        if(!empty($id)){
            if (substr($request->text,0,4)=='AD 2'){
                $return='gen/'.$request->arpt.'/'.$id.'/'.$request->text;
                $dat =  Content::where('section_id','=',$id)->where('title','=',$request->text)->first();
                $data['tablename']='arpt'; 
                $data['fieldname']='arpt_ident';
                $data['fieldid']=$id; 
            }else{
                $return='gen/'.$id.'/'.$request->text;
                $dat =  Content::where('section_id','=',$id)->first();  
            }
            if(!is_null($dat)){  
                    if(trim($dat->body) != trim($request->body)) { 
                        $dat->body = $request->body; 
                        $next=true; 
                    }  
                    $dat->update($request->all()); 
                    $dat->section_id  = $id ;
                        $message = 'Your Request data has been updated!!'; 
                        $status_save=true;
                } else{
                    $data_baru = new Content;        
                    $data_baru->section_id    = $id ;
                    $data_baru->body          = $request->body;
                    $data_baru->title         = $request->text;
                    $data_baru->status        = 'N';
                    $data_baru->user_id       = $user->id;
                    $data_baru->save(); 
                    $message = 'Your Request data has been saved!!';  
                    $status_save=true;
                }
        }else{
            if ($request->body==null){
                $request->body='<b><i>Reserved</i></b>';
            } 
            $dat = new Content;        
            $dat->section_id    = $id ;
            $dat->body          = $request->body;
            $dat->title         = $request->text;
            $dat->status        = 'N';
            $dat->user_id       = $user->id;
            $dat->save(); 
            $message = 'Your Request data has been saved!!';  
            $status_save=true;
        }
            if($status_save) 
                $update_raw_pub = $this->store_rawpub($data);
            if(!$update_raw_pub) 
                $message = "Raw Data Pub can't be saved";
            
            return redirect('gen.edit/'.$id.'/'.$request->text)->with('message',$message);
    }
       
        // return redirect( $return)->with('message',$message);
        // return redirect('gen/'.$id.'/'.$request->text)->with('message',$message);
}

