<?php


include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (!has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday','entercustomer','id_cc_card'));

if (!isset ($current_page) || ($current_page == "")) {
    $current_page=0;
}

$FG_DEBUG = 0;

$FG_TABLE_NAME="cc_card_history ch LEFT JOIN cc_card ON cc_card.id=id_cc_card";

$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F2EE";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FCFBFB";

$DBHandle  = DbConnect();
$FG_TABLE_COL = array();
$FG_TABLE_COL[]=array (gettext("Account Number"), "username", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$FG_TABLE_COL[]=array (gettext("Date"), "datecreated", "20%", "center", "SORT");
$FG_TABLE_COL[]=array (gettext("Description"), "description", "60%", "center", "SORT");

$FG_TABLE_DEFAULT_ORDER = "ch.datecreated";
$FG_TABLE_DEFAULT_SENS = "DESC";

$FG_COL_QUERY = 'username, ch.datecreated, ch.description';
$FG_LIMITE_DISPLAY = 25;
$FG_NB_TABLE_COL=count($FG_TABLE_COL);
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
$FG_HTML_TABLE_TITLE = " - ".gettext("Customer History")." - ";
$FG_HTML_TABLE_WIDTH = "98%";

$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);

if (is_null ($order) || is_null($sens)) {
    $order = $FG_TABLE_DEFAULT_ORDER;
    $sens  = $FG_TABLE_DEFAULT_SENS;
}

$date_clause='';
if (DB_TYPE == "postgres") {
         $UNIX_TIMESTAMP = "";
} else {
        $UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}
normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) $date_clause.=" AND $UNIX_TIMESTAMP(ch.datecreated) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) $date_clause.=" AND $UNIX_TIMESTAMP(ch.datecreated) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";

if (strpos($SQLcmd, 'WHERE') > 0) {
    $FG_TABLE_CLAUSE = substr($SQLcmd,6).$date_clause;
} elseif (strpos($date_clause, 'AND') > 0) {
    $FG_TABLE_CLAUSE = substr($date_clause,5);
}

if (!isset ($FG_TABLE_CLAUSE) || strlen($FG_TABLE_CLAUSE)==0) {
    $cc_yearmonth = sprintf("%04d-%02d-%02d",date("Y"),date("n"),date("d"));
    $FG_TABLE_CLAUSE=" $UNIX_TIMESTAMP(ch.datecreated) >= $UNIX_TIMESTAMP('$cc_yearmonth')";
}

if (isset($entercustomer)  &&  ($entercustomer>0)) {
    if (strlen($FG_TABLE_CLAUSE)>0) $FG_TABLE_CLAUSE.=" AND ";
    $FG_TABLE_CLAUSE.="ch.id_cc_card='$entercustomer'";
}

if (!$nodisplay) {
    $list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
    $nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE);
}

if ($nb_record<=$FG_LIMITE_DISPLAY) {
    $nb_record_max=1;
} else {
    if ($nb_record % $FG_LIMITE_DISPLAY == 0) {
        $nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY));
    } else {
        $nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY)+1);
    }
}

if ($FG_DEBUG == 3) echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3) echo "<br>Nb_record_max : $nb_record_max";

/*************************************************************/

$smarty->display( 'main.tpl');

?>
  
<?php
    //print_r($_POST);
?>
    <!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
  
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
				Card History                      </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
						<a href="" class="kt-subheader__breadcrumbs-link">
                            User                        </a>
							  				<span class="kt-subheader__breadcrumbs-separator"></span>
						<a href="" class="kt-subheader__breadcrumbs-link">
                            Customer                        </a>
							  				<span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            History                        </a>
                                            
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<!-- end:: Subheader -->
  
<div class="kt-portlet">
	<div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
		<h3 class="kt-portlet__head-title">
           <?php echo gettext("Customer Card History");?>
	    </h3>
        </div>
	</div>
        
    <div class="kt-portlet__body">      
        
        <form METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>&terminatecauseid=<?php echo $terminatecauseid?>" class="kt-form">
            <INPUT TYPE="hidden" NAME="posted" value=1>
            <INPUT TYPE="hidden" NAME="current_page" value=0>
			
			<table border="0" cellspacing="2" align="center" class="table">
			<tr>
			<td style="border:0;">
			</td>
			
			<td style="border:0;"><?php echo gettext("Enter the customer ID  ");?>
			</td>
			
			<td style="border:0;"> <input TYPE="text" NAME="entercustomer" value="<?php echo $entercustomer?>" class="form-control" style="   float: left;"> 
							
							
			</td>
			
			<td style="border:0;"><a href="#" onclick="window.open('billing_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','scrollbars=1,scrollbars=1');">
							
								<i class ="flaticon2-next" ></i>
						
							</a>
			</td>
			
			
			</tr>
			

<tr>

<td></td>
<td><?php echo gettext("DATE");?> </td>			
<td><input type="checkbox" name="fromday" value="true" 
							<?php  
								if($fromday) 
								{ 
							?>checked<?php }?>> 
						
							<label class=""><?php echo gettext("FROM :");?> </label> <select name="fromstatsday_sday" class="form-control11">
						<?php
							for ($i=1;$i<=31;$i++) 
							{
								if ($fromstatsday_sday==sprintf("%02d",$i)) {$selected="selected";} else {$selected="";}
								echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
							}
						?>
					</select>
					 <select name="fromstatsmonth_sday" class="form-control13" style="width:56%;">
						<?php
							$monthname = array( gettext("JANUARY"), gettext("FEBRUARY"), gettext("MARCH"), gettext("APRIL"), gettext("MAY"), gettext("JUNE"), gettext("JULY"), gettext("AUGUST"), gettext("SEPTEMBER"), gettext("OCTOBER"), gettext("NOVEMBER"), gettext("DECEMBER"));
							$year_actual = date("Y");
							for ($i=$year_actual;$i >= $year_actual-1;$i--) 
							{
								$monthname = array( gettext("JANUARY"), gettext("FEBRUARY"), gettext("MARCH"), gettext("APRIL"), gettext("MAY"), gettext("JUNE"), gettext("JULY"), gettext("AUGUST"), gettext("SEPTEMBER"), gettext("OCTOBER"), gettext("NOVEMBER"), gettext("DECEMBER"));
								if ($year_actual==$i) 
								{
									$monthnumber = date("n")-1; // Month number without lead 0.
								} else 
								{
									$monthnumber=11;
								}
								for ($j=$monthnumber;$j>=0;$j--) 
								{
									$month_formated = sprintf("%02d",$j+1);
									   if ($fromstatsmonth_sday=="$i-$month_formated") {$selected="selected";} else {$selected="";}
									echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
								}
							}
						?>
					</select></td>
					<td></td>


</tr>

<tr>

<td></td>
<td></td>
<td><input type="checkbox" name="fromday" value="true" 
							<?php  if ($today) { ?>checked<?php }?>> 
						
							<label class=""><?php echo gettext("TO :");?> </label> &nbsp; &nbsp; &nbsp;
							<select name="tostatsday_sday" class="form-control11">
						<?php
							for ($i=1;$i<=31;$i++) 
							{
								if ($tostatsday_sday==sprintf("%02d",$i)) {$selected="selected";} else {$selected="";}
								echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
							}
						?>
					</select>
					<select name="tostatsmonth_sday" class="form-control13" style="width:56%;">
						<?php   $year_actual = date("Y");
							for ($i=$year_actual;$i >= $year_actual-1;$i--) 
							{
								   $monthname = array( gettext("JANUARY"), gettext("FEBRUARY"), gettext("MARCH"), gettext("APRIL"), gettext("MAY"), gettext("JUNE"), gettext("JULY"), gettext("AUGUST"), gettext("SEPTEMBER"), gettext("OCTOBER"), gettext("NOVEMBER"), gettext("DECEMBER"));
								   if ($year_actual==$i) {
										$monthnumber = date("n")-1; // Month number without lead 0.
								   } else {
										$monthnumber=11;
								   }
								   for ($j=$monthnumber;$j>=0;$j--) {
											$month_formated = sprintf("%02d",$j+1);
											   if ($tostatsmonth_sday=="$i-$month_formated") {$selected="selected";} else {$selected="";}
											echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
								   }
							}
						?>
					</select></td>
<td></td>
			</tr>
			<tr>
			<td colspan="5" align="right">
			
          			 
				<input class="btn btn-primary" value=" <?php echo gettext("Search");?> " type="submit">
				
		 </td>
		 </tr>
		 </table>
	</div>
</div>
		</form>
        
      
	<br />

    <div class="kt-widget1">
	<!-- ** ** ** ** ** Part to display the CDR ** ** ** ** ** -->
		<table width="<?php echo $FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0" class="table">
			<TR bgcolor="#ffffff">
				<TD class="sorting_1">
					<TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
						<TBODY>
							<TR>
								<TD><SPAN style="COLOR: #666; FONT-SIZE: 11px">
								<B>
									<label class="col-12 col-form-label"><?php echo $FG_HTML_TABLE_TITLE?></LABEL>
								</B>
								</SPAN></TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
			</TR>
			<TR>
				<TD>
					<TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
						<TR class="odd">
							<TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="sorting_1" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px;"></TD>

							<?php
								if (is_array($list) && count($list)>0) {
								for ($i=0;$i<$FG_NB_TABLE_COL;$i++) {
							?>
							<td class="sorting_1" style="padding: 2px;" align="center" width="<?php echo $FG_TABLE_COL[$i][2]?>" >
								<strong>
									<?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT") {?>
									<a href="<?php  echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=".$FG_TABLE_COL[$i][1]."&sens="; if ($FG_SENS=="ASC") {echo"DESC";} else {echo"ASC";}
									echo "&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday";?>">
									<font color="#666666"><?php  } ?>
										<?php echo $FG_TABLE_COL[$i][0]?>
										<?php if ($FG_ORDER==$FG_TABLE_COL[$i][1] && $FG_SENS=="ASC") {?>
										&nbsp;<img src="<?php echo Images_Path_Main;?>/icon_up_12x12.GIF" border="0">
										<?php } elseif ($FG_ORDER==$FG_TABLE_COL[$i][1] && $FG_SENS=="DESC") {?>
										&nbsp;<img src="<?php echo Images_Path_Main;?>/icon_down_12x12.GIF" border="0">
										<?php }?>
										<?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT") {?>
									</font></a>
									<?php }?>
								</strong>
							</TD>
							<?php } ?>

						</TR>
						<?php
							   $ligne_number=0;
							   foreach ($list as $recordset) {
								 $ligne_number++;
								 $recordset[1] = display_GMT($recordset[1], $_SESSION["gmtoffset"], 1);
						?>

                        <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"  onmouseover="bgColor='#FFDEA6'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>'">
							<TD vAlign="top" align="<?php echo $FG_TABLE_COL[$i][3]?>" class="sorting_1"><?php  echo $ligne_number+$current_page*$FG_LIMITE_DISPLAY.".&nbsp;"; ?></TD>
							<?php for ($i=0;$i<$FG_NB_TABLE_COL;$i++) { ?>
							<TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class="sorting_1">
								<?php
									$record_display = $recordset[$i];
									if($FG_TABLE_COL[$i][11] == "linktocustomer") echo linktocustomer(stripslashes($record_display));
									else echo stripslashes($record_display); 
								?>
                            </TD>
							<?php } ?>
						</TR>
						<?php
							}//foreach ($list as $recordset)
							if ($ligne_number < $FG_LIMITE_DISPLAY)  $ligne_number_end=$ligne_number +2;
								while ($ligne_number < $ligne_number_end) {
								$ligne_number++;
						?>
						<TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>">
							  <?php for ($i=0;$i<$FG_NB_TABLE_COL;$i++) {
							  ?>
							  <TD vAlign=top class="sorting_1">&nbsp;</TD>
							  <?php  } ?>
							  <TD align="center" vAlign=top class="sorting_1">&nbsp;</TD>
						</TR>

						<?php
							} //END_WHILE

						} 
						else 
						{
								  echo gettext("No data found !!!");
						}//end_if
						?>
					</TABLE>
				</td>
			</tr>
		</table>
	</div>
</div>

<?php

$smarty->display( 'footer.tpl');
