<?php
 

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';

getpost_ifset(array( 'tariffplan', 'group','task'));

$FG_DEBUG = 0;

$DBHandle  = DbConnect();

$instance_table_tariffname = new Table("cc_tariffgroup LEFT JOIN cc_agent_tariffgroup ON cc_tariffgroup.id = cc_agent_tariffgroup.id_tariffgroup", "id, tariffgroupname");

$FG_TABLE_CLAUSE = "id_agent = ".$_SESSION['agent_id'];

$list_tariffname = $instance_table_tariffname  -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "tariffgroupname", "ASC", null, null, null, null);

$instance_table_group = new Table("cc_card_group", "id, name");

$FG_TABLE_CLAUSE = "id_agent = ".$_SESSION['agent_id'];

$list_group = $instance_table_group -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, "id", "ASC", null, null, null, null);

$disabled =false;

if ($task=="generate" && !empty($tariffplan) && !empty($group)) {
    $code = gen_card('cc_agent_signup',10,'code');
    $table_signup = new Table('cc_agent_signup');
    $fields = "code,id_agent,id_tariffgroup,id_group";
    $values =  "'$code','".$_SESSION['agent_id']."', '$tariffplan','$group'";
    $result_insert = $table_signup -> Add_table($DBHandle,$values,$fields);
    if($result_insert)$URL = $A2B->config['signup']['urlcustomerinterface']."customer_signup.php?key=$code";
}

?>
<?php
$smarty->display('main.tpl');

?>
<script type="text/javascript">
<!--

function submit_form(form)
{
    if ((form.tariffplan.value.length < 1)||(form.group.value.length < 1)) {
        return (false);
    }

    document.forms["form"].elements["task"].value = "generate";
    document.form.submit();
}

//-->
</script>

<?php
    echo $CC_help_generate_signup;
?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            
            <h3 class="kt-subheader__title">
                User                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer Signup                       </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Add New Signup URL                      </a>
                         
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
       
    </div>
</div>
<!-- end:: Subheader -->					
					<!-- begin:: Content -->
	<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h1 class="kt-portlet__head-title">
				<?php echo gettext("Add New Signup URL  "); ?>
				
			</h1>
		</div>
	</div>
	 


     
       
    <div class="kt-portlet__body">



              <form name="form" enctype="multipart/form-data" action="billing_signup_agent.php" method="post" class="kt-form">
			  
			  
			  <div class="col-md-12">
		
		<table width="100%" border="0" cellspacing="5" cellpadding="5" class="table widget-box" style="border-bottom: 1px solid #CDCDCD;">

<tr>
    <td class="widget-title" colspan="4" style="border-top: 1px solid #CDCDCD; padding: 0px;">
                   <label class="control-label" style="margin-bottom: 0px; width:auto;">
			  <?php echo gettext("Customer Signup URL");?></label>
			  </td></tr>
			  
			  <tr>
			  <td colspan="4" align="center"><b style="color: #646c9a;"><?php echo gettext("Create signup url for a specific agent, customer group and Call Plan.");?>.</b>
			  </td>
			  </tr>

                <tr>
				
				<td>&nbsp;
						</td>
                  <td align=left width="30%">
                  <?php echo gettext("Choose the Call Plan to use");?></td>
				  <td width="50%">
                  <select id="tariff" NAME="tariffplan" size="1"  class="form-control" >
                                <option value=''><?php echo gettext("Choose a Call Plan");?></option>

                                <?php
                                 foreach ($list_tariffname as $recordset) {
                                ?>
                                    <option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset['id']==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>
                                <?php 	 }
                                ?>
                        </select>
                        </td>
						<td>&nbsp;
						</td>
						
						</tr>
						<tr>
						<td>&nbsp;
						</td>
						<td align="left" width="30%">
                   <?php echo gettext("Choose the Customer group to use");?></td>
				   <td width="50%">
                  <select id="group" NAME="group" size="1"  class="form-control" >
                              <option value=''><?php echo gettext("Choose a Customer Group");?></option>
                                <?php
                                 foreach ($list_group as $recordset) {
                                ?>
                                    <option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset[0]==$group) echo "selected";?>><?php echo $recordset[1]?></option>
                                <?php 	 }
                                ?>
                        </select>
                        

                </td>
				<td>&nbsp;
						</td>
				
                </tr>
               <tr>
                    <td>
                         &nbsp;
                     </td>
					 <td>
                         &nbsp;
                     </td>
					 <td>&nbsp;
						</td>
						 <td>&nbsp;
						</td>
                </tr>
                <tr>
				 <td>&nbsp;
						</td>
                 <td  align="right">
                        <a class="btn btn-secondary"  href="billing_entity_signup_agent.php?section=2">
                            <?php echo gettext("RETURN TO URL KEY LIST"); ?>
                        </a>
                  </td>
                  <td  align="right" >
                        <input type="hidden" name="task" value="">
                      <input id="generate" type="button" value="<?php echo gettext('ADD URL KEY');?>" onFocus=this.select() class="btn btn-brand" name="submit1" onClick="submit_form(this.form);">
                       </p>
                  </td>
				  <td>&nbsp;
						</td>
                </tr>
               
                <tr>
                  <td colspan="4"  align="left">

                        <div id="result" >

                         <?php if (!empty($URL)) { ?>
                         <span style="font-family: sans-serif" >
                         <b>
                             <a href="<?php echo $URL;?>"> <?php 	echo gettext("URL")."";?> <img src="<?php echo Images_Path."/link.png"?>" border="0" style="vertical-align:bottom;" title="<?php echo gettext("Link to the URL")?>" alt="<?php echo  gettext("Link to the URL")?>"></a>
                             <?php
                             echo " : ".$URL;	echo "</b><br>"; ?>
                           </span>
                        <?php  }  ?>

                        </div>

                  </td>
                </tr>

				</table>
				</div>
				
              </form>
			  
			  </div>
			  </div>
			  </div>
             

<?php
    $smarty->display('footer.tpl');
?>

<script type="text/javascript">

function checkgenerate()
{
 var test = true;
  test = test && ($('#tariff').val().length>0);
  test = test && ($('#group').val().length>0);
  if (test) {
       $('#generate').removeAttr("disabled");
       $('#generate').attr("class","btn btn-brand");
   } else {
      $('#generate').attr("disabled", true);
      $('#generate').attr("class","btn btn-brand");
      }
}

$(document).ready(function () {
    $('#selectagent').change(function () {
              document.form.method="GET";
              $('form').submit();
            });
    $('#group').change(function () {
               checkgenerate();
               $('#result').empty();
            });
    $('#tariff').change(function () {
               checkgenerate();
               $('#result').empty();
            });
});
</script>
