<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_BILLING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}


//getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'lst_time','trunks'));

$DBHandle  = DbConnect();
$instance_table = new Table();

$ebsdata = $instance_table->SQLExec($DBHandle, "SELECT * FROM cc_config WHERE config_key= 'ebs_secret_key'");  
//print_r($ebsdata);
$result ="";

// update query

$DBHandle  = DbConnect();
$instance_table = new Table('cc_config');

if(isset($_POST['submit']) && $_POST['loginkey'] != NULL && trim($_POST['loginkey']) !='')
{
	
	
	$loginkey = sanitize_data($_POST['loginkey']);
	$return = $instance_table -> Update_table($DBHandle, " config_value = '$loginkey'", "config_key = 'ebs_secret_key'");

		if($return)
        {
            echo '<script>alert("Key has been updated");window.location.href="ebs_payment.php"; </script>' ;
        }
        else 
        {
            echo '<script>alert("There is a problem in updating");window.location.href="ebs_payment.php"; </script>' ;
			
        } 
         
		 
 }



//     Initialization of variables    ///////////////////////////////
  

// #### HEADER SECTION
$smarty->display('main.tpl');

?>
   
<script LANGUAGE="JavaScript">
        function test(){
            if(document.theForm.loginkey.value==""){
                alert("Please Enter Your EBS Login Key !");
                return false;
            }
            if(document.theForm.transkey.value==""){
                alert("Please Enter Your EBS Transaction Key !");
                return false;
            }
            return true;
        }
    </script>
   

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                EBS Configuration                          </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Payment Methods                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="credit_transfer.php" class="kt-subheader__breadcrumbs-link">
                            EBS Payment Config                   </a>
						
							  
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
           <?php echo gettext("EBS Payment Configuration"); ?>
	    </h5>
        </div>
	</div>


 
 
<form name="theForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" onSubmit="return test()" > 
  
<table width="60%"  align="center" >
  
  <tr><td colspan="2">&nbsp;</td></tr>   
   <tr > 
    <td align="left" width="20%"> <b>Payment Method </b>      </td>
    <td align="left">EBS Payment
     
    </td>
  </tr>   
  <tr > 
    <td align="left" width="20%"><b>Key  </b>     </td>
    <td align="left">
      <input name="loginkey" class="form-control" value="<?php echo $ebsdata[0]['config_value']; ?>">
    </td>
  </tr>


  <tr>
    <td colspan="2"  align="center" valign="bottom">
        <input type="submit" name="submit" class="btn btn-primary btn-small" value="UPDATE ">
        
      </td>
     </tr> 
 </table>
</form>

</div>
</div>

<?php

$smarty->display('footer.tpl');
 
