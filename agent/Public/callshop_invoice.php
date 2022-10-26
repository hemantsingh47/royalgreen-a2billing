 <?php                
include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';
include '../lib/fpdf/fpdf.php';
 
$DBHandle  = DbConnect();
$instance_table = new Table();
$files = glob("./uploads/logo*");
  
// #### HEADER SECTION
 if(isset($_REQUEST['custid']) && isset($_REQUEST['callid']) && isset($_REQUEST['custref']))
 {
     $customer_id=$_REQUEST['custid'];
     $callshop_id=$_REQUEST['callid'];
     $cust_username=$_REQUEST['custref'];
 }
 else
 {
    
     die;
 }
 
$QUERY ="SELECT * FROM `callshop_cc_call` WHERE `card_id`='".$customer_id."'";
$callshop = $instance_table -> SQLExec($DBHandle, $QUERY);


//FROM CALL SHOP TEMPLATE
$callshop_temp_query ="SELECT * FROM `callshop_template` WHERE `agentid`='".$_SESSION['agent_id']."'";
$callshop_temp_result=$instance_table -> SQLExec($DBHandle, $callshop_temp_query);

//FROM CALL SHOP TABLE
$callshop_query ="SELECT * FROM `callshop_cc_call` WHERE `card_id`='".$customer_id."'";
$callshop_result=$instance_table -> SQLExec($DBHandle, $callshop_query);
//print_r($callshop_result);die;
class PDF extends FPDF
{
    function Header()
    {
    /*if(!empty($_FILES["file"]))
        {
    $uploaddir = "logo/";
    $nm = $_FILES["file"]["name"];
    $random = rand(1,99);
    move_uploaded_file($_FILES["file"]["tmp_name"], $uploaddir.$random.$nm);
    $this->Image($uploaddir.$random.$nm,10,10,20);
    unlink($uploaddir.$random.$nm);
    }*/
        $this->SetFont('Arial','B',12);
        $this->Ln(1);
    }

    function Footer()
    {
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    
    function ChapterTitle($num, $label)
    {
    $this->SetFont('Arial','',12);
    $this->SetFillColor(200,220,255);
    $this->Cell(185,6,"$num $label",0,1,'L',true);
    $this->Ln(0);
    }
    
    function ChapterTitle2($num, $label)
    {
        $this->SetFont('Arial','',12);
        $this->SetFillColor(249,249,249);
        $this->Cell(0,6,"$num $label",0,1,'L',true);
        $this->Ln(0);
    }
}   
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',10);
    $pdf->SetTextColor(32);

   /* header("Content-type: {$imageData}"); 
    $imgdata = base64_decode($blobcontents);*/
    //$pdf->Image('@'.$imgdata);

    $pdf->Image($files[0], 10, 10);
    //$pdf->Cell(0,5,$img,0,1,'R');
    $pdf->Cell(0,5,$callshop_temp_result[0]['company'],0,1,'R');
    $pdf->Cell(0,5,$callshop_temp_result[0]['address'],0,1,'R');
    $pdf->Cell(0,5,$callshop_temp_result[0]['email'],0,1,'R');
    $pdf->Cell(0,5,'Tel: '.$callshop_temp_result[0]['phone'],0,1,'R');
    $pdf->Cell(0,30,'',0,1,'R');
    $pdf->SetFillColor(200,220,255);
    $pdf->ChapterTitle('CallShop Receipt No. ',"callshop_invoice_".$cust_username);
    $pdf->ChapterTitle('Date ',date('d-m-Y'));
    $pdf->Cell(0,20,'',0,1,'R');
    $pdf->SetFillColor(224,235,255);
    $pdf->SetDrawColor(192,192,192);
    //$pdf->Cell(170,7,'Time',1,0,'L');
    //$pdf->Cell(20,7,'Destination',1,1,'C');

    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(50,10,"Start Time",1,0,'C',true);
    $pdf->Cell(50,10,"Stop Time",1,0,'C',true);
    $pdf->Cell(30,10,"Duration(Sec.)",1,0,'C',true);
    $pdf->Cell(35,10,"Destination",1,0,'C',true);
    $pdf->Cell(20,10,"Charge",1,0,'C',true);

    $pdf->Ln();
   
       for($i=0;$i<count($callshop_result);$i++)
         { 
            $recbill = $rows2['sessiontime'];   
            $calldest = $rows2['calledstation']; 
            $starttime=$rows2['starttime'];
            $stoptime=$rows2['stoptime'];
            $sebill= -0 + $rows2['sessionbill'];
            $pdf->Cell(50,7,$callshop_result[$i]['starttime'],1,0,'C',0);
            $pdf->Cell(50,7,$callshop_result[$i]['stoptime'],1,0,'C',0); 
            $pdf->Cell(30,7,$callshop_result[$i]['sessiontime'],1,0,'C',0);
            $pdf->Cell(35,7,$callshop_result[$i]['calledstation'],1,0,'C',0);           
            $pdf->Cell(20,7,-0+$callshop_result[$i]['sessionbill'],1,1,'C',0);   
         }
         $total_call_duration=0;
         $total_cal_charges=0;
         for($ii=0;$ii<count($callshop_result);$ii++)
         {
             $total_call_duration+=(float)$callshop_result[$ii]["sessiontime"];
             $total_cal_charges+=(float)$callshop_result[$ii]['sessionbill'];
         }
     
    
              
    $pdf->Cell(0,6,'',0,1,'R');              
    $pdf->Cell(185,6,"Total Calls = ".count($callshop_result),0,1,'R',true);
    $pdf->Cell(185,6,"Total Duration = ".$total_call_duration." (Sec.)",0,1,'R',true);
    $pdf->Cell(185,6,"Total Charges = ".$total_cal_charges,0,1,'R',true);
    //$pdf->Output($filename,'F');
    $pdf->Output('D',"callshop_invoice_".$cust_username.'.pdf');


 
?>
 
   
