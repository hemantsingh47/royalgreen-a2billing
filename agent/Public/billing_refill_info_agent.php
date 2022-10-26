<?php

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_BILLING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id'));

if (empty($id)) {
    header("Location: billing_entity_logrefill_agent.php?atmenu=payment&section=2");
}

$DBHandle  = DbConnect();

$refill_table = new Table('cc_logrefill_agent','*');
$refill_clause = "id = ".$id;
$refill_result = $refill_table -> Get_list($DBHandle, $refill_clause, 0);
$refill = $refill_result[0];

if (empty($refill)) {
    header("Location: billing_entity_logrefill_agent.php?atmenu=payment&section=2");
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
                Refill Info                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Billing                    </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Agent's Refill                     </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
       
    </div>
</div>

<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h1 class="kt-portlet__head-title">
				 <?php echo gettext("REFILL INFO") ?>
				
			</h1>
		</div>
	</div>
	
	 <div class="kt-portlet__body">
	 
	 <div class="col-md-12">
<table class="table widget-box" style="border-left: 1px solid #eee; border-right: 1px solid #eee; border-top: 1px solid #eee;">
      <tr>
        <td  class="form_head">
            <?php echo gettext("AGENT") ?> :
        </td>
        <td class="tableBodyRight" >
            <?php echo nameofagent($refill['agent_id']);?>
        </td>
   </tr>
   <tr >
        <td  class="form_head">
            <?php echo gettext("AMOUNT") ?> :
        </td>
        <td class="tableBodyRight"  >
            <?php echo $refill['credit']." ".strtoupper(BASE_CURRENCY);?>
        </td>
   </tr>
       <tr >
        <td  class="form_head">
            <?php echo gettext("CREATION DATE") ?> :
        </td>
        <td class="tableBodyRight"  >
            <?php echo $refill['date']?>
        </td>
    </tr>
   <tr >
        <td  class="form_head">
            <?php echo gettext("REFILL TYPE") ?> :
        </td>
        <td class="tableBodyRight"  >
            <?php
            $list_type = Constants::getRefillType_List();
            echo $list_type[$refill['refill_type']][0];?>
        </td>
   </tr>
   <tr >
        <td  class="form_head">
            <?php echo gettext("DESCRIPTION ") ?> :
        </td>
        <td class="tableBodyRight"  >
            <?php echo $refill['description']?>
        </td>
    </tr>

 </table>
 <br/>
<div style="width : 100%; text-align : right; margin-left:auto;margin-right:auto;" >
     <a class="btn btn-primary"  href="billing_entity_logrefill_agent.php?atmenu=payment&section=2">
        <?php echo gettext("AGENT'S REFILL LIST"); ?>
    </a>
</div>

</div>
</div>
</div>
</div>

<?php

$smarty->display( 'footer.tpl');
