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

<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Customer SignUp </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Home                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                    Customer Signup URL                        </a>
                </div>
            </span>
        </div>
    </div>
</div>
<!-- end:: Subheader -->


<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="col-md-12" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                    <font class="kt-portlet__head-title"><?php echo gettext("Add Customer Signup URL"); ?></font>
				</div>
			</div>

      <!--begin::Form-->
			<form method="post" class="kt-form" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-modif"?>" name="frmPass"> <br> 
            <div align="center"><b><?php echo gettext("Create signup url for a specific agent, using customer group and Call Plan.");?></b></div><br/><br/>
				<div class="kt-portlet__body">
					<div class="form-group row">
						<font class="col-lg-4 col-sm-12"><?php echo gettext("Choose the Call Plan to use");?> </font>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <select id="tariff" name="component" class="form-control">
                            <td>
                                    <option value=''><?php echo gettext("Choose a Call Plan");?></option>
                                    <?php
                                 foreach ($list_tariffname as $recordset) {
                                ?>
                                    <option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset['id']==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>
                                <?php 	 }
                                ?>
                                </select>
                            </td>
                        </div>    
					</div>
					
                    <div class="form-group row">
						<font class="col-lg-4 col-sm-12"><?php echo gettext("Choose the Customer group to use");?> </font>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <select id="tariff" name="component" class="form-control">
                            <td>
                                    <option value=''><?php echo gettext("Choose a Customer Group");?></option>
                                    <?php
                                 foreach ($list_tariffname as $recordset) {
                                ?>
                                    <option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset['id']==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>
                                <?php 	 }
                                ?>
                                </select>
                            </td>
                        </div>    
					</div>

                    <div align="center" class="kt-portlet__foot">
                    <div class="form-actions">
                        <a class="btn btn-brand"  href="billing_entity_signup_agent.php?section=2">
                            <?php echo gettext("RETURN TO URL KEY LIST"); ?>
                        </a> &nbsp;&nbsp;&nbsp;&nbsp;
                        <input id="generate" type="button" name="cancel" value="&nbsp;<?php echo gettext('ADD URL KEY');?>&nbsp;" class="btn btn-success" onFocus=this.select() class="btn btn-success" name="submit1" onClick="submit_form(this.form);">
                    </div>
                    </div>

                    <tr>
                        <td colspan="2"> &nbsp; </td>
                    </tr>

                    <div id="result" >
                        <?php if (!empty($URL)) { 
                            ?>
                                <span style="font-family: sans-serif" >
                                <b> 
                                    <a href="<?php echo $URL;?>"> <?php 	echo gettext("URL")."";?> <img src="<?php echo Images_Path."/link.png"?>" border="0" style="vertical-align:bottom;" title="<?php echo gettext("Link to the URL")?>" alt="<?php echo  gettext("Link to the URL")?>"></a>
                                    <?php
                                        echo " : ".$URL;	echo "</b><br>"; 
                                    ?>
                                </span>
                        <?php  
                        }  
                        ?>
                    </div>
			</form>
			<!--end::Form-->


<!-- <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
<h5><?php echo gettext("Customer Signup URL");?></h5> 
</div> 
<div align="center" class="md-card">
<div align="center" class="md-card-content">
<center>
        <b><?php echo gettext("Create signup url for a specific agent, customer group and Call Plan.");?>.</b><br/><br/>
        <table width="95%" border="0" cellspacing="2" align="center" class="records">

              <form name="form" enctype="multipart/form-data" action="billing_signup_agent.php" method="post">

                <tr>
                  <td colspan="2" align=center>
                  <?php echo gettext("Choose the Call Plan to use");?> :
                  <select id="tariff" NAME="tariffplan" size="1"  style="width=250" class="form_input_select" >
                                <option value=''><?php echo gettext("Choose a Call Plan");?></option>

                                <?php
                                 foreach ($list_tariffname as $recordset) {
                                ?>
                                    <option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset['id']==$tariffplan) echo "selected";?>><?php echo $recordset[1]?></option>
                                <?php 	 }
                                ?>
                        </select>
                        <br><br>
                   <?php echo gettext("Choose the Customer group to use");?> :
                  <select id="group" NAME="group" size="1"  style="width=250" class="form_input_select" >
                              <option value=''><?php echo gettext("Choose a Customer Group");?></option>
                                <?php
                                 foreach ($list_group as $recordset) {
                                ?>
                                    <option class=input value='<?php  echo $recordset['id']?>' <?php if ($recordset[0]==$group) echo "selected";?>><?php echo $recordset[1]?></option>
                                <?php 	 }
                                ?>
                        </select>
                        <br>
                        </br>

                </td>
                </tr>
               <tr>
                    <td  colspan="2">
                         &nbsp;
                     </td>
                </tr>
                <tr>
                 <td  width="50%" align="center">
                        <a class="md-btn md-btn-primary"  href="billing_entity_signup_agent.php?section=2">
                            <?php echo gettext("RETURN TO URL KEY LIST"); ?>
                        </a>
                  </td>
                  <td  align="center" width="50%">
                        <input type="hidden" name="task" value="">
                      <input id="generate" type="button" value="<?php echo gettext('ADD URL KEY');?>" onFocus=this.select() class="btn btn-success" name="submit1" onClick="submit_form(this.form);">
                       </p>
                  </td>
                </tr>
                <tr>
                    <td  colspan="2">
                         &nbsp;
                     </td>
                </tr>

                <tr>
                  <td colspan="2"  align="left">

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

              </form>
            </table>
</center>
          </div></div> -->

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
       $('#generate').attr("class","md-btn md-btn-primary");
   } else {
      $('#generate').attr("disabled", true);
      $('#generate').attr("class","md-btn md-btn-primary_disabled");
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
