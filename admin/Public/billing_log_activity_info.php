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
    header("Location: A2B_entity_log_viewer.php?section=16");
}

$DBHandle  = DbConnect();

$log_table = new Table('cc_system_log','*');
$log_clause = "id = ".$id;
$log_result = $log_table -> Get_list($DBHandle, $log_clause, 0);
$log = $log_result[0];

if (empty($log)) {
    header("Location: A2B_entity_log_viewer.php?section=16");
}

// #### HEADER SECTION
$smarty->display('main.tpl');
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                Others                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                           Others                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Tools                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_log_viewer.php?section=16" class="kt-subheader__breadcrumbs-link">
                            Activity Info                     </a>
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
				<?php echo gettext("Activity Info "); ?>
				
			</h1>
		</div>
	</div>
	<br>


<table style="width : 80%;" >
   
   <tr height="20px">
        <td >
            <label class="col-12 col-form-label"><?php echo gettext("ID") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php  echo $log['id'];?></p>
			
        </td>
   </tr>
   <tr height="20px">
        <td >
            <label class="col-12 col-form-label"><?php echo gettext("USER") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo nameofadmin($log['iduser']);?></p>
			
        </td>
		
   </tr>
    <tr height="20px">
        <td >
            <label class="col-12 col-form-label"><?php echo gettext("LOG-LEVEL") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $log['loglevel']?></p>
			
        </td>
		
		
    </tr>
        
	<tr height="20px">
        <td >
            <label class="col-12 col-form-label"><?php echo gettext("ACTION") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $log['action']?></p>
			
        </td>
		
	</tr>
    <tr height="20px">
        <td >
            <label class="col-12 col-form-label"><?php echo gettext("DESCRIPTION") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $log['description']?></p>
			
        </td>
	
    </tr>
    <tr height="20px">
        <td >
            <label class="col-12 col-form-label"><?php echo gettext("TABLENAME") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $log['tablename']?></p>
			
        </td>
		
		
    </tr>
    <tr height="20px">
        <td >
            <label class="col-12 col-form-label"><?php echo gettext("IPADDRESS") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $log['ipaddress']?></p>
			
        </td>
		
    </tr>
    <tr height="20px">
        <td >
            <label class="col-12 col-form-label"><?php echo gettext("CREATION DATE") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"><?php echo $log['creationdate']?></p>
			
        </td>
		
		
    </tr>
    <tr height="20px">
         <td >
            <label class="col-12 col-form-label"><?php echo gettext("DATA") ?> :</label>
        </td>
        <td>
            &nbsp;<p class="form-control-static"> <?php echo $log['data']?></p>
			
        </td>
		
		<td  class="form_head">
            
        </td>
        
    </tr>
 </table>
 <br/>
 <div style="width : 80%; text-align : right; margin-left:auto;margin-right:auto;" >
     <a class="btn btn-primary"  href="<?php echo "billing_entity_log_viewer.php?section=16" ?>">
        
       <?php echo gettext("LOG LIST"); ?> 
    </a>
	<br>
</div>
<br>
<?php

$smarty->display( 'footer.tpl');
