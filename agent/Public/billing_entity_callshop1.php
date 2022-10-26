<?php
$url = $_SERVER['REQUEST_URI'];
//header("Refresh:10;URL=\"". $url."\""); 
//redirect in 5 seconds
?>

<?php
include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_signup_agent.inc';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_SIGNUP)) {
       Header ("HTTP/1.0 401 Unauthorized");
       Header ("Location: PP_error.php?c=accessdenied");
       die();
}
 $DBHandle = DbConnect();
 $inst_table = new Table();
?>
 
<?php
    $HD_Form -> setDBHandler (DbConnect());
    $first_login = $_SESSION["pr_login"];
    $totaldur = 0;
    $totalcharge =0;
    $agent_id = "SELECT id from cc_agent where login='$first_login'";
    $agvalue = $inst_table -> SQLExec($DBHandle, $agent_id);
    $agentid =$agvalue[0]['id'];     //fetch agent id
    
    $agent_group_id="SELECT id FROM `cc_card_group` WHERE `id_agent`='".$agentid."'";
    $agvalue= $inst_table -> SQLExec($DBHandle, $agent_group_id);
    $groupid =$agvalue[0]['id']; //fetch cc_card_group
    $agentcust="SELECT DISTINCT  cc_card.id,cc_card.username,cc_card.currency FROM cc_card,callshop_cc_call WHERE cc_card.id_group='".$groupid."' AND cc_card.callshop_status='1' AND cc_card.id=callshop_cc_call.card_id ORDER BY cc_card.id";
    /*$agentcust="SELECT callshop_cc_call.id,cc_card.id,cc_card.username,cc_card.currency FROM cc_card,callshop_cc_call WHERE cc_card.id_group='".$groupid."' AND cc_card.callshop_status='1' AND cc_card.id=callshop_cc_call.card_id ORDER BY cc_card.id";*/
    $custacc = $inst_table -> SQLExec($DBHandle, $agentcust);
    //print_r(($custacc));
// #### HEADER SECTION
$smarty->display('main.tpl');
?>
 <!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> CallShop Facility</h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            CallShop                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            CallShop Billing                        </a>
                </div>
            </span>
        </div>
    </div>
</div>
<!-- end:: Subheader -->
 <?php
      if($custacc[0]["id"] != 0)
{
  ?> 
<div class="row-fluid">
        
                
<?php

    for($i=0;$i<count($custacc);$i++)
    {
        $query_callshop ="SELECT * FROM callshop_cc_call WHERE card_id='".$custacc[$i]["id"]."' ORDER BY starttime DESC";
        $call_shop_details = $inst_table -> SQLExec($DBHandle, $query_callshop);
           $total_call_duration=0;
           $total_cal_charges=0;
           for($ii=0;$ii<count($call_shop_details);$ii++)
            {
                 $total_call_duration+=(float)$call_shop_details[$ii]["sessiontime"];
                 $total_cal_charges+=(float)$call_shop_details[$ii]['sessionbill'];
            }
        //Getting total number of calls
        if($i%2 ==0 )
		{
			echo '</div><div class="row-fluid">';
		}
        ?>
        
	<div class="span6">
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5><?php echo gettext("Booth")?> <strong><?php echo $custacc[$i]["username"]?></strong></h5>
            <span class="label label-info">Featured</span> 
			</div>
          <div class="">
            <table class="table table-bordered table-striped ">
              <thead>
                <tr>
                  
                  <th><?php echo gettext("Unpaid Calls")?></th>
                  <th><?php echo gettext("Last Call Details")?></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  
                  <td>
					 <table>
                         <tr><td style="border-left: 0;"><?php echo gettext("Calls"); ?> </td><td> <?php echo count($call_shop_details); ?></td></tr>
                         <tr><td style="border-left: 0;"><?php echo gettext("Duration (sec)"); ?> </td><td><?php echo $total_call_duration; ?></td></tr>
                         <tr><td style="border-left: 0;"><?php echo gettext("Charges"); ?> </td><td> <?php echo $total_cal_charges." ".$custacc[$i]["currency"]; ?></td></tr>
                         
					</table>
                                     
										
				  
				  </td>
                  <td>
					    
						<table>
                         <tr><td><?php echo gettext("Called Number"); ?> </td><td> <?php echo $call_shop_details[0]["calledstation"] ?></td></tr>
                         <tr><td><?php echo gettext("Start Time"); ?> </td><td><?php echo $total_call_duration; ?></td></tr>
                         <tr><td><?php echo gettext("End Time"); ?></td><td> <?php echo $call_shop_details[0]["stoptime"] ?></td></tr>
                         <tr><td><?php echo gettext("Call Duration (sec)"); ?></td><td><?php echo $call_shop_details[0]["sessiontime"] ?></td></tr>
                         <tr><td><?php echo gettext("Call Charge"); ?></td><td><?php echo $call_shop_details[0]["sessionbill"]." ".$custacc[$i]["currency"] ?></td></tr>
                        
					</table>
						
                                        <div class="row">
                                            <div class="uk-grid" data-uk-grid-margin="">
                                                <div class="uk-width-large-2-2 " style="text-align: center;">
                                                   <?php 
                                                    echo "<a href=\"callshop_report.php?custid={$custacc[$i]["id"]}&callid={$call_shop_details[0]["id"]}&custref={$custacc[$i]["username"]}\" style='float:center;color:#ffffff;'  class='btn btn-success ' style='color:#ffffff'>Receipt & Clear Booth </a>";
                                                    ?> 
                                                </div>
                                                
                                            </div>
                                        </div>
                                    
				  
				  </td>
                </tr>
			</tbody>
		</table>
	</div>
</div>
</div>
		
						
                              
     <?php
		
    }

?>
 
        
<?php
 }
else
{
  ?>
  
  <div class="md-card md-card-hover " data-snippet-title="Smooth scrolling to top of page" style="text-align: center;">
        <div class="md-card-content">
            <h4><?php echo gettext("No Callshop Booth generated...");?></h4>
        </div>
  </div>
  <?php  
}
      
  // #### HEADER SECTION
$smarty->display('footer.tpl'); 
?>      
      
        

      
