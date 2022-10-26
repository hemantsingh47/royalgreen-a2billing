<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_PACKAGEOFFER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id', 'addrate', 'delallrate', 'addbatchrate', 'delrate', 'id_trunk', 'id_tariffplan','tag', 'prefix', 'destination', 'rbDestination', 'rbPrefix'));

if (empty($id)) {
    Header ("Location: billing_entity_package.php?atmenu=package&section=12");
}

$table_pack = new Table("cc_package_offer ","*");
$pack_clauses = "id = $id";
$result_pack=$table_pack ->Get_list(DbConnect(), $pack_clauses);

if (!is_array($result_pack)|| sizeof($result_pack)!=1) {
    Header ("Location: billing_entity_package.php?atmenu=package&section=12");
}

if (isset($addbatchrate) && ($addbatchrate)) {
    $DBHandle = DbConnect();

    $rates_clauses = "";
    $table_rates = new Table("cc_ratecard"," DISTINCT COUNT(destination)");
    if (isset($id_trunk)) {
        $rates_clauses = " id_trunk = '{$id_trunk}'";
    }
    if (isset($id_tariffplan)) {
        $rates_clauses .= (isset($id_trunk)? 'AND':'') . " idtariffplan = '{$id_tariffplan}'";
    }
    if (isset($tag)) {
        $rates_clauses .= (!empty($rates_clauses)? 'AND':'') . " rc.tag = '{$tag}'";
    }
    if (isset($prefix)) {
        $rates_clauses .= (!empty($rates_clauses)? 'AND':'');
        switch ($rbPrefix) {
            case 1 : $rates_clauses .= " destination = '{$prefix}'"; break;
            case 2 : $rates_clauses .= " destination LIKE '{$prefix}%'"; break;
            case 3 : $rates_clauses .= " destination LIKE '%{$prefix}%'"; break;
            case 4 : $rates_clauses .= " destination LIKE '%{$prefix}'"; break;
            case 5 :
                    $arr_prefix = array();
                    if ( strpos($prefix,',') ) {
                        $single = explode(',', $prefix);

                        foreach ($single as $value) {
                            if ( strpos( $value, '-' ) ) {
                                $arr_prefix[] = explode( '-', $value );
                            } else {
                                $arr_prefix[] = $value;
                            }
                        }
                    } elseif ( strpos( $prefix,'-' ) ) {
                        $arr_prefix[] = explode( '-', $prefix );
                    } else {
                        $arr_prefix[] = $prefix;
                    }

                    if ( sizeof($arr_prefix,1) ) {
                        end( $arr_prefix );
                        $last_key = key( $arr_prefix );
                        foreach ($arr_prefix as $key=>$value) {
                            $OPL = ($key == $last_key)? '':' OR ';
                            if ( is_array( $value ) ) {
                                $rates_clauses .= " (destination BETWEEN '$value[0]' AND '$value[1]') $OPL";
                            } else {
                                $rates_clauses .= " destination = '$value' $OPL";
                            }
                        }
                    }
            break;
            default : $rates_clauses .= " destination = '{$prefix}'"; break;
        }
    }
    $QUERY = "SELECT {$id},id FROM cc_ratecard rc WHERE {$rates_clauses} GROUP BY destination";
    $table_rates -> SQLExec( $DBHandle, "INSERT IGNORE INTO cc_package_rate(package_id , rate_id) ({$QUERY})" );
    Header ("Location: billing_package_manage_rates.php?id=$id");
}

if (isset($addrate) && is_numeric($addrate)) {
    $DBHandle = DbConnect();
    $add_rate_table = new Table("cc_package_rate", "*");
    $fields = " package_id , rate_id";
    $values = " $id , $addrate";
    $add_rate_table->Add_table($DBHandle, $values, $fields);
    Header ("Location: billing_package_manage_rates.php?id=$id");
}

if (isset($delrate) && is_numeric($delrate)) {
    $DBHandle = DbConnect();
    $del_rate_table = new Table("cc_package_rate", "*");
    $CLAUSE = " package_id = " . $id . " AND rate_id = $delrate";
    $del_rate_table->Delete_table($DBHandle, $CLAUSE);
    Header ("Location: billing_package_manage_rates.php?id=$id");
}

if (isset($delallrate) && ($delallrate)) {
    $DBHandle = DbConnect();
    $del_rate_table = new Table("cc_package_rate", "*");
    $CLAUSE = " package_id = " . $id;
    $del_rate_table->Delete_table($DBHandle, $CLAUSE);
    Header ("Location: billing_package_manage_rates.php?id=$id");
}

$smarty->display('main.tpl');

//load rates
$DBHandle = DbConnect();

$table_rates = new Table("cc_package_rate JOIN cc_ratecard ON cc_ratecard.id = cc_package_rate.rate_id LEFT JOIN cc_prefix ON cc_prefix.prefix = cc_ratecard.destination ","DISTINCT cc_ratecard.id,cc_prefix.destination, cc_ratecard.dialprefix");
$rates_clauses = " cc_package_rate.package_id = $id";
$result_rates=$table_rates ->Get_list(DbConnect(), $rates_clauses);

echo $CC_help_offer_package;

?>
<br/>

<SCRIPT LANGUAGE="javascript">
var win= null;
function addrate(selvalue)
{
    //test si win est encore ouvert et close ou refresh
    win=MM_openBrWindow('billing_entity_def_ratecard.php?popup_select=1&package=<?php echo $id ?>','','scrollbars=yes,resizable=yes,width=700,height=500');
}
function delrate()
{
    //test si val is not null & numeric
    if ($('#rate').val()!=null) {
        self.location.href= "billing_package_manage_rates.php?id=<?php echo $id; ?>&delrate="+$('#rate').val();
    }
}
function delallrate()
{
    self.location.href= "billing_package_manage_rates.php?id=<?php echo $id; ?>&delallrate=true";
}
</SCRIPT>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Billing                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Packages                      </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_package_manage_rates.php" class="kt-subheader__breadcrumbs-link">
                            Manage Package Rates                </a>
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
           <?php echo gettext("Manage Package Rates"); ?>
		   
	    </h5>
        </div>
	</div>
<br>


<table style="width : 80%;" class="editform_table1">
    <tr height="20px">
        <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("PACKAGE: "); ?>
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php echo $result_pack[0]['label']; ?>
			</p>		
		</td>
	</tr>
	<tr>
		<td>
            <label class="col-12 col-form-label">
				<?php echo gettext("DATE: "); ?>
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php echo $result_pack[0]['creationdate']; ?>
			</p>		
		</td>
            
    </tr>
    <tr>
		<td>
            <label class="col-12 col-form-label">
				<?php echo gettext("PACKAGE TYPE"); ?>
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php $pck_type = Constants::getPackagesTypeList(); echo $pck_type[$result_pack[0]['packagetype']][0]; ?>
			</p>		
		</td>
		
		
    </tr>
    <tr>
        <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("NUMBER"); ?>
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<?php echo $result_pack[0]['freetimetocall']; ?>&nbsp;<?php $pck_type = Constants::getPackagesTypeList(); echo $pck_type[$result_pack[0]['packagetype']][0]; ?>&nbsp;<?php echo gettext('per') ?>
                <?php if($result_pack[0]['billingtype']==0) echo gettext("month"); else echo gettext("week"); ?>
			</p>		
		</td>
			
			
    </tr>
	<tr>
        <td>
            <label class="col-12 col-form-label">
				<?php echo gettext("RATES ASSIGNED"); ?>
			</label>
        </td>
        <td>
			<p class="form-control-static">
				<select id="rate" name="rate" size="10" style="width:250px;" class="form-control">
					<?php foreach ($result_rates as $rate) { ?>
					<option value="<?php echo $rate['id'] ?>"  ><?php echo $rate['destination'] ;?>&nbsp;:&nbsp;<?php echo $rate['dialprefix']; ?></option>
					<?php } ?>
				</select>
			</p>		
		</td>
	</tr>
	<tr>
          
		<td align="center">
			<a href="javascript:;" onClick="addrate()" > <img src="../Public/templates/default/images/add.png" alt="<?php echo gettext("Add Rate"); ?>" border="0"></a>
			<a href="javascript:;" onClick="delrate()" > <img src="../Public/templates/default/images/del.png" alt="<?php echo gettext("Del Rate"); ?>" border="0"></a>
			<a href="javascript:;" onClick="delallrate()" > <img src="../Public/templates/default/images/delete.png" alt="<?php echo gettext("Del All Rate"); ?>" border="0"></a>
		</td>
	</tr>
</table>
        



<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');
