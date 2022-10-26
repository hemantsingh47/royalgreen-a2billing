<?php
include ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
 

if (!$ACXACCESS) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");       
    die();       
}

$smarty->display('main.tpl');
 
?>
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Lang" content="en">
<script type="text/javascript" src="javascript/jscolor.js"></script>
<script src="javascript/jquery/jquery1.3.1.min.js" type="text/javascript"></script>
 
   
</head>
<body>    
 <?php 
 $flag=true;
 $flag1=true;
 if(isset($_REQUEST['id']))
{
    $id=$_REQUEST['id'];
} 
if(isset($_REQUEST['pn']))
    {
        $pn= $_REQUEST['pn'];
    }
 $DBHandle = DbConnect();
 //query to get name of theme
$queryname="SELECT theme FROM cc_theme WHERE ID='$id'";
$resultname=mysql_query($queryname) or die(mysql_error());

    while($row=mysql_fetch_array($resultname))
    {
        $themename=$row['theme'];   
    }
    
   //getting values from the form
    if(isset($_POST['submit']))
     {
         $flag=false;
         $pn-=1;
        if(isset($_GET['theme1']))
        {
            $theme1=$_GET['theme1'];
        } 

                                 

        //query for delete
        $query="DELETE FROM cc_theme WHERE ID='$id' ";
        
             
      $resultdelete=mysql_query($query) or die(mysql_error());
 
         if($resultdelete == false)
         {
            $msg =  gettext("---Try Again---");
         }
         else
         {
             $flag1=false;
             if($themename=="")
             {
                $msg =  gettext("---Try Again---");
             }
             else
             {
            $msg = gettext("---'$themename' has been Successfully Deleted ---");
             }
         }
     }
     
 ?>
  <center>
   <br />
<font style="font-size:14px; font-weight:bold;" class="fontstyle_searchoptions"><?php echo gettext("THEME DELETION") ; ?></font>
 <br />
<form name="myform" action="tufdelete.php?theme1=<?php echo $themename; ?>&pn=<?php echo $pn; ?>&id=<?php echo $id; ?>" method="post" > 
  
 <table width="90%" border="0" cellpadding="0" cellspacing="0" >
    <tr align="center" >
        <td><br /><font style="font-size:12px; font-weight:bold; color:red;" ><?php echo $msg; ?></font><br /></td>
    </tr>
    <tr>
    <td align="left"> <font style="font-size:14px; font-weight:bold; " class="fontstyleaddcallerid_002"><?php if($flag){echo gettext("You really want to Delete '$themename' "); }?></font>  </td>
   </tr>  
   <tr align="center">
   <td >
   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
    <tr>
   <?php if($flag==true && $flag1==true){ ?><td align="right">
   <input name="submit" type="submit" value="<?php echo gettext("Delete Theme"); ?>" class="form_input_button" style="width:120px;"/> </td><td align="left"><a href="tufpanel.php?pn=<?php echo $pn; ?>"><input name="back" type="button" value="<?php echo gettext("Back"); ?>" class="form_input_button" style="width:120px;" /></a></td><?php }else{ ?>
    
  <td align="center"><a href="tufpanel.php?pn=<?php echo $pn; ?>"><input name="back" type="button" value="<?php echo gettext("Back"); ?>" class="form_input_button" style="width:120px;" /></a></td>
  <?php } ?>
   </tr> 
   </table>
   </td>
   </tr>
</table>
<br/>
 
</form>
</center>


 </body>
 </html>