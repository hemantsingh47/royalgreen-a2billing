<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
$DBHandle = DbConnect();


if($_REQUEST)
{
    $id     = $_REQUEST['parent_id'];
    if($id==0)
    {
        echo gettext("Select an agent.");
    }
    else if($id==-1)
    {
        echo gettext("Select an agent."); 
    }
    else 
    {
   
  $context=0;
  
$group_table = new Table('cc_card_group','id,name,id_agent,group_logo_path');
$groupt_clause = "id_agent >0 AND id_agent='$id'";
$groupresult = $group_table -> Get_list($DBHandle, $groupt_clause, 0);
    $ar=array();
    
    if(count($groupresult)==0)
        {
            echo gettext("No group assigned for this agent.");
        }
        else{
            
    for ($i=0;$i<count($groupresult);$i++) 
    {
        
        
  ?>
  <table><tr>
    <td><input type="checkbox" name="th<?php echo ($i+1);?>" id="th<?php echo ($i+1);?>" value="<?php echo $groupresult[$i]['id']; ?>" class="input"></td>
    <td>&nbsp;&nbsp;<strong class="fontstyle_006"><?php echo $groupresult[$i]['name']."-(".$groupresult[$i]['id_agent'].")"; ?></strong></td>
    <td>&nbsp;&nbsp;<strong class="fontstyle_006"><img src="../../agent/Public/templates/default/images/agent_logo/<?php echo $groupresult[$i]['group_logo_path']; ?>" width="80" height="30" border="0" style="border-radius:50%;-webkit-border-radius:50%;-moz-border-radius:50%;-o-border-radius:50%;">
</strong></td>
  </tr>
  </table>
  <?php 
            }
        } 
    }
}
    
    ?>
  <input type="hidden" name="no" id="no" value="<?php echo count($groupresult); ?>" />
