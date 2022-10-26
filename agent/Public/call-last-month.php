<?php

 

include_once(dirname(__FILE__) . "/../lib/agent.defines.php");
include_once(dirname(__FILE__) . "/../lib/agent.module.access.php");
include '../lib/agent.smarty.php';

if (! has_rights (ACX_CALL_REPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('entercustomer','entertariffgroup','enterratecard','fromstatsmonth_sday','months_compare','dst','dsttype','posted'));
$DBHandle  = DbConnect();

$smarty->display('main.tpl');

?>

<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Reports                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Monthly Traffic                        </a>
                                             
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
				<?php echo gettext("Monthly Traffic"); ?>
				
			</h1>
		</div>
	</div>
	 


     
       
    <div class="kt-portlet__body">





 
    <FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=<?php echo $s?>&t=<?php echo $t?>&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>" class="kt-form">
    <INPUT TYPE="hidden" NAME="posted" value=1>
	
	
       <div class="col-md-12">
		
		<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="4" style="border-top: 1px solid #CDCDCD; padding: 0px;"><label class="control-label"><?php echo gettext("CUSTOMERS");?></label></td>
                </tr>
				<tr>
            <td align="right" style="width:20%;">
            
                    <?php echo gettext("Enter the customer ID");?>: </td>
					<td>
					<INPUT TYPE="text" NAME="entercustomer" value="<?php echo $entercustomer?>" class="form-control" style="width:90%">
                    <a href="#" onclick="window.open('billing_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><i class="flaticon2-fast-next"></i></a>
                </td>
                <td align="right">
                     
                            <?php echo gettext("Rate");?> :</td>
                            <td><INPUT TYPE="text" NAME="enterratecard" value="<?php echo $enterratecard?>" size="4" class="form-control" style="width:90%">&nbsp;<a href="#" onclick="window.open('billing_entity_def_ratecard.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterratecard' , 'RatecardSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
                        </tr>
                    </table>
					</div>
					
					
                <div class="col-md-12">
		
		<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="4" style="border-top: 1px solid #CDCDCD; padding: 0px;"><label class="control-label"><?php echo gettext("SELECT MONTH");?></label></td>
                </tr>
				<tr>
                  <td align="right">
                      <?php echo gettext("From");?> :</td>
					  <td>
                     <select name="fromstatsmonth_sday" class="form-control">
                    <?php
                        $monthname = array( gettext("January"), gettext("February"),gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));
                        $year_actual = date("Y");
                        for ($i=$year_actual;$i >= $year_actual-1;$i--) {
                            if ($year_actual==$i) {
                                $monthnumber = date("n")-1; // Month number without lead 0.
                            } else {
                                $monthnumber=11;
                            }
                            for ($j=$monthnumber;$j>=0;$j--) {
                                $month_formated = sprintf("%02d",$j+1);
                                if ($fromstatsmonth_sday=="$i-$month_formated") $selected="selected";
                                else $selected="";
                                echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                            }
                        }
                    ?>
                    </select>
                    </td>
					
					<td align="right">
                    <?php echo gettext("Number of months to compare");?> :</td>
					<td>
                     <select name="months_compare" class="form-control">
                    <option value="6" <?php if ($months_compare=="6") { echo "selected";}?>>- 6 <?php echo gettext("months");?></option>
                    <option value="5" <?php if ($months_compare=="5") { echo "selected";}?>>- 5 <?php echo gettext("months");?></option>
                    <option value="4" <?php if ($months_compare=="4") { echo "selected";}?>>- 4 <?php echo gettext("months");?></option>
                    <option value="3" <?php if ($months_compare=="3") { echo "selected";}?>>- 3 <?php echo gettext("months");?></option>
                    <option value="2" <?php if (($months_compare=="2")|| !isset($months_compare)) { echo "selected";}?>>- 2 <?php echo gettext("months");?></option>
                    <option value="1" <?php if ($months_compare=="1") { echo "selected";}?>>- 1 <?php echo gettext("months");?></option>
                    </select>
                    </td>
					</tr></table>
					
					</div>
					
                  <div class="col-md-12">
		
		<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;"><label class="control-label"><?php echo gettext("CALLEDNUMBER");?></label></td>
	</tr>
	<tr>
                
                <td>
                <INPUT TYPE="text" NAME="dst" value="<?php echo $dst?>" class="form-control"></td>
                <td><input type="radio" NAME="dsttype" value="1" <?php if ((!isset($dsttype))||($dsttype==1)) {?>checked<?php }?>> <?php echo gettext("Exact");?></td>
                <td><input type="radio" NAME="dsttype" value="2" <?php if ($dsttype==2) {?>checked<?php }?>> <?php echo gettext("Begins with");?></td>
                <td><input type="radio" NAME="dsttype" value="3" <?php if ($dsttype==3) {?>checked<?php }?>> <?php echo gettext("Contains");?></td>
                <td><input type="radio" NAME="dsttype" value="4" <?php if ($dsttype==4) {?>checked<?php }?>> <?php echo gettext("Ends with");?></td>
                </tr></table>
				
				</div>
            
			
			<div class="col-md-12">
			
            
                             <div class="form-actions" align="right">
                              <input class="btn btn-primary" value=" <?php echo gettext("Search");?> " type="submit">
                              </div>
                            
                           </div>
    </FORM>
 

<?php  if ($posted==1) { ?>
    <table class="table">
        <tr>
            <td style="border:0">
                <?php echo gettext("TRAFFIC")?><br>
                <IMG SRC="graph_pie.php?graphtype=1&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
            </td>
            <td style="border:0">
                <?php echo gettext("SELL")?> <br>
                <IMG SRC="graph_pie.php?graphtype=3&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
            </td>
        </tr>
   </table>
   
   
    <!--<div class="col-md-6">
    <?php echo gettext("TRAFFIC")?><br>
    <IMG SRC="graph_pie.php?graphtype=1&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
    </div>
    
    <div class="col-md-6">
    <?php echo gettext("SELL")?> <br>
    <IMG SRC="graph_pie.php?graphtype=3&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
    </div>-->

<?php  } ?>
</center>
  </div>
  </div>
  </div>
<br>
<br>

<?php
$smarty->display('footer.tpl');
