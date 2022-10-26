<?php
include ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");


if (!$ACXACCESS) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");       
    die();       
}
$DBHandle = DbConnect(); 
$smarty->display('main.tpl');
?>

 <script type="text/javascript">

$(document).ready(function() {

    $('#loader').hide();
    $('#show_side').hide();
    $('#show_side1').hide();
     
    $('#ag').change(function(){
        $('#groups').fadeOut();
        $('#loader').show();
        $.post("billing_group_logo_agent.php", {
            parent_id: $('#ag').val(),
            
        }, function(response){
            
            setTimeout("finishAjax('groups', '"+escape(response)+"')", 400);
        });
        return false;
    });
});

function finishAjax(id, response){
  $('#loader').hide();
  $('#show_side').show();
  $('#show_side1').show();
  $('#'+id).html(unescape(response));
  $('#'+id).fadeIn();
} 

function alert_id()
{
    if($('#ag').val() == '-1')
    document.getElementById("groups").innerHTML="No Agent Selected";
    
    return false;
}

</script>
<?php
$username=$_SESSION['pr_login'];
 $b = true;
 if(isset($_POST['ag'])) {     $agent_id= $_POST['ag']; }

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
                                $clause="id='$ar1[$i]' AND id_agent='$agent_id'";
                                $resultgroup=$instance_table -> Get_list($DBHandle, $clause);
                                
                               if (is_array($result)  ) {
                                   //update
                                    $clause = "id='$ar1[$i]' AND id_agent='$agent_id'";
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
                                    $values="'$name','$agent_id'";
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
   

 //FOR AGENTS NAME
$agent_table = new Table('cc_agent','id,login');
$agent_clause = null;
$agent_result = $agent_table -> Get_list($DBHandle, $agent_clause, 0);



?>

<!--<script type="text/javascript" src="javascript/jscolor.js"></script>
<script src="javascript/jquery/jquery1.3.1.min.js" type="text/javascript"></script>
<link media="screen" href="templates/default/css/colorbox.css" rel="stylesheet" type="text/css">
<link media="screen" href="templates/default/css2/main.php" rel="stylesheet" type="text/css">
<link  rel="stylesheet" href="" />
<script src="javascript/jquery/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="javascript/jquery/jquery-1.3.2.js"></script> 
 <script src="js/jquery.popupoverlay.min.js"></script>
 <script src="js/jquery.slimscroll.min.js"></script>
 <script src="js/jquery.cookie.min.js"></script>  -->
 



<h2><?php echo gettext("Upload Group logo"); ?></h2> 
<div class="row-fluid">
    <div class="widget-box">
    
    
    <font style="font-size:12px; font-weight:bold; color:red;"><?php echo ($msg); ?></font>

<form name="myform" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"  enctype="multipart/form-data"> 
 
  
 <table width="90%" border="0" cellpadding="0" cellspacing="0" >
     
   <tr align="center"><td>
  
   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
    <tr >
  <td><strong><?php echo gettext("Select Agent"); ?> </strong></td>
        <td><strong>:</strong></td>
  <td align="left">
 <select class="input" name="ag" id="ag" >
   <option value="-1"><strong class="fontstyle_006"><?php echo gettext("None"); ?></strong></option>
  <?php 
  for ($i=0;$i<count($agent_result);$i++) 
    {
  ?>
 <option value="<?php echo $agent_result[$i]['id']; ?>"><strong class="fontstyle_006"><?php echo $agent_result[$i]['login']."-(".$agent_result[$i]['id'].")"; ?></strong></option>
 <?php 
    } ?>
 </select>
 </td></tr>
  
  <tr > 
  <td><strong><div id="show_side"> <?php echo gettext("Select Group"); ?> </div>  </strong></td>
        <td><strong><div id="show_side1">:</div></strong></td>
  <td align="left">
  <div id="groups" ><i id="loader" class="uk-icon-spinner uk-icon-medium uk-icon-spin"></i>
  </div>


 </td></tr>
   <tr>
        <td><strong><?php echo gettext("Change Logo"); ?> </strong></td>
        <td><strong>:</strong></td>
        <td><div ><input type="file" name="image" class="cssbutton_big" value="Browse" /></div></td>
        
        
  </tr>
  <tr>
  <td> &nbsp;</td>
  <td colspan="2">
        <strong><?php echo gettext("Preferred Size for the logo is 203 X 62 and File size range must be within 50 KB."); ?> </strong>
        
        
  </td>
  </tr>
  </td></tr>
 
     
   <tr align="center"><td colspan="7"><input name="submit6" type="submit" value="<?php echo gettext("Update Logo"); ?>" class="btn btn-primary" /> </td></tr>
  </table>

 <br />
  
</table>

 
</form> 
    </div>
</div>
 <?php
     $smarty->display('footer.tpl');

 ?>