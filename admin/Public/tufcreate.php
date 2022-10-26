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
<script type="text/javascript">
function myfield()
{
     
    for (i=1;i<28;i++) {
        box = document.myform.elements[i];
        if (!box.value) {
            alert('You haven\'t filled in ' + box.name + '!');
            box.focus()
            return false;
        }
        if((box.value.length)!=6)
        {
            alert('Enter only six character in ' + box.name + '!');
            box.focus()
            return false;
        }
    }
    return true;

    
}
</script> 
   
</head>
<body>    
 <?php  

 $DBHandle = DbConnect();
 if(isset($_REQUEST['pn']))
    {
        $pn= $_REQUEST['pn'];
    }
   //getting values from the form
    if(isset($_POST['submit']))
     {
         if(isset($_POST['themename'])){$themename=$_POST['themename'];}
         if(isset($_POST['menubgcolor'])){$menubgcolor=$_POST['menubgcolor'];}
         if(isset($_POST['menufont'])){$menufont=$_POST['menufont'];}
         if(isset($_POST['hovermenubgcolor'])){$hovermenubgcolor=$_POST['hovermenubgcolor'];}
         if(isset($_POST['hovermenufont'])){$hovermenufont=$_POST['hovermenufont'];}
         if(isset($_POST['activemenufont'])){$activemenufont=$_POST['activemenufont'];}
          if(isset($_POST['sidelistbg'])){$sidelistbg=$_POST['sidelistbg'];}
          
         if(isset($_POST['infobgcolor'])){$infobgcolor=$_POST['infobgcolor'];}
         if(isset($_POST['infofont'])){$infofont=$_POST['infofont'];}
         
         if(isset($_POST['infodatabg'])){$infodatabg=$_POST['infodatabg']; }
         if(isset($_POST['infodatafont'])){$infodatafont=$_POST['infodatafont'];}
         if(isset($_POST['infoborder'])){$infoborder=$_POST['infoborder'];}                                                                                          
         
         if(isset($_POST['formheaderbg'])){$formheaderbg=$_POST['formheaderbg'];}
         if(isset($_POST['formheaderfont'])){$formheaderfont=$_POST['formheaderfont'];}
         if(isset($_POST['formdatabg'])){$formdatabg=$_POST['formdatabg'];}
         if(isset($_POST['formdatafont'])){$formdatafont=$_POST['formdatafont'];}
         if(isset($_POST['formborderbg'])){$formborderbg=$_POST['formborderbg'];}
         
         if(isset($_POST['tbheaderbg'])){$tbheaderbg=$_POST['tbheaderbg'];}
         if(isset($_POST['tbheaderfont'])){$tbheaderfont=$_POST['tbheaderfont'];}
         if(isset($_POST['tbdatabg'])){$tbdatabg=$_POST['tbdatabg'];}
         if(isset($_POST['tbdatafont'])){$tbdatafont=$_POST['tbdatafont'];}
         
         if(isset($_POST['tbfirstbg'])){$tbfirstbg=$_POST['tbfirstbg'];}
         if(isset($_POST['tbfirstfont'])){$tbfirstfont=$_POST['tbfirstfont'];}
         if(isset($_POST['tbsecondbg'])){$tbsecondbg=$_POST['tbsecondbg'];}
         if(isset($_POST['tbsecondfont'])){$tbsecondfont=$_POST['tbsecondfont'];}
         if(isset($_POST['tbhovertbg'])){$tbhovertbg=$_POST['tbhovertbg'];}
         if(isset($_POST['tbhoverfont'])){$tbhoverfont=$_POST['tbhoverfont'];}
         if(isset($_POST['tbborderbg'])){$tbborderbg=$_POST['tbborderbg'];}
      
         
         if(isset($_POST['btnbg'])){$btnbg=$_POST['btnbg'];}
         if(isset($_POST['btnfont'])){$btnfont=$_POST['btnfont'];}
         if ($_FILES['image'])
         {
           $image = $_FILES['image'];
           $path = $_FILES['image']['tmp_name'];
           if(filesize($path) >0)
           {
            $type=mysql_real_escape_string($image['type']);
            
            $data=base64_encode(file_get_contents($image['tmp_name']));
           }
         }
        
        //query for insert
        $query="INSERT INTO cc_theme  (theme,slate_bg, slate_bg_font, slate_hover_bg, slate_hover_bg_font, slate_active_font, slate_list_hover,info_header,info_data,info_header_font,info_data_font,form_header_font,form_data_font,form_header_bg,form_data_bg,form_border,tb_header_font,tb_header_bg,tb_data_bg,tb_border,tb_data_font,tb_frow_bg,tb_frow_font,tb_srow_bg,tb_srow_font,tb_hover_bg,tb_hover_font,button_bg,button_font,info_border,theme_type,theme_data) 
        VALUES ('$themename','$menubgcolor', '$menufont', '$hovermenubgcolor','$hovermenufont', '$activemenufont','$sidelistbg',  '$infobgcolor', '$infodatabg', '$infofont', '$infodatafont', '$formheaderfont', '$formdatafont', '$formheaderbg', '$formdatabg', '$formborderbg', '$tbheaderfont', '$tbheaderbg', '$tbdatabg', '$tbborderbg', '$tbdatafont', '$tbfirstbg', '$tbfirstfont', '$tbsecondbg', '$tbsecondfont','$tbhovertbg','$tbhoverfont', '$btnbg', '$btnfont','$infoborder','$type','$data')";
     
      $resultinsert=mysql_query($query) or die(mysql_error());
 
         if($resultinsert == false)
         {
            $msg =  gettext("---Try Again---");
         }
         else
         {
            $msg = gettext("---Created Successfully---");
            
           
         }
     }
     
 ?>
  <center>
   <br />
<font style="font-size:14px; font-weight:bold;" class="fontstyle_searchoptions"><?php echo gettext("THEME CREATION") ; ?></font>
 <br />
<form name="myform" action="tufcreate.php?pn=<?php echo $pn; ?>" method="post" onsubmit="return myfield();" enctype="multipart/form-data"> 
  
 <table width="90%" border="0" cellpadding="0" cellspacing="0" >
    <tr align="center" >
        <td><font style="font-size:12px; font-weight:bold; color:red;"><?php echo $msg; ?></font></td>
    </tr>
    <tr align="center"><td>
      
    <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
        <tr class="tableBodyRight">
                                                    
            <td colspan="3" ><?php echo gettext("Enter Theme Name");?></td>
            <td><strong>:</strong></td>
            <td colspan="3"><input name="themename" type="text" size="15" value=""  /></td>
       </tr>
  
      <tr class="tableBodyRight">
                                                       
            <td colspan="7" ><?php echo gettext("Menu Appearence");?></td>
           
       </tr>
       <tr> 
            <td><strong><?php echo gettext("Background Color");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="menubgcolor" type="text" size="15" value="7292C5" class="color" /></td>
            <td><strong>|</strong></td>
            <td><strong><?php echo gettext("Font Color");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="menufont" type="text" size="15" value="FFFFFF" class="color" /></td>
      </tr>
      <tr>
            <td><strong><?php echo gettext("Hover Background Color");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="hovermenubgcolor" type="text" size="15" value="EFEAEA" class="color" /></td>
            <td><strong>|</strong></td>
            <td><strong><?php echo gettext("Hover Font Color");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="hovermenufont" type="text" size="15" value="000000" class="color" /></td>
      </tr>
      <tr>
            <td><strong><?php echo gettext("Active Font Color");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="activemenufont" type="text" size="15" value="000000" class="color" /></td>
             <td><strong>|</strong></td>
            <td><strong><?php echo gettext("slate List Hover");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="slidelistbg" type="text" size="15" value="45a9ce" class="color" /></td>
      </tr>
      </table>
    
    </td></tr>
     
      <tr align="center"><td> 
   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
   <tr class="tableBodyRight">
        <td colspan="7" ><?php echo gettext("Information Appearence");?> </td>
   </tr>
   <tr>
        <td><strong><?php echo gettext("Header Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="infobgcolor" type="text" size="15"  value="7292C5" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Header Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="infofont" type="text" size="15" value="FFFFFF" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="infodatabg" type="text" size="15"  value="E3F1F9" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Data Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="infodatafont" type="text" size="15" value="3D72B8" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Border Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="infoborder" type="text" size="15"  value="7292C5" class="color" /></td>
        <td><strong>|</strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
  </table>
 
  </td></tr>
   <tr align="center"><td>
  
  <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
  <tr class="tableBodyRight">
        <td colspan="7" ><?php echo gettext("Form Appearence");?> </td>
   </tr>
   <tr>
        <td><strong><?php echo gettext("Header Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="formheaderbg" type="text" size="15" value="7292C5" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Header Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="formheaderfont" type="text" size="15" value="FFFFFF" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="formdatabg" type="text" size="15" value="E3F1F9" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong> <?php echo gettext("Data Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="formdatafont" type="text" size="15" value="3D72B8" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Border Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="formborderbg" type="text" size="15" value="7292C5" class="color" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
  </table>
  

  </td></tr>   <!-- table -->
   <tr align="center"><td>
   
   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
  <tr class="tableBodyRight">
        <td colspan="7" ><?php echo gettext("Table Appearence");?> </td>
   </tr>
   <tr>
        <td><strong><?php echo gettext("Header Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbheaderbg" type="text" size="15" value="7292C5" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Header Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbheaderfont" type="text" size="15" value="FFFFFF" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbdatabg" type="text" size="15" value="E3F1F9" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong> <?php echo gettext("Data Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbdatafont" type="text" size="15" value="3D72B8" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data First Row Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbfirstbg" type="text" size="15" value="F2F2EE" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("First Row Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbfirstfont" type="text" size="15" value="000000" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Second Row Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbsecondbg" type="text" size="15" value="FCFBFB" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Second Row Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbsecondfont" type="text" size="15" value="000000" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Hover Row Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbhovertbg" type="text" size="15" value="B1E3FF" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Hover Row Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbhoverfont" type="text" size="15" value="000000" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Border Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbborderbg" type="text" size="15" value="7292C5"color" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
  </table>

 </td></tr>
   <tr align="center"><td>

   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
  <tr class="tableBodyRight">
        <td colspan="7" ><?php echo gettext("Button Appearence");?> </td>
   </tr>
   <tr>
        <td><strong><?php echo gettext("Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="btnbg" type="text" size="15" value="7292C5" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="btnfont" type="text" size="15" value="FFFFFF" class="color" /></td>
  </tr>
  </table>

 </td></tr>
   <tr align="center"><td>

   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
  <tr class="tableBodyRight">
        <td colspan="7" ><?php echo gettext("Theme Preview");?> </td>
   </tr>
   <tr>
        <td><strong><?php echo gettext("Image");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><div ><input type="file" name="image" class="form_input_button" value="Browse" /></div></td>
        <td><strong>|</strong></td>
        <td colspan="3"><strong> <?php echo gettext("Preferred File Size for the preview is 2M, you can change it from php.ini.");?> </strong></td>
        
        <td>&nbsp;</td>
        
  </tr>
  </table>

 <br />
 </td></tr>
 
      
   <tr align="center">
   <td >
   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
   <tr><td align="right">
   <input name="submit" type="submit" value="<?php echo gettext("Create Theme"); ?>" class="form_input_button" style="width:120px;"/> </td>
   <td align="left"><a href="tufpanel.php?pn=<?php echo $pn; ?>"><input name="back" type="button" value="<?php echo gettext("Back"); ?>" class="form_input_button" style="width:120px;" /></a></td>
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