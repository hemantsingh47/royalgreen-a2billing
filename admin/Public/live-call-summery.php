<?php

//$url = $_SERVER['REQUEST_URI'];
//header("Refresh:10;URL=\"". $url."\""); //redirect in 5 seconds

include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/regular_express.inc");
include ("../lib/phpagi/phpagi-asmanager.php");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_MAINTENANCE)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}
// #### HEADER SECTION
$smarty->display('main.tpl');
?>

 <script>
 $(document).ready(function(){
  setInterval(function() {
      $.ajax({
                type: "GET",
                url: "live-call-summery_data_live.php",
                data:"section=6&callinfo=all",
                success: function(response) {
                   
                    $("#mycall").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }

        });
  
   }, 1000);
 });
  $(document).ready(function(){
  setInterval(function() {
      $.ajax({
                type: "GET",
                url: "live-call-summery_data_live.php",
                data:"section=6&countryinfo=all",
                success: function(response) {
                   
                    $("#mycountries").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }

        });
  
   }, 1000);
 });
</script>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Live Calls Reports                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Reports                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="live-call-summery.php?section=6&type=tool&display=live_call&extdisplay=all" class="kt-subheader__breadcrumbs-link">
                             Live Call Reports               </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
	
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h1 class="kt-portlet__head-title">
            <?php echo gettext('Live Call Reports'); ?>
	      </h1>
        </div>
    </div>

 


    <?php
    //$response = $astman->send_request('Command',array('Command'=>$value));
    
?>


<div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title bg_ly" ><span class="icon"><i class="icon-list"></i></span>
            <h5><?php echo gettext("Countries Information");?></h5>
          </div>
          <div class="widget-content nopadding " >          
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="uk-text-center"><?php echo gettext("Sr. No.");?></th>
                                    <th class="uk-text-center"><?php echo gettext("Country Code");?></th>
                                    <th class="uk-text-center"><?php echo gettext("Country Name");?></th>
                                    <th class="uk-text-center"><?php echo gettext("Total No. of Calls");?></th>
                                </tr>
                            </thead>
                            
                             <tbody >
                             <tr>
							 <td colspan="8"><div id="mycountries"></div></td>
							 </tr>
                                                           
                             </tbody>
                        </table>
          </div>
        </div>
      </div>
      
 </div>
 <div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
          <div class="widget-title bg_ly" ><span class="icon"><i class="icon-list"></i></span>
            <h5><?php echo gettext("Call Information");?></h5>
          </div> 
          <div class="widget-content nopadding " >                  
                        <table class="table table-bordered" id="ts_issues">
                            <thead >
                                <tr>
                                    <th class="uk-text-center"><?php echo gettext("Sr. No.");?></th>
                                    <th><?php echo gettext("Duration");?></th>
                                    <th><?php echo gettext("Account/Pin");?></th>
                                    <th><?php echo gettext("Destination");?></th>
                                    <th><?php echo gettext("Trunk");?></th>
									<th><?php echo gettext("Provider");?></th>
                                    <th><?php echo gettext("Status");?></th>
                                    <th><?php echo gettext("Action");?></th>
                                </tr>
                            </thead>
							
							
                            
                             <tbody >
                              <tr>
							 <td colspan="8"><div id="mycall"></div></td>
							 </tr>
                             </tbody>
                        </table>
          </div>
        </div>
      </div>
 </div>
 <br><BR><BR><BR><bR><br><BR><BR><BR>
<?php
       
// #### FOOTER SECTION
$smarty->display('footer.tpl');
