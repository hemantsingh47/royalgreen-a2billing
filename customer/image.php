<?php
include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_card.inc';
include './lib/customer.smarty.php';
$DBHandle  = DbConnect(); 
$cust = $_SESSION['pr_login'];


//getting  group_id from customer reference
$QUERY = "SELECT  id_group FROM cc_card WHERE username = '" . $_SESSION["pr_login"] . "'";

$numrow = 0;
$resmax = $DBHandle->Execute($QUERY);
if ($resmax)
$numrow = $resmax->RecordCount();

if($numrow==0)
	exit();
    $customer_info =$resmax->fetchRow();
	//print_r($customer_info); die;
//$gr_id_query="SELECT id_group FROM cc_card WHERE username='$cust'";
//$gr_id_result = @mysql_query($QUERY) or die(mysql_error());
//while($row=mysql_fetch_array($gr_id_result))
//{
  // echo $gr_id=$row['id_group'];
//}
//echo $gr_id;

//now getting the group from group reference
  $gr_query="SELECT id,id_agent FROM cc_card_group WHERE id='$customer_info[0]'";
  
  $numrow = 0;
$resmax = $DBHandle->Execute($gr_query);
if ($resmax)
$numrow = $resmax->RecordCount();

if($numrow==0)
	exit();
    $customer_info =$resmax->fetchRow();
	//print_r($customer_info); die;
//$gr_result = mysql_query($gr_query) or die(mysql_error());
//while($row=mysql_fetch_array($gr_result))
//{
   $gr=$customer_info[0];
   $ag_id=$customer_info[1];
//}
//echo $gr;
//echo $ag_id;

$desired_width = 203;
$desired_height = 62;

//getting reference from image
$ex_query="SELECT * FROM cc_image WHERE group_id='$gr' AND agent_id='$ag_id'";
$numrow = 0;
$resmax = $DBHandle->Execute($ex_query);
if ($resmax)
$numrow = $resmax->RecordCount();

//if($numrow==0)
	//exit();
    $customer_info =$resmax->fetchRow();


//$ex_result=mysql_query($ex_query) or die(mysql_error());
//$ex_nr = mysql_num_rows($ex_result);
if($numrow==0)
{
   $query = "SELECT logo_data,mime_type FROM cc_image WHERE id='-1' "; 
}
else
{
  $query_ehll = "SELECT logo_data,mime_type FROM cc_image WHERE group_id='$gr' AND agent_id='$ag_id'";
}

$numrow = 0;
$resmax = $DBHandle->Execute($query_ehll);
print_r($resmax);
if ($resmax)
$numrow = $resmax->RecordCount();
echo $numrow;


 header("Content-type: {$numrow}"); 
    echo base64_decode($numrow);
   
?>