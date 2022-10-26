<?php
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
 <h3><?php echo gettext("Live Call Reports");?></h3><hr>


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
                            
                             <tbody id="mycountries">
                             
                                                           
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
                                    <th><?php echo gettext("Status");?></th>
                                    <th><?php echo gettext("Action");?></th>
                                </tr>
                            </thead>
                            
                             <tbody id="mycall">
                             
                             </tbody>
                        </table>
          </div>
        </div>
      </div>
 </div>
 
<?php
       
// #### FOOTER SECTION
$smarty->display('footer.tpl');
