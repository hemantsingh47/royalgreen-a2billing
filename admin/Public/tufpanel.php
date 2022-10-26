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



 <style type="text/css">
<!--
.pagNumActive {
    color: #000;
    border:#060 1px solid; background-color: #D2FFD2; padding-left:3px; padding-right:3px;
}
.paginationNumbers a:link {
    color: #000;
    text-decoration: none;
    border:#999 1px solid; background-color:#F0F0F0; padding-left:3px; padding-right:3px;
}
.paginationNumbers a:visited {
    color: #000;
    text-decoration: none;
    border:#999 1px solid; background-color:#F0F0F0; padding-left:3px; padding-right:3px;
}
.paginationNumbers a:hover {
    color: #000;
    text-decoration: none;
    border:#060 1px solid; background-color: #D2FFD2; padding-left:3px; padding-right:3px;
}
.paginationNumbers a:active {
    color: #000;
    text-decoration: none;
    border:#999 1px solid; background-color:#F0F0F0; padding-left:3px; padding-right:3px;
}
-->
</style>  
</head>
<body>
<a href="m_intro.php" style="color:red;">Back to control pannel</a>    
 <?php  

 $DBHandle = DbConnect();

     //query for collecting values from the table 
    $query="SELECT ID FROM cc_theme ORDER BY ID ASC " ;
    $result = mysql_query($query) or die(mysql_error());
    $nr = mysql_num_rows($result);//total rows
    if (isset($_GET['pn'])) 
    { // Get pn from URL vars if it is present
    $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
    } 
    else 
    { // If the pn URL variable is not present force it to be value of page number 1
    $pn = 1;
    }
    $itemsPerPage = 1;
    
    $lastPage = ceil($nr / $itemsPerPage);
    if ($pn < 1) 
    { 
    $pn = 1; 

    } 
    else if ($pn > $lastPage) 
    { 
    $pn = $lastPage; 
    }
    $centerPages = "";
$sub1 = $pn - 1;
$sub2 = $pn - 2;
$add1 = $pn + 1;
$add2 = $pn + 2;

if ($pn == 1) {
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
} else if ($pn == $lastPage) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
} else if ($pn > 2 && $pn < ($lastPage - 1)) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub2 . '">' . $sub2 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add2 . '">' . $add2 . '</a> &nbsp;';
} else if ($pn > 1 && $pn < $lastPage) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '">' . $add1 . '</a> &nbsp;';
}
$limit = 'LIMIT ' .($pn - 1) * $itemsPerPage .',' .$itemsPerPage;
$sql2 = mysql_query("SELECT ID,theme FROM cc_theme ORDER BY ID ASC $limit"); 
$paginationDisplay = "";
if ($lastPage != "1"){
    // This shows the user what page they are on, and the total number of pages
    $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage. '&nbsp;  &nbsp;  &nbsp; ';
    // If we are not on page 1 we can place the Back button
    if ($pn != 1) {
        $previous = $pn - 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '"> '.gettext('Back').'</a> ';
    }
    // Lay in the clickable numbers display here between the Back and Next links
    $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
    // If we are not on the very last page we can place the Next button
    if ($pn != $lastPage) {
        $nextPage = $pn + 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $nextPage . '"> '.gettext('Next').'</a> ';
    }
}
$outputList = ''; 
 while ($row = mysql_fetch_array($sql2)) 
    {
        
       $id=$row['ID']; 
       $theme=$row['theme'];
       $outputList .= '<strong style="font-size:20px;">'.strtoupper($theme).'</strong><table width="67%"  cellpadding="0" cellspacing="9" align="center" class="imagepreview">
    <tr  >
        
        <td colspan="3" align="center"> <img src="tufimage.php?theme='.$id.'&pn='.$pn.'" width="560" height="350" border="0" alt="Adore Infotech"> </td>
        
    </tr>
    <tr >
        <td align="right" ><br /><a href="tufupdate.php?id='.$id.'&pn='.$pn.'" ><font class="btn btn-line-warning">'.gettext("Edit Theme").'</font></a></td>
        <td>&nbsp;</td>
        <td align="left"><br /><a href="tufdelete.php?id='.$id.'&pn='.$pn.'" ><font class="btn btn-line-warning">'. gettext("Delete Theme").'</font></a></td>
    </tr></table>';
     
    }
    ?>

  <center>
   <br />
<font style="font-size:14px; font-weight:bold;" class="fontstyle_searchoptions"><?php echo gettext("THEME PANEL") ; ?></font>
 <br />
       
      <div style="margin-left:64px; margin-right:64px;"><?php echo "$outputList"; ?></div>
      <br />
     <div class="formdata" style="margin-left:64px; width:57%; margin-right:64px; padding:6px; "><?php echo $paginationDisplay; ?><a href="tufcreate.php?pn=<?php echo $pn; ?>" ><div style="float:right; text-align:right;"><font class="form_input_button" ><?php echo gettext("Create New Theme");?></font></div></a></div>

</center>
  
 </body>
 </html>