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
     
    for (i=2;i<28;i++) {
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

   
    if(isset($_REQUEST['id']))
    {
        $id=$_REQUEST['id'];
    }
    
    if(isset($_REQUEST['pn']))
    {
        $pn= $_REQUEST['pn'];
    }
    

    //getting values from the form
    if(isset($_POST['submit']))
     {
         
         if(isset($_POST['themename'])){ $themename= $_POST['themename'];} else{$themename=$theme_name;}
         if(isset($_POST['menubgcolor'])){$menubgcolor=$_POST['menubgcolor'];}else{$menubgcolor=$slate_bg;}
         if(isset($_POST['menufont'])){$menufont=$_POST['menufont'];}else{$menufont=$slate_bg_font;}
         if(isset($_POST['hovermenubgcolor'])){$hovermenubgcolor=$_POST['hovermenubgcolor'];}else{$hovermenubgcolor=$slate_hover_bg;}
         if(isset($_POST['hovermenufont'])){$hovermenufont=$_POST['hovermenufont'];}else{$hovermenufont=$slate_hover_bg_font;}
         if(isset($_POST['activemenufont'])){$activemenufont=$_POST['activemenufont'];}else{$activemenufont=$slate_active_font;}
          if(isset($_POST['sidelistbg'])){$sidelistbg=$_POST['sidelistbg'];}else{$sidelistbg=$slate_list_hover;}
         
         if(isset($_POST['infobgcolor'])){$infobgcolor=$_POST['infobgcolor'];}else{$infobgcolor=$info_header;}
         if(isset($_POST['infofont'])){$infofont=$_POST['infofont'];}else{$infofont=$info_header_font;}
         
         if(isset($_POST['infodatabg'])){$infodatabg=$_POST['infodatabg']; }else{$infodatabg=$info_data;}
         if(isset($_POST['infodatafont'])){$infodatafont=$_POST['infodatafont'];}else{$infodatafont=$info_data_font;}
         if(isset($_POST['infoborder'])){$infoborder=$_POST['infoborder'];}else{$infoborder= $info_border;}                                                                                          
         
         if(isset($_POST['formheaderbg'])){$formheaderbg=$_POST['formheaderbg'];}else{$formheaderbg=$form_header_bg;}
         if(isset($_POST['formheaderfont'])){$formheaderfont=$_POST['formheaderfont'];}else{$formheaderfont=$form_header_font;}
         if(isset($_POST['formdatabg'])){$formdatabg=$_POST['formdatabg'];}else{$formdatabg=$form_data_bg;}
         if(isset($_POST['formdatafont'])){$formdatafont=$_POST['formdatafont'];}else{$formdatafont=$form_data_font;}
         if(isset($_POST['formborderbg'])){$formborderbg=$_POST['formborderbg'];}else{$formborderbg=$form_border;}
         
         if(isset($_POST['tbheaderbg'])){$tbheaderbg=$_POST['tbheaderbg'];}else{$tbheaderbg=$tb_header_bg;}
         if(isset($_POST['tbheaderfont'])){$tbheaderfont=$_POST['tbheaderfont'];}else{$tbheaderfont=$tb_header_font;}
         if(isset($_POST['tbdatabg'])){$tbdatabg=$_POST['tbdatabg'];}else{$tbdatabg=$tb_data_bg;}
         if(isset($_POST['tbdatafont'])){$tbdatafont=$_POST['tbdatafont'];}else{$tbdatafont=$tb_data_font;}
         
         if(isset($_POST['tbfirstbg'])){$tbfirstbg=$_POST['tbfirstbg'];}else{$tbfirstbg=$tb_frow_bg;}
         if(isset($_POST['tbfirstfont'])){$tbfirstfont=$_POST['tbfirstfont'];}else{$tbfirstfont=$tb_frow_font;}
         if(isset($_POST['tbsecondbg'])){$tbsecondbg=$_POST['tbsecondbg'];}else{$tbsecondbg=$tb_srow_bg;}
         if(isset($_POST['tbsecondfont'])){$tbsecondfont=$_POST['tbsecondfont'];}else{$tbsecondfont=$tb_srow_font;}
         if(isset($_POST['tbhovertbg'])){$tbhovertbg=$_POST['tbhovertbg'];}else{$tbhovertbg=$tb_hover_bg;}
         if(isset($_POST['tbhoverfont'])){$tbhoverfont=$_POST['tbhoverfont'];}else{$tbhoverfont=$tb_hover_font;}
         if(isset($_POST['tbborderbg'])){$tbborderbg=$_POST['tbborderbg'];}else{$tbborderbg=$tb_border;}
      
         
         if(isset($_POST['btnbg'])){$btnbg=$_POST['btnbg'];}else{$btnbg=$button_bg;}
        if(isset($_POST['btnfont'])){$btnfont=$_POST['btnfont'];}else{ $btnfont=$button_font;}
        
        
        //query for update
        $query="UPDATE cc_theme SET theme='$themename', slate_bg='$menubgcolor', slate_bg_font='$menufont', slate_hover_bg='$hovermenubgcolor', slate_hover_bg_font='$hovermenufont', slate_active_font='$activemenufont',slate_list_hover='$sidelistbg', info_header='$infobgcolor', info_data='$infodatabg', info_header_font='$infofont', info_data_font='$infodatafont', form_header_font='$formheaderfont', form_data_font='$formdatafont', form_header_bg='$formheaderbg', form_data_bg='$formdatabg', form_border='$formborderbg', tb_header_font='$tbheaderfont', tb_header_bg='$tbheaderbg', tb_data_bg='$tbdatabg', tb_border='$tbborderbg', tb_data_font='$tbdatafont', tb_frow_bg='$tbfirstbg', tb_frow_font='$tbfirstfont', tb_srow_bg='$tbsecondbg', tb_srow_font='$tbsecondfont',
    tb_hover_bg='$tbhovertbg', tb_hover_font='$tbhoverfont', button_bg='$btnbg', button_font='$btnfont',info_border='$infoborder' WHERE ID='$id'" ;
     
      $resultupdate=mysql_query($query) or die(mysql_error());
 
         if($resultupdate == false)
         {
            $msg =  gettext("---Try Again---");
         }
         else
         {
            $msg = gettext("---Updated Successfully---");
         }
    
      if ($_FILES['image'])
     {
       $image = $_FILES['image'];
       $path = $_FILES['image']['tmp_name'];
       if(filesize($path) >0)
       {
       
        $type=mysql_real_escape_string($image['type']);
        
        $data=base64_encode(file_get_contents($image['tmp_name']));
       
        $query = "UPDATE cc_theme SET theme_type='$type', theme_data='$data' WHERE ID='$id'"; 
            $result = mysql_query($query) or mysql_error($DBHandle);
             if ($result==0)
             {
                $msg= "Image has not been inserted due to some error, Please Try Again";
                
             }
       
       }
     } 
 }
 
      //query for collecting value from the table
   $query="SELECT * FROM cc_theme WHERE ID='$id' " ;
   $result = mysql_query($query) or die(mysql_error());
   while ($row = mysql_fetch_array($result)) 
    {
        
       $slate_bg=$row['slate_bg'];
       $slate_bg_font=$row['slate_bg_font'];
       $slate_hover_bg=$row['slate_hover_bg'];
       $slate_hover_bg_font=$row['slate_hover_bg_font'];
       $slate_active_font=$row['slate_active_font'];
       $slate_list_hover=$row['slate_list_hover'];
        
       $info_header=$row['info_header'];
       $info_data=$row['info_data'];
       $info_header_font=$row['info_header_font'];
       $info_data_font=$row['info_data_font'];
       $info_border=$row['info_border'];
       
       $form_header_font=$row['form_header_font'] ;
       $form_data_font=$row['form_data_font'];
       $form_header_bg=$row['form_header_bg'];
       $form_data_bg=$row['form_data_bg'];
       $form_border=$row['form_border'];
       
       $tb_header_font=$row['tb_header_font'];
       $tb_header_bg=$row['tb_header_bg'];
       $tb_data_bg=$row['tb_data_bg'];
       $tb_border=$row['tb_border'];
       $tb_data_font=$row['tb_data_font'];
       
       $tb_frow_bg=$row['tb_frow_bg'];
       $tb_frow_font=$row['tb_frow_font'];
       $tb_srow_bg=$row['tb_srow_bg'];
       $tb_srow_font=$row['tb_srow_font'];
       $tb_hover_bg=$row['tb_hover_bg'];
       $tb_hover_font=$row['tb_hover_font'];
       
       $button_bg=$row['button_bg'];
       $button_font=$row['button_font']; 
       
       $theme_name=$row['theme'];
          
    }
 ?>
  <center>
   <br />
<font style="font-size:14px; font-weight:bold;" class="fontstyle_searchoptions"><?php echo gettext("THEME UPDATION") ; ?></font>
 <br />

<form name="myform" action="tufupdate.php?id=<?php echo $id; ?>&pn=<?php echo $pn; ?>" method="post" onsubmit="return myfield();" enctype="multipart/form-data"> 
  
 <table width="90%" border="0" cellpadding="0" cellspacing="0" >
    <tr align="center" >
        <td><font style="font-size:12px; font-weight:bold; color:red;"><?php echo $msg; ?></font></td>
    </tr>
    <tr align="center"><td>
      
    <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
        <tr class="tableBodyRight">
                                                    
            <td colspan="3" ><?php echo gettext("Theme Name");?></td>
            <td><strong>:</strong></td>
            <td colspan="3"><input name="themename" type="text" size="15" value="<?php echo $theme_name; ?>"  /></td>
       </tr>
      <tr class="tableBodyRight">
           
            <td colspan="7" ><?php echo gettext("Menu Appearence");?></td>
           
       </tr>
       <tr> 
            <td><strong><?php echo gettext("Background Color");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="menubgcolor" type="text" size="15" value="<?php echo $slate_bg; ?>" class="color" /></td>
            <td><strong>|</strong></td>
            <td><strong><?php echo gettext("Font Color");?> </strong></td>
            <td><strong>:</strong></td>
            <td><input name="menufont" type="text" size="15" value="<?php echo $slate_bg_font; ?>" class="color" /></td>
      </tr>
      <tr>
            <td><strong><?php echo gettext("Hover Background Color");?> </strong></td>
            <td><strong>:</strong></td>
            <td><input name="hovermenubgcolor" type="text" size="15" value="<?php echo $slate_hover_bg; ?>" class="color" /></td>
            <td><strong>|</strong></td>
            <td><strong><?php echo gettext("Hover Font Color");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="hovermenufont" type="text" size="15" value="<?php echo $slate_hover_bg_font; ?>" class="color" /></td>
      </tr>
      <tr>
            <td><strong><?php echo gettext("Active Font Color");?> </strong></td>
            <td><strong>:</strong></td>
            <td><input name="activemenufont" type="text" size="15" value="<?php echo $slate_active_font; ?>" class="color" /></td>
            <td><strong>|</strong></td>
            <td><strong><?php echo gettext("Sidebar List Hover");?>  </strong></td>
            <td><strong>:</strong></td>
            <td><input name="sidelistbg" type="text" size="15" value="<?php echo $slate_list_hover; ?>" class="color" /></td>
      </tr>
      </table>
    
    </td></tr>
     
      <tr align="center"><td> 
   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
   <tr class="tableBodyRight">
        <td colspan="7" ><?php echo gettext("Information Appearence");?></td>
   </tr>
   <tr>
        <td><strong><?php echo gettext("Header Background Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="infobgcolor" type="text" size="15"  value="<?php echo $info_header; ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Header Font Color");?></strong></td>
        <td><strong>:</strong></td>
        <td><input name="infofont" type="text" size="15" value="<?php echo $info_header_font; ?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="infodatabg" type="text" size="15"  value="<?php echo $info_data; ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Data Font Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="infodatafont" type="text" size="15" value="<?php echo $info_data_font; ?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Border Color");?></strong></td>
        <td><strong>:</strong></td>
        <td><input name="infoborder" type="text" size="15"  value="<?php echo $info_border; ?>" class="color" /></td>
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
        <td colspan="7" ><?php echo gettext("Form Appearence");?></td>
   </tr>
   <tr>
        <td><strong><?php echo gettext("Header Background Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="formheaderbg" type="text" size="15" value="<?php echo $form_header_bg; ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Header Font Color");?></strong></td>
        <td><strong>:</strong></td>
        <td><input name="formheaderfont" type="text" size="15" value="<?php echo $form_header_font; ?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Background Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="formdatabg" type="text" size="15" value="<?php echo $form_data_bg; ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong> <?php echo gettext("Data Font Color");?></strong></td>
        <td><strong>:</strong></td>
        <td><input name="formdatafont" type="text" size="15" value="<?php echo $form_data_font; ?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Border Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="formborderbg" type="text" size="15" value="<?php echo $form_border; ?>" class="color" /></td>
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
        <td colspan="7" ><?php echo gettext("Table Appearence");?></td>
   </tr>
   <tr>
        <td><strong><?php echo gettext("Header Background Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbheaderbg" type="text" size="15" value="<?php echo $tb_header_bg; ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Header Font Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbheaderfont" type="text" size="15" value="<?php echo $tb_header_font; ?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Background Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbdatabg" type="text" size="15" value="<?php echo $tb_data_bg; ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Data Font Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbdatafont" type="text" size="15" value="<?php echo $tb_data_font; ?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data First Row Color");?></strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbfirstbg" type="text" size="15" value="<?php echo $tb_frow_bg; ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("First Row Font Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbfirstfont" type="text" size="15" value="<?php echo $tb_frow_font; ?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Second Row Color");?></strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbsecondbg" type="text" size="15" value="<?php echo $tb_srow_bg; ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Second Row Font Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbsecondfont" type="text" size="15" value="<?php echo  $tb_srow_font; ?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Data Hover Row Color");?></strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbhovertbg" type="text" size="15" value="<?php echo $tb_hover_bg;?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Hover Row Font Color");?>  </strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbhoverfont" type="text" size="15" value="<?php echo $tb_hover_font;?>" class="color" /></td>
  </tr>
  <tr>
        <td><strong><?php echo gettext("Border Color");?></strong></td>
        <td><strong>:</strong></td>
        <td><input name="tbborderbg" type="text" size="15" value="<?php echo $tb_border; ?>" class="color" /></td>
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
        <td><strong><?php echo gettext("Background Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="btnbg" type="text" size="15" value="<?php echo $button_bg;  ?>" class="color" /></td>
        <td><strong>|</strong></td>
        <td><strong><?php echo gettext("Font Color");?> </strong></td>
        <td><strong>:</strong></td>
        <td><input name="btnfont" type="text" size="15" value="<?php echo $button_font; ?>" class="color" /></td>
  </tr>
  </table>

 </td></tr>
   <tr align="center"><td>

   <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">
  <tr class="tableBodyRight">
        <td colspan="7" ><?php echo gettext("Theme Preview");?></td>
   </tr>
   <tr>
        <td colspan="7" align="right">
        <br />
        <center>
        <table>
        <tr>
        <td><img src="tufimage.php?theme=<?php echo $id; ?>" width="250" height="150" border="0" alt="Adore Infotech"></td>
        <td><strong><?php echo gettext("Preferred File Size for the preview is 2M, <br />you can change it from php.ini.");?></strong></td>
        </tr>
        </table>
        
        
         </center>
         <br />
        <strong><?php echo gettext(" Change Image");?></strong>&nbsp;&nbsp;&nbsp;
        
       <input type="file" name="image" class="form_input_button" value="Browse" />
      
        <br /> </td>
        
        
        
  </tr>
  </table>

 
 </td></tr>
 
   <tr align="center">
   <td >
    <table width="65%" border="0" cellpadding="5" cellspacing="5" class="formdata" style="font-size:11px;">  
        <tr align="right"><td ><input name="submit" type="submit" value="<?php echo gettext("Update"); ?>" class="form_input_button" style="width:100px;" /> </td>
            <td align="left"><a href="tufpanel.php?pn=<?php echo $pn; ?>"><input name="back" type="button" value="<?php echo gettext("Back"); ?>" class="form_input_button" style="width:100px;" /></a></td>
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