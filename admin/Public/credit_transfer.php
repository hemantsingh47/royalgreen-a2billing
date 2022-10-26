<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';



if (! has_rights (ACX_CALL_REPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

//getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'lst_time','trunks'));

$DBHandle  = DbConnect();
$inst_table = new Table();

//     Initialization of variables    ///////////////////////////////
  

// #### HEADER SECTION
$smarty->display('main.tpl');

?>
   
	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Balance Transfer                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="credit_transfer.php" class="kt-subheader__breadcrumbs-link">
                             Balance Transfer                   </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
	
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
	<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h5 class="kt-portlet__head-title">
           <?php echo gettext("Balance Transfer"); ?>
	    </h5>
        </div>
	</div>

<script LANGUAGE="JavaScript">
        function test(){
            if(document.theform.raccno.value==""){
                alert("Please Enter Receiver Account Number!");
                return false;
            }
            if(document.theform.amt.value==""){
                alert("Please Enter Amount For Transfer !");
                return false;
            }
            return true;
        }
</script>
	
<table class="table" style="width:50%; margin:0 auto;">
	<form name="theForm" action="credit_transfer.php" method="post" onsubmit="return test()" class="kt-form"> 	
		<tr height="20px">
			
			
			<td>
				<label class="col-form-label">
					<?php echo gettext("Sender Account Number") ?> :
				</label>
			</td>
			<td>
				
					<input name="pr_login" type="text" class="form-control"> 
				
			</td>
	   </tr>
	   
	   <tr height="20px">
			<td>
				<label class="col-form-label">
					<?php echo gettext("Receiver Account Number") ?> :
				</label>
			</td>
			<td>
				
					<input name="raccno" type="text" class="form-control"> 
				
			</td>
	   </tr>
	   
	   <tr height="20px">
			<td>
				<label class="col-form-label">
					<?php echo gettext("Amount") ?> :
				</label>
			</td>
			<td>
				
					<input name="amt" type="text" class="form-control"> 
				
			</td>
	   </tr>
		
		<tr >
			<td align="center" colspan="2"> 
			
					<input type="submit" name="submit" value="&nbsp;<?php echo gettext("TRANSFER")?>&nbsp;" class="btn btn-primary"  > 
						
			</td>
	   </tr>
			
			
	   
</form>
</table>

   

 
<?php
if(isset($_POST['submit']))
{


$first_login = $_POST['pr_login'];

$second_login = $_POST["raccno"];

$tr_amt = $_POST["amt"];

//
    
        //echo $result1= mysql_query($inst);
        
        
      

//
$sql1 = "SELECT `credit` FROM cc_card WHERE username=$first_login";
$result1 = $inst_table -> SQLExec($DBHandle, $sql1);
if (!$result1) 
{    
//echo 'MySQL Error: ' . mysql_error();
//echo 'Error: Please Enter Corret Receiver Account Number & Amount ';
echo "<Center><font style='color:#FF0000 ;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif'>Error: Please Enter Correct Receiver Account Number & Amount</font></center>";
   
} 

$credit1 = $result1[0]['credit'];




$sql = "SELECT `credit` FROM cc_card WHERE username=$second_login";
$result = $inst_table -> SQLExec($DBHandle, $sql);
if (!$result) 
{    
//echo 'MySQL Error: ' . mysql_error();
//echo 'Error: Please Enter Correct Receiver Account Number & Amount ';
echo "<center><font style='color:#FF0000 ;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif'>Error: Please Enter Correct Receiver Account Number/Pin & Amount</font></center>";
   
} 

$credit2 = $result[0]['credit'];


if($credit1<$tr_amt)
{
    echo "<center><font style='color:#FF0000 ;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif'>You have not enough credit for transfer.</font></center>";
    /*echo "You have not enough credit for transfer.";*/
}
else
{
$source_credit = $credit1-$tr_amt;
$sink_credit = $credit2+$tr_amt;
$mysql_query1 = ("UPDATE cc_card SET credit = $source_credit WHERE username = $first_login");
$credit_src = $inst_table -> SQLExec($DBHandle, $mysql_query1);

$mysql_query2 = ("UPDATE cc_card SET credit = $sink_credit WHERE username = $second_login");
$cred_sink = $inst_table -> SQLExec($DBHandle, $mysql_query2);
}

if($source_credit && $sink_credit)
    {   
        echo "<div style='text-align:center;color:#008000'>Transfer Successful. Now Your Source Credit is :". $source_credit."<br></div>"; 
        echo "<div style='text-align:center;color:#008000'>And Your Receiver Credit is :". $sink_credit."</div>";

       $cdate = date("Y-m-d");
       $ctime = date("h:i:s", time() + 9060);
       $result_message = "success";
	   
        $insta= "INSERT into `cc_credit`(`transferFrom`,`transferTo`,`Amount`,`date`,`time`, `result_value`)VALUES('".$first_login."','".$second_login."','".$tr_amt."','".$cdate."','".$ctime."','".$result_message."' )";
       $result11 = $inst_table -> SQLExec($DBHandle, $insta); 
        
    }


else
{
     echo"<center><font style='color:#FF0000 ;font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif'>Entry doesn't Exist!</font></center>";
}
}

?>

 </form>
 
</div>
<?php
?>
</div>

<?php
$smarty->display( 'footer.tpl');

