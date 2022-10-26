<?php

include_once(dirname(__FILE__) . "/../lib/admin.defines.php");
include_once(dirname(__FILE__) . "/../lib/admin.module.access.php");
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CALL_REPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('months_compare', 'current_page', 'fromstatsmonth_sday', 'days_compare', 'min_call', 'posted',  'dsttype', 'srctype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid', 'userfieldtype', 'userfield', 'accountcodetype', 'accountcode', 'customer', 'entercustomer', 'enterprovider','entertariffgroup', 'entertrunk', 'enterratecard', 'graphtype'));
$smarty->display('main.tpl');
/*        echo '<div id="page_content_inner">';
   echo '<h3 class="heading_b uk-margin-bottom">Call Daily Load</h3>';
         echo '<div class="row-fluid">';
                echo '<div class="widget-box">';
                    echo '<div class="uk-grid" data-uk-grid-margin="">';
                           echo '<div class="uk-width-1-1 uk-row-first">';  */     
?>


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
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Trunk & Traffic                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Monthly Traffic                       </a>
							   
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




<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
     
    <FORM METHOD=POST name="myForm" class="form-horizontal" ACTION=<?php echo $PHP_SELF?>?s=<?php echo $s?>&t=<?php echo $t?>&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>" class="kt-form">
    <INPUT TYPE="hidden" NAME="posted" value=1>
	
         <div class="col-md-12">
		
		<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="5" style="border-top: 1px solid #CDCDCD; padding: 0px;">
	<?php
        if ($_SESSION ["pr_groupID"] == 2 && is_numeric ( $_SESSION ["pr_IDCust"] )) {
            ?>
        <?php
        } else {
            ?>
              <label class="control-label"><?php echo gettext ( "Monthly Load" ); ?></label> 
			  </td>
			  </tr>
			  <tr>
			 <td class="fontstyle_searchoptions"  align="left">
                <?php
            echo gettext ( "Enter the customer ID" ); ?>: </td>
			<td>
			<INPUT TYPE="text"
                    NAME="entercustomer" value="<?php echo $entercustomer?>"
                    class="form-control" style="width:90%"> <a href="#"
                    onclick="window.open('billing_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><i class="flaticon2-fast-next"></i></a>
					</td>
               <td width="5%">
                <b>OR</b> </td>
               <td align="left"> 
                <?php echo gettext ( "Enter the customer number" );?>: </td>
				<td><INPUT TYPE="text" NAME="entercustomer_num"
                    value="<?php echo $entercustomer_num?>" class="form-control" style="width:90%"> <a href="#"
                                        onclick="window.open('billing_entity_card.php?popup_select=2&popup_formname=myForm&popup_fieldname=entercustomer_num' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><i class="flaticon2-fast-next"></i></a>
              </td>
			  </tr>
			  <tr>
			  <td align="left">	  
			  <?php echo gettext ( "CallPlan" ); ?> : </td>
			  <td><INPUT TYPE="text" NAME="entertariffgroup" value="<?php echo $entertariffgroup?>" size="4" class="form-control" style="width:90%">&nbsp;<a href="#" onclick="window.open('billing_entity_tariffgroup.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertariffgroup' , 'CallPlanSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
                <td width="5%">
               <b>OR</b> </td>
               <td align="left"> 
                <?php echo gettext ( "Provider" ); ?> :</td> 
				<td><INPUT
                            TYPE="text" NAME="enterprovider"
                            value="<?php echo $enterprovider?>" size="4" class="form-control" style="width:90%">&nbsp;<a href="#"
                            onclick="window.open('billing_entity_provider.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterprovider' , 'ProviderSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
              </tr>
			  <tr>
			  <td align="left">
			  
                <?php echo gettext ( "Trunk" ); ?> : </td>
				<td><INPUT
                            TYPE="text" NAME="entertrunk" value="<?php echo $entertrunk?>"
                            size="4" class="form-control" style="width:90%">&nbsp;<a href="#"
                            onclick="window.open('billing_entity_trunk.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertrunk' , 'TrunkSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
                 <td width="5%">
               <b>OR</b> </td>
               <td align="left"> 
                <?php echo gettext ( "Rate" ); ?> : </td>
				<td><INPUT
                            TYPE="text" NAME="enterratecard"
                            value="<?php echo $enterratecard?>" size="4"
                            class="form-control" style="width:90%">&nbsp;<a href="#"
                            onclick="window.open('billing_entity_def_ratecard.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterratecard' , 'RatecardSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><i class="flaticon2-fast-next"></i></a></td>
              
			   <?php
        }
        ?>
     </tr>
                <tr>

<td align="left" >
              <label class=""><?php echo gettext ( "Select Day" ); ?></label></td>
			  
              <td>
                <input type="checkbox"
                    name="fromday" value="true" <?php
                    if ($fromday) {
                        ?> checked
                    <?php
                    }
                    ?>>
                <select name="fromstatsday_sday" class="form-control11">
                    <?php
                    for ($i = 1; $i <= 31; $i ++) {
                        if ($fromstatsday_sday == sprintf ( "%02d", $i ))
                            $selected = "selected";
                        else
                            $selected = "";
                        echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                    }
                    ?>
                </select> <select name="fromstatsmonth_sday"
                    class="form-control13" style="width:60%;">
                <?php
                $year_actual = date ( "Y" );
                $monthname = array (gettext ( "January" ), gettext ( "February" ), gettext ( "March" ), gettext ( "April" ), gettext ( "May" ), gettext ( "June" ), gettext ( "July" ), gettext ( "August" ), gettext ( "September" ), gettext ( "October" ), gettext ( "November" ), gettext ( "December" ) );

                for ($i = $year_actual; $i >= $year_actual - 1; $i --) {
                    if ($year_actual == $i) {
                        $monthnumber = date ( "n" ) - 1; // Month number without lead 0.
                    } else {
                        $monthnumber = 11;
                    }
                    for ($j = $monthnumber; $j >= 0; $j --) {
                        $month_formated = sprintf ( "%02d", $j + 1 );
                        if ($fromstatsmonth_sday == "$i-$month_formated")
                            $selected = "selected";
                        else
                            $selected = "";
                        echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                    }
                }
                ?>
                </select>
					</td>
					<td>
					</td>
					<td align="left">
                    <label class=""><?php echo gettext("Number of days to compare");?></label></td>
					<td>
					 
                     <select name="days_compare" class="form-control">
                    <option value="4" <?php if ($days_compare=="4") { echo "selected";}?>>- 4 <?php echo gettext("days");?></option>
                    <option value="3" <?php if ($days_compare=="3") { echo "selected";}?>>- 3 <?php echo gettext("days");?></option>
                    <option value="2" <?php if (($days_compare=="2")|| !isset($days_compare)) { echo "selected";}?>>- 2 <?php echo gettext("days");?></option>
                    <option value="1" <?php if ($days_compare=="1") { echo "selected";}?>>- 1 <?php echo gettext("days");?></option>
                    </select>
					</td>
					</tr>
					<tr>
					
                    <td align="left" colspan="5">
					
					<table class="table" style="margin-bottom:0px;"> 
					<tr>
					 <td align="left" style="border-top:0px; width:16.5%; padding-left:0px;">
              <label class=""><?php echo gettext ( "Called Number" ); ?></label>
              </td>
			 <td style="border-top:0px;">
			  <INPUT TYPE="text" class="form-control" NAME="dst" value="<?php echo $dst?>"> 
			  </td>
			  <td>
			  <input
                    type="radio" NAME="dsttype" value="1"
                    <?php
                    if ((! isset ( $dsttype )) || ($dsttype == 1)) {
                        ?> checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Exact" );
                    ?></td>
					<td style="border-top:0px;">
					<input
                    type="radio" NAME="dsttype" value="2" <?php
                    if ($dsttype == 2) {
                        ?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Begins with" );
                    ?></td>
					<td style="border-top:0px;">
					<input
                    type="radio" NAME="dsttype" value="3" <?php
                    if ($dsttype == 3) { 
					?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Contains" );
                    ?></td>
					<td style="border-top:0px;">
					<input
                    type="radio" NAME="dsttype" value="4" <?php
                    if ($dsttype == 4) {
                        ?>
                    checked <?php
                    }
                    ?>> <?php
                    echo gettext ( "Ends with" );
                    ?>					
			  </td>
			  </tr>
			  </table>
			  </td>
			  </tr>
			  
			  <tr>
			  <td colspan="5">
			  
                           <!-- <input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />-->
                           <div class="form-actions">
                <input type="submit" class="btn btn-primary pull-right"  name="image16" border="0" value="<?php echo gettext("Search"); ?>" />
               
			  </td>
			  </tr>
			  </table>
			  </div>
			  </FORM>


<?php  if ($posted==1) { ?>



    <center>
    <?php echo gettext("TRAFFIC")?><br>
    <IMG SRC="graph_pie.php?graphtype=1&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterprovider=<?php echo $enterprovider?>&entertrunk=<?php echo $entertrunk?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
    </center>
    <br>

    <center>
    <?php echo gettext("PROFIT")?> <br>
    <IMG SRC="graph_pie.php?graphtype=2&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterprovider=<?php echo $enterprovider?>&entertrunk=<?php echo $entertrunk?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
    </center>

    <br>
        <center>
    <?php echo gettext("SELL")?> <br>
    <IMG SRC="graph_pie.php?graphtype=3&min_call=<?php echo $min_call?>&fromstatsday_sday=<?php echo $fromstatsday_sday?>&months_compare=<?php echo $months_compare?>&fromstatsmonth_sday=<?php echo $fromstatsmonth_sday?>&dsttype=<?php echo $dsttype?>&srctype=<?php echo $srctype?>&clidtype=<?php echo $clidtype?>&channel=<?php echo $channel?>&resulttype=<?php echo $resulttype?>&dst=<?php echo $dst?>&src=<?php echo $src?>&clid=<?php echo $clid?>&userfieldtype=<?php echo $userfieldtype?>&userfield=<?php echo $userfield?>&accountcodetype=<?php echo $accountcodetype?>&accountcode=<?php echo $accountcode?>&customer=<?php echo $customer?>&entercustomer=<?php echo $entercustomer?>&enterprovider=<?php echo $enterprovider?>&entertrunk=<?php echo $entertrunk?>&enterratecard=<?php echo $enterratecard?>&entertariffgroup=<?php echo $entertariffgroup?>" ALT="<?php echo gettext("Stat Graph");?>">
    </center>

<?php  } ?>
</center>

</div>
</div>
</div>

<?php
$smarty->display('footer.tpl');
