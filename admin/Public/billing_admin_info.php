<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_ADMINISTRATOR)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id','groupID'));

if(!is_numeric($groupID) || ($groupID != 0 && $groupID != 1)) $groupID =0;

if (empty($id)) {
    header("Location: billing_entity_user.php?atmenu=user&groupID=$groupID&section=3");
}

$DBHandle  = DbConnect();

$admin_table = new Table('cc_ui_authen','*');
$admin_clause = "userid = ".$id;
$admin_result = $admin_table -> Get_list($DBHandle, $admin_clause, 0);
$admin = $admin_result[0];

if (empty($admin)) {
    header("Location: billing_entity_user.php?atmenu=user&groupID=$groupID&section=3");
}

// #### HEADER SECTION
$smarty->display('main.tpl');
$lg_liste= Constants::getLanguages();
?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                User                            </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Administrator                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            List Administrator	                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_user.php" class="kt-subheader__breadcrumbs-link">
                            Administrator Info                        </a>
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
				<?php echo gettext("ADMIN INFO") ?>
			</h1>
		</div>
	</div>

<table style="width : 80%;" >
    <tr>
        <td>
            <label class="col-12 col-form-label"><?php echo gettext("LOGIN") ?> :
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['login']?></p>
        </td>
    </tr>
    <tr height="20px">
        <td  >
            <label class="col-12 col-form-label"><?php echo gettext("NAME") ?> :</LABEL>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['name']?></p>
        </td>
    </tr>

    <tr height="20px">
        <td>
            <label class="col-12 col-form-label"><?php echo gettext("ADDRESS") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['direction']?></p>
        </td>

    </tr>

    <tr height="20px">
        <td>
            <label class="col-12 col-form-label"><?php echo gettext("ZIP CODE") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['zipcode']?></p>
        </td>
    </tr>

    <tr  height="20px">
        <td>
           <label class="col-12 col-form-label"> <?php echo gettext("CITY") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['city']?></p>
        </td>

    </tr>

    <tr  height="20px">
        <td>
            <label class="col-12 col-form-label"><?php echo gettext("STATE") ?> :</p>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['state']?></p>
        </td>

    </tr>

    <tr  height="20px">
        <td >
           <label class="col-12 col-form-label"> <?php echo gettext("COUNTRY") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['country']?></p>
        </td>

    </tr>
    <tr  height="20px">
        <td>
           <label class="col-12 col-form-label"> <?php echo gettext("EMAIL") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['email']?></p>
        </td>

    </tr>
    <tr  height="20px">
        <td>
            <label class="col-12 col-form-label"><?php echo gettext("PHONE") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['phone']?></p>
        </td>
    </tr>
    <tr  height="20px">
        <td>
           <label class="col-12 col-form-label"> <?php echo gettext("FAX") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $admin['fax']?></p>
        </td>
    </tr>

 </table>
 <br/>
<div style="width : 95%; text-align : right; margin-left:auto;margin-right:auto;" >
     <a class="btn btn-primary"  href="<?php echo "billing_entity_user.php?atmenu=user&groupID=$groupID&section=3" ?>">
        
        <?php echo gettext("ADMIN LIST"); ?> 
    </a>
	<br>
</div>
<?php

$smarty->display( 'footer.tpl');
