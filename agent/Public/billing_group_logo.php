<?php
include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_BILLING)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
$DBHandle = DbConnect(); 
$smarty->display('main.tpl');
 $b = true;

if(isset($_POST['no'])){     $group_no=$_POST['no'];    }
$ar1=array();
$len=0;
for($i=1;$i<=$group_no;$i++)
{   
    $th="th".$i;
    if(isset($_POST[$th]))
    {
        $ar1[$len]=$_POST[$th];
        $len++;
        
    }
    
}
 //UPDATING LOGO
  if(isset($_POST['submit6']))
     {
        if($len!=0)
         {
             $groupimg=array();
             for($i=0;$i<count($ar1);$i++)
             {
                array_push($groupimg,"group_".$agent_id."_".$ar1[$i]); 
             }
             $result=imageUpload($_FILES,$groupimg,array(AGENTIMGDIR."agent_logo",CUSTIMGDIR."agent_logo"),"image successfully uploaded",$DBHandle);
           //print_r($result);
             if(empty($result["error"]))
                {         
                        for($i=0;$i<count($result['img']);$i++)
                         {
                                
                                $name=$result['img'][$i];
                                //query to find existence
                                $instance_table = new Table("cc_card_group","*");
                                $clause="id='$ar1[$i]' AND id_agent='".$_SESSION['agent_id']."'";
                                $resultgroup=$instance_table -> Get_list($DBHandle, $clause);
                              // print_r($resultgroup); 
                               if (is_array($resultgroup)  ) {
                                   //update
                                     $clause = "id='$ar1[$i]' AND id_agent='".$_SESSION['agent_id']."'";
                                    $values="group_logo_path='$name'";
                                    $return=$instance_table -> Update_table($DBHandle, $values, $clause);
                                   // print_r($return);
                                    if($return){$msg = gettext("--- Group Logo Updated Successfully---");}
                                    else {$msg= gettext(" Group Logo has not been updated due to some error, Please Try Again");} 
                                    
                                    
                                }
                                else 
                                {
                                     //insert
                                    
                                    $fields="group_logo_path,id_agent";
                                   echo  $values="'$name','".$_SESSION['agent_id']."'";
                                    $return=$instance_table->Add_table($DBHandle, $values, $fields);
                                   // print_r($return);
                                    if($return){$msg = gettext("--- Group Logo inserted Successfully---");}
                                    else {$msg= gettext(" Group Logo has not been inserted due to some error, Please Try Again");}
                                     
                                
                                } 
                         }
                              
                }
                       
             
         }  
     else
     {
        $msg= gettext("No Group Selected, Please Select First");
     }
  }
   

 ?>
<!-- <div class="widget-box">
  <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
<h4><?php echo gettext("Upload Group logo"); ?></h4>
</div> 
<div class="md-card">
    <div class="md-card-content">
    
    
    <font style="font-size:12px; font-weight:bold; color:red;"><?php echo ($msg); ?></font>

<form name="myform" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"  enctype="multipart/form-data"> 
 
  
 <table width="90%" border="0" cellpadding="0" cellspacing="0" >
     
   <tr align="center"><td>
  
   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
   
  <tr > 
  <td><strong><div id="show_side"> <?php echo gettext("Select Group"); ?> </div>  </strong></td>
        <td><strong><div id="show_side1">:</div></strong></td>
  <td align="left">
  <div id="groups" >
  <?php
    $group_table = new Table('cc_card_group','id,name,id_agent,group_logo_path');
    $groupt_clause = "id_agent >0 AND id_agent='".$_SESSION['agent_id']."'";
    $groupresult = $group_table -> Get_list($DBHandle, $groupt_clause, 0);
    
    
    if(($groupresult)==0)
        {
            echo gettext("No group assigned for this agent.");
        }
        else{
            
    for ($i=0;$i<count($groupresult);$i++) 
    {
        
        
  ?>
  <table><tr>
    <td><input type="checkbox" name="th<?php echo ($i+1);?>" id="th<?php echo ($i+1);?>" value="<?php echo $groupresult[$i]['id']; ?>" class="md-input"></td>
    <td>&nbsp;&nbsp;<strong class="fontstyle_006"><?php echo $groupresult[$i]['name']."-(".$groupresult[$i]['id_agent'].")"; ?></strong></td>
    <td>&nbsp;&nbsp;<strong class="fontstyle_006"><img src="templates/default/images/agent_logo/<?php echo $groupresult[$i]['group_logo_path']; ?>" width="80" height="30" border="0" style="border-radius:50%;-webkit-border-radius:50%;-moz-border-radius:50%;-o-border-radius:50%;">
  </tr>
  </table>
  <?php 
            }
        } 
    
    ?>
  
  </div> -->

  <!-- begin:: Subheader -->
  <div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"> Group Logo Uploading </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            User                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Customer                       </a>
                                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
                            Group Logo                        </a>
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
                        <font class="kt-portlet__head-title"><?php echo gettext("Upload Group Logo"); ?></font>
				  </div>
			  </div>
			  <!--begin::Form-->
			  <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="frmPass" enctype="multipart/form-data">
				  <div class="kt-portlet__body">
					  <div class="form-group row">
                          <div class="col-lg-1"></div>
						  <font class="col-lg-2 col-sm-12"><?php echo gettext("Select Group");?> </font>
                          <div class="col-lg-6 col-md-9 col-sm-12" id="groups">
                      
                          <?php
                          $group_table = new Table('cc_card_group','id,name,id_agent,group_logo_path');
                          $groupt_clause = "id_agent >0 AND id_agent='".$_SESSION['agent_id']."'";
                          $groupresult = $group_table -> Get_list($DBHandle, $groupt_clause, 0);
    
                          if(($groupresult)==0)
                            {
                                echo gettext("No group assigned for this agent.");
                            }
                          else {
                                for ($i=0;$i<count($groupresult);$i++) 
                                    {
                                        ?>
                                          <table>
                                              <tr>
                                                  <td><input type="checkbox" name="th<?php echo ($i+1);?>" id="th<?php echo ($i+1);?>" value="<?php echo $groupresult[$i]['id']; ?>" class="md-input"></td>
                                                  <td>&nbsp;&nbsp;<strong class="fontstyle_006"><?php echo $groupresult[$i]['name']."-(".$groupresult[$i]['id_agent'].")"; ?></strong></td>
                                                  <td>&nbsp;&nbsp;<strong class="fontstyle_006"><img src="templates/default/images/agent_logo/<?php echo $groupresult[$i]['group_logo_path']; ?>" width="80" height="30" border="0" style="border-radius:50%;-webkit-border-radius:50%;-moz-border-radius:50%;-o-border-radius:50%;">
                                              </tr>
                                          </table>
                                        <?php 
                                    }
                                } 
                          ?>
                          </div>
                          <input type="hidden" name="no" id="no" value="<?php echo count($groupresult); ?>" />
                      </div>    
				  </div>

            <div class="form-group row">
            <div class="col-lg-1"></div>
				<font class="col-lg-2 col-sm-12"><?php echo gettext("Change Logo");?></font>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <input type="file" class="custom-file-input" id="customFile" value="Browse" >
						  	<label class="custom-file-label form-control" for="customFile">Choose logo</label>
                    </div>    
			</div>
            <br>

            <div class="form-actions"  align="center"><strong><?php echo gettext("Preferred Size for the logo is 203 X 62 and File size range must be within 50 KB."); ?> </strong>
                        <br><br>
            </div>

            <div align="center" class="kt-portlet__foot">
                <div class="form-actions">
                    <table>
                        
                        <tr align="center"><td colspan="7"><input name="submit6" type="submit" value="<?php echo gettext("Update Logo"); ?>" class="btn btn-brand" /> </td></tr>
                    </table>
                </div>
            </div>
        </div>
			  </form>
			  <!--end::Form-->			
		</div>
		<!--end::Portlet-->
	</div>
<!-- begin:: Content -->
</div>

 <!--  <input type="hidden" name="no" id="no" value="<?php echo count($groupresult); ?>" />
 </td></tr>
   <tr>
        <td><strong><?php echo gettext("Change Logo"); ?> </strong></td>
        <td><strong>:</strong></td>
        <td><div ><input type="file" name="image" class="cssbutton_big" value="Browse" /></div></td>
        
        
  </tr> -->
  
 <?php
     $smarty->display('footer.tpl');

 ?>