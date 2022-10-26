<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/regular_express.inc");
include ("../lib/phpagi/phpagi-asmanager.php");
include ("../lib/admin.smarty.php");


/*if (! has_rights (ACX_MAINTENANCE)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}*/
// #### HEADER SECTION

$smarty->display('header.tpl'); 

?>

 <script>
 $(document).ready(function(){
  setInterval(function() {
      $.ajax({
                type: "GET",
               url: "https://billingglobal.yepingo.com/billing/lkwk_hrpt/admin/Public/live-call-summery_data_live.php",
                data:"section=6&callinfo=all",
                success: function(response) {
                   
                    $("#mycallglobal").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }

        });
        
       
  
   }, 1000);
   setInterval(function() {
      $.ajax({
                type: "GET",
               url: "https://billing.yepingo.co.uk/billing/lkwk_hrpt/admin/Public/live-call-summery_data_live.php",
                data:"section=6&callinfo=all",
                success: function(response) {
                   
                    $("#mycalluk").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }

        });
        
       
  
   }, 1000);
   setInterval(function() {
      $.ajax({
                type: "GET",
               url: "https://billing.yepingo.it/billing/lkwk_hrpt/admin/Public/live-call-summery_data_live.php",
                data:"section=6&callinfo=all",
                success: function(response) {
                   
                    $("#mycallit").html(response);
                    
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
               url: "https://billingglobal.yepingo.com/billing/lkwk_hrpt/admin/Public/live-call-summery_data_live.php",
                data:"section=6&countryinfo=all",
                success: function(response) {
                   
                    $("#mycountriesglobal").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }

        });
        
  
   }, 1000);
   setInterval(function() {
      $.ajax({
                type: "GET",
               url: "https://billing.yepingo.co.uk/billing/lkwk_hrpt/admin/Public/live-call-summery_data_live.php",
                data:"section=6&countryinfo=all",
                success: function(response) {
                   
                    $("#mycountriesuk").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }

        });
        
  
   }, 1000);
   setInterval(function() {
      $.ajax({
                type: "GET",
               url: "https://billing.yepingo.it/billing/lkwk_hrpt/admin/Public/live-call-summery_data_live.php",
                data:"section=6&countryinfo=all",
                success: function(response) {
                   
                    $("#mycountriesit").html(response);
                    
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    alert(response+"failed"+errorThrown);
                   
                }

        });
        
  
   }, 1000);
 });
</script>
 <h3><?php echo gettext("Live Call Reports");?></h3>


    <?php
    //$response = $astman->send_request('Command',array('Command'=>$value));
    
?>
<div id="page_content_inner" style="padding: 1px 5px 60px;">
           
           
                    <div class="uk-grid " data-uk-grid-margin>
                        <div class="uk-width-medium-1-2 uk-row-first">
                            <div class="row-fluid ">
                        <div class="widget-box">
                            <div class="uk-overflow-container uk-margin-bottom ">
                            <h3>Countries Information : GLOBAL</h3>
                                <table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_issues">
                                    <thead>
                                        <tr>
                                            <th class="uk-text-center"><?php echo gettext("Sr. No.");?></th>
                                            <th class="uk-text-center"><?php echo gettext("Country Code");?></th>
                                            <th class="uk-text-center"><?php echo gettext("Country Name");?></th>
                                            <th class="uk-text-center"><?php echo gettext("Total No. of Calls");?></th>
                                        </tr>
                                    </thead>
                                    
                                     <tbody id="mycountriesglobal">
                                     
                                                                   
                                     </tbody>
                                </table>
                            </div>
                            <div>
                                <a href="#" class="btn btn-success btn-sm"  style="margin-bottom: 10px;" onclick="javascript:$('#globalserver').toggle('slide')"><span class="glyphicon glyphicon-plus" ></span>More Info</a>
                            </div>
                       </div>
                    </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="row-fluid ">
                                <div class="widget-box">
                    <div class="uk-overflow-container uk-margin-bottom">
                    <h3>Countries Information : UK</h3>
                        <table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_issues">
                            <thead>
                                <tr>
                                    <th class="uk-text-center"><?php echo gettext("Sr. No.");?></th>
                                    <th class="uk-text-center"><?php echo gettext("Country Code");?></th>
                                    <th class="uk-text-center"><?php echo gettext("Country Name");?></th>
                                    <th class="uk-text-center"><?php echo gettext("Total No. of Calls");?></th>
                                </tr>
                            </thead>
                            
                             <tbody id="mycountriesuk">
                             
                                                           
                             </tbody>
                        </table>
                    </div>
                    <div>
                        <a href="#" class="btn btn-success btn-sm"  style="margin-bottom: 10px;" onclick="javascript:$('#ukserver').toggle('slide')"><span class="glyphicon glyphicon-plus" ></span>More Info</a>
                    </div>
               </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="uk-grid " data-uk-grid-margin>
                        <div class="uk-width-medium-1-2 uk-row-first">
                            <div class="row-fluid" id="globalserver" style="display: none;">
                                <div class="widget-box">
                                <div class="uk-overflow-container uk-margin-bottom">
                                <h3>Call Information :  GLOBAL</h3>
                                    <table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_issues">
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
                                        
                                         <tbody id="mycallglobal">
                                         
                                         </tbody>
                                    </table>
                                </div>
                           </div>
                           </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                        
                            <div class="row-fluid" id="ukserver" style="display: none;">
                                <div class="widget-box">
                        <div class="uk-overflow-container uk-margin-bottom">
                        <h3>Call Information :  UK</h3>
                            <table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_issues">
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
                                
                                 <tbody id="mycalluk">
                                 
                                 </tbody>
                            </table>
                        </div>
                   </div>
                            </div>
                        </div>
                    </div>
             
                    
                    <div class="uk-grid " data-uk-grid-margin>
                        <div class="uk-width-medium-1-2 uk-row-first">
                            <div class="row-fluid">
                                <div class="widget-box">
                                    <div class="uk-overflow-container uk-margin-bottom">
                                    <h3>Countries Information : ITALY</h3>
                                        <table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_issues">
                                            <thead>
                                                <tr>
                                                    <th class="uk-text-center"><?php echo gettext("Sr. No.");?></th>
                                                    <th class="uk-text-center"><?php echo gettext("Country Code");?></th>
                                                    <th class="uk-text-center"><?php echo gettext("Country Name");?></th>
                                                    <th class="uk-text-center"><?php echo gettext("Total No. of Calls");?></th>
                                                </tr>
                                            </thead>
                                            
                                             <tbody id="mycountriesit">
                                             
                                                                           
                                             </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        <a href="#" class="btn btn-success btn-sm"  style="margin-bottom: 10px;" onclick="javascript:$('#itserver').toggle('slide')"><span class="glyphicon glyphicon-plus" ></span>More Info</a>
                                    </div>
                               </div>
                            </div>
                        
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="row-fluid">
                                <div class="widget-box">
                                    <div class="uk-overflow-container uk-margin-bottom">
                                    <h3>Countries Information : FRANCE</h3>
                                        <table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_issues">
                                            <thead>
                                                <tr>
                                                    <th class="uk-text-center"><?php echo gettext("Sr. No.");?></th>
                                                    <th class="uk-text-center"><?php echo gettext("Country Code");?></th>
                                                    <th class="uk-text-center"><?php echo gettext("Country Name");?></th>
                                                    <th class="uk-text-center"><?php echo gettext("Total No. of Calls");?></th>
                                                </tr>
                                            </thead>
                                            
                                             <tbody id="mycountriesfr">
                                             
                                                                           
                                             </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        <a href="#" class="btn btn-success btn-sm"  style="margin-bottom: 10px;" onclick="javascript:$('#frserver').toggle('slide')"><span class="glyphicon glyphicon-plus" ></span>More Info</a>
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="uk-grid " data-uk-grid-margin>
                        <div class="uk-width-medium-1-2 uk-row-first">
                            <div class="row-fluid" id="itserver" style="display: none;">
                                <div class="widget-box">
                                    <div class="uk-overflow-container uk-margin-bottom">
                                    <h3>Call Information :ITALY</h3>
                                        <table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_issues">
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
                                            
                                             <tbody id="mycallit">
                                             
                                             </tbody>
                                        </table>
                                    </div>
                               </div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="row-fluid" id="frserver" style="display: none;">
                                <div class="widget-box">
                                    <div class="uk-overflow-container uk-margin-bottom">
                                    <h3>Call Information :FRANCE</h3>
                                        <table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_issues">
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
                                            
                                             <tbody id="mycallit">
                                             
                                             </tbody>
                                        </table>
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
             
            
 
            
            
            <!-- ITALY END -->
 </div>
 
<?php
       
// #### FOOTER SECTION
$smarty->display('footer.tpl');
