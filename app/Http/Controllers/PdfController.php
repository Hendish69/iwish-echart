<?php
namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Request;
use Auth;
use \Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Api\AtsTemp;
use App\Models\Api\WaypointTemp;
use App\Models\Api\RawdataPub as RawPub;
// use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Codedge\Fpdf\Fpdf\Fpdf;


class PdfController extends Controller
{
    private $fpdf;
    private $lm,$rm,$ln,$ptxt,$pisi,$tab,$spc7,$head,$foot,$btshal,$lebar,$lebararea,$draft,$header,$footer,$pubdate,$effdate,$width,$high,$src,$source,$nr,$eaipdata,$filename,$n_page,$pos_y;
    protected $B;
	protected $I;
	protected $U;
	protected $HREF;
	protected $fontList;
	protected $issetfont;
	protected $issetcolor;

    public function __construct()
    {
        $this->fpdf='tes';
        $this->ptxt=61;$this->pisi=55;$this->lm=20;$this->rm=15;$this->ln=4;$this->tab=4;$this->spc7=6;
        $this->head=15;$this->foot=15;$this->btshal=190;$this->draft='D R A F T';$this->n_page=false; $this->lebar=160;
        $this->pos_y=0;
        $this->B=0;
		$this->I=0;
		$this->U=0;
		$this->HREF='';
		$this->PRE=false;
		// $this->fpdf->SetFont('Arial','',8);
		$this->fontlist=array('Arial','Arial');
		$this->issetfont=false;
		$this->issetcolor=false;
        //'UTF-8', 'windows-1252'
        $this->from='iso-8859-2';         // input encoding
		$this->to='cp1250';               // output encoding
		$this->useiconv=false;            // use iconv
		$this->bi=true;
		// $this->articletitle=$_title;
		// $this->articleurl=$_url;
		$this->debug=false;
		// $this->fpdf->AliasNbPages();
    }

    function hex2dec($color = "#000000"){
        $tbl_color = array();
        $tbl_color['R']=hexdec(substr($color, 1, 2));
        $tbl_color['G']=hexdec(substr($color, 3, 2));
        $tbl_color['B']=hexdec(substr($color, 5, 2));
        return $tbl_color;
    }
    
    function px2mm($px){
        return $px*25.4/72;
    }
    
    function txtentities($html){
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return strtr($html, $trans);
    }

    function _convert($s) {
		if ($this->useiconv) 
			return iconv($this->from,$this->to,$s); 
		else 
			return $s;
	}

    function WriteHTML($html,$bi)
	{
		//remove all unsupported tags
        $str=array(
            '<br />' => '<br>',
            '<hr />' => '<hr>',
            '[r]' => '<red>',
            '[/r]' => '</red>',
            '[l]' => '<blue>',
            '[/l]' => '</blue>',
            '&#8220;' => '"',
            '&#8221;' => '"',
            '&#8222;' => '"',
            '&#8230;' => '...',
            '&#8217;' => '\'',
            '&emsp;' => '   ',
            '&nbsp;' => '',
            '&ldquo;'=>'"',
            '&rdquo;'=>'"',
            '&ndash;' =>'-'
            );
            foreach ($str as $_from => $_to){

                $html = str_replace($_from,$_to,$html);
            }
            // dd($html,$bi);
		$this->bi=$bi;
		if ($bi)
			$html=strip_tags($html,"<a><img><p><br><font><tr><td><blockquote><h1><h2><h3><h4><pre><red><blue><ul><li><hr><b><i><u><strong><em>"); 
		else
			$html=strip_tags($html,"<a><img><p><br><font><tr><td><blockquote><h1><h2><h3><h4><pre><red><blue><ul><li><hr>"); 
		$html=str_replace("\n",' ',$html); //replace carriage returns with spaces
		// debug
		if ($this->debug) { echo $html; exit; }

		$html = str_replace('&trade;','™',$html);
		$html = str_replace('&copy;','©',$html);
		$html = str_replace('&euro;','€',$html);

		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        dd($a);
		$skip=false;
		foreach($a as $i=>$e)
		{
            // var_dump($e);
			if (!$skip) {
				if($this->HREF)
					$e=str_replace("\n","",str_replace("\r","",$e));
				if($i%2==0)
				{
					// new line
					if($this->PRE)
						$e=str_replace("\r","\n",$e);
					else
						$e=str_replace("\r","",$e);
					//Text
					if($this->HREF) {
						$this->PutLink($this->HREF,$e);
						$skip=true;
					} else 
						$this->fpdf->Write(5,stripslashes(txtentities($e)));
                        // var_dump($this->fpdf->getX(),$e);
                        // $this->fpdf->SetX($this->lm);
                        // $this->fpdf->Cell($this->lm,4,stripslashes(txtentities($e)));
				} else {
					//Tag
                    if (substr(trim($e),0,1)=='/'){
                    // dd(strtoupper(substr($e,strpos($e,'/'))));
						$this->CloseTag(strtoupper(substr($e,strpos($e,'/'))));
                    }else {
						//Extract attributes
						$a2=explode(' ',$e);
						$tag=strtoupper(array_shift($a2));
						$attr=array();
						foreach($a2 as $v) {
							if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
								$attr[strtoupper($a3[1])]=$a3[2];
						}
                        // var_dump($tag,$attr);
						$this->OpenTag($tag,$attr);
					}
				}
			} else {
				$this->HREF='';
				$skip=false;
			}
		}
	}

	function OpenTag($tag,$attr)
	{
		//Opening tag
        // var_dump($tag);
		switch($tag){
			case 'STRONG':
			case 'B':
				if ($this->bi)
					$this->SetStyle('B',true);
				else
					$this->SetStyle('U',true);
				break;
            
			case 'H1':
				$this->fpdf->Ln(5);
				$this->fpdf->SetTextColor(150,0,0);
				$this->fpdf->SetFontSize(22);
				break;
			case 'H2':
				$this->fpdf->Ln(5);
				$this->fpdf->SetFontSize(18);
				$this->SetStyle('U',true);
				break;
			case 'H3':
				$this->fpdf->Ln(5);
				$this->fpdf->SetFontSize(16);
				$this->SetStyle('U',true);
				break;
			case 'H4':
				$this->fpdf->Ln(5);
				$this->fpdf->SetTextColor(102,0,0);
				$this->fpdf->SetFontSize(14);
				if ($this->bi)
					$this->SetStyle('B',true);
				break;
			case 'PRE':
				$this->fpdf->SetFont('Courier','',11);
				$this->fpdf->SetFontSize(11);
				$this->SetStyle('B',false);
				$this->SetStyle('I',false);
				$this->PRE=true;
				break;
			case 'RED':
				$this->fpdf->SetTextColor(255,0,0);
				break;
			case 'BLOCKQUOTE':
				$this->fpdf->mySetTextColor(100,0,45);
				$this->fpdf->Ln(3);
				break;
			case 'BLUE':
				$this->fpdf->SetTextColor(0,0,255);
				break;
			case 'I':
			case 'EM':
				if ($this->bi)
					$this->SetStyle('I',true);
				break;
			case 'U':
				$this->SetStyle('U',true);
				break;
			case 'A':
				$this->HREF=$attr['HREF'];
				break;
			case 'IMG':
				if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
					if(!isset($attr['WIDTH']))
						$attr['WIDTH'] = 0;
					if(!isset($attr['HEIGHT']))
						$attr['HEIGHT'] = 0;
					$this->fpdf->Image($attr['SRC'], $this->fpdf->GetX(), $this->fpdf->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
					$this->fpdf->Ln(3);
				}
				break;
			case 'LI':
				$this->fpdf->Ln(2);
				$this->fpdf->SetTextColor(190,0,0);
				$this->fpdf->Write(5,'     » ');
				$this->fpdf->mySetTextColor(-1);
				break;
			case 'TR':
				$this->fpdf->Ln(7);
				$this->PutLine();
				break;
            case 'TD':
                $this->fpdf->Ln(7);
                $this->PutLineVer();
                break;
			case 'BR':
				$this->fpdf->Ln(4);
				break;
			case 'P':
				$this->fpdf->Ln(5);
				break;
			case 'HR':
				$this->PutLine();
				break;
			case 'FONT':
				if (isset($attr['COLOR']) && $attr['COLOR']!='') {
					$coul=hex2dec($attr['COLOR']);
					$this->mySetTextColor($coul['R'],$coul['G'],$coul['B']);
					$this->issetcolor=true;
				}
				if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
					$this->fpdf->SetFont(strtolower($attr['FACE']));
					$this->issetfont=true;
				}
				break;
		}
	}

	function CloseTag($tag)
	{
		//Closing tag
        // dd($tag);
		if ($tag=='H1' || $tag=='H2' || $tag=='H3' || $tag=='H4'){
			$this->fpdf->Ln(6);
			$this->fpdf->SetFont('Times','',12);
			$this->fpdf->SetFontSize(12);
			$this->SetStyle('U',false);
			$this->SetStyle('B',false);
			$this->mySetTextColor(-1);
		}
        if ($tag=='/B' || $tag=='/STRONG'){
            $this->SetStyle('B',false);
        }
        if ($tag=='/P'){
            $this->fpdf->SetX($this->lm);
        }
		if ($tag=='PRE'){
			$this->fpdf->SetFont('Times','',12);
			$this->fpdf->SetFontSize(12);
			$this->PRE=false;
		}
		if ($tag=='RED' || $tag=='BLUE')
			$this->mySetTextColor(-1);
		if ($tag=='BLOCKQUOTE'){
			$this->mySetTextColor(0,0,0);
			$this->fpdf->Ln(3);
		}
		if($tag=='STRONG')
			$tag='B';
		if($tag=='EM')
			$tag='I';
		if((!$this->bi) && $tag=='B')
			$tag='U';
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
		if($tag=='FONT'){
			if ($this->issetcolor==true) {
				$this->fpdf->SetTextColor(0,0,0);
			}
			if ($this->issetfont) {
				$this->fpdf->SetFont('Times','',12);
				$this->issetfont=false;
			}
		}
	}

	function Footer()
	{
		//Go to 1.5 cm from bottom
		$this->SetY(-15);
		//Select Arial italic 8
		$this->SetFont('Times','',8);
		//Print centered page number
		$this->SetTextColor(0,0,0);
		$this->Cell(0,4,'Page '.$this->PageNo().'/{nb}',0,1,'C');
		$this->SetTextColor(0,0,180);
		$this->Cell(0,4,'Created by HTML2PDF / FPDF',0,0,'C',0,'http://hulan.info/blog/');
		$this->mySetTextColor(-1);
	}

	function Header()
	{
		//Select Arial bold 15
		$this->SetTextColor(0,0,0);
		$this->SetFont('Times','',10);
		$this->Cell(0,10,$this->articletitle,0,0,'C');
		$this->Ln(4);
		$this->Cell(0,10,$this->articleurl,0,0,'C');
		$this->Ln(7);
		$this->Line($this->GetX(),$this->GetY(),$this->GetX()+187,$this->GetY());
		//Line break
		$this->Ln(12);
		$this->SetFont('Times','',12);
		$this->mySetTextColor(-1);
	}

	function SetStyle($tag,$enable)
	{
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s) {
			if($this->$s>0)
				$style.=$s;
		}
		$this->fpdf->SetFont('',$style);
	}

	function PutLink($URL,$txt)
	{
		//Put a hyperlink
		$this->fpdf->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->fpdf->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->mySetTextColor(-1);
	}

	function PutLine()
	{
		$this->fpdf->Ln(2);
		$this->fpdf->Line($this->fpdf->GetX(),$this->fpdf->GetY(),$this->fpdf->GetX()+125,$this->fpdf->GetY());
		$this->fpdf->Ln(3);
	}
    function PutLineVer()
	{
		// $this->fpdf->cell(10);
		// $this->fpdf->Line($this->fpdf->GetX(),$this->fpdf->GetY(),$this->fpdf->GetX(),$this->fpdf->GetY()+7);
		// $this->fpdf->Ln(3);
	}

	function mySetTextColor($r,$g=0,$b=0){
		static $_r=0, $_g=0, $_b=0;

		if ($r==-1) 
			$this->fpdf->SetTextColor($_r,$_g,$_b);
		else {
			$this->fpdf->SetTextColor($r,$g,$b);
			$_r=$r;
			$_g=$g;
			$_b=$b;
		}
	}


    public function reportcdm($vano,$id){
        $frq = "select * from tx_cdm where va_no ='$vano'";
        $data['cdm'] =DB::select(DB::raw($frq));
        $cdmid=$data['cdm'][0]->cdm_id;

        $frq = "select * from tm_volcano where va_no ='$vano'";
        $volcano =DB::select(DB::raw($frq));

        $frq = "select * from tb_reff where reff_group='0002' and reff_code ='$id'";
        $tbreff =DB::select(DB::raw($frq));

        $frq = "select * from tx_cdm_log where cdm_id =$cdmid and cdm_type='$id' order by cdm_issued desc";
        $cdmlog =DB::select(DB::raw($frq));
        // $cord = toWgs($volcano[0]->va_lon,'LON');
        // $cord1 = toWgs($volcano[0]->va_lat,'LAT');
        // dd($o[2]->coordinates[1].' '.$o[2]->coordinates[0],$cord1.' '.$cord);
        $lat=sprintf('%02d',$volcano[0]->va_lat_deg). '° '.sprintf('%02d',$volcano[0]->va_lat_min).'" '.sprintf('%02d',$volcano[0]->va_lat_sec). "' ".$volcano[0]->va_lat_ns;
        $lon=sprintf('%02d',$volcano[0]->va_lon_deg). '° '.sprintf('%02d',$volcano[0]->va_lon_min).'" '.sprintf('%02d',$volcano[0]->va_lon_sec). "' ".$volcano[0]->va_lon_ew;
        $crd=iconv('UTF-8', 'windows-1252', $lat.' '.$lon);
        $status='';$R=0;$G=0;$B=0;
        switch ($volcano[0]->va_status) {
            case '1':
                $status='GREEN';
                $R=34;$G=177;$B=76;
                break;
            case '2':
                $status='YELLOW';
                $R=249;$G=249;$B=0;

                break;
            case '3':
                $status='ORANGE';
                $R=255;$G=139;$B=23;

                break;
            case '4':
                $status='RED';
                $R=255;$G=0;$B=0;
                break;
        }
        // dd($cdmid,$volcano,$cdmlog);
        $this->filename=$volcano[0]->va_name . ' - ' . $volcano[0]->va_no.'_'.$tbreff[0]->reff_name;
        $this->fpdf = new Fpdf('P','mm',[210,297]);
        $this->lebar=210;
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia'.' ' .$volcano[0]->va_name . ' - ' . $volcano[0]->va_no,$volcano[0]->va_name . ' - ' . $volcano[0]->va_no);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->fpdf->SetFont('Arial','B',12);
        // Move to the right
        $this->fpdf->setX($this->lm);
        $this->fpdf->setY($this->head);
        $jmlhdata=count($cdmlog);
        if ($jmlhdata > 0){
                $titel=$cdmlog[0]->cdm_stakeholder.' Stakeholder and '.$tbreff[0]->reff_name.' Message Type';
        }else{
                $titel='Stakeholder and '.$tbreff[0]->reff_name.' Message Type';
        }
        $this->fpdf->Cell(0,10,'CDM Report',0,0,'C');
        $this->fpdf->ln(5);
        $this->fpdf->SetFont('Arial','BI',12);
        $this->fpdf->Cell(0,10,$titel,0,0,'C');
        $this->fpdf->ln(5);
        $this->fpdf->SetFont('Arial','B',12);
        $this->fpdf->Cell(0,10,'Relationship',0,0,'C');
        $this->fpdf->ln(5);
        $this->fpdf->line(20,33,195,33);
        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->Cell(20,15,'Volcano',0,0,'L');
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->Cell(20,15,': '.$volcano[0]->va_name . ' - ' . $volcano[0]->va_no);
        $this->fpdf->ln(5);
        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->Cell(20,15,'Status',0,0,'L');
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->SetTextColor($R,$G,$B);
        $this->fpdf->Cell(20,15,': '.$status);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->ln(5);
        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->Cell(20,15,'Location',0,0,'L');
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->Cell(20,15,': '.$crd,0,0,'L');
        $this->fpdf->ln(5);
        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->Cell(20,15,'Elevation',0,0,'L');
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->Cell(20,15,': '.$volcano[0]->va_summit_elevm . 'm');
        $this->fpdf->ln(5);
        $this->fpdf->SetFont('Arial','',10);
        $this->fpdf->Cell(20,15,'Area',0,0,'L');
        $this->fpdf->SetFont('Arial','B',10);
        $this->fpdf->Cell(20,15,': '.$volcano[0]->va_subregion . ' - ' . $volcano[0]->va_state);
        $this->fpdf->ln(12);

        $posY=$this->fpdf->GetY();

        if ($jmlhdata ==0){
            $pesan='Not found '.$tbreff[0]->reff_name. ' Message Type';
            $this->fpdf->SetFont('Arial','i',20);
            $this->fpdf->SetTextColor(128,128,128);
            $this->fpdf->cell(0,50,$pesan,0,0,'C');
            $this->fpdf->SetTextColor(0,0,0);
        }else{
            $this->fpdf->SetFillColor(128,128,128);
            $this->fpdf->Rect($this->fpdf->GetX(),  $this->fpdf->GetY(), $this->lebararea, 10,'F');
            // $this->fpdf->Rect($pX,$pY, $a, 10);
            $this->fpdf->SetTextColor(255,255,255);

            $this->fpdf->cell(35,10,'Time',0,0,'C');
            $this->fpdf->cell(80,10,'Response Time',0,0,'C');
            $this->fpdf->cell(35,10,'Notice Number',0,0,'C');
            $this->fpdf->cell(20,10,'Description',0,0,'C');
            $this->fpdf->SetTextColor(0,0,0);
            $this->fpdf->SetFont('Arial','',9);
            $this->fpdf->ln(10);
            for ($i=0;$i < $jmlhdata;$i++){
                $this->fpdf->cell(35,10,$cdmlog[$i]->cdm_issued,0,0,'C');
                $this->fpdf->cell(80,10,ConvertTime($cdmlog[$i]->cdm_response),0,0,'C');
                $this->fpdf->cell(35,10,$cdmlog[$i]->cdm_noticenumber,0,0,'C');
                $this->fpdf->cell(20,10,$tbreff[0]->reff_name,0,0,'C');
                $this->fpdf->ln(5);
                if ($this->fpdf->GetY() > 275){
                    $this->fpdf->AddPage();
                    $this->fpdf->SetAutoPageBreak(true,0);
                    $this->fpdf->SetRightMargin($this->rm);
                    $this->fpdf->SetLeftMargin($this->lm);
                    $this->fpdf->SetTopMargin($this->head);

                    $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                    $this->fpdf->SetFillColor(128,128,128);
                    $this->fpdf->Rect($this->fpdf->GetX(),  $this->fpdf->GetY(), $this->lebararea, 10,'F');
                    // $this->fpdf->Rect($pX,$pY, $a, 10);
                    $this->fpdf->SetTextColor(255,255,255);

                    $this->fpdf->cell(40,10,'Time',0,0,'C');
                    $this->fpdf->cell(80,10,'Response Time',0,0,'C');
                    $this->fpdf->cell(30,10,'Stakeholder',0,0,'C');
                    $this->fpdf->cell(20,10,'Description',0,0,'C');
                    $this->fpdf->SetTextColor(0,0,0);
                    $this->fpdf->SetFont('Arial','',9);

                    $this->fpdf->ln(10);
                }
            }
        }
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;

    }
    private function enr($id){
       
        $this->header= $this->header.' ( VOL I )';
        switch ($id) {
            case '30':
                $this->gen22($id);
                break;
            case '32':
                
                $this->gen24($id);
                break;
            case '33':
                $this->gen25($id);
                break;
            case '61':
            case '62':
            case '63':
            case '64':
                $this->atsroute($id);
                break;
            case '66':
                $this->enr41($id);
            case '68':
                $this->enr43($id);
                break;
            case '59':
                $this->enr21($id);
                break;
            case '70':
            case '71':
                $this->enr51($id);
                break;

            default:
                # code...
                break;
        }
    }
    private function ConverLebarColum($col){
        return $col/136 * $this->lebararea;
    }
    private function GetSymbol($wpttype){
        // $path= env('APP_URL');;

        $sym='';
        switch ($wpttype) {
            case '1':
            $sym =public_path('/images/Enr/CRP.jpg');
                break;
            case '2':
                $sym =public_path('/images/Enr/NCRP.jpg');
                    break;
            case '3':
                $sym =public_path('/images/Enr/M_CRP.jpg');
                    break;
            case '4':
                $sym =public_path('/images/Enr/M_NCRP.jpg');
                    break;
            case 'UP':
                $sym =public_path('/images/Enr/ArrowUp.jpg');
                    break;
            case 'DOWN':
                $sym =public_path('/images/Enr/ArrowDown.jpg');
                    break;
            // case '5':
            //     $sym =public_path('/images/marker/RNAVC.jpg');
            //         break;

            default:
            $sym =public_path('/images/Enr/CRP.jpg');
                break;
        }
        return $sym;
    }
    private function DrawPoint($ats,$point,$Xpos,$Ypos,$col,$idx){
        $e=$ats;$tgH=3.5;$imgtot=$Xpos+2.5;$lbr=125;
        if ($point=='1'){
            $type=$e['type1'];
            $pnt=$e['point1'];
            $lat=$e['lat1'];
            $lon=$e['lon1'];
        }else{
            if ($e['ats_type']=='V'){
                $lbr -= 26;
            }else{
                $lbr -= 23;
            }
            $type=$e['type2'];
            $pnt=$e['point2'];
            $lat=$e['lat2'];
            $lon=$e['lon2'];
        }
        $stts=$e['status'];
// dd($e);
        $symb=$this->GetSymbol($type);
        $this->fpdf->Image($symb,$Xpos+1,$Ypos+1,-1600);
        $this->fpdf->SetX($imgtot);
        $this->fpdf->Cell($col,$tgH,$pnt,0,0,'L');

        //     if ($stts =='U'){
        //     $this->fpdf->SetFont('Arial','',8);
        // }else{
        //     $this->fpdf->SetFont('Arial','BI',8);
        // }
        if ($idx==0){
            // var_dump($pnt,$idx, $count);
            $this->fpdf->Line($Xpos+$col,$Ypos+$tgH,$Xpos + $lbr,$Ypos+$tgH);
        }

        $ret=$this->fpdf->GetY();

        $this->fpdf->Ln($tgH);
        $this->fpdf->SetX($imgtot);
        $this->fpdf->Cell($col,$tgH,$lat,0,0,'L');
        $this->fpdf->Ln($tgH);
        $this->fpdf->SetX($imgtot);
        $this->fpdf->Cell($col,$tgH,$lon,0,0,'L');
        $this->fpdf->SetFont('Arial','',8);
        return  $ret;
    }
    private function Convtext($txt){
        // var_dump($txt);
        if($txt=='' || $txt==null){
            return '';
        }else{
        $str = $txt;
        $pattern = '/�C/u';
        $string = preg_replace($pattern, '', $str);
        $string = preg_replace('/[\x00-\x1F\x7F]/u', '', $txt);
        // $hasil=$txt;
        // if (strtolower($txt) != 'utf-8') {
            // var_dump($txt);
        //     $hasil = iconv($txt, 'utf-8', $hasil);
        //     // $xml = str_replace('encoding="'.$matches[1].'"', 'encoding="utf-8"', $xml);
        // }
        // iconv($matches[1], 'utf-8//TRANSLIT', $xml);
        // var_dump($txt);
        $hasil = iconv('UTF-8','windows-1252', $string);
        // $hasil=iconv('UTF-8//TRANSLIT', 'ASCII//TRANSLIT',$txt);
        return $hasil;// iconv('UTF-8//IGNORE', 'windows-1252',$txt);

        }
    }
    private function Pretextsuas51(){
        $nom=['1.','2.','3.','4.','5.','6.','7.'];

        $j1='INTRODUCTION';
        $j2='DANGER AREA';
        $j3='PROHIBITED AREA';
        $j4='RESTRICTED AREA';

        $j11='1.1';
        $j21='2.1';
        $j31='3.1';
        $j12='All airspace in which a potential hazard to aircraft operations may exist and all areas over which the operation of civil aircraft may, for one reason or another be restricted either temporarily or permanently, are classified according to the following three types of areas as defined by ICAO.';
        $j22='An airspace of defined dimensions within which activities dangerous to the flight of aircraft may exist at specified times. This term is used only when the potential danger to aircraft has not led the designation of the airspace as restricted or prohibited. The effect of the creation of the danger areas is to caution operators or pilots of aircraft that it is necessary for them to assess the angers in relation to their responsibility for the safety of their aircraft.';
        $j32='An airspace of defined dimensions, above the land areas or territorial waters of a State, within the flight of aircraft is prohibited. This term is used only when the flight of civil aircraft within the designated airspace is not permitted at any time under any circumstances.';
        $j42='An airspace of defined dimensions, above the land areas or territorial waters of a State, within which the flight of aircraft is restricted in accordance with certain specified conditions. This term is used whenever the flight of civil aircraft within the designated airspace is not absolutely prohibited but may be made only if specified conditions are complied whit. Thus, prohibition of flight except at certain meteorological conditions. Similarly, prohibition of flight unless special Permission had been obtained, leads to the designation of restricted area.'. PHP_EOL .'However, conditions of flight imposed as a result of application of rules of the air traffic service practice or procedures (for example, compliance with minimum safe heights or with rules stemming from the establishment of controlled airspace) do not constitute conditions calling for designation as a restricted area.';
        $j5='Each area is numbered and single series of numbers is used for all Areas, regardless of type to ensure that a numbers is never duplicated.';
        $j6='The type of area involved is indicated by the letter “P” for Prohibited, “R” for Restricted and “D” for Danger. For example, areas are assigned numbers and letters in the following manner – WAD1, WAR2, WAP3, WID4 etc.';
        $j7='Each area is described in the tabulation found in page ENR 5.1-2 – to 5.1-8. Which indicates its lateral and vertical limits, the type of restriction or hazard involved, the times at which it applies and other pertinent information.';
        $no1=[$j11,$j21,$j31];
        $no2=[$j12,$j22,$j32,$j42];
        $nom1=[$j1,$j2,$j3,$j4,$j5,$j6,$j7];
        $this->fpdf->setX($this->lm);
        for ($i=0; $i < count($nom); $i++) {
            $this->fpdf->SetFont('Arial','B',8);
            $this->fpdf->Cell(5,4,$nom[$i],0,0,'L');
            if ($i < 3){
                $this->fpdf->MultiCell(30,4,iconv('UTF-8', 'windows-1252',$nom1[$i]),0,'J');
                $this->fpdf->Cell(5);
                $this->fpdf->SetFont('Arial','',8);
                $yp=$this->fpdf->GetY();
                $xp=$this->fpdf->GetX();
                $this->fpdf->MultiCell(6,4,iconv('UTF-8', 'windows-1252',$no1[$i]),0,'J');
                $this->fpdf->SetY($yp);
                $this->fpdf->SetX($xp+6);
                $this->fpdf->MultiCell(113,4,iconv('UTF-8', 'windows-1252',$no2[$i]),0,'J');
            }else if ($i == 3){

                $this->fpdf->MultiCell(30,4,iconv('UTF-8', 'windows-1252',$nom1[$i]),0,'J');
                $this->fpdf->Cell(5);
                $xp=$this->fpdf->GetX();
                $this->fpdf->SetFont('Arial','',8);
                $this->fpdf->SetX($xp);
                // dd($no2[$i]);
                $this->fpdf->MultiCell(120,4,iconv('UTF-8', 'windows-1252',$no2[$i]),0,'J');
            }else{
                $this->fpdf->SetFont('Arial','',8);
                $this->fpdf->MultiCell(120,4,iconv('UTF-8', 'windows-1252',$nom1[$i]),0,'J');

            }
            // $this->fpdf->Ln(4);
            $this->fpdf->SetFont('Arial','',8);

        }
    }
    private function enr51($id){
        $originalInput=Request::input();
        $user = Auth::user();
        $asptemp = getDataApi($originalInput,'api/suas/temp/list?ctry=ID&deleted=0&sort=suas_ident:asc');
        // $asp = getDataApi($originalInput,'api/airspace/list?ctry=ID&deleted=0&sort=airspace_name:asc');
        $subcode = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        $tbl = getDataApi($originalInput,'api/eaip/codtableheader?tbl=eaip');

        // dd($asptemp);

        $cod=[];$jdl='';$hal='';$jdl1='ENR 5 NAVIGATION WARNING';
        if (!empty($subcode)){
            $cod=$subcode;
            $jdl=$cod[0]->sub_id.' '.$cod[0]->definition;
            $hal=$cod[0]->sub_id;
        }
        $data['subid'] = $id; 
        $this->fpdf = new Fpdf('P','mm',[160,210]);

        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$jdl);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
            $rht=5;
        }else{
            $rht=0;
            $this->lm=20;$this->rm=15;
        }
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($hal.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->Cell(0,0,$jdl1,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->Cell(0,0,$jdl,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','',8);
        $this->fpdf->ln(4);



        if($id=='70'){
            $this->Pretextsuas51();
            $this->fpdf->ln($this->ln);
            $this->AipFooter();        
            // var_dump($this->fpdf->GetX(),'1');

            $this->tableenr51($jdl,$hal);
            // var_dump($this->fpdf->GetX(),'2');

            // var_dump($this->fpdf->GetX(),'3');
            $LineY=$this->fpdf->GetY();
            $this->fpdf->Ln(1);
            $tgH=3.5;$lbr=125;
            $AwalY=$LineY; $AwalX= $this->lm;
            $posX=$this->lm;
            $posY=$this->fpdf->GetY();
            $aY=$this->fpdf->GetY();
            $col=[65,30,30];
            $jdarr=['D','P','R'];
            $j2='DANGER AREA';
            $j3='PROHIBITED AREA';
            $j4='RESTRICTED AREA';
            $jjjd=[$j2,$j3,$j4];
        }else{

            // $this->AipFooter();        
            // var_dump($this->fpdf->GetX(),'1');

            $this->tableenr51($jdl,$hal,$jdl1);

            $this->AipFooter();        
            // var_dump($this->fpdf->GetX(),'1');

            $this->tableenr51($jdl,$hal);

            // var_dump($this->fpdf->GetX(),'2');

            // var_dump($this->fpdf->GetX(),'3');
            $LineY=$this->fpdf->GetY();
            $this->fpdf->Ln(1);
            $tgH=3.5;$lbr=125;
            $AwalY=$LineY; $AwalX= $this->lm;
            $posX=$this->lm;
            $posY=$this->fpdf->GetY();
            $aY=$this->fpdf->GetY();
            $col=[65,30,30];


            $jdarr=['M','T'];
            $j1='MILITARY OPERATIONS AREA';
            $j3='WARNING';
            $j2='TRAINING AREAS';
            $jjjd=[$j1,$j2];
        }
        if (!empty($jdarr)){

            for ($f=0; $f < count($jdarr); $f++) { 
                $this->fpdf->SetFont('Arial','BU',9);
                $this->fpdf->MultiCell(65,$tgH,$jjjd[$f],0,'L');
                // $this->fpdf->MultiCell(65,$tgH,'P',0,'L');
                $this->fpdf->Ln(2);
                $aY=$this->fpdf->GetY();
                $posY=$aY;unset($selisih);$selisih=0;
                foreach ($asptemp as $key => $as) {
                    if ($as->suas_type==$jdarr[$f]){
                        // dd($as);
                    // if ($as->suas_type=='P'){
                        $this->fpdf->SetY($aY);
                        $YlineAwal=$this->fpdf->GetY();
                        $this->fpdf->SetX($posX);
                        $this->fpdf->SetFont('Arial','B',8);
                        $this->fpdf->MultiCell(65,$tgH,$as->suas_ident.' '.$as->suas_name,0,'L');
                        $this->fpdf->SetFont('Arial','',8);
                        $textasp=GetSegmentText($as,$as->suas_type);
                        $this->fpdf->SetX($posX);
                        $this->fpdf->MultiCell(65,$tgH,$textasp,0,'J');
                        $aY=$this->fpdf->GetY()+2;
                        $this->fpdf->SetY($posY+2);
                        $this->fpdf->SetX($posX+65);
                        $this->fpdf->SetFont('Arial','U',8);
                        $this->fpdf->Cell(30,$tgH, $as->upper,0,0,'C');
                        $this->fpdf->SetFont('Arial','',8);
                        $this->fpdf->Ln($tgH);
                        $this->fpdf->SetX($posX+65);
                        $this->fpdf->Cell(30,$tgH,$as->lower,0,0,'C');
                        $this->fpdf->SetY($posY+2);
                        $remm='';
                        if (!empty($as->remarks)){

                            for ($i=0; $i < count($as->remarks); $i++) {
                                $this->fpdf->SetX($posX+95);
                                $this->fpdf->MultiCell(30,$tgH,iconv('UTF-8', 'windows-1252',$as->remarks[$i]->remarks),0,'L');
                                $this->fpdf->Ln($tgH);
                                $this->fpdf->SetY($posY+2);
                                $remm=iconv('UTF-8', 'windows-1252',$as->remarks[$i]->remarks);
                                // $this->fpdf->SetX($posX+95);

                            }
                        }else{
                            $this->fpdf->SetX($posX+95);
                            $this->fpdf->MultiCell(30,$tgH,$as->eff_times,0,'L');
                            $remm=iconv('UTF-8', 'windows-1252',$as->eff_times);
                        }
                        $arrrem=explode("\n",$this->fpdf->WordWrap($textasp,65));
                        $stt=count($arrrem)*$tgH;
                        $arrrem=explode("\n",$this->fpdf->WordWrap($remm,30));
                        $stts=count($arrrem)*$tgH;
                        if ($stt < $stts){
                            if ($as->suas_ident !=='WAR19 (1)'){
                                $aY +=($stts-$stt);
                            }
                            //jika remakrk lebih tingi dari pada isi text
                            // var_dump($stt ,$stts,$as->suas_ident);

                        }

                        $this->fpdf->SetY($aY);
                        $posY=$aY;
                        $this->fpdf->SetX($posX);
                        $YlineAkhir=$this->fpdf->GetY();
                        if ($posY > $this->btshal-15){

                            $count=count($col);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
                            $btsakhir=$posY;
                            if ($btsakhir <  $this->btshal){
                                $btsakhir=$this->btshal+1;
                            }
                            // $this->fpdf->SetDrawColor(0,255,0); //Hijau
                            for($i = 0; $i < $count; $i++ ) {
                                $this->fpdf->Line($xx,$LineY,$xx,$btsakhir);
                                $xx += $col[$i];
                            }
                            $newpage=true;
                            $this->fpdf->Line($xx,$LineY,$xx,$btsakhir);

                            // $this->fpdf->SetDrawColor(0,255,0); //Hijau
                            $this->fpdf->Line($x1,$btsakhir,$xx,$btsakhir);
                            $this->fpdf->ln($this->ln);
                            $this->AipFooter();

                            if ($f < count($jdarr)){
                                $this->tableenr51($jdl,$hal);
                                $LineY=$this->fpdf->GetY();
                                $this->fpdf->ln(1);
                                $posY=$this->fpdf->GetY();
                                $aY=$this->fpdf->GetY();
                                $AwalY=$LineY; $AwalX= $this->fpdf->GetX();
                                $posX=$this->fpdf->GetX();
                            }
                        }
                        if ($as->status !== 'U'){
                            $this->vertikalline($YlineAwal,$YlineAkhir);
                        }
                    }
                }
                $this->fpdf->ln(2);
            }
            $count=count($col);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
            $btsakhir=$posY;
            if ($btsakhir <  $this->btshal){
                $btsakhir=$this->btshal+1;
            }
            // $this->fpdf->SetDrawColor(0,255,0); //Hijau
            for($i = 0; $i < $count; $i++ ) {
                $this->fpdf->Line($xx,$LineY,$xx,$btsakhir);
                $xx += $col[$i];
            }
            $newpage=true;
            $this->fpdf->Line($xx,$LineY,$xx,$btsakhir);

            // $this->fpdf->SetDrawColor(0,255,0); //Hijau
            $this->fpdf->Line($x1,$btsakhir,$xx,$btsakhir);
        }


        $this->AipFooter();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }
    private function enr21($id){
        $originalInput=Request::input();
        $user = Auth::user();
        $asptemp = getDataApi($originalInput,'api/airspace/temp/list?ctry=ID&deleted=0&sort=airspace_name:asc');
        // $asp = getDataApi($originalInput,'api/airspace/list?ctry=ID&deleted=0&sort=airspace_name:asc');
        $subcode = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        $tbl = getDataApi($originalInput,'api/eaip/codtableheader?tbl=eaip');

        unset($asptype);
        $asptype =array();
        $asptype[0]['id']='FIR';
        $asptype[0]['def']='FLIGHT INFORMATION REGIONS (FIR) :';

        $asptype[1]['id']='UTA';
        $asptype[1]['def']='UPPER CONTROL AREAS (UTA) WITHIN JAKARTA & UJUNG PANDANG FIRS : ';
        $asptype[2]['id']='SECTOR';
        $asptype[2]['def']='SECTORS WITHIN JAKARTA & UJUNG PANDANG FIRS : ';
        $asptype[3]['id']='CTA';
        $asptype[3]['def']='CONTROL AREA (CTA) : CTA WITHIN JAKARTA FIR :';
        $asptype[4]['id']='MTCA';
        $asptype[4]['def']='';
        $asptype[5]['id']='TMA';
        $asptype[5]['def']='TERMINAL CONTROL AREAS (TMA) WITHIN JAKARTA & UJUNG PANDANG FIRS : ';

        // $asptype =array['id':'FIR','def':'FLIGHT INFORMATION REGIONS (FIR) :'}, {'id':'UTA','def':'UPPER CONTROL AREAS (UTA)<br>WITHIN JAKARTA & UJUNG PANDANG FIRS : '}, {'id':'FSS','def':'FLIGHT SERVICE SECTORS (FSS)<br>WITHIN JAKARTA & UJUNG PANDANG FIRS : '}, {'id':'CTA',def:'CONTROL AREA (CTA) : <br> CTA WITHIN JAKARTA FIR :'}, {'id':'MTCA','def':''},{'id':'TMA','def':'TERMINAL CONTROL AREAS (TMA)<br> WITHIN JAKARTA & UJUNG PANDANG FIRS : '];
            // dd($asptype);
        $cod=[];$jdl='';$hal='';$jdl1='ENR 2. AIR TRAFFIC SERVICE AIRSPACE';
        if (!empty($subcode)){
            $cod=$subcode;
            $jdl=$cod[0]->sub_id.' '.$cod[0]->definition;
            $hal=$cod[0]->sub_id;
        }
        $data['subid'] = $id; 
        $this->fpdf = new Fpdf('P','mm',[160,210]);

        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$jdl);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($hal.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);

        $ce=[43,17,23,18,24];
        $this->createtableheaderasp($tbl,'asp',$ce,$jdl1,$jdl);
        $this->fpdf->ln(7);

        $LineY=$this->fpdf->GetY();
        $tgH=3.5;$lbr=125;
        $AwalY=$LineY; $AwalX= $this->fpdf->GetX();
        $posX=$this->fpdf->GetX();
        $posY=$this->fpdf->GetY();
        foreach ($asptype as $key => $as) {
            // $as =array();
            // $as['id']='UTA';
            // $as['def']='FLIGHT INFORMATION REGIONS (FIR) :';


            $data=[];
            $data=$this->CollectDataAsp($asptemp,$as,$ce,$posX,$posY);
            // dd($data);
            $this->DrawAsp($data,$as['def'],$ce,$posX,$posY,$jdl,$jdl1,$hal,$tbl);

        }
        $AkhY=$this->fpdf->GetY();
        $count=count($ce);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
        for($i = 0; $i < $count; $i++ ) {
            $this->fpdf->Line($xx,$posY,$xx,$AkhY+2 );
            $xx += $ce[$i];
        }

        $this->fpdf->Line($xx,$posY,$xx,$AkhY+2);
        $this->fpdf->Line($x1,$AkhY+2,$xx,$AkhY+2);


        $this->AipFooter();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }
    private function AddaspPage($jdl,$jdl1,$hal,$tbl){
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$jdl);
        $this->Watermark($this->draft);
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
            $rht=0;
        }else{
            $rht=0;
            $this->lm=20;$this->rm=15;
        }
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($hal.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);
        $ce=[43,17,23,18,24];
        $this->createtableheaderasp($tbl,'asp',$ce,$jdl1,$jdl);
    }
    private function DrawAsp($data,$type,$col,$pX,$pY,$jdl,$jdl1,$hal,$tbl){
        if(!empty($data)) {
            $posAsX=$this->fpdf->GetX();
            $posAsY=$this->fpdf->GetY();
            // dd($posAsY);
            unset($newpage);
            $newpage=false;
            $YlineAwal=$posAsY; $YlineAkhir=$posAsY;$tinggirem=0; $selisih=0;$jumlahData=count($data)-1;
            foreach ($data as $key => $as) {
                $YlineAwal=$this->fpdf->GetY();
                $this->fpdf->SetY($posAsY+$selisih);
                $arrrem=explode("\n",$this->fpdf->WordWrap($as['rem'],$col[4]));
                $stt=count($arrrem)*4;
                $arrrem=explode("\n",$this->fpdf->WordWrap($as['csign'],$col[2]));
                $stts=count($arrrem)*4;

                if ($this->fpdf->GetY() + $stt > $this->btshal-5 || $this->fpdf->GetY() + $stts > $this->btshal-5){
                    $count=count($col);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
                    $btsakhir=$posAsY+3;
                    if ($btsakhir <  $this->btshal){
                        $btsakhir=$this->btshal;
                    }
                    for($i = 0; $i < $count; $i++ ) {
                        $this->fpdf->Line($xx,$pY,$xx,$btsakhir);
                        $xx += $col[$i];
                    }
                    $newpage=true;
                    $this->fpdf->Line($xx,$pY,$xx,$btsakhir);
                    // $this->fpdf->SetDrawColor(0,0,255); //biru
                    $this->fpdf->Line($x1,$btsakhir,$xx,$btsakhir);

                    $this->fpdf->ln($this->ln);
                    $this->AipFooter();
                    $this->AddaspPage($jdl,$jdl1,$hal,$tbl);
                    $this->fpdf->ln(7);
                    $pY=$this->fpdf->GetY();
                    // $this->fpdf->Line($this->lm,$this->fpdf->GetY(), ($this->lebar - $this->rm),$this->fpdf->GetY());
                    $posAsY= $this->fpdf->GetY();
                    $pY=$posAsY;
                    $pX= $this->lm;
                    $YlineAwal=$this->fpdf->GetY();
                    $tinggirem=0;
                    $selisih=0;
                }

                // var_dump($this->fpdf->GetY(),$posAsY,$as['name']);
                // var_dump($posAsY,'AWAL',$as['name']);
                $posAsX=$this->lm;
                $this->fpdf->SetY($posAsY+$selisih);
                $this->fpdf->SetX($this->lm);
                // $this->fpdf->SetDrawColor(255,0,0);
                $this->fpdf->Line($this->lm,$posAsY+$selisih, ($this->lebar - $this->rm),$posAsY+$selisih);

                $this->fpdf->SetFont('Arial','B',8.5);
                if ($key==0){
                    $this->fpdf->Ln(2);
                    $this->fpdf->MultiCell($col[0],4,$type,0,'C');
                }
                $this->fpdf->Ln(2);

                $this->fpdf->MultiCell($col[0],4,$as['name'],0,'L');
                $this->fpdf->SetFont('Arial','',8);
                $this->fpdf->Ln(1);
                $posAsY=$this->fpdf->GetY();

                $posAsX +=$col[0];
                $this->fpdf->SetY($posAsY-4);
                $this->fpdf->SetX($posAsX);
                $this->fpdf->SetFont('Arial','',7.5);
                $this->fpdf->MultiCell($col[1],4,$as['unit'],0,'C');
                $this->fpdf->SetFont('Arial','',8);
                $this->fpdf->SetY($posAsY-4);
                $posAsX +=$col[1];
                $this->fpdf->SetX($posAsX);
                $this->fpdf->MultiCell($col[2],4,$as['csign'],0,'C');
                $this->fpdf->SetY($posAsY-4);
                $posAsX +=$col[2];
                $this->fpdf->SetX($posAsX);
                $this->fpdf->MultiCell($col[3],4,$as['frq'],0,'J');
                $this->fpdf->SetY($posAsY-4);
                $posAsX +=$col[3];
                $this->fpdf->SetX($posAsX);

                $this->fpdf->MultiCell($col[4],4,$as['rem'],0,'J');

                $tinggirem=$this->fpdf->GetY();

                $this->fpdf->SetY($posAsY);
                $this->fpdf->SetX($pX);
                $arrisi=explode("\n",$this->fpdf->WordWrap($as['text'],$col[0]));
                // $arrisi=$this->fpdf->WordWrap($as['text'],$col[0]);
                // dd($arrisi);
                // looping isi text (boundary koordinate)
                $alg='L';
                for ($x=0; $x < count($arrisi); $x++) { 
                    $this->fpdf->SetX($this->lm);
                    $content = iconv('UTF-8', 'windows-1252',$arrisi[$x]);
                    $this->fpdf->SetFont('Arial','',8);
                    if (substr($content,0,2)=='@@'){
                        $this->fpdf->SetFont('Arial','U',8);
                        $content= str_replace('@@','',$content);
                        $alg='C';
                    }
                    if (substr($content,0,2)=='##'){
                        $this->fpdf->SetFont('Arial','',8);
                        $content= str_replace('##','',$content);
                        $alg='C';
                    }
                    $this->fpdf->Cell($col[0],$this->ln,$content,0,0,$alg);
                    $this->fpdf->ln(4);
                    $posAsY=$this->fpdf->GetY();

                    if ($this->fpdf->GetY() > $this->btshal-2){
                        $YlineAkhir=$this->fpdf->GetY();
                        $newpage=true;
                        $count=count($col);$xx=$this->lm;$x1=$this->lm;
                        for($i = 0; $i < $count; $i++ ) {
                            $this->fpdf->Line($xx,$pY,$xx,$posAsY);
                            $xx += $col[$i];
                        }
                        $this->fpdf->Line($xx,$pY,$xx,$posAsY);
                        // $this->fpdf->SetDrawColor(0,255,255); //cyan
                        $this->fpdf->Line($x1,$posAsY,$xx,$posAsY);
                        $this->fpdf->ln($this->ln);
                        // membuat vertikal line
                        if ($as['status'] !== 'U'){
                            $this->vertikalline($YlineAwal,$YlineAkhir);
                        }

                        $this->AipFooter();
                        $this->AddaspPage($jdl,$jdl1,$hal,$tbl);
                        $this->fpdf->ln(7);
                        $pY=$this->fpdf->GetY();
                        $this->fpdf->Line($this->lm,$this->fpdf->GetY(), ($this->lebar - $this->rm),$this->fpdf->GetY());
                        $posAsY= $this->fpdf->GetY();
                        $pY=$posAsY;
                        $pX= $this->fpdf->GetX();
                        $this->fpdf->ln(1);
                        $YlineAwal=$this->fpdf->GetY();
                        $tinggirem=0;
                    }


                }
                // akhir looping text

                $YlineAkhir=$this->fpdf->GetY();
                $this->fpdf->Ln(1);
                $selisih=0;
                if ($tinggirem >  $this->fpdf->GetY()){
                    $selisih= round(($tinggirem - $this->fpdf->GetY())/4)+2;
                }
                $posAsY +=$selisih;



                if ($posAsY > $this->btshal-15 && $key < $jumlahData){
                    $count=count($col);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
                    $btsakhir=$posAsY+$selisih;
                    if ($btsakhir <  $this->btshal){
                        $btsakhir=$this->btshal+1;
                    }
                    // $this->fpdf->SetDrawColor(0,255,0); //Hijau
                    for($i = 0; $i < $count; $i++ ) {
                        $this->fpdf->Line($xx,$pY,$xx,$btsakhir);
                        $xx += $col[$i];
                    }
                    $newpage=true;
                    $this->fpdf->Line($xx,$pY,$xx,$btsakhir);

                    // $this->fpdf->SetDrawColor(0,255,0); //Hijau
                    $this->fpdf->Line($x1,$btsakhir,$xx,$btsakhir);

                    $this->fpdf->ln($this->ln);
                    $this->AipFooter();
                    $this->AddaspPage($jdl,$jdl1,$hal,$tbl);

                    $this->fpdf->ln(7);
                    $pY=$this->fpdf->GetY();
                    // $this->fpdf->Line($this->lm,$this->fpdf->GetY(), ($this->lebar - $this->rm),$this->fpdf->GetY());
                    $posAsY= $this->fpdf->GetY();
                    $pY=$posAsY;
                    $pX= $this->lm;

                    $YlineAwal=$this->fpdf->GetY();
                    $tinggirem=0;
                    $selisih=0;
                }

                $this->fpdf->SetY($posAsY+$selisih);
                $posAsX=$pX;
                $this->fpdf->SetX($posAsX);

                if ($as['status'] !== 'U'){
                    $this->vertikalline($YlineAwal,$YlineAkhir);
                }
            }

        }
        // return $posAsY+$selisih;
    }
    private function CollectDataAsp($data,$type){
        If(! empty($data)) {
            $aspl=[];
            for ($i=0; $i < count($data); $i++) {
                $asp=$data[$i];
                if ($asp->airspace_type==$type['id']){
                    // dd($asp);
                    $ass=[];
                    $stsasp=$asp->status;
                    $ass[ 'asptype' ] = $type['def'];
                    $ass[ 'status' ] = $stsasp;
                    $astyp=$asp->airspace_type;
                    if ($astyp=='FSS'){
                        $astyp='SECTOR';
                    }
                    $ass[ 'name' ] =  $asp->airspace_name .' '.$astyp;
                    $cls='';
                    if ( $asp->class[ 0 ]->asp_class == '' || $asp->class[ 0 ]->asp_class == null ) {
                        $cls='';
                    } else {
                        $cls='Airspace Classification : '.$asp->class[0]->asp_class;
                    }
                    $textasp=GetSegmentText($asp,$asp->airspace_type);
                    $textasp=$textasp. PHP_EOL .'@@'.$asp->class[ 0 ]->upper. PHP_EOL .'##'.$asp->class[ 0 ]->lower. PHP_EOL .'##'.$cls;

                    $ass[ 'text' ] =$textasp;
                    // dd($textasp);


                    $ass[ 'unit' ] = str_replace('Fic','FIC', str_replace('Acc','ACC',str_replace('Fss','FIC',str_replace('App','APP',ucwords(strtolower($asp->ats_unit))))));
                    $unit =ucwords(strtolower($asp->freq[ 0 ]->callsign[ 0 ]->call_sign)) . PHP_EOL .'English'. PHP_EOL .$asp->freq[ 0 ]->callsign[ 0 ]->segment[ 0 ]->opr_hrs;
                    $ass[ 'csign' ] = $unit;
                    // $ass[ 'lang' ] = 'English';
                    // $ass[ 'oprhrs' ] = $asp->freq[ 0 ]->callsign[ 0 ]->segment[ 0 ]->opr_hrs;
                    $frq='';
                    $cnt=count($asp->freq[ 0 ]->callsign[ 0 ]->segment);
                    for ($x = 0; $x < $cnt; $x++){
                        $f ='';
                        if ( $asp->freq[ 0 ]->callsign[ 0 ]->segment[ $x ]->value[ 0 ]->unit == 'V' ) {
                            $f= ($asp->freq[ 0 ]->callsign[ 0 ]->segment[ $x ]->value[ 0 ]->freq/1000000) .'MHz';
                        } else {
                            $f= ($asp->freq[ 0 ]->callsign[ 0 ]->segment[ $x ]->value[ 0 ]->freq/1000) .'kHz';
                        }
                        if ( $frq == '' ) {
                            $frq=$f;
                        } else {
                            $frq =$frq.', '.$f;
                        }
                    }
                    $ass[ 'frq' ] = $frq;
                    if ( $asp->freq[ 0 ]->callsign[ 0 ]->remarks == null ) {
                        $ass[ 'rem' ] ='';
                    } else {
                        $ass[ 'rem' ] = $asp->freq[ 0 ]->callsign[ 0 ]->remarks;
                    }
                    // dd($ass);
                    array_push($aspl,$ass);
                }
            }
            return $aspl;
        }
    }

    private function atsroute($id){
        $originalInput=Request::input();
        $user = Auth::user();

        $tbl = getDataApi($originalInput,'api/eaip/codtableheader?tbl=eaip');
        $enr = getDataApi($originalInput,'api/ats/list/temp/'.$id.'?ctry=ID');
        $subcode = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        $ats=[];
        // dd($cod[0]->sub_id);
        If(! empty($enr)) {foreach ($enr as $key => $value) {
            $at=[];
            $at = getDataApi($originalInput,'api/ats/temp?ctry='.$value->ctry.'&sort=seq_424:asc');
            // $at = getDataApi($originalInput,'api/ats/temp?ctry=W33_ID&sort=seq_424:asc');
            array_push($ats, $at);
            }
        }
        $cod=[];$jdl='';$hal='';$jdl1='ENR 3 ATS ROUTES';
        if (!empty($subcode)){
            $cod=$subcode;
            $jdl=$cod[0]->sub_id.' '.$cod[0]->definition;
            $hal=$cod[0]->sub_id;
        }
        $data['subid'] = $id; 
        $this->fpdf = new Fpdf('P','mm',[160,210]);
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$jdl);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($hal.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);
        $charcode='';
        if ($id=='64'){
            $ce=[35,10,9,17,17,11,26];
            $charcode='ats_vfr';
        }else{
            $ce=[25,10,9,12,17,11,9,9,23];
            $charcode='ats_ifr';
        }
        $this->createtableheaderenr($tbl,$charcode,$ce,$jdl1,$jdl);
        $this->fpdf->ln(6);
        $RowH=10;$fillc='';$alg='C';$PosX=$this->head;$intTinggi=6.5;

        $imgtot=$this->lm +2;$LineY=$this->fpdf->GetY();
        $tgH=3.5;$lbr=125;
        $AwalY=$LineY; $AwalX= $this->fpdf->GetX();

        $this->fpdf->Ln(2);
        If(! empty($ats)) {
            for ($i=0; $i < count($ats); $i++) { 
                $a=$ats[$i];

                If(! empty($a)) {
                    $jml=count($a)-1;
                    $isArrowUp=false;$isArrowDown=false;$oneWay=false;$dir424='';$remsama=[];
                    $isTracksama=false;$isDistsama=false;$isUppersama=false;$isMeasama=false;$isRnpsama=false;
                    $atscollect=$this->CollectAts($a);
                    // dd($atscollect);
                    $ident='';$btsh=20;$key=0;$keyats=0;
                    $c_track=0;$c_dist=0;$c_alt=0;$c_mea=0;$c_rnp=0;$c_rem=0;
                    foreach ($atscollect as $ixx => $e) {
                        $key=$ixx;
                        $keyats=$ixx;
                        // menyimpan utk halaman berikutnya seandainya mash nyambung
                        if ($e['track'] !==''){
                            $c_track=$key;
                        }
                        if ($e['dist'] !==''){
                            $c_dist=$key;
                        }
                        if ($e['alt'] !==''){
                            $c_alt=$key;
                        }
                        if ($e['mea'] !==''){
                            $c_mea=$key;
                        }
                        if ($e['rnp'] !==''){
                            $c_rnp=$key;
                        }
                        if ($e['rem'] !==''){
                            $c_rem=$key;
                        }
                        //==========

                        if ($ident !== $e['ident']){
                            // $pX=$this->fpdf->Ln(1);
                            $this->DrawAtsname($e['ident'],$tgH);

                        }
                            /// POINT 1 PLOT
                        $pX=$this->fpdf->GetX();
                        $pY=$this->fpdf->GetY();
                        $pY=$this->DrawPoint($e,'1',$this->fpdf->GetX(),$this->fpdf->GetY(),$ce[0],$key);
                        // var_dump($pY);
                        $pY1 = $pY;//untuk batas atas remarks
                        $pY +=$intTinggi;
                        // utnuk merubah index data kalau pindah halaman
                        $drawatt=true;

                        if ($this->fpdf->GetY() > $this->btshal-$btsh){
                            if ($charcode=='ats_ifr'){
                                $this->DrawOddEven($arrDown,'UP',$pX2,$pY+2,$ce[6]);
                            }
                            $this->fpdf->SetFont('Arial','B',8);
                            $this->fpdf->ln(3);
                            $this->fpdf->Cell($ce[0],$tgH,'Cont.',0,0,'R');
                            $this->fpdf->SetFont('Arial','',8);
                            $isArrowUp=false;
                            $isArrowDown=false;

                            // garis utk batas bawah table
                            $this->fpdf->Line($this->lm,$pY+$intTinggi+2,$this->lm+ $lbr,$pY+$intTinggi+2);
                            $this->tableline($ce,$pX,$AwalY,$pY+$intTinggi+2);
                            $this->AddNewPage($hal);
                            $this->createtableheaderenr($tbl,$charcode,$ce,$jdl1,$jdl);
                            $this->fpdf->ln(6);
                            $AwalX=$this->fpdf->GetX();
                            $this->fpdf->SetX($this->lm);
                            $this->fpdf->SetFont('Arial','B',8);
                            $AwalY=$this->fpdf->GetY()+2;
                            $this->fpdf->Cell($ce[0],4,'Cont.',0,1,'R');
                            $this->fpdf->SetFont('Arial','',8);

                            $this->DrawAtsname($e['ident'],$tgH);
                            $drawatt=false;
                            $pX=$AwalX;
                            // kembali ke index yg ada valuenya
                            $key=0;
                            $pY=$this->DrawPoint($e,'1',$this->fpdf->GetX(),$this->fpdf->GetY(),$ce[0],0);
                            $pY1 = $pY;//untuk batas atas remarks
                            $pY +=$intTinggi;
                        }
                        // // PLOT ATRIBUTE
                        $cy=$this->fpdf->GetY();
                        // if ($drawatt==true){

                            $pX1 =$pX+$ce[0];
                            $arrUp='';$arrDown='';
                            //Draw Track
                            $underline=false; $tgHTrack=$tgH;
                            if ($e['dir424']=='F'){
                                $oneWay=true;
                                $arrUp=$e['ArrUp'];
                                $arrDown='';
                                $tgHTrack *=2;
                            }else if ($e['dir424']=='B'){
                                $oneWay=true;
                                $arrUp='';
                                $arrDown=$e['ArrDown'];
                                $tgHTrack *=2;
                            }else{
                                $underline=true;
                                $oneWay=false;
                                $arrUp=$e['ArrUp'];
                                $arrDown=$e['ArrDown'];
                            }
                            if ($drawatt==false){
                                $keyats=$c_track;
                            }
                            $this->DrawContent($atscollect,'track',$tgHTrack,$pY1+3,$pX1,$pY,$keyats,$key,$ce[1],$e['status'],$ixx,$underline,true,$drawatt);
                            //dist
                            if ($drawatt==false){
                                $keyats=$c_dist;
                            }
                            $pX1 +=$ce[1];
                            $this->DrawContent($atscollect,'dist',$tgH*2,$pY1+3,$pX1,$pY,$keyats,$key,$ce[2],$e['status'],$ixx,false,false);
                            // UPPER LOWER
                            if ($drawatt==false){
                                $keyats=$c_alt;
                            }
                            $pX1 +=$ce[2];
                            $this->DrawContent($atscollect,'alt',$tgH,$pY1+3,$pX1,$pY,$keyats,$key,$ce[3],$e['status'],$ixx,true,true);
                            // //MEA
                            if ($drawatt==false){
                                $keyats=$c_mea;
                            }
                            $pX1 +=$ce[3];
                            $this->DrawContent($atscollect,'mea',$tgH,$pY1+3,$pX1,$pY,$keyats,$key,$ce[4],'U',$ixx,false,true);
                            // //RNP
                            if ($drawatt==false){
                                // $drawatt=true;
                                $keyats=$c_rnp;
                            }
                            $pX1 +=$ce[4];
                            $this->DrawContent($atscollect,'rnp',$tgH,$pY1+3,$pX1,$pY,$keyats,$key,$ce[5],'U',$ixx,false,false);
                            //posisi panah
                            $keyats=$ixx;
                            $idcol=6;
                            $pX1 +=$ce[5];
                            $pX2=$pX1;

                            if ($charcode=='ats_ifr'){
                                $arah=$e['dir424'];
                                if ($arah=='' || $arah==null){
                                    $arah='';
                                }
                                if ($dir424 !== $arah){
                                    $isArrowUp=false;
                                    $isArrowDown=true;
                                }
                                    $pX2=$pX1;
                                    $pX1 =$pX2;
                                    // $pX1 +=$ce[5];
                                    // $this->fpdf->SetX($pX1+3);
                                    if($key !==0 && $isArrowUp==false){
                                        $oXX=$pX2+$ce[5];
                                        $this->fpdf->Line($pX2,$pY1+3,$pX2 + ($ce[6]*2),$pY1+3);
                                    }
                                    if ($isArrowUp==false){
                                        $isArrowUp= $this->DrawOddEven($arrUp,'DOWN',$pX2,$cy,$ce[6]);
                                    }
                                    $dir424=$e['dir424'];
                                    if ($dir424=='' || $dir424==null){
                                        $dir424='';
                                    }

                                    $idcol=8;
                                $this->fpdf->SetY($pY1);
                                $pX1 +=$ce[6]+$ce[7];

                            }
                            $yyrem= $this->fpdf->GetY();
                            $this->fpdf->SetY($yyrem+$tgH);
                            $this->fpdf->SetX($pX1);

                            if (!empty($e['rem']) && $remsama !== $e['rem']){
                                if($key !==0){
                                    $this->fpdf->Line($this->fpdf->GetX(),$this->fpdf->GetY()-0.5,$this->fpdf->GetX()+$ce[$idcol],$this->fpdf->GetY()-0.5);
                                }
                                $rem=$e['rem'][0];
                                $rTex='';
                                    $rTex=$this->Convtext($rem['rem']);
                                    $NewYpos= $this->Drawreamrks($rTex,$ce[$idcol],$pX1);
                                    // $tbg=explode("\n",$this->fpdf->WordWrap($rTex,$ce[$idcol]));
                                    // $t=count($tbg);
                                    // // dd($tbg);
                                    // for($i = 0;$i < $t;$i++){
                                    //     // var_dump($tbg[$i]);
                                    //     $this->fpdf->Cell($ce[$idcol],$tgH,$tbg[$i],0,0,'L');
                                    //     $this->fpdf->ln($this->ln);
                                    //     $this->fpdf->SetX($pX1);
                                    //     // $newpage=$this->fpdf->getY();
                                    //     // if ($newpage > $this->btshal){
                                    //     //     $this->loncatnewpage($icao);
                                    //     //     $this->fpdf->Cell($this->tab);
                                    //     // }
                                    // }
                                    // dd($atscollect[$ixx+1],$rem['rem']);
                                    // $this->fpdf->MultiCell($ce[$idcol],$tgH,$rTex,0,'L');
                                    if ($ixx < count($atscollect)-1){
                                        if (!empty($atscollect[$ixx+1]['rem'])){
                                            $pY = $NewYpos ;
                                        }
                                    }
                                    if ($NewYpos > $this->btshal-$btsh){
                                        $pY = $NewYpos-10 ;
                                    }
                                    // if ($atscollect as $ixx)
                                    // $rTex=$this->Convtext($rem['unit']);
                                    // $this->fpdf->SetX($pX1);
                                    // $this->fpdf->MultiCell($ce[$idcol],$tgH,$rTex,0,'L');
                                    // $rTex=$this->Convtext($rem['freq']);
                                    // $this->fpdf->SetX($pX1);
                                    // $this->fpdf->MultiCell($ce[8],$tgH,$rTex,0,'L');
                                // }else{
                                //     $rTex=$this->Convtext($rem['unit']);
                                //     $this->fpdf->MultiCell($ce[$idcol],$tgH,$rTex,0,'L');
                                //     // $this->fpdf->SetX($pX1);
                                //     // $rTex=$this->Convtext($rem['freq']);
                                //     // $this->fpdf->MultiCell($ce[8],$tgH,$rTex,0,'L');
                                // }
                                $this->fpdf->SetY($pY);
                            }else{
                                $this->fpdf->Cell($ce[$idcol],$tgH,'',0,0,'L');
                                $this->fpdf->Ln($tgH);
                            }
                            if ($remsama ==$e['rem']){
                                $this->fpdf->SetY($pY);
                            // }else{
                            //     $pY=$this->fpdf->GetY();
                            //     $this->fpdf->SetY($pY);
                            }
                            $remsama=$e['rem'];
                            $this->fpdf->Ln($intTinggi);
                        // }

                        /// POINT 2 PLOT
                        if ($jml==$ixx){
                            if ($charcode=='ats_ifr'){

                                $this->DrawOddEven($arrDown,'UP',$pX2,$cy+4,$ce[6]);
                            }
                            $this->DrawPoint($e,'2',$this->fpdf->GetX(),$this->fpdf->GetY(),$ce[0],0);
                            $this->fpdf->Ln(6);
                            $this->tableline($ce,$pX,$AwalY,$this->fpdf->GetY());
                            $this->fpdf->Line($this->lm,$this->fpdf->GetY(),$this->lm + $lbr,$this->fpdf->GetY());
                            $AwalY=$this->fpdf->GetY();
                            $this->fpdf->Ln(2);
                            if ($this->fpdf->GetY() > $this->btshal-25){
                                $isArrowUp=false;
                                $isArrowDown=false;
                                $this->AddNewPage($hal);
                                $this->createtableheaderenr($tbl,$charcode,$ce,$jdl1,$jdl);
                                $this->fpdf->ln(6);
                                $AwalY=$this->fpdf->GetY()-2;
                                $this->fpdf->SetX($this->lm);
                                $AwalX=$this->fpdf->GetX();
                                // $this->fpdf->Line($this->lm, $this->fpdf->GetY(), $this->lm +$this->lebararea, $this->fpdf->GetY());
                                $pX=$AwalX;
                                $pY=$this->fpdf->GetY();
                                $pY1=$pY+$intTinggi;
                            }




                        }
                        $ident=$e['ident'];
                    }
                }
            }
        }




        $this->AipFooter();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }
    Private function Drawreamrks($text,$col,$x){
        $tbg=explode("\n",$this->fpdf->WordWrap($text,$col));
        $t=count($tbg);
        $tgH=3.5;
        for($i = 0;$i < $t;$i++){
            // var_dump($tbg[$i]);
            $this->fpdf->Cell($col,$tgH,$tbg[$i],0,0,'L');
            $this->fpdf->ln($tgH);
            $this->fpdf->SetX($x);
            // $newpage=$this->fpdf->getY();
            // if ($newpage > $this->btshal){
            //     $this->loncatnewpage($icao);
            //     $this->fpdf->Cell($this->tab);
            // }
        }
        return  $this->fpdf->GetY()-($tgH*2);
    }
    Private function DrawOddEven($posisi,$symbol,$Xpos,$Ypos,$column){
        if ($posisi !== ''){
            if ($posisi=='ODD'){
                $Xpos += $column/2;
            }else{
                $Xpos += $column + ($column/2);
            }
            $symb=$this->GetSymbol($symbol);
            $this->fpdf->Image($symb,$Xpos,$Ypos-3,-200);
            return true;
        }else{
            return false;
        }
    }
    private function checktinggimergecolum($pos,$jmlah){
        unset($tinggi);unset($btshal);
        if ($jmlah > 2){
            $tinggi=$pos+($jmlah*4);
        }else{
            $tinggi=$pos+($jmlah*6.5);
        }

        $btshal=$this->btshal-15;
        if ($tinggi >  $btshal){
            // var_dump($pos,$tinggi,$jmlah,$pos + (($btshal-$pos)/2),'llllll');
            $tinggi= $pos + (($btshal-$pos)/2);
        }
        return $tinggi;
    }
    private function CheckNumeric($TextString){
        $hsl = false;
        $txt = Trim($TextString,' ');
        $jm = strlen($txt);
        for($i= 1;$i<$jm;$i++){
            $tmid=substr($txt,$i,1);
            if (is_numeric($tmid)){
                $hsl = true;
                break;
            }
        }

        return $hsl;
    }
    private function DrawAtsname($name,$tgH){

        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Cell(0,$tgH,iconv('UTF-8', 'windows-1252', $name),0,0,'L');
        $this->fpdf->Ln($tgH);
        $this->fpdf->SetFont('Arial','BU',8);
        if ($this->CheckNumeric($name)==true && strlen($name) < 8){
            $this->fpdf->Cell(0,$tgH,ConverNumChart($name),0,0,'L');
        }
        $this->fpdf->Ln(5);
        $this->fpdf->SetFont('Arial','',8);
    }
    private function DrawContent($ats,$fieldname,$tggitext,$yline,$xpos,$ypos,$atsidx,$idx,$col,$status,$key,$underline,$explode,$newpage=true){
        // $this->DrawContent($atscollect,'track',$tgHTrack,$pY1+3,$pX1,$pY,$keyats,$key,$ce[1],$e['status'],$underline,true);
        $val=$ats[$atsidx][$fieldname];
        $fld='';$fld1='';$AwY=$ypos-3;
        
        if ($newpage == false && $key > 0){
            // if ($ats[$atsidx]['ident']=='A339'){
                // var_dump($ats[$atsidx]['ident'],$ats[$atsidx]['track'],$atsidx,$idx);
            // }
            if ($ats[$key]['track'] == $ats[$key-1]['track']){
                $ypos =$yline-9.5;
                // dd($ats[$key],$ats[$key-1]);

            }
        
        }
        // if ($atsidx > 0){
        //     if ($val == $ats[$atsidx-1][$fieldname]){
        //         $ypos=$yline;
        //     }
        // }
        if ($explode==true){
            // dd($value);
            $value= explode('@',$val,2);
            $fld=$this->Convtext($value[0]);
            $fld1=$this->Convtext( isset($value[1])?$value[1]:'');
        }else{
            $fld=$val;
        }
        // dd($ats[$idx][$fieldname]);//get value field
        unset($jm);unset($Ycorrectpos);
        $jm=$this->carisama($ats,$fieldname,$atsidx);

        $Ycorrectpos=$this->checktinggimergecolum($ypos,$jm);
        $ypos=$Ycorrectpos;
        // if ($AwY+$Ycorrectpos > $this->btshal){

        //     $ypos = $AwY+3.5;
        // }

        $this->fpdf->SetY($ypos);
        $this->fpdf->SetX($xpos);
        $und='';
        if ($underline==true){
            $und='U';
        }
        if($idx > 0 && $val !==''){
            // if ($fieldname=='rnp'){
            //     var_dump( $ypos,$ypos,$jm,$fld);
            // }
            // var_dump($idx,$value);
            $this->fpdf->Line($xpos,$yline,$xpos+$col,$yline);
        }
        $this->fpdf->SetFont('Arial',$und,8);
        $this->fpdf->Cell($col,$tggitext,$fld,0,0,'C');
        $this->fpdf->SetFont('Arial','',8);
        if  ($explode==true){
            $this->fpdf->ln(3);
            $this->fpdf->SetX($xpos);
            $this->fpdf->Cell($col,$tggitext,$fld1,0,0,'C');
        }
        $this->fpdf->SetFont('Arial','',8);
        unset($xline);
        $xline= $this->lm + 126.5;
        if ($status !=='U'){
            $this->fpdf->SetLineWidth(0.4);
            $this->fpdf->Line($xline,$AwY,$xline,$AwY+20);
            $this->fpdf->SetLineWidth(0);
        }
    }

    Private function carisama($ats,$fld,$idx){
    // dd($fld,$idx);
        $no=0;
        if (!empty($ats)){
            // for ($i=0; $i < count($ats); $i++) { 
            //     # code...
            // }
            foreach ($ats as $key => $v) {
                // var_dump($v);
                if ($key > $idx){
                    // dd($v[$fld],$key,$idx);
                    if ($v[$fld]==''){
                        $no +=1;
                    }else{
                        break;
                    }
                }
            }

        }

        return $no;
    }
    private function CollectAts($ats){
        // dd($ats);
        $hasil=[];$trkO='';$trkI='';$dist='';$maa='';$mfa='';$mea='';$seguse='';$rnp='';$rem=[];$remarks=[];
        foreach ($ats as $key => $e) {
            $a['ident']=$e->ats_ident;
            $a['status']=$e->status;
            $a['ats_type']=$e->type;
            $a['seq']=$key;
            if ($e->dir_424==null || $e->dir_424 =='' ){
                if($e->track_out > 179 && $e->track_out < 359){
                    $a['ArrUp']='EVEN';
                    $a['ArrDown']='ODD';
                }else{
                    $a['ArrUp']='ODD';
                    $a['ArrDown']='EVEN';
                }
                $a['dir424']='';
            }else{
                if ($e->dir_424=='F'){
                    if($e->track_out > 179 && $e->track_out < 359){
                        $a['ArrUp']='EVEN';
                        $a['ArrDown']='';
                    }else{
                        $a['ArrUp']='ODD';
                        $a['ArrDown']='';
                    }

                }else{
                    if($e->track_out > 179 && $e->track_out < 359){
                        $a['ArrUp']='';
                        $a['ArrDown']='EVEN';
                    }else{
                        $a['ArrUp']='';
                        $a['ArrDown']='ODD';
                    }
                }
                $a['dir424']=$e->dir_424;
            }
            if ($e->track_out==null || $e->track_out =='' || $e->track_out =='NIL' ){
                $trackout='';
            }else{
                $trackout= $e->track_out;
            }
            if ($e->track_in==null || $e->track_in =='' || $e->track_in =='NIL' ){
                $trackin='';
            }else{
                $trackin= $e->track_in;
            }
            if ($trkO == $trackout  && $trkI == $trackin){
                $a['track']='';
            }else{
                $a['track']=$trackout.'@'.$trackin;
            }

            $trkO=$trackout;$trkI=$trackin;

            if($dist !== $e->dist){
                $a['dist']=$e->dist;
            }else{
                $a['dist']='';
            }
            $dist=$e->dist;
            if($maa == $e->maa && $mfa == $e->mfa){
                $a['alt']='';
            }else{
                if ($e->mfa=='SFC' || $e->mfa=='GND'){
                    $a['alt']=$e->maa.'@GND/Water';
                }else{

                    $a['alt']=$e->maa.'@'.$e->mfa;
                }
            }
            $maa=$e->maa;
            $mfa=$e->mfa;

            if($mea == $e->mea_out && $seguse == $e->seg_use){
                $a['mea']='';
            }else{
                if ($e->mea_out=='SFC' || $e->mea_out=='GND'){
                    $a['mea']='GND/Water@'.$e->seg_use;
                }else{
                    $a['mea']=$e->mea_out.'@'.$e->seg_use;
                }

            }
            $mea=$e->mea_out;
            $seguse=$e->seg_use;
            if($rnp !== $e->rnp_type){
                $a['rnp']=$e->rnp_type;
            }else{
                $a['rnp']='';
            }
            $rnp=$e->rnp_type;
            unset($cordformat);
            $wpt1=[];$wpt2=[];$cordformat='';
            if (!empty($e->nav1)){
                $wpt1=$e->nav1;
                $cordformat='IAC';
                // dd($wpt1);
                $a['point1']=$wpt1[0]->nav_ident.' '.$wpt1[0]->definition;
            }else{
                $cordformat='ENR';
                $wpt1=$e->wpt1;
                if ($e->type=='V'){
                    $a['point1']=$wpt1[0]->desc_name;

                }else{

                    $a['point1']=$wpt1[0]->wpt_name;
                }
            }
            $a['type1']=$e->wpt_type;
            $cord = toWgs($wpt1[0]->geom->coordinates[0],'LON');
            $cord1 = toWgs($wpt1[0]->geom->coordinates[1],'LAT');
            // dd($cord,$cord1);
            $a['lat1']=$cord1[0][$cordformat] ;
            $a['lon1']=$cord[0][$cordformat];
            unset($cordformat);
            $cordformat='';
            if (!empty($e->nav2)){
                $cordformat='IAC';
                $wpt2=$e->nav2;
                $a['point2']=$wpt2[0]->nav_ident.' '.$wpt2[0]->definition;
            }else{
                $cordformat='ENR';
                $wpt2=$e->wpt2;
                if ($e->type=='V'){
                    $a['point2']=$wpt2[0]->desc_name;

                }else{

                    $a['point2']=$wpt2[0]->wpt_name;
                }
                // $a['point2']=$wpt2[0]->wpt_name;
            }
            $a['type2']=$e->wpt_type2;
            $cord = toWgs($wpt2[0]->geom->coordinates[0],'LON');
            $cord1 = toWgs($wpt2[0]->geom->coordinates[1],'LAT');
            $a['lat2']=$cord1[0][$cordformat] ;
            $a['lon2']=$cord[0][$cordformat];
            // dd($e->remarks);
            if (!empty($e->remarks)){
                $rem= $this->Getremark($e->remarks);
            }
            if ($remarks==$rem){
                $a['rem']=[];
            }else{
                $a['rem']=$rem;
            }
            $remarks=$rem;
            array_push($hasil,$a);
        }
        // dd($hasil);
        return $hasil;
    }
    private function Getremark($remarks){
        $originalInput=Request::input();
        $user = Auth::user();
        $remm=$remarks[0];
        $rem=[];
        $r['rem']=$remm->remarks;
        array_push($rem, $r);
        // if ($remm->asp_id){
        //     $aspid=explode(',',$remm->asp_id);
        //     for ($i=0; $i < count($aspid) ; $i++) { 
        //         $dd = getDataApi($originalInput,'api/airspace/list?ats_airspace_id='.$aspid[$i].'&deleted=0');
        //         if ($dd){
        //             $at=$dd[0];
        //             // if ($at->airspace_type=='UTA'){
        //                 $r['unit']=$at->ats_unit;
        //             // }else{

        //             //     $r['unit']=$at->airspace_name.' '.$at->airspace_type;
        //             // }
        //             $frq=$at->freq[0]->callsign[0]->segment;
        //             $freq='';
        //             foreach ($frq as $key => $f) {
        //                 $frr=Airspacefreq($f->value[0]->freq,$f->value[0]->unit);
        //                 if ($freq==''){
        //                     $freq =$frr;
        //                 }else{
        //                     $freq = $freq.', '.$frr;
        //                 }
        //                 // dd($value);
        //             }
        //             $r['freq']=$freq;
        //             // dd($at[0]->ats_unit);
        //             array_push($rem, $r);

        //         }
        //     }
        //     // dd($aspid);
        // }
        // dd($rem);
        return $rem;
    }

    Private function tableline($acol,$posX,$posY,$posYAkhir){
        $colT=$acol;
        array_unshift ($colT, 0);
        $int=$posX;
        $jg=count($colT);
        $posisiY=$posY;
        // var_dump($posisiY);
        // dd($int);
        for ($i=0; $i < $jg ; $i++) {
            $int +=$colT[$i];
            $tbb=14;
            if ($i==0 || $i == $jg-1){
                $tbb=-2;
            }
            $this->fpdf->Line($int, $posisiY+$tbb,$int,$posYAkhir);
        }
    }
    Private function AddNewPage($page){
        $this->AipFooter();
        $this->fpdf->AddPage();
        $this->Watermark($this->draft);
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
        }else{
            $this->lm=20;$this->rm=15;
        }
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($page.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);
    }
    private function enr41($id){
        $originalInput=Request::input();
        $user = Auth::user();
        // $sql="SELECT wpt_id,ats_ident  FROM waypoint_temp a inner join ats_temp b on b.point=a.wpt_id or b.point2=a.wpt_id where a.ctry='ID' and b.type not in ('X','V') GROUP BY wpt_id,ats_ident order by b.ats_ident";
        // $ww =DB::select(DB::raw($sql));

        $sql="SELECT nav_id  FROM navaid_temp a inner join ats_temp b on b.point=a.nav_id or b.point2=a.nav_id where a.ctry='ID' and b.type not in ('X','V') GROUP BY nav_id order by a.nav_name";
        $www =DB::select(DB::raw($sql));
        // dd($www);
        // sort( $result );
        // $dt= $result[0];
        $waypoints=[];$proc=[];$ats=[];$lwpt=[];
        $cod = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        $waypoints = getDataApi($originalInput,'/api/navaid/temp?ctry=ID&deleted=0&sort=nav_ident:asc');
        $navaids = getDataApi($originalInput,'api/navarpt/temp');
        // dd($waypoints);
        foreach ($waypoints as $key => $wp) {
            // dd($wp->nav_id);
            $searchword=$wp->nav_id;
            $stn='';
            foreach($navaids as $key => $value) {
                if ($value->nav_id == $searchword) {
                    $stn=$value->airport[0]->city_name.'/'.ucwords(strtolower($value->airport[0]->arpt_name));
                        // $ats=$value->ats_ident;
                }
            }
            $found = false;  
                foreach($www as $key => $value) {
                    if ($value->nav_id == $searchword) {
                        $found = true;
                        break;
                    }
                }

                if ($found){
                    $cord = toWgs($wp->geom->coordinates[0],'LON');
                    $cord1 = toWgs($wp->geom->coordinates[1],'LAT');
                    $nnn['id']=$searchword;
                    if ($stn==''){
                        $nnn['station']=$wp->nav_name;
                    }else{
                        $nnn['station']=$stn;
                    }

                    $nnn['type']=$wp->definition;
                    $nnn['ident']=$wp->nav_ident;
                    $nnn['elev']=$wp->dme_elev;
                    $nnn['status']=$wp->status_vld;
                    $nnn['remarks']=iconv('UTF-8', 'windows-1252', $wp->remarks);
                    $freq=$this->FreqFormat($wp->freq,$wp->type,'');
                    if ($wp->type=='4'){
                        $freq = $freq.' CH-'.$wp->channel;
                    }
                    $nnn['freq']=$freq;
                    $nnn['hrs']=$wp->opr_hrs;
                    $nnn['lat']=$cord1[0]['NONFIR'];
                    $nnn['lon']=$cord[0]['NONFIR'];
                    array_push($lwpt, $nnn);
                }

        }
        // $data['lwpt']=$waypoints;
        usort($lwpt, function($a, $b) {
            return $a['station'] <=> $b['station'];
        });
        // $cod = $data['cod'];$waypoints=$data['wpt'];
        // dd($lwpt);
        $this->fpdf = new Fpdf('P','mm',[160,210]);
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$cod[0]->sub_id.' '.$cod[0]->definition);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($cod[0]->sub_id.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->Cell(0,0,'ENR 4 NAVIGATION SYSTEM',0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->Cell(0,0,$cod[0]->sub_id.' '.$cod[0]->definition,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','',8);
        $AwalY=$this->fpdf->GetY();
        $this->tableenr41();
        $this->fpdf->ln(1);
        $AwalX=$this->fpdf->GetX();
        unset($tinggi);
        $tinggi=0;
        $col1=30;$col2=8;$col3=16;$col4=15;$col5=18;$col6=8;$col7=30;
        unset($col);
        // $col1=28;$col2=10;$col3=17;$col4=15;$col5=18;$col6=8;$col7=29;
        $col=[0,$col1,$col2,$col3,$col4,$col5,$col6,$col7];
        foreach ($lwpt as $key => $w) {
            $x=$this->fpdf->GetX();
            $y=$this->fpdf->GetY();

            $this->fpdf->SetFont('Arial','U',8);
            $this->fpdf->MultiCell($col1,4,$w['station'],0,'L');
            $this->fpdf->SetFont('Arial','',7.5);
            // $hgt=(($lgts/$col1 ) + 2 )* 4;
            unset($jr);
            $jr=$x;
            $this->fpdf->ln(1);

            $this->fpdf->Cell($col1,4,$w['type'],0,0,'L');
            $this->fpdf->Ln(4);
            $tinggi=$this->fpdf->GetY();
            $this->fpdf->SetY($y);
            $x +=$col1;
            $this->fpdf->SetX($x);
            $this->fpdf->Cell($col2,4,$w['ident'],0,0,'C');
            $this->fpdf->MultiCell($col3,4,$w['freq'],0,'C');
            $this->fpdf->SetY($y);
            $x +=$col2+$col3;
            $this->fpdf->SetX($x);
            $this->fpdf->Cell($col4,4,$w['hrs'],0,0,'C');
            $this->fpdf->Cell($col5,4,$w['lat'],0,0,'C');
            $this->fpdf->ln(4);
            $x +=$col4;
            $this->fpdf->SetX($x);
            $this->fpdf->Cell($col5,4,$w['lon'],0,0,'C');
            $this->fpdf->Cell($col6,4,$w['elev'],0,0,'C');
            $this->fpdf->SetY($y);
            $x +=$col5+$col6;
            $this->fpdf->SetX($x);
            $text=$w['remarks'];
            // $this->fpdf->SetFont('Arial','',7.5);
            $tbg=explode("\n",$this->fpdf->WordWrap($text,$col7));
            $tggirem=(count($tbg) * 3.5);
            if ($this->fpdf->GetY() + $tggirem > $this->btshal){
                $BatsY=$y;
                $btsX=$x-$this->lm;
                for ($i=0; $i < count($tbg) ; $i++) {
                    $this->fpdf->SetX($x);
                    $this->fpdf->Cell($col7,3.5,$tbg[$i],0,0,'L');
                    $this->fpdf->ln(3.5);
                    $BatsY +=3.5;
                    $this->fpdf->SetY($BatsY);

                    if ($this->fpdf->GetY() > $this->btshal){
                        $AwalY= $this->PindahHalaman41($col,$cod[0]->sub_id,$jr,$AwalY);
                        $x=$btsX + $this->lm;
                        $BatsY=$this->fpdf->GetY();
                        $this->fpdf->SetFont('Arial','',7.5);
                    }
                }
                $this->fpdf->Line($this->lm ,  $BatsY,$this->lm + 125, $BatsY);
                $this->fpdf->Ln(2);
            }else{

                $this->fpdf->MultiCell($col7,3.5,$text,0,'L');
                // $this->fpdf->ln(4);
                unset($ypos);
                $ypos=$this->fpdf->GetY();
                if ($tinggi > $ypos){
                    $this->fpdf->SetY($tinggi);
                }else{
                    $this->fpdf->SetY($ypos+1);
                }
                $this->fpdf->Line($jr , $this->fpdf->GetY(),$jr + 125,$this->fpdf->GetY());
                $this->fpdf->ln(1);
            }


            //akhir tulis

            if ($this->fpdf->GetY() > $this->btshal-10){

                $AwalY= $this->PindahHalaman41($col,$cod[0]->sub_id,$jr,$AwalY);

            }
            unset($xline);
            $xline= $this->lm + 126.5;
            if ($w['status']!=='U'){
                $this->fpdf->SetLineWidth(0.4);
                $this->fpdf->Line($xline,$y-1,$xline,$this->fpdf->GetY()-1);
                $this->fpdf->SetLineWidth(0);
            }
        }
        // $this->fpdf->SetX($this->lm);
        $int=$this->lm;
        for ($i=0; $i < count($col) ; $i++) {
            $int=$int +$col[$i];
            $this->fpdf->Line($int, $AwalY,$int,$this->fpdf->GetY()-2);
        }
        $this->AipFooter();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }
    Private function PindahHalaman41($acol,$halaman,$Xpos,$Ypos){
        $col=$acol;
        $int=$Xpos;
        for ($i=0; $i < count($col) ; $i++) {
            $int=$int +$col[$i];
            $this->fpdf->Line($int, $Ypos,$int,$this->fpdf->GetY());
        }
            $this->fpdf->Line($Xpos, $this->fpdf->GetY(),$int,$this->fpdf->GetY());
            $this->AddNewPage($halaman);
            $AwalY=$this->fpdf->GetY();
            $this->tableenr41();
            $this->fpdf->SetX($this->lm);
            $AwalX=$this->fpdf->GetX();
            return $AwalY;
    }
    private function enr43($id){
        $originalInput=Request::input();
        $user = Auth::user();
        $sql="SELECT wpt_id,ats_ident  FROM waypoint_temp a inner join ats_temp b on b.point=a.wpt_id or b.point2=a.wpt_id where a.ctry='ID' and b.type not in ('X','V') GROUP BY wpt_id,ats_ident order by b.ats_ident";
        $ww =DB::select(DB::raw($sql));

        $sql="SELECT wpt_id  FROM waypoint_temp a inner join ats_temp b on b.point=a.wpt_id or b.point2=a.wpt_id where a.ctry='ID' and b.type not in ('X','V') GROUP BY wpt_id order by a.wpt_name";
        $www =DB::select(DB::raw($sql));
        // dd($www);
        // sort( $result );
        // $dt= $result[0];
        $waypoints=[];$proc=[];$ats=[];$lwpt=[];
        $cod = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        $waypoints = getDataApi($originalInput,'/api/waypoint/temp?ctry=ID&deleted=0&sort=wpt_name:asc');
        // dd($waypoints);
        foreach ($waypoints as $key => $wp) {
            $searchword=$wp->wpt_id;
            // print_r($searchword);
            $ats='';
                foreach($ww as $key => $value) {
                    if ($value->wpt_id == $searchword) {
                        if($ats==''){
                            $ats=$value->ats_ident;
                        }else{
                            $ats=$ats.', '.$value->ats_ident;
                        }
                    }
                }
            $found = false;  
                foreach($www as $key => $value) {
                    if ($value->wpt_id == $searchword) {
                        $found = true;
                        break;
                    }
                }

                if ($found){
                    $cord = toWgs($wp->geom->coordinates[0],'LON');
                    $cord1 = toWgs($wp->geom->coordinates[1],'LAT');
                    $nnn['id']=$searchword;
                    $nnn['ident']=$wp->wpt_name;
                    $nnn['status']=$wp->status;
                    $nnn['lat']=$cord1[0]['NONFIR'];
                    $nnn['lon']=$cord[0]['NONFIR'];
                    $nnn['ats']=$ats;
                    array_push($lwpt, $nnn);
                }

        }
        // $data['lwpt']=$waypoints;

        // $cod = $data['cod'];$waypoints=$data['wpt'];
        // dd($waypoints);
        $this->fpdf = new Fpdf('P','mm',[160,210]);
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$cod[0]->sub_id.' '.$cod[0]->definition);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($cod[0]->sub_id.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->Cell(0,0,'ENR 4 NAVIGATION SYSTEM',0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->Cell(0,0,$cod[0]->sub_id.' '.$cod[0]->definition,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','',8);
        $AwalY=$this->fpdf->GetY();
        $this->tableenr43();
        $AwalX=$this->fpdf->GetX();
        $loncat=false;

        foreach ($lwpt as $key => $w) {
            $x=$this->fpdf->GetX();
            $y=$this->fpdf->GetY();
            // if ($w['status'] == 'U'){
            //     $this->fpdf->SetFont('Arial','',8);
            // }else{
            //     $this->fpdf->SetFont('Arial','BI',8);
            // }
            unset($xline);
            $xline= $this->lm - 1.5;
            if ($loncat==true){
                $this->fpdf->SetX($AwalX);
                $x=$this->fpdf->GetX();
                $xline= $this->lm + 126.5;
            }
            $this->fpdf->Cell(18,4,$w['ident'],0,0,'L');
            $this->fpdf->Cell(25,4,$w['lat'],0,0,'L');
            $this->fpdf->ln(4);
            $this->fpdf->SetX($x+18);
            $this->fpdf->Cell(25,4,$w['lon'],0,0,'L');
            $this->fpdf->SetY($y);
            $this->fpdf->SetX($x+40);
            $this->fpdf->MultiCell(20,4,$w['ats'],0);
            $lgt=$this->fpdf->GetStringWidth($w['ats']);
            if ($lgt <20){
                $this->fpdf->ln(5);
            }else{
                $this->fpdf->ln(1);
            }
            $this->fpdf->Line($x , $this->fpdf->GetY()-1,$x + 62,$this->fpdf->GetY()-1);

            if ($w['status'] !== 'U'){
                $this->fpdf->SetLineWidth(0.4);
                $this->fpdf->Line($xline,$y-1,$xline,$this->fpdf->GetY()-1);
                $this->fpdf->SetLineWidth(0);
            }
            //akhir tulis

            if ($this->fpdf->GetY() > $this->btshal-5){
                $col=[0,18,40,62];
                for ($i=0; $i < count($col) ; $i++) { 
                    $this->fpdf->Line($x + $col[$i], $AwalY,$x + $col[$i],$this->fpdf->GetY()-1);
                }
                if ($loncat==false){
                    $loncat=true;
                    $AwalX=$AwalX+63;
                    $this->fpdf->SetY($AwalY);
                    $this->fpdf->SetX($AwalX);
                    $this->tableenr43();
                }else{
                    $loncat=false;
                    $this->AddNewPage($cod[0]->sub_id);
                    $AwalY=$this->fpdf->GetY();
                    $this->tableenr43();
                    $this->fpdf->SetX($this->lm);
                    $AwalX=$this->fpdf->GetX();

                }

            }

        }
        $col=[0,18,40,62];
        for ($i=0; $i < count($col) ; $i++) { 
            $this->fpdf->Line($x + $col[$i], $AwalY,$x + $col[$i],$this->fpdf->GetY()-1);
        }
        $this->AipFooter();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }
    private function  createtableheaderenr($table,$chart,$col,$titel1,$titel2)
    {
    if (!empty($table)){
        if ($chart=='ats_vfr'){
            $this->createtableheaderenrvfr($table,$chart,$col,$titel1,$titel2);
        }else{

            $this->fpdf->SetFont('Arial','B',9);
            $this->fpdf->Cell(0,0,$titel1,0,0,'C');
            $this->fpdf->ln(4);
            $this->fpdf->Cell(0,0,$titel2,0,0,'C');
            $this->fpdf->ln(4);
            $this->fpdf->SetFont('Arial','',8);
            $xx=$this->fpdf->GetX();
            $yy=$this->fpdf->GetY();
            $idxx=0;$tgT=20;
            $xxn=$this->fpdf->GetX();$yyn=$this->fpdf->GetY()+20;
            foreach ($table as $key => $t) {
                if ($t->chart==$chart){
                    $ttll=$this->Convtext($t->t1);
                    if ($idxx==3){
                        $this->fpdf->rect($xx,$yy,$col[$idxx],$tgT);
                        $this->fpdf->SetFont('Arial','U',8);
                        $this->fpdf->SetY($yy+6);
                        $this->fpdf->SetX($xx);
                        $this->fpdf->Cell($col[$idxx],4,'Upper',0,1,'C');
                        $this->fpdf->SetFont('Arial','',8);
                        $this->fpdf->SetX($xx);
                        $this->fpdf->Cell($col[$idxx],4,'Lower',0,0,'C');
                    }else if ($idxx==4){
                            $this->fpdf->rect($xx,$yy,$col[$idxx],8);
                            $this->fpdf->MultiCell($col[$idxx],4,'MNM FLT ALT',0,'C');
                            $this->fpdf->SetY($yy+8);
                            $this->fpdf->rect($xx,$yy+8,$col[$idxx],12);
                            $this->fpdf->SetX($xx);
                            $this->fpdf->MultiCell($col[$idxx],4,'Airspace Classification',0,'C');
                    }else if ($idxx==6){
                        $this->fpdf->rect($xx,$yy,$col[$idxx]*2,14);
                        $this->fpdf->MultiCell($col[$idxx]*2,4,'Direction of Cruising Level',0,'C');
                        $this->fpdf->SetY($yy+14);
                        $this->fpdf->rect($xx,$yy+14,$col[$idxx],6);
                        $this->fpdf->SetX($xx);
                        $this->fpdf->Cell($col[$idxx],6,'ODD',0,0,'C');
                    }else if ($idxx==7){
                        $this->fpdf->SetY($yy+14);
                        $this->fpdf->rect($xx,$yy+14,$col[$idxx],6);
                        $this->fpdf->SetX($xx);
                        $this->fpdf->Cell($col[$idxx],6,'EVEN',0,0,'C');
                    }else{
                        $this->fpdf->SetY($yy+4);
                        $this->fpdf->SetX($xx);
                        $this->fpdf->rect($xx,$yy,$col[$idxx],$tgT);
                        $this->fpdf->MultiCell($col[$idxx],4,$ttll,0,'C');
                    }
                    $this->fpdf->SetY($yy);
                    $xx +=$col[$idxx];
                    $this->fpdf->SetX($xx);
                    $idxx +=1;
                }
            }
            if (!empty($col)){
                $ix=1;
                for ($i=0; $i < count($col) ; $i++) { 
                    $this->fpdf->SetY($yyn);
                    $this->fpdf->SetX($xxn);
                    if ($i==1 || $i==3 || $i==6){
                    $cll=$col[$i] + $col[$i+1];
                    $this->fpdf->rect($xxn,$yyn,$cll,6);
                    $this->fpdf->Cell($cll,6,$ix,0,0,'C');
                    $ix +=1;
                    }else  if ($i==2 || $i==4 || $i==7){
                    }else{
                        $this->fpdf->rect($xxn,$yyn,$col[$i],6);
                        $this->fpdf->Cell($col[$i],6,$ix,0,0,'C');
                        $ix +=1;
                    }
                    $this->fpdf->SetY($yyn);
                    $xxn +=$col[$i];
                    $this->fpdf->SetX($xxn);
                }
            }
        }
    }
    }

    private function  createtableheaderenrvfr($table,$chart,$col,$titel1,$titel2)
    {
    if (!empty($table)){
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->Cell(0,0,$titel1,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->Cell(0,0,$titel2,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','',8);
        $xx=$this->fpdf->GetX();
        $yy=$this->fpdf->GetY();
        $idxx=0;$tgT=20;
        $xxn=$this->fpdf->GetX();$yyn=$this->fpdf->GetY()+20;
        foreach ($table as $key => $t) {
            if ($t->chart=='ats_vfr'){
                $ttll=$this->Convtext($t->t1);
                if ($idxx==3){
                    $this->fpdf->rect($xx,$yy,$col[$idxx],$tgT);
                    $this->fpdf->SetFont('Arial','U',8);
                    $this->fpdf->SetY($yy+6);
                    $this->fpdf->SetX($xx);
                    $this->fpdf->Cell($col[$idxx],4,'Upper',0,1,'C');
                    $this->fpdf->SetFont('Arial','',8);
                    $this->fpdf->SetX($xx);
                    $this->fpdf->Cell($col[$idxx],4,'Lower',0,0,'C');
                }else if ($idxx==4){
                        $this->fpdf->rect($xx,$yy,$col[$idxx],8);
                        $this->fpdf->MultiCell($col[$idxx],4,'MNM FLT ALT',0,'C');
                        $this->fpdf->SetY($yy+8);
                        $this->fpdf->rect($xx,$yy+8,$col[$idxx],12);
                        $this->fpdf->SetX($xx);
                        $this->fpdf->MultiCell($col[$idxx],4,'Airspace Classification',0,'C');
                }else{
                    $this->fpdf->SetY($yy+4);
                    $this->fpdf->SetX($xx);
                    $this->fpdf->rect($xx,$yy,$col[$idxx],$tgT);
                    $this->fpdf->MultiCell($col[$idxx],4,$ttll,0,'C');
                }
                $this->fpdf->SetY($yy);
                $xx +=$col[$idxx];
                $this->fpdf->SetX($xx);
                $idxx +=1;
            }
        }
        if (!empty($col)){
            $ix=1;
            for ($i=0; $i < count($col) ; $i++) { 
                $this->fpdf->SetY($yyn);
                $this->fpdf->SetX($xxn);
                if ($i==1 || $i==3 ){
                $cll=$col[$i] + $col[$i+1];
                $this->fpdf->rect($xxn,$yyn,$cll,6);
                $this->fpdf->Cell($cll,6,$ix,0,0,'C');
                $ix +=1;
                }else  if ($i==2 || $i==4){
                }else{
                    $this->fpdf->rect($xxn,$yyn,$col[$i],6);
                    $this->fpdf->Cell($col[$i],6,$ix,0,0,'C');
                    $ix +=1;
                }
                $this->fpdf->SetY($yyn);
                $xxn +=$col[$i];
                $this->fpdf->SetX($xxn);
            }
        }
    }
    }

    private function  createtableheaderasp($table,$chart,$col,$titel1,$titel2)
    {
    if (!empty($table)){
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->Cell(0,0,$titel1,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->Cell(0,0,$titel2,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','',8);
        $xx=$this->fpdf->GetX();
        $yy=$this->fpdf->GetY();
        $idxx=0;$tgT=22;
        $xxn=$this->fpdf->GetX();$yyn=$this->fpdf->GetY()+22;
        foreach ($table as $key => $t) {
            if ($t->chart=='asp'){
                $hdr=explode('$',$t->t1);
                $this->fpdf->SetY($yy+1);
                $this->fpdf->SetX($xx);
                $ttlY=$yy+1;
                $this->fpdf->rect($xx,$yy,$col[$idxx],$tgT);
                if (count($hdr) > 2){
                    for ($i=0; $i < count($hdr); $i++) {
                        $ttll=$this->Convtext($hdr[$i]);
                        $this->fpdf->MultiCell($col[$idxx],4,$ttll,0,'C');
                        $this->fpdf->ln(4);
                        $ttlY +=4;
                        $this->fpdf->SetY($ttlY);
                        $this->fpdf->SetX($xx);
                    }
                }else{
                    $ttll=$this->Convtext($t->t1);
                    $this->fpdf->MultiCell($col[$idxx],4,$ttll,0,'C');
                }

                $this->fpdf->SetY($yy);
                $xx +=$col[$idxx];
                $this->fpdf->SetX($xx);
                $idxx +=1;
            }
        }
        if (!empty($col)){
            $ix=1;
            for ($i=0; $i < count($col) ; $i++) { 
                $this->fpdf->SetY($yyn);
                $this->fpdf->SetX($xxn);

                    $this->fpdf->rect($xxn,$yyn,$col[$i],6);
                    $this->fpdf->Cell($col[$i],6,$ix,0,0,'C');
                    $ix +=1;

                $this->fpdf->SetY($yyn);
                $xxn +=$col[$i];
                $this->fpdf->SetX($xxn);
            }
        }
    }
    }
    private function createtablecodingtable($tbl,$magvar,$chart,$colm=1){
        if ($this->fpdf->PageNo()%2==0){
            $rht=0;
        }else{
            $rht=0;
        }
        // var_dump($posY, $this->fpdf->GetX(),$column,$this->lm +65);
        $posY=$this->fpdf->GetY();
        $posX=$this->fpdf->GetX();
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
    
    // $this->fpdf->SetFillColor(128,128,128);
        $cols=[];
        $tgH=3.5;
        $lbrc=125;
        $Ay=$posY;
        if ($chart=='169'){
            $this->fpdf->SetFont('Arial','',5);
            $this->fpdf->Rect($posX+108, $posY, 22, $tgH);
            $magvar= iconv('UTF-8', 'windows-1252', $magvar);
            $this->fpdf->Cell(129, $tgH,'MAG VAR '.$magvar,0,0,'R');
            $this->fpdf->SetFont('Arial','',6);
            $this->fpdf->ln($tgH);
            $posY=$this->fpdf->GetY();
            $Ay=$posY;
            $posX=$this->fpdf->GetX();
            foreach ($tbl as $key => $v) {
                if ($v->chart==$chart){
                    $col=$v->col_seq;
                    array_push($cols,$col);
                    $t1= iconv('UTF-8', 'windows-1252', $v->t1);
                    $tbg=explode("\n",$this->fpdf->WordWrap( $t1,$col));
                    // var_dump(count($tbg),$tbg);
                    $this->fpdf->setY($posY);
                switch (count($tbg)) {
                        case 1:
                            $this->fpdf->setY($posY+4);
                            break;
                        case 2:
                            $this->fpdf->setY($posY+2);
                            break;
                        case 3:
                            $this->fpdf->setY($posY+1);
                            break;
                }
                
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $col, 12);
                    $this->fpdf->MultiCell($col, $tgH,$t1,0,'C');
                    $posX +=$col;
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
    
                }
            }

        }else  if ($chart=='HOLDING'){
            $this->fpdf->Rect($posX, $posY, 23, 12);
            $this->fpdf->MultiCell(23, 12,'PATH DESCRIPTOR',0,'C');
            $posX +=23;
            array_push($cols,23);
            $this->fpdf->setY($posY+3);
            $this->fpdf->setX($posX);
            $this->fpdf->Rect($posX, $posY,12, 12);
            $this->fpdf->MultiCell(12, 3,'HOLDING FIX',0,'C');
            $posX +=12;
            array_push($cols,12);
            $this->fpdf->setY($posY+3);
            $this->fpdf->setX($posX);
            $this->fpdf->Rect($posX, $posY, 24, 12);
            $t11= iconv('UTF-8', 'windows-1252', 'INBOUND COURSE °M(°T)');
            $this->fpdf->MultiCell(24, 3,$t11,0,'C');
            $posX +=24;
            array_push($cols,24);
            $this->fpdf->setY($posY+3);
            $this->fpdf->setX($posX);
            $this->fpdf->Rect($posX, $posY, 8, 12);
            $this->fpdf->MultiCell(8, 3,'TIME (MIN)',0,'C');
            $posX +=8;
            array_push($cols,8);
            $this->fpdf->setY($posY+1);
            $this->fpdf->setX($posX);
            $this->fpdf->Rect($posX, $posY, 10, 12);
            $this->fpdf->MultiCell(10, 3,'TURN DIREC- TION',0,'C');
            $posX +=10;
            array_push($cols,10);
            $this->fpdf->setY($posY+3);
            $this->fpdf->setX($posX);
            $this->fpdf->Rect($posX, $posY, 13, 12);
            $this->fpdf->MultiCell(13, 3,'MINIMUM ALT',0,'C');
            $posX +=13;
            array_push($cols,13);
            $this->fpdf->setY($posY+3);
            $this->fpdf->setX($posX);
            $this->fpdf->Rect($posX, $posY, 13, 12);
            $this->fpdf->MultiCell(13, 3,'MAXIMUM ALT',0,'C');
            $posX +=13;
            array_push($cols,13);
            $this->fpdf->setY($posY+3);
            $this->fpdf->setX($posX);
            $this->fpdf->Rect($posX, $posY, 12, 12);
            $this->fpdf->MultiCell(12, 3,'SPEED LIMIT',0,'C');
            $posX +=12;
            array_push($cols,12);
            $this->fpdf->setY($posY);
            $this->fpdf->setX($posX);
            $this->fpdf->Rect($posX, $posY, 15, 12);
            $this->fpdf->MultiCell(15, 12,'NAV SPEC',0,'C');
            $posX +=15;
            array_push($cols,15);
            $this->fpdf->setY($posY);
            $this->fpdf->setX($posX);
        }else{
            if ($colm==1){
                $this->fpdf->Rect($this->lm, $this->fpdf->GetY(), 20, 6);
                $this->fpdf->Rect($this->lm+20, $this->fpdf->GetY(), 40, 6);
                
            }else{
                $this->fpdf->setX($posX+67);
                // dd($posX+65,$posX);
                $this->fpdf->Rect($this->lm+67, $this->fpdf->GetY(), 20, 6);
                $this->fpdf->Rect($this->lm+87, $this->fpdf->GetY(), 40, 6);
            }
            $this->fpdf->cell(20,6,'WPT IDENTIFIER',0,0,'C');
            $this->fpdf->cell(40,6,'COORDINATES',0,0,'C');
            $this->fpdf->cell(5);
        
        
              
        }
        $this->fpdf->SetFont('Arial','',6);
        $Ay +=6;
        $this->fpdf->setY($Ay);
        $this->fpdf->ln($tgH);
        return $cols;
    }
    private function tableenr41(){
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
            $rht=5;
        }else{
            $rht=0;
            $this->lm=20;$this->rm=15;
        }
        // var_dump($posY, $this->fpdf->GetX(),$column,$this->lm +65);
        $posY=$this->fpdf->GetY();
        $posX=$this->fpdf->GetX();
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->fpdf->SetFont('Arial','B',8);
    // $this->fpdf->SetFillColor(128,128,128);
        $tgH=8;
        $lbrc=125;
    
        $col1=30;$col2=8;$col3=16;$col4=15;$col5=18;$col6=8;$col7=30;
        $this->fpdf->Rect($posX, $posY, $lbrc, $tgH);
        // $this->fpdf->Rect($this->lm +$intr, $posY, $lbrc, 10);
        $aY=$this->fpdf->GetY();
        $aX=$this->fpdf->GetX();
        $lbrc=$aX + $col1 +$col2+ $col3+$col4;
        $this->fpdf->Cell($col1, $tgH,'STATION',0,0,'C');
        $this->fpdf->Cell($col2, $tgH,'ID',0,0,'C');
        $this->fpdf->Cell($col3, $tgH,'FREQ/CH',0,0,'C');
        $this->fpdf->SetFont('Arial','B',7.5);
        $this->fpdf->MultiCell($col4,4,'HOUR OF SVC',0,'C');
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->SetY($aY);
        $this->fpdf->SetX($lbrc);
        $this->fpdf->Cell($col5, $tgH,'COORD',0,0,'C');
        $this->fpdf->Cell($col6, $tgH,'ELEV',0,0,'C');
        $this->fpdf->Cell($col7, $tgH,'REMARKS',0,0,'C');
    
        $this->fpdf->SetFont('Arial','',8);
        $this->fpdf->ln($tgH);
        }

    private function tableenr51($jdl,$hal,$judulutama=null){

        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$jdl);
        $this->Watermark($this->draft);
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
            $rht=0;
        }else{
            $rht=0;
            $this->lm=20;$this->rm=15;
        }
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($hal.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);
            if (!empty($judulutama)){
                $this->fpdf->SetFont('Arial','B',9);
                $this->fpdf->Cell(0,0,$judulutama,0,0,'C');
            }

        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->ln(4);
        $this->fpdf->Cell(0,0,$jdl,0,0,'C');
        $this->fpdf->ln(4);
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->fpdf->SetFont('Arial','B',8);

        $posY=$this->fpdf->GetY();
        $posX=$this->fpdf->GetX();
        $intr=18;
        $aY=$this->fpdf->GetY();
        $aX=$this->fpdf->GetX();

        $jd21='Upper Limit';
        $jd22='Lower Limit';
        $jd3='Remarks'. PHP_EOL .'(time of activity, type of nature of hazard, risk of interception)';
        $this->fpdf->MultiCell(65,$intr,'Identification, Name and Lateral Limits',0,'C');
        $this->fpdf->SetY($aY);
        $this->fpdf->Rect($posX, $posY,65, $intr);
        $this->fpdf->SetY($aY+5);
        $this->fpdf->SetX($aX+65);
        $this->fpdf->Rect($aX+65, $posY,30, $intr);
        $this->fpdf->Cell(30,3.5, $jd21,0,0,'C');
        $this->fpdf->Ln(3.5);
        $this->fpdf->SetX($aX+65);
        $this->fpdf->Cell(30,3.5,$jd22,0,0,'C');
        $this->fpdf->SetY($aY);
        $this->fpdf->SetX($aX+95);
        $this->fpdf->Rect($aX+95, $posY,30, $intr);
        $this->fpdf->MultiCell(30,3.5,$jd3,0,'C');
        $aY=$this->fpdf->GetY();
        $this->fpdf->SetX($aX);
        $this->fpdf->Rect($aX, $aY+0.5,65,6);
        $this->fpdf->MultiCell(65,6,'1',0,'C');
        $this->fpdf->SetY($aY);
        $this->fpdf->SetX($aX+65);
        $this->fpdf->Rect($aX+65, $aY+0.5,30,6);
        $this->fpdf->MultiCell(30,6,'2',0,'C');
        $this->fpdf->SetY($aY);
        $this->fpdf->SetX($aX+95);
        $this->fpdf->Rect($aX+95, $aY+0.5,30,6);
        $this->fpdf->MultiCell(30,6,'3',0,'C');
        $this->fpdf->SetFont('Arial','',8);
        $this->fpdf->Line($this->lm,$aY+7,$aX+125,$aY+7);
        $this->fpdf->ln(1);
        $this->fpdf->SetX($this->lm);

    }

    private function tableenr43(){
    if ($this->fpdf->PageNo()%2==0){
        $this->lm=15;$this->rm=20;
        $rht=5;
    }else{
        $rht=0;
        $this->lm=20;$this->rm=15;
    }
    // var_dump($posY, $this->fpdf->GetX(),$column,$this->lm +65);
    $posY=$this->fpdf->GetY();
    $posX=$this->fpdf->GetX();
    $this->lebararea=$this->lebar - ($this->rm + $this->lm);
    $this->fpdf->SetFont('Arial','B',8);
// $this->fpdf->SetFillColor(128,128,128);
    // var_dump( $this->lebararea);
    $intr=63;
    $lbrc=62;
    $this->fpdf->Rect($posX, $posY, $lbrc, 10);
    // $this->fpdf->Rect($this->lm +$intr, $posY, $lbrc, 10);
    $aY=$this->fpdf->GetY();
    $aX=$this->fpdf->GetX();
    // var_dump( $aY, $aX,$col);
    $this->fpdf->MultiCell(18,4,'Name Code Designator',0,'C');
    $this->fpdf->SetY($aY);
    $this->fpdf->SetX($aX+16);
    $this->fpdf->MultiCell(25,10,'Coordinates',0,'C');
    $this->fpdf->SetY($aY);
    $this->fpdf->SetX($aX+41);
    $this->fpdf->MultiCell(20,5,'ATS route or other route',0,'C');

    $this->fpdf->SetFont('Arial','',8);
    $this->fpdf->ln(2);
    }
    private function gen22($id){
        $originalInput=Request::input();
        $user = Auth::user();
        $cod = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        $lwpt = getDataApi($originalInput,'api/abbr/temp?deleted=0&sort=ident:asc');


        $this->fpdf = new Fpdf('P','mm',[160,210]);
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$cod[0]->sub_id.' '.$cod[0]->definition);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($cod[0]->sub_id.' - '.$this->fpdf->PageNo(),0);
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->Cell(0,0,'GEN 2 TABLES AND CODES',0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->Cell(0,0,$cod[0]->sub_id.' '.$cod[0]->definition,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','',8);
        $AwalY=$this->fpdf->GetY();
        $AwalX=$this->fpdf->GetX();
        $loncat=false;
        $pref='';
        foreach ($lwpt as $key => $w) {
            $x=$this->fpdf->GetX();
            $y=$this->fpdf->GetY();
            unset($xline);
            $xline= $this->lm - 1.5;
            if ($loncat==true){
                $this->fpdf->SetX($AwalX);
                $x=$this->fpdf->GetX();
                $xline= $this->lm + 126.5;
            }
            if ($w->pref !== $pref){
                $this->fpdf->SetFont('Arial','BU',9);
                $this->fpdf->Cell(15,5,$w->pref,0,0,'L');
                $this->fpdf->ln(6);
                $this->fpdf->SetFont('Arial','',8);
                $y=$this->fpdf->GetY();
                $this->fpdf->SetX($AwalX);
                $x=$this->fpdf->GetX();
            }
            $this->fpdf->Cell(15,4,iconv('UTF-8', 'windows-1252', $w->ident),0,0,'L');
            $this->fpdf->MultiCell(45,4,iconv('UTF-8', 'windows-1252', $w->definition),0,'L');
            $lgt=$this->fpdf->GetStringWidth($w->definition);
            // if ($lgt <45){
            //     $this->fpdf->ln(5);
            // }else{
                $this->fpdf->ln(1);
            // }

            if ($w->status !== 'U'){
                $this->fpdf->SetLineWidth(0.4);
                $this->fpdf->Line($xline,$y,$xline,$this->fpdf->GetY());
                $this->fpdf->SetLineWidth(0);
            }
            //akhir tulis

            if ($this->fpdf->GetY() > $this->btshal-5){

                if ($loncat==false){
                    $loncat=true;
                    $AwalX=$AwalX+63;
                    $this->fpdf->SetY($AwalY);
                    $this->fpdf->SetX($AwalX);
                }else{
                    $loncat=false;
                    $this->AddNewPage($cod[0]->sub_id);
                    $AwalY=$this->fpdf->GetY();
                    $this->fpdf->SetX($this->lm);
                    $AwalX=$this->fpdf->GetX();

                }

            }
            $pref=$w->pref;
        }

        $this->AipFooter();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }

    private function gen24($id){
        $originalInput=Request::input();
        $user = Auth::user();
        $cod = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        $npush=[];
        $indtemp = getDataApi($originalInput,'api/gen/locindicator/temp?ctry=ID&deleted=0&sort=city:asc');
        foreach ($indtemp as $key => $nav) {
            // var_dump($nav);
            
                    $nnn['id']=$nav->loc_id;
                    $nnn['status']=$nav->status;
                    if (strlen($nav->indicator) < 4){
                        $nnn['indicator']='';
                    }else{
                        $nnn['indicator']=$nav->indicator;
                    }
                    if ($nav->city==null || $nav->city==''){
                        $nnn['location']=ucwords(strtolower($nav->name));
                    }else{
                        $cct=$nav->city.'_/_'.ucwords(strtolower($nav->name));
                        $repl1=['A.a','Ii','Sis'];
                        $repl2=['A.A','II','SIS'];
                        $nnn['location']=str_replace($repl1,$repl2,$cct);

                    }
                    if ($nnn['indicator'] !== ''){
                        array_push($npush, $nnn);

                    }
                
            
        }

// dd($npush);

        $this->fpdf = new Fpdf('P','mm',[160,210]);
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$cod[0]->sub_id.' '.$cod[0]->definition);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        // $this->fpdf->SetMargins($this->lm,$this->head,$this->rm);

        for ($i=0; $i < 2; $i++) { 
            if($i==0){
                usort($npush, function($a, $b) {
                    return $a['location'] <=> $b['location'];
                });
                $this->AipHeader($cod[0]->sub_id.' - '.$this->fpdf->PageNo(),0);
                $this->fpdf->ln(5);
                $this->fpdf->SetFont('Arial','B',10);
                $this->drawgen24($npush,$cod,'ENCODE');
            }else{
                usort($npush, function($a, $b) {
                    return $a['indicator'] <=> $b['indicator'];
                });
                $this->fpdf->AddPage();
                if ($this->fpdf->PageNo()%2==0){
                    $this->lm=15;$this->rm=20;
                    $rht=5;
                }else{
                    $rht=0;
                    $this->lm=20;$this->rm=15;
                }
                $this->fpdf->SetAutoPageBreak(true,0);
                $this->fpdf->SetRightMargin($this->rm);
                $this->fpdf->SetLeftMargin($this->lm);
                $this->fpdf->SetTopMargin($this->head);
                $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                $this->AipHeader($cod[0]->sub_id.' - '.$this->fpdf->PageNo(),0);
                $this->fpdf->ln(5);
                $this->fpdf->SetFont('Arial','B',10);
                $this->drawgen24($npush,$cod,'DECODE');
            }
        }

        $this->AipFooter();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }
    private function drawgen24($npush,$cod,$orderby){
        $this->fpdf->Cell(0,0,$cod[0]->sub_id.' '.$cod[0]->definition,0,0,'C');
        $this->fpdf->ln(5);
        $this->fpdf->Cell(0,0,$orderby,0,0,'C');
        $this->fpdf->ln(6);
        $jml=(count($npush));
        $this->tablegen24($orderby,$jml,0);
        $AwalY=$this->fpdf->GetY();

        $loncat=0;$colum=0;
        
        foreach ($npush as $key => $n) {
            $AwY=$this->fpdf->GetY();
            $tbg=explode("\n",$this->fpdf->WordWrap($n['location'],44));
            $t=count($tbg);
            $pjisi=$this->fpdf->GetStringWidth($n['location']);
            $pX=$this->fpdf->GetX();
            $pY=$this->fpdf->GetY();
            $tbh=0;
            // var_dump($n['location']);
            // for($i = 0;$i <= $t-1;$i++){
            //     // var_dump($tbg[$i]);
            //     $this->fpdf->Cell(45,$this->ln,$tbg[$i],0,0,'L');
            //     $this->fpdf->ln($this->ln);
            //     // $this->fpdf->Cell($this->tab);
            // }
            $this->fpdf->SetFont('Arial','',8);
            if ($orderby=='ENCODE'){
                $contt=str_replace('_',' ', $n['location']);
                if ($t > 1){
                    $tbh=4;
                    if ($t ==3){
                        $tbh=8;
                    }
                    $this->fpdf->MultiCell(45,4,$contt,0,'L');
                    $this->fpdf->SetY($pY);
                    $this->fpdf->setX($pX+45);
                }else{
                    $this->fpdf->cell(45,4,$contt,0,0,'L');
                }
                $this->fpdf->cell(15,4,$n['indicator'],0,0,'C');
            }else{
                $this->fpdf->cell(15,4,$n['indicator'],0,0,'C');
                $contt=str_replace('_',' ', $n['location']);
                if ($t > 1){
                    $this->fpdf->MultiCell(45,4,$contt,0,'L');
                    $this->fpdf->SetY($pY);
                    $this->fpdf->setX($pX+45);
                    $tbh=4;
                    if ($t ==3){
                        $tbh=8;
                    }
                }else{
                    $this->fpdf->cell(45,4,$contt,0,0,'L');
                }
            }
            //  var_dump($this->fpdf->getY());
        
        
            $this->fpdf->ln(5+ $tbh);
            $this->fpdf->Line($this->lm+$colum, $this->fpdf->GetY(), $this->lm +$colum+60, $this->fpdf->GetY());
            $this->fpdf->ln(2);
            if ($this->fpdf->GetY() > $this->btshal-5){
                if ($orderby=='ENCODE'){
                    $col=[0,45,60];

                }else{
                    $col=[0,15,60];
                }
                for ($i=0; $i < count($col) ; $i++) { 
                    $this->fpdf->Line($this->lm +$colum+ $col[$i], $AwalY-8, $this->lm +$colum+ $col[$i],$this->fpdf->GetY()-2);
                }
                if ($loncat==0){
                    $loncat=1;
                    $this->fpdf->SetY($AwalY);
                    $colum=65;
                }else{
                    $loncat=0;
                    $colum=0;
                    $this->AddNewPage($cod[0]->sub_id);
                    $this->tablegen24($orderby,$jml,$key);
                    $this->fpdf->SetX($this->lm);
                    $AwalY=$this->fpdf->GetY();
                }
            }
            $this->fpdf->SetX($this->lm+$colum);
            unset($xline);
            if ($orderby=='ENCODE'){
                $xline= $this->lm - 1.5;
            }else{
                $xline= $this->lm + 61.5;
            }
            if ($n['status']!=='U'){
                    $this->fpdf->SetLineWidth(0.4);
                    $this->fpdf->Line($xline,$AwY-2,$xline,$this->fpdf->GetY()-2);
                    $this->fpdf->SetLineWidth(0);

                
            }

        }
        if ($orderby=='ENCODE'){
            $col=[0,45,60];

        }else{
            $col=[0,15,60];
        }
        for ($i=0; $i < count($col) ; $i++) { 
            $this->fpdf->Line($this->lm + $col[$i], $AwalY-8, $this->lm + $col[$i],$this->fpdf->GetY()-2);
        }
    }
    private function tablegen24($order,$jml,$seq){
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
            $rht=5;
        }else{
            $rht=0;
            $this->lm=20;$this->rm=15;
        }
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->fpdf->SetFont('Arial','',9);
        $coll2=true;
        if ($jml-$seq < 20 ){
            $coll2=false;
        }
    // $this->fpdf->SetFillColor(128,128,128);
    $this->fpdf->Rect($this->lm, $this->fpdf->GetY(), 60, 6);
    if ($coll2){
        $this->fpdf->Rect($this->lm+65, $this->fpdf->GetY(), 60, 6);

    }
        if ($order=='ENCODE'){
            $this->fpdf->cell(45,6,'Location',0,0,'C');
            $this->fpdf->cell(15,6,'Indicator',0,0,'C');
            $this->fpdf->cell(5);
            if ($coll2){
            $this->fpdf->cell(45,6,'Location',0,0,'C');
            $this->fpdf->cell(15,6,'Indicator',0,0,'C');
            }

        }else{
            $this->fpdf->cell(15,6,'Indicator',0,0,'C');
            $this->fpdf->cell(45,6,'Location',0,0,'C');
            $this->fpdf->cell(5);
            if ($coll2){
            $this->fpdf->cell(15,6,'Indicator',0,0,'C');
            $this->fpdf->cell(45,6,'Location',0,0,'C');
            }
        }
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Arial','',9);
        $this->fpdf->ln(8);
    }
    private function gen25($id){
        $originalInput=Request::input();
        $user = Auth::user();
        $cod = getDataApi($originalInput,'api/eaip/menu?id='.$id);
        $point1['point'] = AtsTemp::selectRaw("point")
        ->where('point','like', 'NAV%')
        ->where('ctry','like', '%ID')
        ->groupby('point')
        ->get();
        $point2['point'] = AtsTemp::selectRaw("point2")
        ->where('point2','like', 'NAV%')
        ->where('ctry','like', '%ID')
        ->groupby('point2')
        ->get();
        $result = array_merge($point1,$point2);
        $result = array_map("unserialize", array_unique(array_map("serialize", $result)));
        //array is sorted on the bases of id
        sort( $result );
        $nav=$result[0];
        $navaids = getDataApi($originalInput,'api/navarpt/temp');
        $navcurr = getDataApi($originalInput,'api/navarpt');
        // dd($navaids[0],$navcurr[0]);
        $npush=[];$cpush=[];
        foreach ($navaids as $key => $nav) {
            // var_dump($nav);
            if (count($nav->navaid) > 0){
                $n=$nav->navaid[0];
                if ($n->type === '20' || $n->type === '9' || $n->type === '11'){
                }else{
                    $nnn['id']=$n->id;
                    $nnn['nav_id']=$n->nav_id;
                    $nnn['status']=$n->status_vld;
                    $check=AtsTemp::selectRaw("ats_ident,point,point2")
                    ->where('point','=', $n->nav_id)->orwhere('point2','=', $n->nav_id)
                    ->where('ctry','like', '%ID')
                    ->get();
                    // var_dump(count($check));
                    // $x= in_array($n->nav_id, $nav,true);
                    // $x = array_search($n->nav_id, $nav,true); // $key = 2;
                    if (count($check)>0){
                        $nnn['purpose']='AE';
                    }else{
                        $nnn['purpose']='A';
                    }
                    $nnn['ident']=$n->nav_ident;
                    $nnn['station']=$nav->airport[0]->city_name.' / '.ucwords(strtolower($nav->airport[0]->arpt_name));
                    $nnn['facility']=$n->definition;
                    array_push($npush, $nnn);
                }
            }
            if (count($nav->ils) > 0){
                $n=$nav->ils[0];

                $nnn['id']=$n->id;
                $nnn['nav_id']=$n->ils_id;
                $nnn['status']=$n->status;
                $nnn['purpose']='A';
                $nnn['ident']=$n->ils_ident;
                $nnn['station']=$nav->airport[0]->city_name.' / '.ucwords(strtolower($nav->airport[0]->arpt_name));
                $nnn['facility']='ILS/LLZ';
                array_push($npush, $nnn);
            }
        }

        foreach ($navcurr as $key => $nav) {
            // var_dump($nav);
            if (count($nav->navaid) > 0){
                $n=$nav->navaid[0];
                if ($n->type === '20' || $n->type === '9' || $n->type === '11'){
                }else{
                    $nnn['id']=$n->id;
                    $nnn['nav_id']=$n->nav_id;
                    $nnn['status']=$n->status_vld;
                    $check=AtsTemp::selectRaw("ats_ident,point,point2")
                    ->where('point','=', $n->nav_id)->orwhere('point2','=', $n->nav_id)
                    ->where('ctry','like', '%ID')
                    ->get();
                    // var_dump(count($check));
                    // $x= in_array($n->nav_id, $nav,true);
                    // $x = array_search($n->nav_id, $nav,true); // $key = 2;
                    if (count($check)>0){
                        $nnn['purpose']='AE';
                    }else{
                        $nnn['purpose']='A';
                    }
                    $nnn['ident']=$n->nav_ident;
                    $nnn['station']=$nav->airport[0]->city_name.' / '.ucwords(strtolower($nav->airport[0]->arpt_name));
                    $nnn['facility']=$n->definition;
                    array_push($cpush, $nnn);
                }
            }
            if (count($nav->ils) > 0){
                $n=$nav->ils[0];

                $nnn['id']=$n->id;
                $nnn['nav_id']=$n->ils_id;
                $nnn['status']=$n->status;
                $nnn['purpose']='A';
                $nnn['ident']=$n->ils_ident;
                $nnn['station']=$nav->airport[0]->city_name.' / '.ucwords(strtolower($nav->airport[0]->arpt_name));
                $nnn['facility']='ILS/LLZ';
                array_push($cpush, $nnn);
            }
        }


        $this->fpdf = new Fpdf('P','mm',[160,210]);
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$cod[0]->sub_id.' '.$cod[0]->definition);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);

        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        // $this->fpdf->SetMargins($this->lm,$this->head,$this->rm);

        for ($i=0; $i < 2; $i++) { 
            if($i==0){
                usort($npush, function($a, $b) {
                    return $a['ident'] <=> $b['ident'];
                });
                $this->AipHeader($cod[0]->sub_id.' - '.$this->fpdf->PageNo(),0);
                $this->fpdf->ln(5);
                $this->fpdf->SetFont('Arial','B',10);
                $this->drawgen25($npush,$cpush,$cod,'BY IDENTIFICATION ( ID )');
            }else{
                usort($npush, function($a, $b) {
                    return $a['station'] <=> $b['station'];
                });
                $this->fpdf->AddPage();
                if ($this->fpdf->PageNo()%2==0){
                    $this->lm=15;$this->rm=20;
                    $rht=5;
                }else{
                    $rht=0;
                    $this->lm=20;$this->rm=15;
                }
                $this->fpdf->SetAutoPageBreak(true,0);
                $this->fpdf->SetRightMargin($this->rm);
                $this->fpdf->SetLeftMargin($this->lm);
                $this->fpdf->SetTopMargin($this->head);
                $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                $this->AipHeader($cod[0]->sub_id.' - '.$this->fpdf->PageNo(),0);
                $this->fpdf->ln(5);
                $this->fpdf->SetFont('Arial','B',10);
                $this->drawgen25($npush,$cpush,$cod,'BY STATION');
            }
        }

        $this->AipFooter();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }
    private function drawgen25($npush,$curr,$cod,$orderby){
        $this->fpdf->Cell(0,0,$cod[0]->sub_id.' '.$cod[0]->definition,0,0,'C');
        $this->fpdf->ln(5);
        $this->fpdf->Cell(0,0,$orderby,0,0,'C');
        $this->fpdf->ln(6);

        $this->tablegen25();
        $AwalY=$this->fpdf->GetY();


        foreach ($npush as $key => $n) {
            // var_dump($n['status']);
            // if($n['status']=='U'){
            //     $this->fpdf->SetFont('Arial','',8);
            // }else{
            //     $this->fpdf->SetFont('Arial','BI',8);
            // }
          
            $AwY=$this->fpdf->GetY();
            $this->fpdf->cell(15,4,$n['ident'],0,0,'L');
            $pjisi=$this->fpdf->GetStringWidth($n['station']);
            $pX=$this->fpdf->GetX();
            $pY=$this->fpdf->GetY();
            $tbh=0;
            if ($pjisi > 70){
                $this->fpdf->MultiCell(70,4,$n['station'],0,'L');
                $this->fpdf->SetY($pY);
                $this->fpdf->setX($pX+75);
                $tbh=4;
            }else{
                $this->fpdf->cell(75,4,$n['station'],0,0,'L');
            }
            //  var_dump($this->fpdf->getY());
            $this->fpdf->cell(18,4,$n['facility'],0,0,'C');
            $this->fpdf->cell(17,4,$n['purpose'],0,0,'C');
            $this->fpdf->ln(5+ $tbh);
            $this->fpdf->Line($this->lm, $this->fpdf->GetY(), $this->lm +$this->lebararea, $this->fpdf->GetY());
            $this->fpdf->ln(2);
            if ($this->fpdf->GetY() > $this->btshal-5){
                $col=[0,15,90,108,125];
                for ($i=0; $i < count($col) ; $i++) { 
                    $this->fpdf->Line($this->lm + $col[$i], $AwalY-8, $this->lm + $col[$i],$this->fpdf->GetY()-2);
                }
                $this->AddNewPage($cod[0]->sub_id);
                $this->tablegen25();
                $this->fpdf->SetX($this->lm);
                $AwalY=$this->fpdf->GetY();
            }
            unset($xline);
            $xline= $this->lm + 126.5;
            if ($n['status']!=='U'){
                $found = false;$drawline=true;
                foreach($curr as $key => $value) {
                    if ($value['nav_id'] == $n['nav_id']) {
                        if ($curr[$key]['ident'] == $n['ident'] && $curr[$key]['station'] == $n['station']){
                            $drawline=false;
                        }
                        // else{
                        //     dd($curr[$key]['ident'] , $n['ident'] ,$curr[$key]['station'] , $n['station']);
                        // }
                        // dd($curr[$key],$n['nav_id']);
                        break;
                    }
                }

                // if ($found) unset($curr[$key]);
                // $idxcurr=array_search($n['nav_id'],$curr);
                // dd($idxcurr,$curr[$idxcurr],$n['nav_id']);
                if ($drawline==true){
                    $this->fpdf->SetLineWidth(0.4);
                    $this->fpdf->Line($xline,$AwY-2,$xline,$this->fpdf->GetY()-2);
                    $this->fpdf->SetLineWidth(0);

                }
            }

        }
        $col=[0,15,90,108,125];
        for ($i=0; $i < count($col) ; $i++) { 
            $this->fpdf->Line($this->lm + $col[$i], $AwalY-8, $this->lm + $col[$i],$this->fpdf->GetY()-2);
        }
    }
    private function tablegen25(){
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
            $rht=5;
        }else{
            $rht=0;
            $this->lm=20;$this->rm=15;
        }
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->fpdf->SetFont('Arial','',9);
    // $this->fpdf->SetFillColor(128,128,128);
        // var_dump( $this->lebararea);
        $this->fpdf->Rect($this->lm, $this->fpdf->GetY(), $this->lebararea, 6);
        $this->fpdf->cell(15,6,'ID',0,0,'C');
        $this->fpdf->cell(75,6,'STATION NAME',0,0,'C');
        $this->fpdf->cell(18,6,'AID',0,0,'C');
        $this->fpdf->cell(17,6,'PURPOSE',0,0,'C');
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Arial','',9);
        $this->fpdf->ln(8);
    }
    private function getfix($data,$holding){
        $originalInput=Request::input();
        // dd($data);
        if ($holding==true){
            $hasil= getDataApi($originalInput, 'api/holding/temp?id='.$data);  
        }else{
            if (substr($data,0,3)=='WPT'){
                $hslwpt= getDataApi($originalInput, 'api/waypoint/temp/list?wpt_id='.$data);  
                $lon=$hslwpt[0]->geom->coordinates[0];
                $lat=$hslwpt[0]->geom->coordinates[1];
                $fixname=$hslwpt[0]->desc_name;
           
                $hasil= array('lat' => $lat , 'lon' =>$lon, 'fix' =>$fixname);
            }else{
                $hslwpt= getDataApi($originalInput, 'api/navaid/temp?nav_id='.$data); 
                $lon=$hslwpt[0]->geom->coordinates[0];
                $lat=$hslwpt[0]->geom->coordinates[1];
                $fixname=$hslwpt[0]->nav_ident;
           
                $hasil= array('lat' => $lat , 'lon' =>$lon, 'fix' =>$fixname);

            }
        }
        // dd( $hasil);
        return $hasil;
    }
    private function codingtable($id,$chart,$chart_type){
        $originalInput=Request::input();
        $user = Auth::user();
        $chart= getDataApi($originalInput, 'api/proc/chart?chart_id='.$chart.'&sort=seq:asc');
        $tbl= getDataApi($originalInput, 'api/eaip/codtableheader');
        $arpt= getDataApi($originalInput, 'api/airports?arpt_ident='.$id);
        $wptdesc= getDataApi($originalInput, 'api/cod/list/cod_wpt_desc');
        
        // $wptd=DB::table('cod_wpt_desc')->orderby('id','asc')->get();
        // // $wptdesc=$wptd;
       
        // $last_names = array_column($wptdesc, 'd43');
        // print_r($last_names);
        //  dd($last_names);
        
        $trans=[];
        // foreach ($chart as $kky => $nav) {
            $trs=$chart[0]->procedure;
          
            for ($i=0; $i < count( $trs); $i++) { 
                if ($trs[$i]->segment !== null){

                    array_push($trans,$trs[$i]->segment);
                }
            }
            // dd($nav->procedure[$kky]);
            // foreach ($trs as $k => $t) {
            //     array_push($trans,$t);
                
            // }
        // }
       
        // dd($sql);/eaip/codtabl;eheader
        $flnm=explode(',',$this->filename);
        // dd($trans,$flnm);

      
        $this->rm=5;
        $this->lm=13;
        $this->fpdf = new Fpdf('P','mm',[148,210]);
        $this->fpdf->AddFont('Century','','century.php');
       
        $this->lebar=148;
        $this->fpdf->AddPage();
        $this->properties('Iwish Indonesia',$chart[0]->chart_id.' '.$chart[0]->chart_name);
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);
        switch ($arpt[0]->vol) {
            case '2':
                $this->header= $this->header.' ( VOL II )';
                break;
            case '3':
                $this->header= $this->header.' ( VOL III )';
                break;
            case '4':
                $this->header= $this->header.' ( VOL IV )';
                break;
            case '5':
                $this->header= $this->header.' ( VOL V )';
                break;

        }
        $navspec='RNAV-1';
        if ($chart[0]->nav=='RNP'){
            $navspec='RNP APCH';
        }
        $jdl1=$chart[0]->nav;
        if ($chart[0]->nav=='RNAV'){
            $jdl1='RNAV1';
        }
        switch ($chart[0]->chart_type) {
            case '45':
                $judul='Coding Table '.$chart[0]->nav.' RWY '.$chart[0]->rwy.' '.$chart[0]->cat;
                break;
            case '46':
                $judul='Coding Table SID '.$jdl1.' RWY '.$chart[0]->rwy;
                if ($chart[0]->nav=='RNP'){
                    $navspec='RNP-1';
                }
                break;
            case '47':
                $judul='Coding Table STAR '.$jdl1.' RWY '.$chart[0]->rwy;
                if ($chart[0]->nav=='RNP'){
                    $navspec='RNP-1';
                }
                break;
            
        }
        $epoch=date('2020-06-01');
        $alt=0;
        $mv1 = GetMagvar($arpt[0]->geom->coordinates[0], $arpt[0]->geom->coordinates[1], $epoch,$alt,);
        // dd($mv1,$cs->geom->coordinates[1], $cs->geom->coordinates[0]);
        // dd($mv1);
        $mgvar2 = $mv1->nav;
        $dmag= $mv1->dec;
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipChartHeader($arpt[0],$chart[0],$flnm[0]);
        // dd($this->lebararea);
        $this->fpdf->SetFont('Arial','',7);
        $this->fpdf->Cell(0,4,$judul,0,0,'C');
        $this->fpdf->ln(4);
        $this->fpdf->SetFont('Arial','',6);
        $cols= $this->createtablecodingtable($tbl,$mgvar2,'169');
        $this->fpdf->ln(2.5);
        $posY= $this->fpdf->getY();
        $posX= $this->fpdf->getX();
        $htxt=7;$aX=$posX;$fixcollect=[];$holdingcollect=[];
        $charttype=$chart[0]->chart_type;
        $jmltrans=count($trans)-1;
        foreach ($trans as $key => $cd) {
            // dd(count($trans),$key,$cd->segment);
            $no=10;
            if ($charttype !=='45'){
                $procname=explode("\n",$cd->proc_text);
                // dd($procname);
                $this->fpdf->Rect($posX, $posY, $this->lebararea, $htxt);
                $this->fpdf->Cell(0,$htxt,$procname[0],0,0,'L');
                $this->fpdf->ln(7);
                $posY= $this->fpdf->getY();
                $posX= $this->fpdf->getX();
            }
            //  dd($cd->segment[1],$trans);
            foreach ($cd->segment as $k => $c) {
                if (!empty($c->transition[0]->runway)){

                    $rwyelev=$c->transition[0]->runway[0]->thr_elev;
                }
                // var_dump($key);
                if (!empty($c->transition)){
                    $trns=$c->transition[0]->segment;
                    usort($trns, fn($a, $b) => strnatcmp($a->seq_num, $b->seq_num));
                    // dd($trns);
                    foreach ($trns as $k => $c) {
                        if ( $c->path_term == 'IF' && $no > 10 ){
                            if ($c->center_fix !== null){
                                $kky = array_search($c->wd4, array_column($wptdesc, 'd43'));
                                if ($wptdesc[$kky]->holding=='Y'){
                                    // var_dump($c->center_fix);
                                    // dd( $kky,$c->wd4,$wptdesc[$kky],$c->center_fix);
                                    $hsl=$this->getfix($c->center_fix,true);
                                    // dd($hsl);
                                    $hold=$hsl[0];
                                    $hold->fix_name=$pnt['fix'];
                                
                                    array_push($holdingcollect,$hold);
                                    
                                }
                            }
                        }else{
                            $this->fpdf->Rect($posX, $posY, $cols[0], $htxt);
                            $this->fpdf->MultiCell($cols[0],$htxt,sprintf('%03d',$no),0,'C');
                            $posX +=$cols[0];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            $this->fpdf->Rect($posX, $posY, $cols[1], $htxt);
                            $this->fpdf->MultiCell($cols[1],$htxt,$c->path_term,0,'C');
                            $posX +=$cols[1];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            $pnt=getfixedcoordinate($c);
                            array_push($fixcollect,$pnt);
                            $wpt=$pnt['fix'];
                            if ($pnt['fix']==null){
                                $wpt='-';
                            }
                            $hwpt=$htxt;
                            if ($charttype =='45' && $wpt !== '-'){
                                $ky = array_search($c->wd4, array_column($wptdesc, 'd43'));
                                // var_dump($wptdesc[$ky]->descr);
                                $w_desc=$wptdesc[$ky]->descr;
                                if ($w_desc ==null){
                                    $this->fpdf->setY($posY);
                                }else{
                                    $wpt.=' ('.$wptdesc[$ky]->descr.')';
                                    $hwpt=($htxt/3);
                                    $this->fpdf->setY($posY+1);
                                }
                                $this->fpdf->setX($posX);
                            }
                            $cfix='-';
                            if ($c->center_fix !== null){
                                if ($c->path_term=='RF' || $c->path_term=='AF'){
                                    $hsl=$this->getfix($c->center_fix,false);
                                    $cfix=$hsl['fix'];
                                    array_push($fixcollect,$hsl);
                                    // dd($hsl);

                                }else{
                                    $kky = array_search($c->wd4, array_column($wptdesc, 'd43'));
                                    if ($wptdesc[$kky]->holding=='Y'){
                                        // var_dump($c->center_fix);
                                        // dd( $kky,$c->wd4,$wptdesc[$kky],$c->center_fix);
                                        $hsl=$this->getfix($c->center_fix,true);
                                        // dd($hsl);
                                        $hold=$hsl[0];
                                        $hold->fix_name=$pnt['fix'];
                                    
                                        array_push($holdingcollect,$hold);
                                        
                                    }
                                    
                                }
                                
                            }
                            $trueb= $c->mag_crs.'('.$c->true_crs.')';
                            $dist=$c->rt_dist_from;
                            if ($dist==null){
                                $dist='-';
                            }
                            if ($this->TampilBearandDist($c->path_term) == false){
                                $trueb = '-';
                                $dist='-';
                            }
                            if ($c->path_term=='RF' || $c->path_term=='AF'){
                                $trueb = $c->arc_rad.' Arc';
                                $dist=$c->rt_dist_from;
                            }
                            if ($c->path_term=='CF' || $c->path_term=='CA' || $c->path_term=='VA'){
                                $dist= '-';
                            }
                            $this->fpdf->Rect($posX, $posY, $cols[2], $htxt);
                            $this->fpdf->MultiCell($cols[2],$hwpt,$wpt,0,'C');
                            $posX +=$cols[2];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            $this->fpdf->Rect($posX, $posY, $cols[3], $htxt);
                            $this->fpdf->MultiCell($cols[3],$htxt,$cfix,0,'C');
                            $posX +=$cols[3];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            $fy='-';
                            if ($c->wd2 == "B" ||$c->wd2 == "Y") $fy = "Y";
                            $this->fpdf->Rect($posX, $posY, $cols[4], $htxt);
                            $this->fpdf->MultiCell($cols[4],$htxt,$fy,0,'C');
                            $posX +=$cols[4];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                        
                            $this->fpdf->Rect($posX, $posY, $cols[5], $htxt);
                            $this->fpdf->MultiCell($cols[5],$htxt,$trueb,0,'C');
                            $posX +=$cols[5];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            
                            $this->fpdf->Rect($posX, $posY, $cols[6], $htxt);
                            $this->fpdf->MultiCell($cols[6],$htxt,$dist,0,'C');
                            $posX +=$cols[6];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            $turn=$c->turn_dir;
                            if ($turn==null){
                                $turn='-';
                            }
                            $this->fpdf->Rect($posX, $posY, $cols[7], $htxt);
                            $this->fpdf->MultiCell($cols[7],$htxt,$turn,0,'C');
                            $posX +=$cols[7];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            $desalt=$c->alt_desc;
                            $alt=$desalt.$c->alt1;
                            if ($c->alt1==null){
                                $alt='-';
                            }
                            if ($c->wd4 == "M" && substr($c->fix_id,0,3) == "RWY"){
                                $alt= $rwyelev + $c->tch;
                            }
                            $hwpt=$htxt;
                            if ($c->alt2 ==null || $c->alt2 ==''){

                            }else{
                                $alt='-'.$c->alt2.' '.$desalt.$c->alt1;
                                $ky = array_search($c->wd4, array_column($wptdesc, 'd43'));
                                $wpt.=' ('.$wptdesc[$ky]->descr.')';
                                $hwpt=($htxt/3);
                                $this->fpdf->setY($posY+1);
                                $this->fpdf->setX($posX);
                            }
                            
                            $this->fpdf->Rect($posX, $posY, $cols[8], $htxt);
                            $this->fpdf->MultiCell($cols[8],$hwpt,$alt,0,'C');
                            $posX +=$cols[8];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            $speed='-'.$c->sp_lim;
                            if ($speed==null){
                                $speed='-';
                            }
                            $this->fpdf->Rect($posX, $posY, $cols[9], $htxt);
                            $this->fpdf->MultiCell($cols[9],$htxt,$speed,0,'C');
                            $posX +=$cols[9];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            $tch=$c->vert_angle.'/'.$c->tch;
                            if ($c->vert_angle==null){
                                $tch='-';
                            }
                            if ($charttype !=='45' && $tch !=='-'){
                                $tch=$c->vert_angle.'%';
                            }
                            $this->fpdf->Rect($posX, $posY, $cols[10], $htxt);
                            $this->fpdf->MultiCell($cols[10],$htxt,$tch,0,'C');
                            $posX +=$cols[10];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);

                            $this->fpdf->Rect($posX, $posY, $cols[11], $htxt);
                            $this->fpdf->MultiCell($cols[11],$htxt,$navspec,0,'C');
                            $posX +=$cols[11];
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($posX);
                            // $this->fpdf->Cell($cols[0],$htxt,sprintf('%03d',$no),0,0,'C');
                            // $this->fpdf->Cell($cols[1],$htxt,$c->path_term,0,0,'C');
                            // $pnt=getfixedcoordinate($c);
                            // $wpt=$pnt['fix'];
                            // if ($pnt['fix']==null){
                            //     $wpt='-';
                            // }
                        
                            // if ($c->wd4 !== null){
                            //     $ky = array_search($c->wd4, array_column($wptdesc, 'd43'));
                            //     $wpt.=' ('.$wptdesc[$ky]->descr.')';
                            //     // dd($wptdesc[$ky],$c->wd2);
                            //     $posx= $this->fpdf->getX();
                            //     $this->fpdf->MultiCell($cols[2],$htxt,$wpt,0,'C');
                            //     $this->fpdf->setY($posy);
                            //     $this->fpdf->setX($posx);
                            // }else{
                            //     $this->fpdf->Cell($cols[2],$htxt,$wpt,0,0,'C');
                                
                            // }
                        
                            
                            // $dist=$c->rt_dist_from;
                            // if ($dist==null){
                            //     $dist='-';
                            // }
                            // $this->fpdf->Cell($cols[5],$htxt,$dist,0,0,'C');
                            // $turn=$c->turn_dir;
                            // if ($turn==null){
                            //     $turn='-';
                            // }
                            // $this->fpdf->Cell($cols[6],$htxt,$turn,0,0,'C');
                            // $alt=$c->alt1;
                            // if ($alt==null){
                            //     $alt='-';
                            // }
                            // $this->fpdf->Cell($cols[7],$htxt,$alt,0,0,'C');
                            // $speed='-'.$c->sp_lim;
                            // if ($speed==null){
                            //     $speed='-';
                            // }
                            // $this->fpdf->Cell($cols[8],$htxt,$speed,0,0,'C');
                            $this->fpdf->ln($htxt);
                            $newpage= $this->fpdf->GetY();
                            if ($newpage > $this->btshal-7){
                                $this->AipFooterCodingTable();
                                $this->fpdf->AddPage();
                                $this->properties('Iwish Indonesia',$chart[0]->chart_id.' '.$chart[0]->chart_name);
                                $this->Watermark($this->draft);
                                $this->fpdf->SetAutoPageBreak(true,0);
                                $this->fpdf->SetRightMargin($this->rm);
                                $this->fpdf->SetLeftMargin($this->lm);
                                $this->fpdf->SetTopMargin($this->head);

                                $this->AipChartHeader($arpt[0],$chart[0],$flnm[0]);
                                // dd($this->lebararea);
                                if ($key <$jmltrans){
                                    $this->fpdf->SetFont('Arial','',7);
                                    $this->fpdf->Cell(0,4,$judul,0,0,'C');
                                    $this->fpdf->ln(4);
                                    $this->fpdf->SetFont('Arial','',6);
                                    $cols= $this->createtablecodingtable($tbl,$mgvar2,'169');
                                    $this->fpdf->ln(2.5);

                                }
                                $posY= $this->fpdf->getY();
                                $posX= $this->fpdf->getX();
                            }
                            $posY= $this->fpdf->GetY();
                            $this->fpdf->setY($posY);
                            $this->fpdf->setX($aX);
                            $posX=$aX;
                            $no+=10;
                        }
                    }
                }
                // dd($c);
                # code...
            }
        }
        
        if (!empty($holdingcollect)){
            $this->fpdf->SetFont('Arial','',7);
            $this->fpdf->ln($htxt);
            $this->fpdf->Cell(50,-3,'HOLDING IDENTIFICATION',0,0,'L');
            $this->fpdf->SetFont('Arial','',6);
            $this->fpdf->ln(1);
            $cols= $this->createtablecodingtable($tbl,$mgvar2,'HOLDING');
            $this->fpdf->ln(2.5);
            $posY= $this->fpdf->getY();
            $posX= $this->fpdf->getX();
            usort($holdingcollect, fn($a, $b) => strcmp($a->fix_name, $b->fix_name));
            $hldfix='';
            // dd($holdingcollect);
            foreach ($holdingcollect as $key => $h) {
                if ($h->fix_name !==$hldfix){

                    $this->fpdf->Rect($posX, $posY, $cols[0], $htxt);
                    $this->fpdf->MultiCell($cols[0],$htxt,'HM',0,'C');
                    $posX +=$cols[0];
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $cols[1], $htxt);
                    $this->fpdf->MultiCell($cols[1],$htxt,$h->fix_name,0,'C');
                    $posX +=$cols[1];
                    $crs=$h->crs/10;
                    $crs=sprintf('%03d',$crs);
                    $crss=$crs.'('.$crs.'.0)';
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $cols[2], $htxt);
                    $this->fpdf->MultiCell($cols[2],$htxt,$crss,0,'C');
                    $posX +=$cols[2];
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $cols[3], $htxt);
                    $time=$h->leg_time/10;
                    if ($h->leg_time < 10){
                        $time=$h->leg_time;
                    }
                    $this->fpdf->MultiCell($cols[3],$htxt,$time,0,'C');
                    $posX +=$cols[3];
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $cols[4], $htxt);
                    $this->fpdf->MultiCell($cols[4],$htxt,$h->turn,0,'C');
                    $posX +=$cols[4];
                    $minalt=$h->min_alt;
                    $maxalt=$h->max_alt;
                    if ($h->min_alt == null || $h->min_alt==''){
                        $minalt=$h->max_alt;
                        $maxalt='-';
                    }
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $cols[5], $htxt);
                    $this->fpdf->MultiCell($cols[5],$htxt,$minalt,0,'C');
                    $posX +=$cols[5];
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $cols[6], $htxt);
                    $this->fpdf->MultiCell($cols[6],$htxt,$maxalt,0,'C');
                    $posX +=$cols[6];
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $cols[7], $htxt);
                    $sp='-'.$h->speed;
                    if ($h->speed==null || $h->speed==''){
                        $sp='-';
                    }
                    $this->fpdf->MultiCell($cols[7],$htxt,$sp,0,'C');
                    $posX +=$cols[7];
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $this->fpdf->Rect($posX, $posY, $cols[8], $htxt);
                    $this->fpdf->MultiCell($cols[8],$htxt,$navspec,0,'C');
                    $posX +=$cols[8];
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($posX);
                    $posY= $this->fpdf->GetY()+$htxt;
                    $this->fpdf->setY($posY);
                    $this->fpdf->setX($aX);
                    $posX=$aX;
                    $newpage= $this->fpdf->GetY();
                    if ($newpage > $this->btshal){
                            $this->AipFooterCodingTable();
                            $this->fpdf->AddPage();
                            $this->properties('Iwish Indonesia',$chart[0]->chart_id.' '.$chart[0]->chart_name);
                            $this->Watermark($this->draft);
                            $this->fpdf->SetAutoPageBreak(true,0);
                            $this->fpdf->SetRightMargin($this->rm);
                            $this->fpdf->SetLeftMargin($this->lm);
                            $this->fpdf->SetTopMargin($this->head);
        
                            $this->AipChartHeader($arpt[0],$chart[0],$flnm[0]);
                            // dd($this->lebararea);
                            $this->fpdf->SetFont('Arial','',7);
                            $this->fpdf->Cell(50,-3,'HOLDING IDENTIFICATION',0,0,'L');
                            $this->fpdf->SetFont('Arial','',6);
                            $this->fpdf->ln(1);
                            $cols= $this->createtablecodingtable($tbl,$mgvar2,'HOLDING');
                            // $this->fpdf->ln(2.5);
                            $posY= $this->fpdf->getY()-3.5;
                            $posX= $this->fpdf->getX();
                            $this->fpdf->setY($posY);
                            $xWpt=$posX; 
                            
                        
                    }
                }
                $hldfix=$h->fix_name;
            }
            $this->fpdf->ln($htxt);
        }
        usort($fixcollect, fn($a, $b) => strcmp($a['fix'], $b['fix']));
        $this->fpdf->SetFont('Arial','',7);
        $this->fpdf->ln($htxt);
        $this->fpdf->Cell(50,-3,'WAYPOINT LIST',0,0,'L');
        $this->fpdf->SetFont('Arial','',6);
        $this->fpdf->ln(1);
        $yWpt= $this->fpdf->getY();
        $cols= $this->createtablecodingtable($tbl,$mgvar2,'WPT');
        $this->fpdf->ln(-3.5);
        $posY= $this->fpdf->getY();
        $posX= $this->fpdf->getX();
        $xWpt=$posX;$tambh=0;
        $wptnm='';$htxt=5;$colm=1;
        foreach ($fixcollect as $key => $h) {
            // dd($h);
            if ($h['fix'] !== $wptnm){
                $this->fpdf->Rect($posX, $posY,20, $htxt);
                $this->fpdf->MultiCell(20,$htxt,$h['fix'],0,'C');
                $posX +=20;
                $this->fpdf->setY($posY);
                $this->fpdf->setX($posX);
                $cord = toWgs($h['lon'],'LON');
                $cord1 = toWgs($h['lat'],'LAT');
                if($chart[0]->chart_type=='45'){
                    $crd=$cord1[0]['IAC VIEW'].' '.$cord[0]['IAC VIEW'];
                }else{
                    $crd=$cord1[0]['ENR VIEW'].' '.$cord[0]['ENR VIEW'];
                }
                if (substr($h['fix'],0,3)=='RWY'){
                    $crd=$cord1[0]['VIEW'].' '.$cord[0]['VIEW'];
                }
                if (substr($h['fix'],0,3)=='NAV'){
                    $crd=$cord1[0]['IAC VIEW'].' '.$cord[0]['IAC VIEW'];
                }
                $crd= iconv('UTF-8', 'windows-1252', $crd);
                $this->fpdf->Rect($posX, $posY,40, $htxt);
                $this->fpdf->MultiCell(40,$htxt,$crd,0,'C');
                $posY= $this->fpdf->getY();
                $this->fpdf->ln($htxt);
                $posX =$xWpt;
                $this->fpdf->setY($posY);
                $this->fpdf->setX($posX);
                $newpage= $this->fpdf->GetY();
                if ($newpage > $this->btshal){
                    if ($colm==1){
                        $this->fpdf->setY($yWpt);
                        $this->fpdf->setX($xWpt);
                        $this->fpdf->SetFont('Arial','',6);
                        $cols= $this->createtablecodingtable($tbl,$mgvar2,'WPT',2);
                        $colm=2;
                        $posX= 80;
                        $xWpt=$posX; 
                        $posY= $this->fpdf->getY()-3.5;
                        // $this->fpdf->ln(2.5);
                        $this->fpdf->setY($posY);
                        $this->fpdf->setX($posX);
                    }else{
                        $this->AipFooterCodingTable();
                        $this->fpdf->AddPage();
                        $this->properties('Iwish Indonesia',$chart[0]->chart_id.' '.$chart[0]->chart_name);
                        $this->Watermark($this->draft);
                        $this->fpdf->SetAutoPageBreak(true,0);
                        $this->fpdf->SetRightMargin($this->rm);
                        $this->fpdf->SetLeftMargin($this->lm);
                        $this->fpdf->SetTopMargin($this->head);
    
                        $this->AipChartHeader($arpt[0],$chart[0],$flnm[0]);
                        // dd($this->lebararea);
                        $this->fpdf->SetFont('Arial','',7);
                        $this->fpdf->Cell(50,-2,'WAYPOINT LIST',0,0,'L');
                        $this->fpdf->ln(2);
                        $this->fpdf->SetFont('Arial','',6);
                        $cols= $this->createtablecodingtable($tbl,$mgvar2,'WPT');
                        // $this->fpdf->ln(2.5);
                        $posY= $this->fpdf->getY()-3.5;
                        $posX= $this->fpdf->getX();
                        $this->fpdf->setY($posY);
                        $xWpt=$posX; 
                        
                    }
                }

            }
            $wptnm=$h['fix'];
        }
        //  dd($fixcollect,$holdingcollect);
      
        $this->AipFooterCodingTable();
        $this->fpdf->Output('I',$this->filename.'.pdf');
        exit;
    }
   
    private function TampilBearandDist($pathterm){
        $hsl = true;
        if ($pathterm == "DF" || $pathterm == "IF" || $pathterm == "RF" || $pathterm == "HM" || $pathterm == "HA" || $pathterm == "HF" ){
            $hsl = false;
         }
        return $hsl;
     }
    public function createPDF()
    {


        $originalInput=Request::input();
        $user = Auth::user();
        $request=$originalInput;
        $id=$request['arptid'];
        $this->nr=$request['nr'];
        $this->draft=$request['wtrmark'];
        $this->header=$request['header'];
        $this->footer=$request['footer'];
        $this->width=$request['width'];
        $this->high=$request['high'];
        $this->source=$request['source'];
        $dtpub=date_create($request['pubdate']);
        $dteff=date_create($request['effdate']);
        $this->eaipdata=$request['eaipdata'];
        $this->filename=$request['filenm'].'_'.date('Ymd_his');
        // dd($request,$request['table']);
        // $this->nr='90';
        if ($this->nr==null){
            $this->nr='XX';
        }
        

        if ($this->nr=='XX'){
            $this->pubdate='Publication Date : xx XXX xx';
            // $this->effdate='Effective Date : xx XXX xx';
            $this->effdate='xx XXX xx';
        }else{
            $this->pubdate='Publication Date : '.strtoupper(date_format($dtpub,"d M y"));
            // $this->effdate='Effective Date : '.strtoupper(date_format($dteff,"d M y"));
            $this->effdate=strtoupper(date_format($dteff,"d M y"));
            $dat =  RawPub::where('tablename','=','arpt')
            ->where('fieldid','=',$id)
            ->where('status_raw','!=',90) // isDirty
            ->first();
            if(!is_null($dat)){
                $dat->nr= $this->nr; 
                $dat->pub_date =date_format($dtpub,"Y-m-d");
                $dat->eff_date=date_format($dteff,"Y-m-d");
                $dat->pub_type=$this->source;
                // dd($dat);
                $dat->save(); 
            }
        }
        $s_nr=$this->nr;
        if (strlen($this->nr)>3){
            $sn=explode('/',$this->nr);
            $s_nr= $sn[0];
        }
            $this->src=$this->source.' '.$s_nr;
        // dd($request);
        if ($request['table'] =='codingtable'){
            $this->codingtable($id,$this->eaipdata,$request['chart']);
        }else{
        if ($request['table'] !=='arpt'){
            $this->enr($id);
       
        }else{
            // dd($id,$this->src);
            if ($user->isAdmin()) {
                // return view('pages.admin.home');
            }
            //             $method='GET';
            //             $raw_dat = RawPub::where('tablename', 'arpt')
            //             ->where('fieldname', 'arpt_ident')
            //             ->where('fieldid', $id)
            //             ->where('status_raw','<=',70)
            //             ->where('status_raw','<>','100')
            //             // ->where('status_raw','<', 100)
            //             ->get();
            // dd($raw_dat);
            $data['eaiplist'] = getDataApi($originalInput,'/api/eaip/codaipsub');
            $data['aprontwy'] = getDataApi($originalInput,'/api/arpt/temp/aprontwy?arpt_ident=' .$id.'&deleted=0&sort=sequence:asc');
            $data['tblheader'] = getDataApi($originalInput,'/api/eaip/codtableheader');
            $data['obstacle'] = getDataApi($originalInput,'/api/eaip/obstacletemp?arpt_ident='.$id.'&deleted=0&sort=position:asc');
            $data['rwylist'] = getDataApi($originalInput,'/api/rwy/temp?arpt_ident='.$id);
            // $data['rwylighting'] = getDataApi($originalInput,'/api/rwyarpt/'.$id);
            if ($this->eaipdata=='request' || $this->eaipdata=='publication'){
                $sql='/api/eaip/contenttemp?arpt_ident='.$id.'&sort=sequence:asc';
            }else if ($this->eaipdata=='current'){
                $sql='/api/eaip/content?arpt_ident='.$id.'&sort=sequence:asc';
            }
            $data['airportcontent'] = getDataApi($originalInput,$sql);
            $data['codaip'] = getDataApi($originalInput,'/api/eaip/codaip');
            $data['arpt'] = getDataApi($originalInput,'/api/airports?arpt_ident='.$id);
            $data['freq'] = getDataApi($originalInput,'/api/freq/temp/usage?arpt_ident='.$id.'&deleted=0&sort=seq:asc');
            $data['navaid'] = getDataApi($originalInput,'/api/navarpt/temp?arpt_ident='.$id);
            $data['channel'] = getDataApi($originalInput,'/api/nav/channel');
            $chart = getDataApi($originalInput, 'api/airport/chart?arpt_ident='.$id.'&aip_sub_id=AD 2.24&deleted=0&sort=seq:asc');
            $data['gen'] = getDataApi($originalInput,'api/eaip/gen/content?section_id=16');
            // dd($data['chart']);
            $arpt=$data['arpt'][0];
            $codaip=$data['codaip'];
            $eaiplist=$data['eaiplist'];
            $content=$data['airportcontent'];
            $apron=$data['aprontwy'];
            $table=$data['tblheader'];
            $obs=$data['obstacle'];
            $rwy=$data['rwylist'];
            $comm=$data['freq'];
            $nav=$data['navaid'];
            $ch=$data['channel'];
            $gen=$data['gen'];

            // $twy=$data['twylist'];
          
            $this->fpdf = new Fpdf('P','mm',[160,210]);
            // dd($arpt);
           
            $a= explode('@',$this->getisi($content,229));
            if ($a[0]=='' || $a[0]=='NIL'){
                $icao=$arpt->icao;
                $stsa='U';
            }else{
                $icao=$a[0];
                $stsa=$a[2];
            }

            $b= explode('@',$this->getisi($content,231));
            if ($b[0]=='' || $b[0]=='NIL'){
                $name=$arpt->arpt_name;
                $stsb='U';
            }else{
                $name=$b[0];
                $stsb=$b[2];
            }
            $c= explode('@',$this->getisi($content,232));
            if ($c[0]=='' || $c[0]=='NIL'){
                $city=$arpt->arpt_name;
                $stsc='U';
            }else{
                $city=$c[0];
                $stsc=$c[2];
            }
            switch ($arpt->vol) {
                case '2':
                    $this->header= $this->header.' ( VOL II )';
                    break;
                case '3':
                    $this->header= $this->header.' ( VOL III )';
                    break;
                case '4':
                    $this->header= $this->header.' ( VOL IV )';
                    break;
                case '5':
                    $this->header= $this->header.' ( VOL V )';
                    break;

            }
           


            $this->fpdf->AddPage();
            $this->properties('Iwish Indonesia',$icao.'-'.$name);
            $this->Watermark($this->draft);
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);

            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            // $this->fpdf->SetMargins($this->lm,$this->head,$this->rm);
            // dd($gen);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),0);
            $this->fpdf->SetFont('Arial','',8);
            // $this->WriteHTML($this->_convert(stripslashes($gen[0]->body)),$this->bi);

            $this->fpdf->ln(3);
            foreach ($codaip as $id) {
                $d=explode('#', $id->id, 2);
                $i=$d[1];
                // dd($codaip);

                switch ($d[0]) {
                    case 'AD 2.1':
                        $jdl=$icao.' '.$d[0].' '.$i;
                       
                        // dd($a[0]);
                        $isi=$icao.' - '.$city.' / '.ucwords(strtolower($name));
                        $this->fpdf->SetFont('Arial','B',9);
                        // $this->fpdf->Cell($this->lm);
                        $this->fpdf->Cell(0,$this->ln,$jdl,0,0,'L');
                        $this->fpdf->ln($this->spc7);
                        $this->fpdf->SetFont('Arial','B',11);
                        $this->fpdf->Cell(0,$this->ln,$isi,0,0,'C');
                        if ($stsa =='R' || $stsa =='N' || $stsb =='R' || $stsb =='N' || $stsc =='R' || $stsc =='N'){
                            $tg = $this->ln;
                            $this->vertikalline($this->fpdf->getY(),$this->fpdf->getY()+$tg);
                            
                        }
                        for ($i=229; $i < 235 ; $i++) { 
                            # code...
                            $page['category_id']=$i;
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);
                        }

                        $this->fpdf->ln($this->spc7);
                        break;
                    case 'AD 2.2':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $this->fpdf->SetFont('Arial','B',9);
                        $pg=[2,3,4,5,6,7,8,9,11,12,13,14,235,212,227];
                        for ($i=0; $i < count($pg) ; $i++) { 
                            $page['category_id']=$pg[$i];
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);
                        }
                        $this->ad22($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.3':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        for ($i=15; $i <= 26 ; $i++) { 
                            $page['category_id']=$i;
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);

                        }
                        
                        $this->ad23($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.4':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        for ($i=27; $i <= 33 ; $i++) { 
                            $page['category_id']=$i;
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);

                        }
                        $this->ad24($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.5':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        for ($i=34; $i <= 41 ; $i++) { 
                            if ($i !== 40){

                                $page['category_id']=$i;
                                $page['arpt_ident']=$arpt->arpt_ident;
                                $page['page']=$this->fpdf->PageNo();
                                $page['src_id']=null;
                                savePagePdf($page);
                            }

                        }
                        $this->ad25($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.6':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        for ($i=42; $i <= 45 ; $i++) { 
                            

                                $page['category_id']=$i;
                                $page['arpt_ident']=$arpt->arpt_ident;
                                $page['page']=$this->fpdf->PageNo();
                                $page['src_id']=null;
                                savePagePdf($page);
                            

                        }
                        $this->ad26($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.7':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        for ($i=46; $i <= 48 ; $i++) { 
                            $page['category_id']=$i;
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);
                        }
                        $this->ad27($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.8':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $pg=[49,52,53,57,156,59];
                        for ($i=0; $i < count($pg) ; $i++) { 
                        // for ($i=46; $i <= 48 ; $i++) { 
                            $page['category_id']=$pg[$i];
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);
                        }
                        $this->ad28($jdl,$eaiplist,$apron,$content,$id,$icao);
                        break;
                    case 'AD 2.9':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $pg=[60,61,62,149,63];
                        for ($i=0; $i < count($pg) ; $i++) { 
                        // for ($i=46; $i <= 48 ; $i++) { 
                            $page['category_id']=$pg[$i];
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);
                        }
                        $this->ad29($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.10':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        // $pg=[60,61,62,149,63];
                        // for ($i=0; $i < count($pg) ; $i++) { 
                        // // for ($i=46; $i <= 48 ; $i++) { 
                        //     $page['category_id']=$i;
                        //     $page['arpt_ident']=$arpt->arpt_ident;
                        //     $page['page']=$this->fpdf->PageNo();
                        //     $page['src_id']=null;
                        //     savePagePdf($page);
                        // }
                        $this->ad210($jdl,$eaiplist,$obs,$table,$id,$icao);
                        break;
                    case 'AD 2.11':
                        $jdl=$icao.' '.$d[0].' '.$i;
                      
                        // for ($i=0; $i < count($pg) ; $i++) { 
                        for ($i=70; $i <= 79 ; $i++) { 
                            // if ($i < 80 && $i > 212){
                                $page['category_id']=$i;
                                $page['arpt_ident']=$arpt->arpt_ident;
                                $page['page']=$this->fpdf->PageNo();
                                $page['src_id']=null;
                                savePagePdf($page);

                            // }
                        }
                        for ($i=213; $i <= 216 ; $i++) { 
                            // if ($i < 80 && $i > 212){
                                $page['category_id']=$i;
                                $page['arpt_ident']=$arpt->arpt_ident;
                                $page['page']=$this->fpdf->PageNo();
                                $page['src_id']=null;
                                savePagePdf($page);

                            // }
                        }
                        $this->ad211($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.12':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $this->ad212($jdl,$eaiplist,$rwy,$table,$id,$icao);
                        break;
                    case 'AD 2.13':
                        
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $this->ad213($jdl,$eaiplist,$rwy,$table,$id,$icao);
                        break;
                    case 'AD 2.14':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $this->ad214($jdl,$eaiplist,$rwy,$table,$id,$icao);
                        break;
                    case 'AD 2.15':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $pg=[83,84,86,87,88,217,218];
                        for ($i=0; $i < count($pg) ; $i++) { 
                        // for ($i=46; $i <= 48 ; $i++) { 
                            $page['category_id']=$pg[$i];
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);
                        }
                        $this->ad215($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.16':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $pg=[89,90,91,92,93,94,95,219];
                        for ($i=0; $i < count($pg) ; $i++) { 
                        // for ($i=46; $i <= 48 ; $i++) { 
                            $page['category_id']=$pg[$i];
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);
                        }
                        $this->ad216($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.17':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $pg=[96,220,221,222,223,224,236,225];
                        for ($i=0; $i < count($pg) ; $i++) { 
                        // for ($i=46; $i <= 48 ; $i++) { 
                            $page['category_id']=$pg[$i];
                            $page['arpt_ident']=$arpt->arpt_ident;
                            $page['page']=$this->fpdf->PageNo();
                            $page['src_id']=null;
                            savePagePdf($page);
                        }
                        $this->ad217($jdl,$eaiplist,$content,$id,$icao);
                        break;
                    case 'AD 2.18':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $this->ad218($jdl,$eaiplist,$comm,$table,$id,$icao);
                        break;
                    case 'AD 2.19':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $this->ad219($jdl,$eaiplist,$nav,$ch,$table,$id,$icao);
                        break;
                    case 'AD 2.20':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $page['category_id']=99;
                        $page['arpt_ident']=$arpt->arpt_ident;
                        $page['page']=$this->fpdf->PageNo();
                        $page['src_id']=null;
                        savePagePdf($page);
                        $this->ad220($jdl,$content,99,$icao,$gen);
                        break;
                    case 'AD 2.21':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $page['category_id']=108;
                        $page['arpt_ident']=$arpt->arpt_ident;
                        $page['page']=$this->fpdf->PageNo();
                        $page['src_id']=null;
                        savePagePdf($page);
                        $this->ad220($jdl,$content,108,$icao,$gen);
                        break;
                    case 'AD 2.22':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $page['category_id']=109;
                        $page['arpt_ident']=$arpt->arpt_ident;
                        $page['page']=$this->fpdf->PageNo();
                        $page['src_id']=null;
                        savePagePdf($page);
                        $this->ad220($jdl,$content,109,$icao,$gen);
                        break;
                    case 'AD 2.23':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $page['category_id']=110;
                        $page['arpt_ident']=$arpt->arpt_ident;
                        $page['page']=$this->fpdf->PageNo();
                        $page['src_id']=null;
                        savePagePdf($page);
                        $this->ad220($jdl,$content,110,$icao,$gen);
                        break;
                    case 'AD 2.24':
                        $jdl=$icao.' '.$d[0].' '.$i;
                        $page['category_id']=114;
                        $page['arpt_ident']=$arpt->arpt_ident;
                        $page['page']=$this->fpdf->PageNo();
                        $page['src_id']=null;
                        savePagePdf($page);
                        $this->ad224($jdl,$chart,114,$icao);
                        break;
                    // default:
                        # code...

                }
                

            }
            // var_dump($this->fpdf->PageNo());
            $this->AipFooter();
            $flnm=$this->filename;
            $this->fpdf->Output('I',$flnm.'.pdf');
            exit;
        }
    }

    }
    function properties($titel,$subject){
        $this->fpdf->SetTitle($titel);
        $this->fpdf->SetSubject($subject);
        $this->fpdf->SetCreator('Hendi Saeful Hamdi');
        $this->fpdf->SetAuthor('Aeross Team');
    }
    private function getisi($cont,$id){
        $count = count($cont);
        $content="NIL@#@U";
        // dd($count);

            for($i = 0; $i < $count; $i++ ) {
                if ($cont[$i]->category_id == $id) {
                    if ($cont[$i]->content==''){
                        $content='NIL@#@U';
                    }else{
                        $content=$cont[$i]->content.'@#@'.$cont[$i]->status;
                    }
                    // dd('ISISISIS',$content,iconv('UTF-8', 'windows-1252', $content));
                    return $content = iconv('UTF-8', 'windows-1252', $content);
                    break;
                }
            }

        return $content;
    }
    public function buatitik($txt,$icao){
        $r='.';
        $stp=$this->fpdf->GetStringWidth($r);
        $pt=$this->fpdf->GetStringWidth($txt);
        $jm = $this->ptxt - $pt;
        // var_dump($txt . $jm);
        for($i = 0;$i <= $jm;$i+=$stp){
            // var_dump($i,$ttk);
            $r = $r.'.';
            // echo $ttk;
        }
        $ttk =$txt.' '.$r;
        if ($pt>$this->ptxt){
            $tbg=explode("\n",$this->fpdf->WordWrap($txt,$this->ptxt));
            $t=count($tbg)-1;
            // dd($t);
            for($i = 0;$i <= $t-1;$i++){
                // var_dump($tbg[$i]);
                $this->fpdf->Cell($this->ptxt,$this->ln,$tbg[$i],0,0,'L');
                $this->fpdf->ln($this->ln);
                $this->fpdf->Cell($this->tab);
                $newpage=$this->fpdf->getY();
                if ($newpage > $this->btshal){
                    $this->loncatnewpage($icao);
                    $this->fpdf->Cell($this->tab);
                }
            }
            $this->buatitik($tbg[$t],$icao);
        }else{

            $this->fpdf->Cell($this->ptxt,$this->ln,$ttk,0,0,'L');
            $this->fpdf->Cell($this->tab);
        }
        // $this->fpdf->MultiCell(55,$this->ln,$ttk,0);
        // $this->fpdf->SetX($sY);
    }
    private function getcontent($cont,$id,$item,$icao)
    {
        $status='U';
            if ($id==235){
                $a=  $this->getisi($cont,228);
                $aa=explode('@#@', $a, 2);
                $b=  $this->getisi($cont,4);
                $bb=explode('@#@', $b, 2);
                $str = explode(' ', $bb[0], 2);;

                    $temper=$this->Convtext($str[0].'°C');

                $elev=$this->Convtext( isset($aa[0])?$aa[0].'ft/':'');
                // $temper=$this->Convtext( isset($bb[0])?$bb[0].'°C':'');
                $content=$elev.$temper;
                if ($aa[1]=='R' || $aa[1]=='N' ||$bb[1]=='R' || $bb[1]=='N'){
                    $status='R';
                }
            }else if ($id==212){
                $a=  $this->getisi($cont,2);
                //  dd($a);
                $aa=explode('@#@', $a, 2);
                $cc=explode(' ',$aa[0]); 
                if (count($cc) == 2){
                    $llat=$cc[0];
                    $llon=$cc[1];
                    $hi=GeoHi($llat, $llon);
                    $hasil=round($hi,2).'m / '.round(($hi * 3.28084),2).'ft';
                }else{
                    $hasil='NIL';
                }
                
                $content=iconv('UTF-8', 'windows-1252', $hasil);
                if ($aa[1]=='R' || $aa[1]=='N'){
                    $status='R';
                }
            }else if ($id==2){
                $a=  $this->getisi($cont,2);
                $aa=explode('@#@', $a, 2);
                $cc=explode(' ',$aa[0]);
                // dd(count($cc));
                if (count($cc) == 2){
                    $llat=toDecimal($cc[0],false);
                    $llon=toDecimal($cc[1],false);
                    // dd($llat);
                    $llat1=toWgs( $llat,'LAT');
                    $llon1=toWgs($llon,'LON');
                    $hasil=$llat1[0]['ENR'].' '.$llon1[0]['ENR'];
                }else{
                    $hasil='NIL';
                }
                $content=iconv('UTF-8', 'windows-1252', $hasil);
                if ($aa[1]=='R' || $aa[1]=='N'){
                    $status='R';
                }
            }else if ($id==7){
                $a=  $this->getisi($cont,6);
                // dd($a);
                $b=  $this->getisi($cont,7);
                $c=  $this->getisi($cont,8);
                $d = $this->getisi($cont,9);
                $e = $this->getisi($cont,11);
                $f = $this->getisi($cont,12);
                $g = $this->getisi($cont,227);
                // dd($c);
                    $aa=explode('@#@', $a, 2);
                    $bb=explode('@#@', $b, 2);
                    $cc=explode('@#@', $c, 2);
                    $dd=explode('@#@', $d, 2);
                    $ee=explode('@#@', $e, 2);
                    $ff=explode('@#@', $f, 2);
                    $gg=explode('@#@', $g, 2);

                    $content=$aa[0]. PHP_EOL .$bb[0];
                    $content =$content. PHP_EOL .'Tel'.'     '.' : '.$cc[0]. PHP_EOL .'Telefax : '.$dd[0]. PHP_EOL .'E-mail  : '.$ee[0]. PHP_EOL .'AFS     : '.$ff[0]. PHP_EOL .'Website : '.$gg[0];
                    if ($aa[1]=='R' || $aa[1]=='N' || $bb[1]=='R' || $bb[1]=='N' || $cc[1]=='R' || $cc[1]=='N' || $dd[1]=='R' || $dd[1]=='N' || $ee[1]=='R' || $ee[1]=='N' || $ff[1]=='R' || $ff[1]=='N' || $gg[1]=='R' || $gg[1]=='N'){
                        $status='R';
                    }
                // dd($content);
            }else {
                $c=  $this->getisi($cont,$id);
                // dd('setelah ISI',$c);
                $cc=explode('@#@', $c, 2);
                $content=$cc[0];
                if ($cc[1]=='R' || $cc[1]=='N'){
                    $status='R';
                }
            }

            $this->fpdf->Cell($this->tab);
            $this->buatitik($item,$icao);

            $tg1 = $this->fpdf->WordWrap($content,$this->pisi);
            $tbg=explode("\n",$tg1);
            $tg2 = $this->fpdf->WordWrap($item,$this->ptxt);
            $tbg1=explode("\n",$tg2);
            $pjisi=$this->fpdf->GetStringWidth($content);
            $t=count($tbg);
            
            $vertY=$this->fpdf->getY();
            for ($i=0;$i <count($tbg);$i++){
                // $this->fpdf->MultiCell($this->pisi,$this->ln,$tbg[$i]);
                $this->fpdf->Cell($this->pisi,$this->ln,$tbg[$i],0,0,'L');
                $this->fpdf->ln($this->ln);
                $this->fpdf->Cell($this->ptxt+($this->tab *2));
                $newpage=$this->fpdf->getY();
                if ($newpage > $this->btshal){
                    if ($status =='R'){
                        $this->vertikalline($vertY,$this->fpdf->getY());
                    }
                    $this->loncatnewpage($icao);
                    $this->fpdf->Cell($this->ptxt+($this->tab *2));
                    $vertY=$this->fpdf->getY();
                }
            }
            if ($status =='R'){
                $this->vertikalline($vertY,$this->fpdf->getY());
            }
            $this->fpdf->SetX($this->lm);
        
    }
    private function loncatnewpage($icao){
        $this->AipFooter();
        $this->fpdf->AddPage();
        $this->Watermark($this->draft);
        $this->fpdf->SetAutoPageBreak(true,0);
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
            $rht=5;
        }else{
            $rht=0;
            $this->lm=20;$this->rm=15;
        }

        $this->fpdf->SetRightMargin($this->rm);
        $this->fpdf->SetLeftMargin($this->lm);
        $this->fpdf->SetTopMargin($this->head);
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
        $this->fpdf->ln($this->ln);
    }
    private function parttitel($judul,$icao,$tinggi=0){
       
        $newpage=$this->fpdf->getY() + $tinggi;
        // var_dump($judul,$newpage);
        if ($newpage > $this->btshal){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            $this->fpdf->SetAutoPageBreak(true,0);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $this->lm=20;$this->rm=15;
                $rht=0;
            }
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);

        }
        // $this->fpdf->ln($this->ln);
        // if ($newpage <52 && $this->fpdf->PageNo() > 1){
        //     $this->fpdf->SetY($this->head+$this->ln);
        // }
        $this->fpdf->SetFont('Arial','B',9);
        $this->fpdf->MultiCell(0,$this->ln,$judul,0);
        // $this->fpdf->ln($this->ln);
        $this->fpdf->SetFont('Arial','',9);
    }
    private function ad22($judul,$sub,$cont,$part,$icao)
    {
        
        $this->parttitel($judul,$icao,0);

        foreach ($sub as $cod) {
            switch ($cod->id) {
                case 2:
                case 3:
                case 235:
                case 212:
                case 5:
                case 7:
                case 13:
                case 14:
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
                    break;
                case 4:
                case 6:
                $this->fpdf->Cell($this->tab);
                $this->fpdf->Cell(0,$this->ln,$cod->item,0,0,'L');
                $this->fpdf->ln($this->ln);
                    break;
            }
        }
        $this->fpdf->ln($this->ln);

    }
    private function ad23($judul,$sub,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,5);
        foreach ($sub as $cod) {
            if ( $cod->id >= 15 &&  $cod->id <=26) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();

        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }
    }
    private function ad24($judul,$sub,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,5);
       
        foreach ($sub as $cod) {
            if ( $cod->id >= 27 &&  $cod->id <=33) {

                $this->getcontent($cont,$cod->id,$cod->item,$icao);
                // $this->getcontent($cont,29,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        // var_dump($pagecurr ,$this->fpdf->PageNo());
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }

    }
    private function ad25($judul,$sub,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,5);
        foreach ($sub as $cod) {
            if ( $cod->id >= 34 &&  $cod->id <=41) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }
    }
    private function ad26($judul,$sub,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,5);
        // var_dump('before',$this->fpdf->PageNo());
        foreach ($sub as $cod) {
            if ( $cod->id >= 42 &&  $cod->id <=45) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }
        // var_dump('after',$this->fpdf->PageNo());
       
    }
    private function ad27($judul,$sub,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,5);
        foreach ($sub as $cod) {
            if ( $cod->id >= 46 &&  $cod->id <=48) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }
    }
    private function ad28($judul,$sub,$apron,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,5);
        foreach ($sub as $cod) {
            switch ($cod->id) {
                case 49:
                    $this->fpdf->Cell($this->tab);
                    $this->fpdf->Cell(0,$this->ln,strtoupper($cod->item),0,0,'L');
                    $this->fpdf->ln($this->ln);
                    // $count = count($apron);
                    $this->isi28($apron,'A',$icao);
                    break;
                    case 52:
                        $np=$this->fpdf->getY();
                        $newpage=$np+4;
                        $rht=0;
                        if ($newpage > $this->btshal){
                            $this->AipFooter();
                            $this->fpdf->AddPage();
                            $this->Watermark($this->draft);
                            if ($this->fpdf->PageNo()%2==0){
                                $this->lm=15;$this->rm=20;
                                $rht=5;
                        }else{
                            $rht=0;
                            $this->lm=20;$this->rm=15;
                        }
                        $this->fpdf->SetAutoPageBreak(true,0);
                        $this->fpdf->SetRightMargin($this->rm);
                        $this->fpdf->SetLeftMargin($this->lm);
                        $this->fpdf->SetTopMargin($this->head);
                        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                        $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
                        $this->fpdf->ln($this->ln);
                    }
                    $this->fpdf->Cell($this->tab);
                    $this->fpdf->Cell(0,$this->ln,strtoupper($cod->item),0,0,'L');
                    $this->fpdf->ln($this->ln);
                    $this->isi28($apron,'B',$icao);
                    break;
                   
                case 53:
                case 57:
                case 156:
                case 59:
                    $this->getcontent($cont,$cod->id,$cod->item,$icao);
                    break;
                default:
                    # code...
                    break;
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }
    }
    private function isi28($apron,$type,$icao){
        foreach ($apron as $a) {
            $aprY=$this->fpdf->getY();
            $sts=$a->status;
            if ($type=='A'){
                if ($a->type==$type){
                    $np=$this->fpdf->getY();
                    $newpage=$np+8;
                    $rht=0;
                    if ($newpage > $this->btshal){
                        $this->AipFooter();
                        $this->fpdf->AddPage();
                        $this->Watermark($this->draft);
                        if ($this->fpdf->PageNo()%2==0){
                            $this->lm=15;$this->rm=20;
                            $rht=5;
                        }else{
                            $rht=0;
                            $this->lm=20;$this->rm=15;
                        }
                        $this->fpdf->SetAutoPageBreak(true,0);
                        $this->fpdf->SetRightMargin($this->rm);
                        $this->fpdf->SetLeftMargin($this->lm);
                        $this->fpdf->SetTopMargin($this->head);
                        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                        $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
                        $this->fpdf->ln($this->ln);
                    }
                    // var_dump($this->fpdf->getY());
                    $nm= iconv('UTF-8', 'windows-1252', '= '.$a->name);
                    $this->fpdf->Cell(20);
                    $this->fpdf->Cell(30,$this->ln,'Designation',0,0,'L');
                    $this->fpdf->Cell(100,$this->ln,$nm,0,0,'L');
                    $this->fpdf->ln($this->ln);
                    $this->fpdf->Cell(20);
                    $this->fpdf->Cell(30,$this->ln,'Surface',0,0,'L');
                    $surface= iconv('UTF-8', 'windows-1252', '= '.$a->surface);
                    $this->fpdf->Cell(100,$this->ln,$surface,0,0,'L');
                    $this->fpdf->ln($this->ln);
                    $this->fpdf->Cell(20);
                    $this->fpdf->Cell(30,$this->ln,'Strength',0,0,'L');
                    $pcn= iconv('UTF-8', 'windows-1252', '= '.$a->strength);
                    $this->fpdf->Cell(100,$this->ln,$pcn,0,0,'L');
                    // var_dump($this->fpdf->getY());
                    $this->fpdf->ln($this->spc7);
                }
            }else{
                if ($a->type==$type){
                    $np=$this->fpdf->getY();
                    $newpage=$np+12;
                    $rht=0;
                    if ($newpage > $this->btshal){
                        $this->AipFooter();
                        $this->fpdf->AddPage();
                        $this->Watermark($this->draft);
                        if ($this->fpdf->PageNo()%2==0){
                            $this->lm=15;$this->rm=20;
                            $rht=5;
                        }else{
                            $rht=0;
                            $this->lm=20;$this->rm=15;
                        }
                        $this->fpdf->SetAutoPageBreak(true,0);
                        $this->fpdf->SetRightMargin($this->rm);
                        $this->fpdf->SetLeftMargin($this->lm);
                        $this->fpdf->SetTopMargin($this->head);
                        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                        $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
                        $this->fpdf->ln($this->ln);
                    }
                    // var_dump($this->fpdf->getY());
                    $this->fpdf->Cell(20);
                    $this->fpdf->Cell(30,$this->ln,'Designation',0,0,'L');
                    $this->fpdf->Cell(100,$this->ln,'= '.$a->name,0,0,'L');
                    $this->fpdf->ln($this->ln);
                    $this->fpdf->Cell(20);
                    $this->fpdf->Cell(30,$this->ln,'Width',0,0,'L');
                    $this->fpdf->Cell(100,$this->ln,'= '.$a->dimension,0,0,'L');
                    $this->fpdf->ln($this->ln);
                    $this->fpdf->Cell(20);
                    $this->fpdf->Cell(30,$this->ln,'Surface',0,0,'L');
                    $this->fpdf->Cell(100,$this->ln,'= '.$a->surface,0,0,'L');
                    $this->fpdf->ln($this->ln);
                    $this->fpdf->Cell(20);
                    $this->fpdf->Cell(30,$this->ln,'Strength',0,0,'L');
                    $this->fpdf->Cell(100,$this->ln,'= '.$a->strength,0,0,'L');
                    // var_dump($this->fpdf->getY());
                    $this->fpdf->ln($this->spc7);
                }
            }
            if ($sts !=='U'){
                $this->vertikalline($aprY,$this->fpdf->getY());
            }
        }
    }
    private function ad29($judul,$sub,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,8);
        foreach ($sub as $cod) {
            if ( $cod->id >= 60 &&  $cod->id <=66) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }
       
    }
    private function ad210($judul,$sub,$cont,$tbl,$part,$icao)
    {
        $np=$this->fpdf->getY();
        $newpage=$np+40;
        $rht=0;
        if ($newpage > $this->btshal){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
        }
        $this->parttitel($judul,$icao,15);
        $obs2=[];
        $obs3=[];
        // print_r(count($cont));
        if (count($cont)==0){
            $obs2[] =array('NIL', 'NIL', 'NIL', 'NIL', 'NIL', 'To be surveyed','U');
            $obs3[] =array('NIL', 'NIL', 'NIL', 'NIL', 'NIL', 'To be surveyed','U');
        }else{
            foreach($cont as $o){
                    if ($o->elev_ft==null && $o->hgt==null ){
                        $elev='NIL';
                    }else{
                        if ($o->elev_ft==null){
                            $elev='NIL';
                        }else{
                            $elev=$o->elev_ft.'ft';
                        }
                        if ($o->hgt==null){
                            $elev=$elev.' / NIL';
                        }else{
                            $elev=$elev.' / '.$o->hgt.'ft';
                        }

                    }
                //    dd($o);
                    $obs22 =['NIL', $o->definition,  $o->geom, $elev, 'NIL', $o->notes, $o->status];
                if ($o->position=='In Area 2'){
                    $obs2[] =$obs22;
                }else if ($o->position=='In Area 3'){
                    $obs3[] =$obs22;
                }
            }

        }
        // print_r($obs2);
        // print_r($obs3);
        // if (count($obs2)==1){
        //     $obs2[] =array('NIL', 'NIL', 'NIL', 'NIL', 'NIL', 'To be surveyed');
        // }
        // if (count($obs3)==1){
        //     $obs3[] =array('NIL', 'NIL', 'NIL', 'NIL', 'NIL', 'To be surveyed');
        // }
        // dd( count($obs2),count($obs3));
        $this->CreateTableObs($tbl,'obs','In Area 2',$obs2,$icao);
        $this->fpdf->ln(2);
        $np=$this->fpdf->getY();
        $newpage=$np+20;
        // dd($newpage,$this->btshal);
        $rht=0;
        if ($newpage > 180){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
        }
        $this->CreateTableObs($tbl,'obs','In Area 3',$obs3,$icao);

        // $this->fpdf->ln($this->ln);
    }

    private function ad211($judul,$sub,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,5);
        foreach ($sub as $cod) {
            if ( $cod->id >= 70 &&  $cod->id <=79) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }

        
    }
    private function ad212($judul,$sub,$rwy,$tbl,$part,$icao)
    {
        $np=$this->fpdf->getY();
        $newpage=$np+20;
        $rht=0;
        if ($newpage > $this->btshal){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
        }
        $this->parttitel($judul,$icao,14);
        $this->fpdf->ln(2);
        $this->CreateTablerwy($icao,$tbl,'rwy1',$rwy,14);
        $this->CreateTablerwy($icao,$tbl,'rwy2',$rwy,22);
        $this->CreateTablerwy($icao,$tbl,'rwy6',$rwy,14);

        // $this->fpdf->ln($this->ln);
    }
    private function ad213($judul,$sub,$rwy,$tbl,$part,$icao)
    {
        $np=$this->fpdf->getY();
        $newpage=$np;
        $rht=0;
        // dd($newpage , $this->btshal);
        if ($newpage > $this->btshal){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
        }
        $this->parttitel($judul,$icao,10);
        $this->fpdf->ln(2);

        $this->CreateTablerwy($icao,$tbl,'rwy3',$rwy,10);

        // $this->fpdf->ln($this->ln);
    }
    private function ad214($judul,$sub,$rwy,$tbl,$part,$icao)
    {
        $np=$this->fpdf->getY();
        $newpage=$np+20;
        $rht=0;
        if ($newpage > $this->btshal){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
        }
        $this->parttitel($judul,$icao,10);
        $this->fpdf->ln(2);
        $this->CreateTablerwy($icao,$tbl,'rwy4',$rwy,10);
        $this->CreateTablerwy($icao,$tbl,'rwy5',$rwy,18);

        // $this->fpdf->ln($this->ln);
    }
    private function ad215($judul,$sub,$cont,$part,$icao)
    {
        $this->parttitel($judul,$icao,5);
        foreach ($sub as $cod) {
            if ( $cod->id >= 83 &&  $cod->id <=88) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }

    }

    private function ad216($judul,$sub,$cont,$part,$icao)
    {

        $this->parttitel($judul,$icao,5);
        $pagea=$this->fpdf->PageNo();
        foreach ($sub as $cod) {
            if ( $cod->id >= 89 &&  $cod->id <=95) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagea == $pagecurr){
            $this->fpdf->ln($this->ln);
        }
    }

    private function ad217($judul,$sub,$cont,$part,$icao)
    {
       
        $this->parttitel($judul,$icao,5);
        foreach ($sub as $cod) {
            if ($cod->id == 96 || $cod->id == 236) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            if ($cod->id >= 220 &&  $cod->id <=225) {
                $this->getcontent($cont,$cod->id,$cod->item,$icao);
            }
            
            $pagecurr=$this->fpdf->PageNo();
        }
        if ( $pagecurr ==$this->fpdf->PageNo()){
            $this->fpdf->ln($this->ln);
        }

    }
    private function ad218($judul,$sub,$comm,$tbl,$part,$icao)
    {
        $np=$this->fpdf->getY();
        $newpage=$np;
        $rht=0;
        if ($newpage > $this->btshal){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
        }
        $this->parttitel($judul,$icao,20);
        $this->fpdf->ln(2);
        $this->CreateTablerwy($icao,$tbl,'comm',$comm,10);
        $this->CreateTablerwy($icao,$tbl,'comm1',$comm,10);

        // $this->fpdf->ln($this->ln);
    }
    private function ad219($judul,$sub,$nav,$ch,$tbl,$part,$icao)
    {
        $np=$this->fpdf->getY();
        $newpage=$np+50;
        $rht=0;
        if ($newpage > $this->btshal){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
        }
        $this->parttitel($judul,$icao,46);
        $this->fpdf->ln(2);
        $this->CreateTablerwy($icao,$tbl,'navaid',$nav,46);
        $this->CreateTablerwy($icao,$tbl,'navaid1',$nav,54);

        // $this->fpdf->ln($this->ln);
    }
    private function ad220($judul,$cont,$part,$icao,$gen=null)
    {
        // dd($gen);
        $np=$this->fpdf->getY();
        $newpage=$np+10;
        $rht=0;
        if ($newpage > $this->btshal-2){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
            $this->fpdf->setX($this->lm);
        }
        $this->parttitel($judul,$icao,0);
        $tb= $this->fpdf->GetX();
        $this->fpdf->ln($this->ln);
        $ada=false;
        foreach ($cont as $cod) {
            if ($cod->category_id == $part) {
                $ada=true;
                switch ($cod->tab) {
                    case '0':
                        // $this->fpdf->Cell(0);
                        break;
                    case '30':
                        // dd($cod->content);
                        $this->fpdf->Cell(5);
                        break;
                    case '60':
                        $this->fpdf->Cell(10);
                        break;
                    case '90':
                        $this->fpdf->Cell(15);
                        break;

                    default:
                        # code...
                        break;
                }
                $np=$this->fpdf->getY();
                $newpage=$np;
                $rht=0;
                $tbg=explode("\n",$this->fpdf->WordWrap($cod->content,125));
                $t=(count($tbg)) * 4;
                // dd($tbg,$t);
                if ($newpage+$t > $this->btshal-2){
                    $this->AipFooter();
                    $this->fpdf->AddPage();
                    $this->Watermark($this->draft);
                    if ($this->fpdf->PageNo()%2==0){
                        $this->lm=15;$this->rm=20;
                        $rht=0;
                    }else{
                        $rht=0;
                        $this->lm=20;$this->rm=15;
                    }
                    $this->fpdf->SetAutoPageBreak(true,0);
                    $this->fpdf->SetRightMargin($this->rm);
                    $this->fpdf->SetLeftMargin($this->lm);
                    $this->fpdf->SetTopMargin($this->head);
                    $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                    $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
                    $this->fpdf->ln($this->ln);
                    $this->fpdf->setX($this->lm);
                }
                if ($part==114){
                    $content = iconv('UTF-8', 'windows-1252', $cod->content);
                    $this->fpdf->Cell(4,4,'- ',0);
                    $this->fpdf->MultiCell(0,4,$content,0);
                }else{

                    $content = iconv('UTF-8', 'windows-1252', $cod->content);
                    $this->fpdf->MultiCell(0,4,$content,0);
                }
                // $this->fpdf->ln($this->ln);
            }
        }
        if ($ada==false){
            $this->fpdf->SetFont('Arial','I',9);
            $this->fpdf->MultiCell(0,4,'Reserved',0,'C');
            $this->fpdf->SetFont('Arial','',9);
        }
        $this->fpdf->ln($this->ln);

    }

    private function ad224($judul,$cont,$part,$icao,$gen=null)
    {
        // dd($gen);
        $np=$this->fpdf->getY();
        $newpage=$np+10;
        $rht=0;
        if ($newpage > $this->btshal-2){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
            $this->fpdf->setX($this->lm);
        }
        $this->parttitel($judul,$icao,0);
        $tb= $this->fpdf->GetX();
        $this->fpdf->ln($this->ln);
        $ada=false;
        foreach ($cont as $cod) {
            // dd($cod);
            $this->fpdf->Cell(10);
            $ada=true;
            $np=$this->fpdf->getY();
            $newpage=$np;
            $rht=0;
                // $tbg=explode("\n",$this->fpdf->WordWrap($cod->chart_name,115));
                // $t=(count($tbg)) * 4;
                // dd($tbg,$t);
            if ($newpage > $this->btshal-2){
                $this->AipFooter();
                $this->fpdf->AddPage();
                $this->Watermark($this->draft);
                if ($this->fpdf->PageNo()%2==0){
                    $this->lm=15;$this->rm=20;
                    $rht=0;
                }else{
                    $rht=0;
                    $this->lm=20;$this->rm=15;
                }
                $this->fpdf->SetAutoPageBreak(true,0);
                $this->fpdf->SetRightMargin($this->rm);
                $this->fpdf->SetLeftMargin($this->lm);
                $this->fpdf->SetTopMargin($this->head);
                $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
                $this->fpdf->ln($this->ln);
                $this->fpdf->setX($this->lm);
                $this->fpdf->Cell(10);
            }
            
                $content = iconv('UTF-8', 'windows-1252', $cod->chart_name);
                if (strpos($content, 'CODING') == false){
                    $this->fpdf->Cell(4,4,'- ',0);
                    $this->fpdf->MultiCell(0,4,$content,0);

                }else{
                    $this->fpdf->setX($this->lm);
                    // $this->fpdf->Cell(10);
                }
                
                // $this->fpdf->ln($this->ln);
            
        }
        if ($ada==false){
            $this->fpdf->SetFont('Arial','I',9);
            $this->fpdf->MultiCell(0,4,'Reserved',0,'C');
            $this->fpdf->SetFont('Arial','',9);
        }
        $this->fpdf->ln($this->ln);

    }


    function Watermark($text)
    {
        // dd($text);
        $this->fpdf->SetFont('Arial','B',50);
        $this->fpdf->SetTextColor(255,192,203);
        $this->fpdf->RotatedText(60,130,$text,45);
        $this->fpdf->SetTextColor(0,0,0);
    }
    Private function ArrowLineDraw($valTxt,$pdfX,$pdfY,$DrawLine, $ColumW,$Ypos= 3){
        if ($DrawLine == True){
            $arra =public_path("/images/Enr/ArrLine.jpg");

            // $arra = scandir($path,3);
            // ArrLine.jpg
            // dd($arra);
            // $arra ="URL::to('/')/images/Enr/ArrLine.jpg";

            $posarrw= ($this->fpdf->GetStringWidth($valTxt) / 2) + 1;
            $this->fpdf->Image($arra,10,10,-300);
            $this->fpdf->SetXY($pdfX, $pdfY);
        }
    }

    public function vertikalline($Y1,$Y2){
        // dd($Y1,$Y2);
        $XXLine=$this->lm + 126.5;
        $this->fpdf->SetLineWidth(0.4);
        $this->fpdf->Line($XXLine, $Y1, $XXLine, $Y2);
        $this->fpdf->SetLineWidth(0);

    }
    function AipHeader($icao,$rgt,$bold=true,$fontsize=11)
    {
        $rgt=0;
        // echo $this->fpdf->GetPageWidth();
        // var_dump($this->lm);
        $bld='B';
        if ($bold==false){
            $bld='';
        }
        $this->fpdf->SetFont('Arial',$bld,$fontsize);
        // Move to the right
        $this->fpdf->setX($this->lm);
        $this->fpdf->setY($this->head);

        $this->fpdf->Cell(0,-5,$this->header,0,0,'L');
        $this->fpdf->Cell($rgt,-5,$icao,0,0,'R');
       
        $this->fpdf->Line($this->lm, $this->head,$this->lm+ $this->lebararea, $this->head);
        $this->fpdf->SetFont('Arial','',9);
        $posY=$this->fpdf->GetY();
        // $this->AipFooter($posY);

    }

    function AipChartHeader($arpt,$chart,$page)
    {
        $rgt=0;
        $this->fpdf->SetFont('Arial','',8);
        // Move to the right
        $this->fpdf->setX($this->lm);
        $this->fpdf->setY($this->head);
        // dd($chart);
        $hgt=4;
        $hal=$this->fpdf->PageNo()+1;
        $ttl=substr($page,0,strlen($page)-1).$hal;
        $procname=$chart->nav.' RWY '.$chart->rwy;
        $cate='';
        $this->fpdf->Cell(0,-10,$ttl,0,0,'R');
        $this->fpdf->ln( $hgt);
            switch ($chart->chart_type) {
                case '45':
                    $ttl1='INSTRUMENT APPROACH';
                    $ttl2='CHART - ICAO';
                    $cate=$chart->cat;
                    $this->fpdf->Cell(0,-10,$arpt->city_name.'/',0,0,'R');
                    $this->fpdf->ln( $hgt);
                    $this->fpdf->Cell(0,-10,$this->header,0,0,'L');
                    $this->fpdf->Cell(0,-10,ucwords(strtolower($arpt->arpt_name)),0,0,'R');
                    $this->fpdf->ln( $hgt);
                    $this->fpdf->SetFont('Arial','B',8);
                    $this->fpdf->Cell(0,-10,$ttl1,0,0,'L');
                    $this->fpdf->SetFont('Arial','',8);
                    $this->fpdf->Cell(0,-10, $procname,0,0,'R');
                    $this->fpdf->ln( $hgt);
                    $this->fpdf->SetFont('Arial','B',8);
                    $this->fpdf->Cell(40,-10,$ttl2,0,0,'L');
                    $this->fpdf->SetFont('Arial','',8);
                    $this->fpdf->Cell(40,-10, 'AD ELEV : '.$arpt->elev,0,0,'C');
                    $this->fpdf->Cell(0,-10, $cate,0,0,'R');
                    break;
                case '46':
                    $ttl1='STANDARD DEPARTURE';
                    $ttl2='CHART - INSTRUMENT (SID) - ICAO';
                    $this->fpdf->Cell(0,-10,$this->header,0,0,'L');
                    $this->fpdf->Cell(0,-10,$arpt->city_name.'/',0,0,'R');
                    $this->fpdf->ln( $hgt);
                    $this->fpdf->SetFont('Arial','B',8);
                    $this->fpdf->Cell(0,-10,$ttl1,0,0,'L');
                    $this->fpdf->SetFont('Arial','',8);
                    $this->fpdf->Cell(0,-10,ucwords(strtolower($arpt->arpt_name)),0,0,'R');
                    $this->fpdf->ln( $hgt);
                    $this->fpdf->SetFont('Arial','B',8);
                    $this->fpdf->Cell(45,-10,$ttl2,0,0,'L');
                    $this->fpdf->SetFont('Arial','',8);
                    $this->fpdf->Cell(40,-10, 'AD ELEV : '.$arpt->elev,0,0,'C');
                    $this->fpdf->Cell(0,-10, $procname,0,0,'R');
                    break;
                case '47':
                    $ttl1='STANDARD ARRIVAL';
                    $ttl2='CHART - INSTRUMENT (STAR) - ICAO';
                    $this->fpdf->Cell(0,-10,$this->header,0,0,'L');
                    $this->fpdf->Cell(0,-10,$arpt->city_name.'/',0,0,'R');
                    $this->fpdf->ln( $hgt);
                    $this->fpdf->SetFont('Arial','B',8);
                    $this->fpdf->Cell(0,-10,$ttl1,0,0,'L');
                    $this->fpdf->SetFont('Arial','',8);
                    $this->fpdf->Cell(0,-10,ucwords(strtolower($arpt->arpt_name)),0,0,'R');
                    $this->fpdf->ln( $hgt);
                    $this->fpdf->SetFont('Arial','B',8);
                    $this->fpdf->Cell(45,-10,$ttl2,0,0,'L');
                    $this->fpdf->SetFont('Arial','',8);
                    $this->fpdf->Cell(40,-10, 'AD ELEV : '.$arpt->elev,0,0,'C');
                    $this->fpdf->Cell(0,-10, $procname,0,0,'R');
                break;
                }

            $py=$this->fpdf->getY()-3;
            $this->fpdf->Line($this->lm,$py,$this->lm+$this->lebararea,$py);
            $this->fpdf->ln(1);
            $this->fpdf->SetFont('Arial','',7);
            $posY=$this->fpdf->GetY();
        // $this->AipFooter($posY);

    }
    function AipFooter()
    {
        // Position at 1.5 cm from bottom
        // var_dump($this->lm,$this->lebararea);
        $this->fpdf->SetY(195);
        $this->fpdf->Line($this->lm, $this->fpdf->GetY(),$this->lm+ $this->lebararea, $this->fpdf->GetY());
        $this->fpdf->SetFont('Arial','',9);
        if ($this->fpdf->PageNo()%2==0){
            $this->lm=15;$this->rm=20;
            $rht=0;
            $rht1=1;
        }else{
            $rht=0;
            $rht1=0;
            $this->lm=20;$this->rm=15;
        }
        // Page number
        $this->fpdf->Cell(0,4,$this->footer,0,0,'L');
        $this->fpdf->Cell($rht,4,$this->src,0,0,'R');
        $this->fpdf->ln($this->ln);
        $this->fpdf->Cell(0);
        // $this->fpdf->Cell(10);
        // $this->fpdf->Cell($rht,4, $this->pubdate,0,0,'R');
        // $this->fpdf->ln($this->ln);
        // $this->fpdf->Cell(0);
        // $this->fpdf->Cell(10);
        $this->fpdf->Cell($rht,4, $this->effdate,0,0,'R');
        // $this->fpdf->SetY($posY);
    }

    function AipFooterCodingTable()
    {
        // Position at 1.5 cm from bottom
        $this->rm=5; $this->lm=13;
        $this->lebararea=$this->lebar - ($this->rm + $this->lm);
        // dd($this->lebar , $this->rm , $this->lm, $this->lebararea);
        $this->fpdf->SetY(195);
        $this->fpdf->Line($this->lm, $this->fpdf->GetY(),$this->lm+$this->lebararea, $this->fpdf->GetY());
        $this->fpdf->SetFont('Arial','',7);
        if ($this->fpdf->PageNo()%2==0){
            $rht=0;
            $rht1=1;
        }else{
            $rht=0;
            $rht1=0;
        }
        // Page number
        $this->fpdf->Cell(0,3.5,$this->footer,0,0,'L');
        $this->fpdf->Cell(0,3.5,$this->src,0,0,'R');
        $this->fpdf->ln($this->ln);
        $this->fpdf->Cell(0);
      
        $this->fpdf->Cell(0,3.5, $this->effdate,0,0,'R');
        // $this->fpdf->SetY($posY);
    }
    public function CreateTableObs($tbl,$id,$jdl=null,$obs,$icao)
    {

        $this->fpdf->ln(1);
        $this->fpdf->Rect($this->fpdf->GetX(),  $this->fpdf->GetY(), $this->lebararea, 8);
        $this->fpdf->ln($this->ln);
        $this->fpdf->Cell(0,0,$jdl,0,0,'C');
        $this->fpdf->ln($this->ln);
        $Xawal='';$b=0;$d=0;
        $alpha =['a', 'b', 'c', 'd', 'e', 'f'];
        $Yawal = 0;$i=0;
        foreach ($tbl as $val) {
            // dd($val->chart,$id);
            if ( $val->chart == $id ) {
                $a = ($val->col_seq/136) * $this->lebararea;
                // console.log('lebar normal ',cod.lebar,a)
                if ( $Xawal == '' ) {
                    $Xawal = $a;
                } else {
                    $Xawal =$Xawal.','.$a;
                }
                $ttl=$val->t1;
                if ( $val->t2 !== null ) {
                    $ttl =$ttl.' '.str_replace("#",",",$val->t2);
                }
                if ( $val->t3 !== null ) {
                    $ttl = $ttl.' '.str_replace("#",",",$val->t3);
                }
                if ( $val->t4 !== null ) {
                    $ttl = $ttl.' '.str_replace("#",",",$val->t4);
                }
                if ( $val->t5 !== null ) {
                    $ttl = $ttl.' '.str_replace("#",",",$val->t5);
                }
                if ( $val->t6 !== null ) {
                    $ttl = $ttl.' '.str_replace("#",",",$val->t6);
                }
                str_replace("#",",",$ttl);
                // $header=$val->t1.' '.$val->t2.' '.$val->t3.' '.$val->t4.' '.$val->t5.' '.$val->t6;
                $pX=$this->fpdf->GetX();
                $pY=$this->fpdf->GetY();
                $b +=$a;
                if ($this->fpdf->GetStringWidth($ttl) + 2 > $a){
                    $c=5;
                }else{
                    $c=10;
                }
                $this->fpdf->Rect($pX,$pY, $a, 10);
                $this->fpdf->Rect($pX,$pY+10, $a, 5);
                $this->fpdf->MultiCell($a,$c,$ttl,0,'C');
                // $this->fpdf->Cell($a);
                $this->fpdf->MultiCell($b+$d,5,$alpha[$i],0,'C');
                $this->fpdf->SetX($pX);
                $this->fpdf->SetY($pY);
                $this->fpdf->ln(10);
                $d +=$a;
                $this->fpdf->SetX($pX);
                $this->fpdf->SetY($pY);
                $this->fpdf->Cell($b);
                $i++;
                // $Yawal=$this->fpdf->GetY();
            }
        }

        // dd($obs,count($obs));
        if (count($obs)==0){
            $obs[] =array("NIL", "NIL", "NIL", "NIL", "NIL", "To be surveyed","U");
        }
        // var_dump(count($obs));
        $ar=explode(",",$Xawal);
            // dd($obs,$ar);
            $this->fpdf->ln(17);
            unset($Yawal);
            $Yawal=$this->fpdf->GetY();
            $Yy=$this->fpdf->GetY()-2;
            $Y2=0;
            $tb=0;
            $crd='NIL';
        foreach ($obs as $o) {
            $newpage=$Yawal;
            $rht=0;
            $line=true;$t=0;
            $tbg=explode("\n",$this->fpdf->WordWrap($o[5],$ar[5]));
            $t=(count($tbg)) * 4;
            // dd($tbg);

            if ($newpage+$t > 185){
                $count=count($ar);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
                for($i = 0; $i < $count; $i++ ) {
                    $this->fpdf->Line($xx,$Yy,$xx,$Y2);
                    $xx += $ar[$i];
                }
                $this->fpdf->Line($xx,$Yy,$xx,$Y2);
                $this->fpdf->Line($x1,$Y2,$xx,$Y2);
                $this->fpdf->ln($this->ln);
                $this->AipFooter();
                $this->fpdf->AddPage();
                $this->Watermark($this->draft);
                if ($this->fpdf->PageNo()%2==0){
                    $this->lm=15;$this->rm=20;
                    $rht=5;
                }else{
                    $rht=0;
                    $this->lm=20;$this->rm=15;
                }
                $this->fpdf->SetAutoPageBreak(true,0);
                $this->fpdf->SetRightMargin($this->rm);
                $this->fpdf->SetLeftMargin($this->lm);
                $this->fpdf->SetTopMargin($this->head);
                $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
                $this->fpdf->ln($this->ln);
                $Yawal=$this->fpdf->GetY();
                $Y2=$Yawal;
                // $line=false;

                $this->fpdf->Line($this->lm,$Y2-2,$this->lm+$this->lebararea,$Y2-2);
                $Yawal=$this->fpdf->GetY();
                $Yy=$Yawal-2;
            }
            if ($o[2] !== 'NIL'){
                $cord = toWgs($o[2]->coordinates[0],'LON');
                $cord1 = toWgs($o[2]->coordinates[1],'LAT');
                // dd($o[2]->coordinates[1].' '.$o[2]->coordinates[0],$cord1.' '.$cord);
                $crd=$cord1[0]['IAC'].' '.$cord[0]['IAC'];
                // dd($crd);
            }
            // $cord=$o[2]->coordinates[1].' '.$o[2]->coordinates[0];
            $this->fpdf->SetY($Yawal);
            $tb=$ar[0];
            $this->fpdf->MultiCell($ar[0],$this->ln,$o[0],0,'C');
            $this->fpdf->SetY($Yawal);
            $this->fpdf->Cell($tb);
            $this->fpdf->MultiCell($ar[1],$this->ln,$o[1],0,'C');
            $this->fpdf->SetY($Yawal);
            $tb +=$ar[1];
            $this->fpdf->Cell($tb);
            $this->fpdf->MultiCell($ar[2],$this->ln,$crd,0,'C');
            $tb +=$ar[2];
            $this->fpdf->SetY($Yawal);
            $this->fpdf->Cell($tb);
            $this->fpdf->MultiCell($ar[3],$this->ln,$o[3],0,'C');
            $tb +=$ar[3];
            $this->fpdf->SetY($Yawal);
            $this->fpdf->Cell($tb);
            $this->fpdf->MultiCell($ar[4],$this->ln,$o[4],0,'C');
            $tb +=$ar[4];
            $this->fpdf->SetY($Yawal);
            $this->fpdf->Cell($tb);

            



            $this->fpdf->MultiCell($ar[5],$this->ln,$o[5],0,'C');
            $this->fpdf->ln($this->ln);
            if ($o[6] !=='U'){
                $this->vertikalline($Yawal,$this->fpdf->GetY());
            }
            $Yawal=$this->fpdf->GetY();
            $Y2=$Yawal;


        }
        if ($line==true){
            $count=count($ar);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
            // var_dump($xx,$Yy,$xx,$Y2);
            for($i = 0; $i < $count; $i++ ) {
                $this->fpdf->Line($xx,$Yy,$xx,$Y2);
                $xx += $ar[$i];
            }
            $this->fpdf->Line($xx,$Yy,$xx,$Y2);
            $this->fpdf->Line($x1,$Y2,$xx,$Y2);
        }
        $this->fpdf->ln($this->ln);
    }

    public function CreateTablerwy($icao,$tbl,$id,$rwy,$tggi)
    {
        $np=$this->fpdf->getY();
        // if ($id=='rwy3'){
            $newpage=$np+$tggi;
        // }else{
        //     $newpage=$np+$tggi+10;
        // }
        $rht=0;
        if ($newpage > 180){
            $this->AipFooter();
            $this->fpdf->AddPage();
            $this->Watermark($this->draft);
            if ($this->fpdf->PageNo()%2==0){
                $this->lm=15;$this->rm=20;
                $rht=5;
            }else{
                $rht=0;
                $this->lm=20;$this->rm=15;
            }
            $this->fpdf->SetAutoPageBreak(true,0);
            $this->fpdf->SetRightMargin($this->rm);
            $this->fpdf->SetLeftMargin($this->lm);
            $this->fpdf->SetTopMargin($this->head);
            $this->lebararea=$this->lebar - ($this->rm + $this->lm);
            $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
            $this->fpdf->ln($this->ln);
        }
        $Xawal='';$b=0;$d=0;
        switch ($id) {
            case 'rwy1':
            case 'rwy4':
                $alpha =['1', '2', '3', '4', '5'];
                break;
            case 'rwy2':
            case 'rwy5':
                $alpha =['6', '7', '8', '9', '10'];
                break;
            case 'rwy6':
                $alpha =['11', '12', '13', '14'];
                break;
            case 'rwy3':
                $alpha =['1', '2', '3', '4', '5','6'];
                break;
            case 'comm':
            case 'navaid':
                $alpha =['1', '2', '3', '4'];
                break;
            case 'comm1':
                $alpha =['5','6', '7'];
                break;
            case 'navaid1':
                $alpha =['5','6','7','8'];
                break;
            default:
                # code...
                break;
        }

        $Yawal = 0;$i=0;
        foreach ($tbl as $val) {
            // dd($val->chart,$id);
            if ( $val->chart == $id ) {
                // var_dump( $this->lebararea);
                $a = ($val->col_seq/136) * $this->lebararea;
                // console.log('lebar normal ',cod.lebar,a)
                if ( $Xawal == '' ) {
                    $Xawal = $a;
                } else {
                    $Xawal =$Xawal.','.$a;
                }
                $ttl=$val->t1;
                if ( $val->t2 !== null ) {
                    $ttl =$ttl.' '.str_replace("#",",",$val->t2);
                }
                if ( $val->t3 !== null ) {
                    $ttl = $ttl.' '.str_replace("#",",",$val->t3);
                }
                if ( $val->t4 !== null ) {
                    $ttl = $ttl.' '.str_replace("#",",",$val->t4);
                }
                if ( $val->t5 !== null ) {
                    $ttl = $ttl.' '.str_replace("#",",",$val->t5);
                }
                if ( $val->t6 !== null ) {
                    $ttl = $ttl.' '.str_replace("#",",",$val->t6);
                }
                str_replace("#",",",$ttl);
                // $header=$val->t1.' '.$val->t2.' '.$val->t3.' '.$val->t4.' '.$val->t5.' '.$val->t6;
                $pX=$this->fpdf->GetX();
                $pY=$this->fpdf->GetY();
                $b +=$a;
                if ($this->fpdf->GetStringWidth($ttl) + 2 > $a){
                    $c=4;
                }else{
                    $c=$tggi;
                }
                $this->fpdf->Rect($pX,$pY, $a, $tggi);
                $this->fpdf->Rect($pX,$pY+$tggi, $a, 5);
                $this->fpdf->MultiCell($a,$c,$ttl,0,'C');
                $this->fpdf->SetY($pY+$tggi);
                $this->fpdf->MultiCell($b+$d,5,$alpha[$i],0,'C');
                $this->fpdf->SetX($pX);
                $this->fpdf->SetY($pY);
                $this->fpdf->ln($tggi);
                $d +=$a;
                $this->fpdf->SetX($pX);
                $this->fpdf->SetY($pY);
                $this->fpdf->Cell($b);
                $i++;
                // $Yawal=$this->fpdf->GetY();
            }
        }

        // dd($rwy);
        $ar=explode(",",$Xawal);
            // dd($obs,$ar);
            $this->fpdf->ln($tggi+7);
            $Yawal=$this->fpdf->GetY();
            $Yy=$this->fpdf->GetY()-2;
            $Y2=0;
            $tb=0;
        if ($id=='rwy1'){
            if (count($rwy)==0){
                $thr1[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                $thr2[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
            }else{

                foreach ($rwy as $o) {
                    $crd='NIL';
                    if ($o->physicals[0]->geom==null){
                        $crd='NIL';
                    }else{
                        if ($o->physicals[0]->geom->coordinates[0]==0 && $o->physicals[0]->geom->coordinates[1]==0){
                            $crd='NIL';
                        }else{
                            $llat=$o->physicals[0]->geom->coordinates[1];
                            $llon=$o->physicals[0]->geom->coordinates[0];
                            $cord = toWgs($llon,'LON');
                            $cord1 = toWgs($llat,'LAT');
                            $hi=GeoHi($cord1[0]['Database'], $cord[0]['Database']);
                            // $hasil='Geoid='.round($hi,2).'m/'.round(($hi * 3.28084),2).'ft';
                            $hasil='Geoid='.round(($hi * 3.28084),2).'ft';

                            $crd=$cord1[0]['NONFIR'].' '.$cord[0]['NONFIR'].' '.$hasil;
                        }
                    }
                    // dd($cord,$cord1);
                    $thr1[] =array($o->thr_low, $o->physicals[0]->true_brg, $o->length.' x '.$o->width, $o->pcn.' '.$o->definition, $crd,$o->status);
                    if ($o->physicals[0]->geom==null){
                        $crd='NIL';
                    }else{
                        if ($o->physicals[0]->geom->coordinates[0]==0 && $o->physicals[0]->geom->coordinates[1]==0){
                            $crd='NIL';
                        }else{
                            $cord = toWgs($o->physicals[1]->geom->coordinates[0],'LON');
                            $cord1 = toWgs($o->physicals[1]->geom->coordinates[1],'LAT');
                            $hi=GeoHi($cord1[0]['Database'], $cord[0]['Database']);
                            // $hasil='Geoid='.round($hi,2).'m/'.round(($hi * 3.28084),2).'ft';
                            $hasil='Geoid='.round(($hi * 3.28084),2).'ft';
                            $crd=$cord1[0]['NONFIR'].' '.$cord[0]['NONFIR'].' '.$hasil;
                        }

                    }
                    $thr2[] =array($o->thr_high, $o->physicals[1]->true_brg, $o->length.' x '.$o->width, $o->pcn.' '.$o->definition, $crd,$o->status);
                }
            }
            $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
            if ($Y2 < $Yy){
                $Yy=$Y2;
                $Y2 +=1;
            }
            $Y2= $this->isirwy($thr2,$Y2,$ar,$Yy,$icao,true);
           
        }
        if ($id=='rwy2'){
            if (count($rwy)==0){
                $thr1[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                $thr2[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
            }else{
                foreach ($rwy as $o) {
                    $no=0;

                    if ($o->physicals[$no]->swy_length==null || $o->physicals[$no]->swy_length==''){
                        $swy='NIL';
                    }else{
                        $swy=$o->physicals[$no]->swy_length.' x '.$o->width;
                    }
                    if ($o->physicals[$no]->cwy_length==null || $o->physicals[$no]->cwy_length==''){
                        $cwy='NIL';
                    }else{
                        $cwy=$o->physicals[$no]->cwy_length.' x '.$o->physicals[$no]->cwy_width;
                    }
                    if ($o->physicals[$no]->slope==null || $o->physicals[$no]->slope==''){
                        $slope='NIL';
                    }else{
                        $slope=$o->physicals[$no]->slope;
                    }
                    if ($o->physicals[$no]->thr_elev==null || $o->physicals[$no]->thr_elev==''){
                        $elev='NIL';
                    }else{
                        $elev=$o->physicals[$no]->thr_elev.'ft';
                    }
                    if ($o->strip_l==null || $o->strip_l==''){
                        $strip='NIL';
                    }else{
                        $strip=$o->strip_l.' x '.$o->strip_w;
                    }
                    $thr1[] =array($elev, $slope, $swy, $cwy, $strip,$o->physicals[$no]->status);
                    $no=1;
                    if ($o->physicals[$no]->swy_length==null || $o->physicals[$no]->swy_length==''){
                        $swy='NIL';
                    }else{
                        $swy=$o->physicals[$no]->swy_length.' x '.$o->width;
                    }
                    if ($o->physicals[$no]->cwy_length==null || $o->physicals[$no]->cwy_length==''){
                        $cwy='NIL';
                    }else{
                        $cwy=$o->physicals[$no]->cwy_length.' x '.$o->physicals[$no]->cwy_width;
                    }
                    if ($o->physicals[$no]->slope==null || $o->physicals[$no]->slope==''){
                        $slope='NIL';
                    }else{
                        $slope=$o->physicals[$no]->slope;
                    }
                    if ($o->physicals[$no]->thr_elev==null || $o->physicals[$no]->thr_elev==''){
                        $elev='NIL';
                    }else{
                        $elev=$o->physicals[$no]->thr_elev.'ft';
                    }

                    $thr2[] =array($elev, $slope, $swy, $cwy, $strip,$o->physicals[$no]->status);

                }
            }
            $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
            if ($Y2 < $Yy){
                $Yy=$Y2;
                $Y2 +=1;
            }
            $Y2= $this->isirwy($thr2,$Y2,$ar,$Yy,$icao,true);
            
        }
        if ($id=='rwy3'){
            if (count($rwy)==0){
                $thr1[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL', 'NIL','U');
                $thr2[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL', 'NIL','U');
            }else{
                foreach ($rwy as $o) {
                    $no=0;
                    $thr1[] =array($o->thr_low, $o->physicals[$no]->tora, $o->physicals[$no]->toda, $o->physicals[$no]->asda, $o->physicals[$no]->lda,'NIL',$o->physicals[$no]->status);
                    $no=1;
                    $thr2[] =array($o->thr_high, $o->physicals[$no]->tora, $o->physicals[$no]->toda, $o->physicals[$no]->asda, $o->physicals[$no]->lda,'NIL',$o->physicals[$no]->status);

                }
            }
            $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
            if ($Y2 < $Yy){
                $Yy=$Y2;
                $Y2 +=1;
            }
            $Y2= $this->isirwy($thr2,$Y2,$ar,$Yy,$icao,true);
           
        }
        if ($id=='rwy4'){
            // dd($rwy);
            if (count($rwy)==0){
                $thr1[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                $thr2[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
            }else{

                foreach ($rwy as $o) {
                    $crcount=count($o->physicals);
                    if ($crcount == 0){
                        // dd($o);
                        $thr1[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                        $thr2[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                    }else{
                        // dd(count($o->physicals));

                        $no=0;
                        // dd(count($o->physicals),count($o->physicals[$no]->lighting));
                        if (count($o->physicals[$no]->lighting)==0){
                            $thr1[] =array($o->thr_low, 'NIL', 'NIL','NIL', 'NIL','U');
                        }else{

                            $lgt=$o->physicals[$no]->lighting[0];
                            if ($lgt->apch_lgt_type_len==null || $lgt->apch_lgt_type_len==''){
                                $len='NIL';
                            }else{
                                $len=$lgt->apch_lgt_type_len;
                            }
                            if ($lgt->thr_lgt_clr_wbar==null || $lgt->thr_lgt_clr_wbar==''){
                                $wbar='NIL';
                            }else{
                                $wbar=$lgt->thr_lgt_clr_wbar;
                            }
                            if ($lgt->vasis_meht_papi==null || $lgt->vasis_meht_papi==''){
                                $papi='NIL';
                            }else{
                                $papi=$lgt->vasis_meht_papi;
                            }
                            if ($lgt->tdz_lgt_len==null || $lgt->tdz_lgt_len==''){
                                $tdz='NIL';
                            }else{
                                $tdz=$lgt->tdz_lgt_len;
                            }
                            $thr1[] =array($o->thr_low, $len, $wbar, $papi, $tdz,$lgt->status);
                        }

                        $no=1;
                        // $lgt=$o->physicals[$no]->lighting[0];

                        if (count($o->physicals[$no]->lighting)==0){
                            $thr2[] =array($o->thr_high, 'NIL', 'NIL','NIL', 'NIL','U');
                        }else{

                            $lgt=$o->physicals[$no]->lighting[0];
                            if ($lgt->apch_lgt_type_len==null || $lgt->apch_lgt_type_len==''){
                                $len='NIL';
                            }else{
                                $len=$lgt->apch_lgt_type_len;
                            }
                            if ($lgt->thr_lgt_clr_wbar==null || $lgt->thr_lgt_clr_wbar==''){
                                $wbar='NIL';
                            }else{
                                $wbar=$lgt->thr_lgt_clr_wbar;
                            }
                            if ($lgt->vasis_meht_papi==null || $lgt->vasis_meht_papi==''){
                                $papi='NIL';
                            }else{
                                $papi=$lgt->vasis_meht_papi;
                            }
                            if ($lgt->tdz_lgt_len==null || $lgt->tdz_lgt_len==''){
                                $tdz='NIL';
                            }else{
                                $tdz=$lgt->tdz_lgt_len;
                            }
                            $thr2[] =array($o->thr_high, $len, $wbar, $papi, $tdz,$lgt->status);
                        }
                    }

                }
            }
            // dd($thr1);
            $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
            if ($Y2 < $Yy){
                $Yy=$Y2;
                $Y2 +=1;
            }
            $Y2= $this->isirwy($thr2,$Y2,$ar,$Yy,$icao,true);
            
        }
        if ($id=='rwy5'){
            if (count($rwy)==0){
                $thr1[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                $thr2[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
            }else{
                foreach ($rwy as $o) {
                    $no=0;
                    // dd($o->physicals[$no]);
                    if (count($o->physicals)==0){
                        $thr1[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                    }else{
                        if (count($o->physicals[$no]->lighting)==0){
                            $thr1[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                        }else{
                            $lgt=$o->physicals[$no]->lighting[0];
                            if ($lgt->rwy_ctrln_lgt_length_spc_clr==null || $lgt->rwy_ctrln_lgt_length_spc_clr==''){
                                $len='NIL';
                            }else{
                                $len=$lgt->rwy_ctrln_lgt_length_spc_clr;
                            }
                            if ($lgt->rwy_edge_lgt_len_spc_clr==null || $lgt->rwy_edge_lgt_len_spc_clr==''){
                                $wbar='NIL';
                            }else{
                                $wbar=$lgt->rwy_edge_lgt_len_spc_clr;
                            }
                            if ($lgt->rwy_end_lgt_clr_wbar==null || $lgt->rwy_end_lgt_clr_wbar==''){
                                $papi='NIL';
                            }else{
                                $papi=$lgt->rwy_end_lgt_clr_wbar;
                            }
                            if ($lgt->swy_lgt_len_clr==null || $lgt->swy_lgt_len_clr==''){
                                $tdz='NIL';
                            }else{
                                $tdz=$lgt->swy_lgt_len_clr;
                            }
                            if ($lgt->remark==null || $lgt->remark==''){
                                $rem='NIL';
                            }else{
                                $rem=$lgt->remark;
                            }

                            $thr1[] =array($len, $wbar, $papi, $tdz,$rem,$lgt->status);
                        }
                        $no=1;
                        if (count($o->physicals[$no]->lighting)==0){
                            $thr2[] =array('NIL', 'NIL', 'NIL','NIL', 'NIL','U');
                        }else{
                            $lgt=$o->physicals[$no]->lighting[0];
                            if ($lgt->rwy_ctrln_lgt_length_spc_clr==null || $lgt->rwy_ctrln_lgt_length_spc_clr==''){
                                $len='NIL';
                            }else{
                                $len=$lgt->rwy_ctrln_lgt_length_spc_clr;
                            }
                            if ($lgt->rwy_edge_lgt_len_spc_clr==null || $lgt->rwy_edge_lgt_len_spc_clr==''){
                                $wbar='NIL';
                            }else{
                                $wbar=$lgt->rwy_edge_lgt_len_spc_clr;
                            }
                            if ($lgt->rwy_end_lgt_clr_wbar==null || $lgt->rwy_end_lgt_clr_wbar==''){
                                $papi='NIL';
                            }else{
                                $papi=$lgt->rwy_end_lgt_clr_wbar;
                            }
                            if ($lgt->swy_lgt_len_clr==null || $lgt->swy_lgt_len_clr==''){
                                $tdz='NIL';
                            }else{
                                $tdz=$lgt->swy_lgt_len_clr;
                            }
                            if ($lgt->remark==null || $lgt->remark==''){
                                $rem='NIL';
                            }else{
                                $rem=$lgt->remark;
                            }

                            $thr2[] =array($len, $wbar, $papi, $tdz,$rem,$lgt->status);
                        }
                    }

                }
            }
            $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
            if ($Y2 < $Yy){
                $Yy=$Y2+2;
                $Y2 +=1;
            }
            $Y2= $this->isirwy($thr2,$Y2,$ar,$Yy,$icao,true);
         
        }
        if ($id=='rwy6'){
            if (count($rwy)==0){
                $thr1[] =array('NIL', 'NIL', 'NIL','NIL','U');
                $thr2[] =array('NIL', 'NIL', 'NIL','NIL','U');
            }else{
                foreach ($rwy as $o) {
                    $no=0;

                    if ($o->physicals[$no]->resa_l==null || $o->physicals[$no]->resa_l==''){
                        $resa='NIL';
                    }else{
                        $resa=$o->physicals[$no]->resa_l.' x '.$o->physicals[$no]->resa_w;
                    }
                    if ($o->physicals[$no]->remarks=='' || $o->physicals[$no]->remarks==null){
                        $rem='NIL';
                    }else{
                        $rem=$o->physicals[$no]->remarks;
                    }
                    $thr1[] =array($resa, 'NIL','NIL', $rem,$o->physicals[$no]->status);

                    $no=1;
                    if ($o->physicals[$no]->resa_l==null || $o->physicals[$no]->resa_l==''){
                        $resa='NIL';
                    }else{
                        $resa=$o->physicals[$no]->resa_l.' x '.$o->physicals[$no]->resa_w;
                    }
                    if ($o->physicals[$no]->remarks=='' || $o->physicals[$no]->remarks==null){
                        $rem='NIL';
                    }else{
                        $rem=$o->physicals[$no]->remarks;
                    }

                    $thr2[] =array($resa, 'NIL','NIL',$rem,$o->physicals[$no]->status);
                }
            }
            $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
            if ($Y2 < $Yy){
                $Yy=$Y2;
                $Y2 +=1;
            }
            $Y2= $this->isirwy($thr2,$Y2,$ar,$Yy,$icao,true);
          
        }
        if ($id=='comm'){
            $comm=[];
            if (count($rwy)==0){
                $thr1[] =array('NIL', 'NIL', 'NIL','NIL','U');
                $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy-2,$icao);
                $Yawal=$Y2;
                
            }else{
                $crwy=count($rwy)-1;$ridx=0;
                $acomm= $this->fpdf->GetY();
                foreach ($rwy as $si=> $o) {
                    $ridx=$si;
                    switch($o->callsign[0]->types){
                        case "SSB":
                            $ctype='RADIO';
                            break;
                        case "AFI":
                            $ctype='AFIS';
                            break;
                        case "ATI":
                            $ctype='ATIS';
                            break;
                        default:
                            $ctype=$o->callsign[0]->types;
                            break;
                    }
                    $calls=$o->callsign[0]->call_sign;
                    $cs=$o->callsign[0];
                    $cnt=count($cs->segment);
                    $frq='';
                    for ($i=0;$i<$cnt;$i++){
                        $sg=$cs->segment[$i];
                        $secondary='';
                        if ($sg->level=='2'){
                            $secondary='(SRY)';
                        }
                        $fval=$this->Airspacefreq($sg->value[0]->freq,$sg->value[0]->unit).$secondary;
                        $fval=str_replace(' ','@',$fval);
                        if ($i==0){
                            $frq =$fval;
                        }else{
                            $frq = $frq.' '.$fval;
                            
                        }
                    }
                        $rem='NIL';
                    $ccm =array($ctype, $calls,$frq, $rem,$cs->status);
                    array_push($comm,$ccm);
                }
                $this->navcomm($comm,$acomm,$ar,$icao);
            }
        }
        if ($id=='comm1'){
            $comm=[];
            if (count($rwy)==0){
                $thr1[] =array('NIL','NIL','NIL','U');
                $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
               
                $Yawal=$Y2;
            }else{
                $crwy=count($rwy)-1;$ridx=0;$commrem='';
                $acomm= $this->fpdf->GetY();
                foreach ($rwy as $si=> $o) {
                    $ridx=$si;
                    // dd($o->callsign[0]->segment[0]);
                    $ctype='NIL';
                    $cs=$o->callsign[0];
                    $frq='NIL'; $sts_comm='U';$rem='';
                    if (!empty($cs->segment)){
                        $frq = $cs->segment[0]->opr_hrs;
                        if ($frq=='' || $frq==null){
                            $frq='NIL';
                        }
                        if ($cs->remarks==''){
                            $rem='NIL';
                        }else{
                            $rem=$cs->remarks;
                        }

                        $sts_comm=$cs->segment[0]->status;
                    }
                    $commrem=$cs->remarks;
                    $ccm=array($ctype,$frq, $rem,$sts_comm);
                    array_push($comm,$ccm);
                }
                $this->navcomm($comm,$acomm,$ar,$icao);
            }
        }
        if ($id=='navaid'){
            if (count($rwy)==0){
                $thr1[] =array('NIL','NIL','NIL','NIL','U');
                $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
                $Yawal=$Y2;
            }else{
                // $crwy=count($rwy)-1;$ridx=0;
                // $Yawal=$this->fpdf->GetY();
                $acomm= $this->fpdf->GetY();
                $comm=[];
                foreach ($rwy as $si=> $o) {
                    $ridx=$si;
                    $thr1=[];
                    if (count($o->navaid) > 0){
                        $cs=$o->navaid[0];
                        if ($cs->type !=='9'){
                            $ctype=$cs->definition;
                            if ($cs->type=='20'){
                                $nid=$cs->nav_name;
                                $freq='';
                            }else{
                                $nid=$cs->nav_ident;
                                $freq=$this->FreqFormat($cs->freq,$cs->type,'');
                                $epoch=date('2020-01-01');
                                $alt=0;
                                $mv1 = GetMagvar($cs->geom->coordinates[0], $cs->geom->coordinates[1], $epoch,$alt,);
                                // dd($mv1,$cs->geom->coordinates[1], $cs->geom->coordinates[0]);
                                $mgvar2 = $mv1->nav;
                                $ctype = $ctype.' '.$mgvar2;
                            }
                            $oh=$cs->opr_hrs;
                            // $thr1[] =array($ctype,$nid,$freq, $oh,$cs->status_vld);
                            $ccm=array($ctype,$nid,$freq, $oh,$cs->status_vld);
                            array_push($comm,$ccm);
                            
                        }
                    }else if (count($o->ils) > 0){
                        $cs=$o->ils[0];
                        // dd($cs);
                        if ($cs->thr !== null){
                            $ctype='ILS/LLZ RWY '.$cs->thr[0]->rwy_ident;
                        }else{
                            $ctype='ILS/LLZ';
                        }
                        $epoch=date('2020-01-01');
                        $alt=0;
                        $mv1 = GetMagvar($cs->geom->coordinates[0], $cs->geom->coordinates[1], $epoch,$alt,);
                        // dd($mv1,$cs->geom->coordinates[1], $cs->geom->coordinates[0]);
                        $mgvar2 = $mv1->nav;
                        $ctype = $ctype.' '.$mgvar2;
                        $nid=$cs->ils_ident;
                        $freq=$cs->freq.' MHz';
                        $oh=$cs->opr_hrs;
                        // $thr1[] =array($ctype,$nid,$freq, $oh,$cs->status);
                        $ccm=array($ctype,$nid,$freq, $oh,$cs->status);
                        array_push($comm,$ccm);
                        
                        if ($cs->thr !== null){
                            $ctype='GP RWY '.$cs->thr[0]->rwy_ident;
                        }else{
                            $ctype='GP';
                        }
                        $nid='';
                        $freq=$cs->gs_freq.' MHz';
                        // $thr1=[];
                        // $thr1[] =array($ctype,$nid,$freq, $oh,$cs->status);
                        // $this->n_page=false;
                        $ccm=array($ctype,$nid,$freq, $oh,$cs->status);
                        array_push($comm,$ccm);
                       
                        if (count($cs->navaid) !==0){
                            $cs=$o->ils[0];
                            // dd($cs);
                            if ($cs->thr !== null){
                                $ctype='T-DME RWY '.$cs->thr[0]->rwy_ident;
                            }else{
                                $ctype='T-DME';
                            }
                            $nid='';
                            $freq='CH-'.$cs->navaid[0]->channel;
                            // $thr1=[];
                            // $thr1[] =array($ctype,$nid,$freq, $oh,$cs->status);
                            $ccm=array($ctype,$nid,$freq, $oh,$cs->status);
                            array_push($comm,$ccm);
                            
                        }
                        if (count($cs->marker) !==0){
                            foreach ($cs->marker as $mkr){
                                $cs=$o->ils[0];
                            // dd($cs);
                                    if ($cs->thr !== null){
                                        $ctype=$mkr->mrkr_type.' RWY '.$cs->thr[0]->rwy_ident;
                                    }else{
                                        $ctype=$mkr->mrkr_type;
                                    }
                                $nid='';
                                $freq=$mkr->freq.' MHz';
                               
                                $ccm=array($ctype,$nid,$freq, $oh,$mkr->status);
                                array_push($comm,$ccm);
                                
                            }
                        }
                    }
                }
                $this->navcomm($comm,$acomm,$ar,$icao,true);
            }
           
        }
        if ($id=='navaid1'){
         
            if (count($rwy)==0){
                $thr1[] =array('NIL','NIL','NIL','NIL','U');
                $Y2= $this->isirwy($thr1,$Yawal,$ar,$Yy,$icao);
                // if  ($this->n_page==true){
                //     $this->n_page=false;
                //     $Yy=$Y2;
                // }
                $Yawal=$Y2;
            }else{
                $acomm= $this->fpdf->GetY();
                $comm=[];
                foreach ($rwy as $si=> $o) {
                    $ridx=$si;
                    $thr1=[];
                    if (count($o->navaid) > 0){
                        $cs=$o->navaid[0];
                        if ($cs->type !=='9'){
                            // dd($cs);
                            $cord = toWgs($cs->geom->coordinates[0],'LON');
                            $cord1 = toWgs($cs->geom->coordinates[1],'LAT');
                            $crd=$cord1[0]['NONFIR'].' '.$cord[0]['NONFIR'];
                            if ($cs->remarks==null || $cs->remarks==''){
                                $rem='NIL';
                            }else{
                                $rem= $cs->remarks;
                            }

                            $ccm=array($crd,'NIL','NIL',$rem,$cs->status_vld);
                            array_push($comm,$ccm);
                            
                        }
                    }else if (count($o->ils) > 0){
                        $cs=$o->ils[0];
                        // dd($cs);
                        $cord = toWgs($cs->geom->coordinates[0],'LON');
                        $cord1 = toWgs($cs->geom->coordinates[1],'LAT');
                        $crd=$cord1[0]['NONFIR'].' '.$cord[0]['NONFIR'];
                        if ($cs->remarks==null || $cs->remarks==''){
                            $rem='NIL';
                        }else{
                            $rem= $cs->remarks;
                        }
                        $thr1=[];
                        $thr1[] =array($crd,'NIL','NIL',$rem,$cs->status);
                        $this->n_page=false;
                        $ccm=array($crd,'NIL','NIL',$rem,$cs->status);
                        array_push($comm,$ccm);
                       
                        $cord = toWgs($cs->gs_geom->coordinates[0],'LON');
                        $cord1 = toWgs($cs->gs_geom->coordinates[1],'LAT');
                        $crd=$cord1[0]['NONFIR'].' '.$cord[0]['NONFIR'];
                        $rem='';
                        $thr1=[];
                        $thr1[] =array($crd,'NIL','NIL',$rem,$cs->status);
                        $this->n_page=false;
                        $ccm=array($crd,'NIL','NIL',$rem,$cs->status);
                        array_push($comm,$ccm);
                        
                        if (count($cs->navaid) !==0){
                            // dd($cs->navaid[0]);
                            $cord = toWgs($cs->navaid[0]->geom->coordinates[0],'LON');
                            $cord1 = toWgs($cs->navaid[0]->geom->coordinates[1],'LAT');
                            $crd=$cord1[0]['NONFIR'].' '.$cord[0]['NONFIR'];
                            if ($cs->navaid[0]->remarks==null || $cs->navaid[0]->remarks==''){
                                $rem='';
                            }else{
                                $rem= $cs->navaid[0]->remarks;
                            }
                            $thr1=[];
                            $thr1[] =array($crd,'NIL','NIL',$rem,$cs->status);
                            $this->n_page=false;
                            $ccm=array($crd,'NIL','NIL',$rem,$cs->status);
                            array_push($comm,$ccm);
                           
                        }
                        if (count($cs->marker) !==0){
                            foreach ($cs->marker as $mkr){
                                // dd( $mkr);
                                $cord = toWgs($mkr->geom->coordinates[0],'LON');
                                $cord1 = toWgs($mkr->geom->coordinates[1],'LAT');
                                $crd=$cord1[0]['NONFIR'].' '.$cord[0]['NONFIR'];
                                if ($mkr->remarks==null || $mkr->remarks==''){
                                    $rem='';
                                }else{
                                    $rem= $mkr->remarks;
                                }
                                $thr1=[];
                                $thr1[] =array($crd,'NIL','NIL',$rem,$mkr->status);
                                $this->n_page=false;
                                $ccm=array($crd,'NIL','NIL',$rem,$mkr->status);
                                array_push($comm,$ccm);
                                
                            }
                        }
                    }

                }
                $this->navcomm($comm,$acomm,$ar,$icao,false);
            }
            
        }
        // $this->fpdf->ln($this->ln);
    }
    function navcomm($thr,$Yawal,$ar,$icao,$nav=false){
        // $thr=$thr[0];
       
        unset($xxlineY); unset($comYline);
        $xxlineY=$this->fpdf->GetY();
        $comYline=$this->fpdf->GetY()-2;
       
        $this->fpdf->SetY($Yawal);
        // dd($thr);
        foreach ($thr as $key => $com) {
            $div=$this->ln/4;
            $space=0;$isi='';$jmllooping=0;$hbr=false;
            unset($count);$tb=0;
            $count=count($com);
            $this->fpdf->SetY($Yawal);
            $space=0; $space1=0;$spc=0;
            for($i = 0; $i < $count-1; $i++ ) {
                $jmllooping=$i;
                $tbg=explode("\n",$this->fpdf->WordWrap($com[$i],$ar[$i]));
                $ccn=count($tbg)<3;$nn=0;
                if ($nav==true){
                    $ccn=count($tbg)<2;
                    // $nn=5;
                }
                      // $this->n_page=false;
                    // var_dump(count($tbg),$com[$i]);
                    if ($ccn){
                        // $this->fpdf->SetTextColor(255,0,0);
                        $content = iconv('UTF-8', 'windows-1252',str_replace('@',' ',$com[$i]));
                        if ($nav==true){
                            $this->fpdf->Cell($ar[$i],$this->ln,$content,0,0,'C');
                            $this->fpdf->ln($this->ln);

                        }else{
                            $this->fpdf->MultiCell($ar[$i],$this->ln,$content,0,'C');
                        }
                        // $this->fpdf->SetTextColor(0,0,0);
                        $spc = count($tbg) * $this->ln;
                        if ($space1 <  $spc){
                            $space1 = $spc;
                        }
                    }else{
                        $xx= $this->fpdf->GetX();
                        for ($x=0; $x < count($tbg); $x++) { 
                            $comX=$this->fpdf->GetX()-$this->lm;
                            if ( $this->fpdf->GetY() > $this->btshal-$nn){
                                $this->buattable($ar,$comYline,true);
                                $this->loncatnewpage($icao);
                                $xx=$this->lm;$x1 =$xx +$this->lebararea ;
                                $this->fpdf->SetX($xx+$comX);
                                $comYline=$this->fpdf->GetY();
                                if (count($thr) > $key){
                                    $this->fpdf->Line($xx,$comYline,$x1,$comYline);
                                    $this->fpdf->ln($div);
                                }
                                $Yawal= $this->fpdf->GetY();
                                $xx=$xx+$comX;$space=0;
                                $this->fpdf->SetX($xx);
                            }
                            // $this->fpdf->SetTextColor(255,0,255);
                            $content = iconv('UTF-8', 'windows-1252',str_replace('@',' ',$tbg[$x]));
                            $this->fpdf->Cell($ar[$i],$this->ln,$content,0,0,'C');
                            $this->fpdf->ln($this->ln);
                            $this->fpdf->SetX($xx);
                            // $this->fpdf->SetTextColor(0,0,0);
                            if ( $space < $space +$this->ln){
                                $space +=$this->ln;
                            }
                        }
                        
                    }
                $this->fpdf->SetY($Yawal);
                $tb+=$ar[$i];
                $this->fpdf->Cell($tb);
            }
            $Yv=$this->fpdf->GetY();
            if ($com[$count-1] !== 'U'){
                // dd($thr,$xxlineY,$Yawal);
                $this->vertikalline($xxlineY,$Yv);
            }
            if ($space > $space1){
                $this->fpdf->ln($space+$div);
            }else{
                $this->fpdf->ln($space1+$div);
            }
            $horl= $this->fpdf->GetY();
            $xx=$this->lm;$x1 =$xx +$this->lebararea ;
            $this->fpdf->Line($xx,$horl,$x1,$horl);
            if ( $this->fpdf->GetY() > $this->btshal-5 || count($thr)-1 == $key){
                $this->buattable($ar,$comYline);
                if ( $this->fpdf->GetY() > $this->btshal-5){
                    $this->loncatnewpage($icao);
                    $div=0;
                }
                $comYline=$this->fpdf->GetY();
                $xx=$this->lm;$x1 =$xx +$this->lebararea ;
                if (count($thr)-1 > $key){
                    $this->fpdf->Line($xx,$comYline,$x1,$comYline);
                }
            }
            $this->fpdf->ln($div);

            $Yawal= $this->fpdf->GetY();

        }
        $this->fpdf->ln($this->ln);
    }
    private function buattable($arr,$Y1,$garisbawah=false){
        $Y2= $this->fpdf->GetY();
        $count=count($arr);$xx=$this->lm;$x1=$this->lm;
        for($i = 0; $i < $count; $i++ ) {
            $this->fpdf->Line($xx,$Y1,$xx,$Y2);
            $xx += $arr[$i];
        }
        $this->fpdf->Line($xx,$Y1,$xx,$Y2);
        if ($garisbawah==true){
            $this->fpdf->Line($x1,$Y2,$xx,$Y2);
        }

    }

    private function isiloop($arrisi,$col,$pX,$pY,$ar,$icao){
        if (!empty($arrisi)){
            unset($arj);
            $arj=count($ar)-1;
            $Yy1=$pY-2;//$this->fpdf->GetY();
            $pXxx =$pX - $this->lm;$halbaru=false;
            // $this->n_page=false;
            for ($x=0; $x < count($arrisi); $x++) { 
                // $this->fpdf->SetTextColor(0,0,255);
                $content = iconv('UTF-8', 'windows-1252',$arrisi[$x]);
                $this->fpdf->Cell($col,$this->ln,$content,0,0,'C');
                // $this->fpdf->SetTextColor(0,0,0);
                $this->fpdf->Ln(4);
                $pY+=4;
                $this->fpdf->SetY($pY);
                $this->fpdf->SetX($pX);
                if ($pY > $this->btshal && $col == $ar[$arj]){
                    $this->n_page=true;
                    $halbaru=true;

                    $this->fpdf->SetDrawColor(255,0,0);//merah
                    $count=count($ar);$xx=$this->lm;$x1=$this->lm;
                    for($i = 0; $i < $count; $i++ ) {
                        $this->fpdf->Line($xx,$Yy1,$xx,$pY);
                        $xx += $ar[$i];
                    }
                    $this->fpdf->Line($xx,$Yy1,$xx,$pY);
                    $this->fpdf->Line($x1,$pY,$xx,$pY);
                    $this->fpdf->SetDrawColor(0,0,0);

                    $this->fpdf->ln($this->ln);
                    $this->AipFooter();
                    $this->fpdf->AddPage();
                    $this->Watermark($this->draft);
                    if ($this->fpdf->PageNo()%2==0){
                        $this->lm=15;$this->rm=20;
                        $rht=0;
                    }else{
                        $rht=0;
                        $this->lm=20;$this->rm=15;
                    }
                    $this->fpdf->SetAutoPageBreak(true,0);
                    $this->fpdf->SetRightMargin($this->rm);
                    $this->fpdf->SetLeftMargin($this->lm);
                    $this->fpdf->SetTopMargin($this->head);
                    $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                    $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
                  
                    // $Yawal=$this->fpdf->GetY();
                    // $Y2=$this->fpdf->GetY();
                    // $line=false;
                    // // var_dump($this->lm,$this->lebararea);
                    if ($x < count($arrisi)-1){
                        $this->fpdf->ln($this->ln);
                        // $this->fpdf->SetDrawColor(255,10,10);
                        $this->fpdf->Line($this->lm,$this->fpdf->GetY(), ($this->lebar - $this->rm),$this->fpdf->GetY());
                        // $this->fpdf->SetDrawColor(0,0,0);
                        $this->fpdf->ln($this->ln);
                        
                    }
                    $Yy1=$this->fpdf->GetY();
                    $pY = $Yy1;
                    $this->fpdf->SetX($pXxx +$this->lm);
                    $pX=$pXxx +$this->lm;
                    // $Yy=$Yawal-2;
                    $Y2=$Yy1;

                }
                $Y2=$this->fpdf->GetY();;

            }
            if ($halbaru==true){
                if ($pY-$Yy1 >$this->ln){
                    $yyatas=$this->head+$this->ln;
                    // $this->fpdf->SetDrawColor(0,255,0);
                    $count=count($ar);$xx=$this->lm;$x1=$this->lm;
                    for($i = 0; $i < $count; $i++ ) {
                        $this->fpdf->Line($xx,$yyatas,$xx,$pY+4);
                        $xx += $ar[$i];
                    }
                    $this->fpdf->Line($xx,$yyatas,$xx,$pY+4);
                    // $this->fpdf->SetDrawColor(0,0,0);

                }

            }
        }
        return $halbaru;
    }
    function isirwy($thr,$Yawal,$ar,$Yy,$icao,$linedraw=false){
        $thr=$thr[0];
        $count=count($thr);
        unset($xxlineY);
        // dd($thr);
        $xxlineY=$this->fpdf->GetY();
        $tb=0;
        $this->fpdf->SetY($Yawal);
        $space=0;$isi='';$jmllooping=0;$hbr=false;
        for($i = 0; $i < $count-1; $i++ ) {
            // var_dump($Yawal,$thr[$i]);
            $jmllooping=$i;
            $tbg=explode("\n",$this->fpdf->WordWrap($thr[$i],$ar[$i]));
            $t=(count($tbg)+1) * 4;
            //ganti dngan function plotisi($tbg,$ar,$Yawal){
                if ($t>$space){
                    $space=$t;
                }
            $isi=$thr[$i];
            unset($content);
            // if (count($tbg)>2){
            //     $hbr= $this->isiloop($tbg,$ar[$i],$this->fpdf->GetX(),$Yawal,$ar,$icao);
            //     if ($hbr==true){
            //         $Yawal=$this->fpdf->GetY();
            //         $Yy=$Yawal;
            //         $space=4;
            //     }

            // }else{
                // $this->fpdf->SetTextColor(255,0,0);
                $content = iconv('UTF-8', 'windows-1252',$thr[$i]);
                $this->fpdf->MultiCell($ar[$i],$this->ln,$content,0,'C');
                // $this->fpdf->SetTextColor(0,0,0);
            // }
            $this->fpdf->SetY($Yawal);
            $tb+=$ar[$i];
            $this->fpdf->Cell($tb);
        }
        $this->fpdf->ln($space);
        $Yawal=$this->fpdf->GetY();
        if ($thr[$count-1] !== 'U'){
            // dd($thr,$xxlineY,$Yawal);
            $this->vertikalline($xxlineY,$Yawal);
        }
            $Y2=$Yawal;
            $newpage=$Y2;
            $rht=0;
            $line=true;
            // $this->n_page=false;
            if ($newpage > $this->btshal-5){
                // var_dump($newpage,$isi);
                $this->n_page=true;
            // $this->fpdf->SetDrawColor(255,0,255);//magenta
                $count=count($ar);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
                for($i = 0; $i < $count; $i++ ) {
                    $this->fpdf->Line($xx,$Yy,$xx,$Y2-1);
                    $xx += $ar[$i];
                }
                $this->fpdf->Line($xx,$Yy,$xx,$Y2-1);
                $this->fpdf->Line($x1,$Y2-1,$xx,$Y2-1);
            // $this->fpdf->SetDrawColor(0,0,0);

                $this->fpdf->ln($this->ln);
                $this->AipFooter();
                $this->fpdf->AddPage();
                $this->Watermark($this->draft);
                if ($this->fpdf->PageNo()%2==0){
                    $this->lm=15;$this->rm=20;
                    $rht=0;
                }else{
                    $rht=0;
                    $this->lm=20;$this->rm=15;
                }
                $this->fpdf->SetAutoPageBreak(true,0);
                $this->fpdf->SetRightMargin($this->rm);
                $this->fpdf->SetLeftMargin($this->lm);
                $this->fpdf->SetTopMargin($this->head);
                $this->lebararea=$this->lebar - ($this->rm + $this->lm);
                $this->AipHeader($icao.' AD 2 - '.$this->fpdf->PageNo(),$rht);
                $this->fpdf->ln($this->ln);
            
                $Y2=$this->fpdf->GetY();
                $line=false;
                $Yy=$Y2;
                $linedraw=false;
                $Yawal=$this->fpdf->GetY();
                // $Yy=$Yawal-2;
                $Y2=$Yy;
                // dd($Y2,$Yawal);
            }
            if ($linedraw==true){
                /// hanya untuk table runway
                $count=count($ar);$xx=$this->fpdf->GetX();$x1=$this->fpdf->GetX();
                // var_dump($Yy);
                // $this->fpdf->SetDrawColor(0,0,255);//biru
               
                if ($this->n_page==true){
                    if ($hbr==false){
                        $Yy -=2;
                    }
                }
                for($i = 0; $i < $count; $i++ ) {
                    $this->fpdf->Line($xx,$Yy,$xx,$Y2);
                    $xx += $ar[$i];
                }
                $this->fpdf->Line($xx,$Yy,$xx,$Y2);
                $this->fpdf->Line($x1,$Y2,$xx,$Y2);
                if ($Yy < 20){
                    $this->fpdf->Line($x1,$Yy,$xx,$Yy);

                }
                // $this->fpdf->SetDrawColor(0,0,0);//biru
                $this->fpdf->ln(2);
                $Y2=$this->fpdf->GetY();
            }    // dd($thr,$Yawal,$ar,$Y2);

        return $Y2;     

    }
    
    
    public function GetTempUpdate($tbl,$id)
    {
        // var_dump($tbl,$id);
        $originalInput=Request::input();
        $user = Auth::user();

        if ($user->isAdmin()) {
            // return view('pages.admin.home');
        }
        $method='GET';
        $qry='/api/temp?table_nm='.$tbl.'&tableid='.$id;
        $request = Request::create($qry, $method,);
        // dd($request);
        Request::replace($request->input());
        $instance = json_decode(Route::dispatch($request)->getContent());
        Request::replace($originalInput);

        if($instance->status=='success'){
            $data['temp'] = $instance->data;
        }
        return  $data['temp'][0]->detail;
    }


    function Airspacefreq($freq,$unit){
        // dd($freq,$unit);
        if ( $unit == 'V' ) {
            $f= ($freq/1000000).' MHz';
        } else {
            $f= ($freq/1000).' kHz';
        }
                    // dd($f);
        return $f;
    }
    function FreqFormat( $freq, $navtype, $usefor ) {

        if ( $freq == '' ) {
            $rslt = 'NIL';
        } else if ( $navtype == '3' || $navtype == '9' ) {
            $rslt = $freq;
        } else {
            $rplc = ["M", "K", "H","z"," "];
            $frq = str_replace($rplc, '', $freq);
            switch ( $navtype ) {
                case '5':
                case '7':
                case '10':
                    if ( $frq >= 100000 ) {
                        $rslt = $frq / 1000;
                    } else {
                        $rslt = $frq;
                    }
                    if ( $usefor == 'DATA' ) {
                        $rslt =number_format($rslt,2);//numeral($rslt).format('0.00');
                    } else {
                        $rslt =number_format($rslt,0).' kHz';
                    }
                    break;
                default:
                // var_dump($frq);
                    if ($frq < 200){
                        $rslt =$frq.' MHz';
                    }else{

                        if ( $frq >= 1000000 ) {
                            if ( $usefor == 'DATA' ) {
                                $rslt =number_format($frq / 10000,2); //numeral($frq / 10000).format('0.00') //format( "####.00", $frq / 10000 )
                            } else {
                                $rslt = number_format($frq / 10000,2).' MHz';//numeral($frq / 10000).format('0.0[00]') + 'MHz' //format( "####.0##", $frq / 10000 ) + 'MHz'
                            }
                        } else if ( $frq < 1000000 && $frq > 100000 ) {
                            if ( $usefor == 'DATA' ) {
                                $rslt =number_format($frq / 10000,2);// numeral($frq / 1000).format('0.00') //format( "####.00", $frq / 1000 )
                            } else {
                                $rslt =number_format($frq / 10000,2).' MHz'; //numeral($frq / 1000).format('0.0[00]') + 'MHz' // format( "####.###", $frq / 1000 ) + 'MHz'
                            }
                        } else {
                            if ( $usefor == 'DATA' ) {
                                $rslt = number_format($frq / 10000,2);//numeral($frq).format('0.00') //format( "###.00", $frq )
                            } else {
                                $rslt =number_format($frq / 10000,2).' MHz';// numeral($frq).format('0.0[00]')+ 'MHz' //format( "###.0##", $frq ) + 'MHz'
                            }
                        }
                    }
                    break;
            }
        }
        // console.log($rslt);
        return $rslt;
    }
}