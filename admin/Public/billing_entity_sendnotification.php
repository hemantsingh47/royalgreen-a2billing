
<?php


include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';  
//include '../lib/support/classes/support_service.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_fcm_notification.inc';

if (!has_rights(ACX_BILLING)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

if ($id != "" || !is_null($id)) {
    $HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
    $form_action = "list"; //ask-add
if (!isset ($action))
    $action = $form_action;

$list = $HD_Form->perform_action($form_action);    
$smarty->display('main.tpl');
 $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>
<style type="text/css">
.tableBody
{
     word-wrap: break-word!important;
    word-break: break-all!important;   
}


h1{text-align: left;
    color: #666;
    font-size: 0.9rem; font-weight:normal;}
h2{text-align: left;
    color: #666;
    font-size: 0.9rem; font-weight:normal;}
</style>
<script type="text/javascript" src="jquery-3.2.1.min.js"></script> 
<script type="text/javascript">
function sendNotification()
            {
                if($('#messagedata').val() == null || $('#messagedata').val() =="")
                {
                    alert("Please Enter Message!");
                    return false;
                }
                else
                {
                      postData = "message="+$('#messagedata').val()+"&maction=send";
                      
                     $.ajax({
                        type: "POST",
                        /*beforeSend: function(){
                            $('.ajax-loader').css("visibility", "visible");
                          },*/
                        url: "<?php echo $actual_link; ?>/webittech/billing_firebase/fcm_notification.php",
                        data:postData,
                        success: function(response) 
                        {
                            
                            
                           var json = $.parseJSON(response);
                           if(json.result == "success")
                           {
                               alert("Notifications has been sent!");
                               window.location.href="<?php echo $_SERVER['PHP_SELF'];?>";
                           }
                           else
                           {
                                   alert("Some Error occured!"); 
                           }
                            
                            
                        },
                        
                        error: function(jqXHR, textStatus, errorThrown) 
                        {
                            alert(response+" failed"+errorThrown);
                           
                        },
           /*             
          complete: function(){
            $('.ajax-loader').css("visibility", "hidden");
          }*/

                });
                }
                 

            }
</script>
 	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Send Notifications                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Push Notification                    </a>
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
           <?php echo gettext("Send Notifications"); ?>
	    </h5>
        </div>
	</div>
 
<div class="kt-widget1">
	<FORM  id="myForm"  name="myForm" class="kt-form">
	
	
	<table class="table">
	<tr>
	<td width="20%"><label class="col-12 col-form-label">
				<?php echo gettext("Enter Message"); ?>:
			</label>
	</td>
	<td width="80%"><textarea name="messagedata" id="messagedata" class ="form-control"></textarea> 
	</td>
	
	</tr>
	
	
	</table>
		 
			
		<div class="" style="text-align:right">
			<button onclick="sendNotification();" title="Create a new FCM Notification" alt="Create a new  FCM Notification" id="submit4" name="submit2" class="btn btn-success" style="text-decoration:none;cursor:pointer;">
				 Send Notification </button>	
					
		</div>
			            
        
        </form>   
 </div> 
      
       
<?php
 // #### HELP SECTION
//echo $CC_help_support_component;
 

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);
?>
</div>
<br>
<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');

