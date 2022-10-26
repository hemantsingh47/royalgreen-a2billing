<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_ADMINISTRATOR)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

if (empty($id)) {
    header("Location: billing_entity_agent.php?atmenu=user&section=2");
}

$DBHandle  = DbConnect();

$agent_table = new Table('cc_agent','*');
$agent_clause = "id = ".$id;
$agent_result = $agent_table -> Get_list($DBHandle, $agent_clause, 0);
$agent = $agent_result[0];

if (empty($agent)) {
    header("Location: billing_entity_agent.php?atmenu=user&section=2");
}

// #### HEADER SECTION
$smarty->display('main.tpl');
$lg_liste= Constants::getLanguages();
?>

	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
            Agent Information                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Users                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                             Agent                       </a>
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
				<?php echo gettext("AGENT INFO") ?>
			</h1>
		</div>
	</div>
<br>
<table style="width : 80%;" >
    <tr>
        <td>
            <label class="col-12 col-form-label" style="font-weight:600"><?php echo gettext("LOGIN") ?> :
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['login']?></p>
        </td>
    </tr>
    <tr height="20px">
        <td  >
            <label class="col-12 col-form-label" style="font-weight:600"><?php echo gettext("NAME") ?> :</LABEL>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['name']?></p>
        </td>
    </tr>

    <tr height="20px">
        <td>
            <label class="col-12 col-form-label" style="font-weight:600"><?php echo gettext("ADDRESS") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['direction']?></p>
        </td>

    </tr>

    <tr height="20px">
        <td>
            <label class="col-12 col-form-label" style="font-weight:600"><?php echo gettext("ZIP CODE") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['zipcode']?></p>
        </td>
    </tr>

    <tr  height="20px">
        <td>
           <label class="col-12 col-form-label" style="font-weight:600"> <?php echo gettext("CITY") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['city']?></p>
        </td>

    </tr>

    <tr  height="20px">
        <td>
            <label class="col-12 col-form-label" style="font-weight:600"><?php echo gettext("STATE") ?> :</p>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['state']?></p>
        </td>

    </tr>

    <tr  height="20px">
        <td >
           <label class="col-12 col-form-label" style="font-weight:600"> <?php echo gettext("COUNTRY") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['country']?></p>
        </td>

    </tr>
    <tr  height="20px">
        <td>
           <label class="col-12 col-form-label" style="font-weight:600"> <?php echo gettext("EMAIL") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['email']?></p>
        </td>

    </tr>
    <tr  height="20px">
        <td>
            <label class="col-12 col-form-label" style="font-weight:600"><?php echo gettext("PHONE") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['phone']?></p>
        </td>
    </tr>
    <tr  height="20px">
        <td>
           <label class="col-12 col-form-label" style="font-weight:600"> <?php echo gettext("FAX") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static" style="font-weight:500"><?php echo $agent['fax']?></p>
        </td>
    </tr>

 </table>
 <br/>
<div style="width : 80%; text-align : right; margin-left:auto;margin-right:auto;" >
     <a class="kt-wizard-v4__nav-item nav-item"  href="<?php echo "billing_entity_agent.php?atmenu=user&groupID=$groupID&section=3" ?>">
        
        <span class="btn btn-brand btn-elevate btn-icon-sm"><?php echo gettext("AGENT LIST"); ?> </span>
    </a>
</div>
<br><br>
<?php

$smarty->display( 'footer.tpl');
