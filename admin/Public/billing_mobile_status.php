<?php
include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/customer.smarty.php';


if (! has_rights (ACX_INVOICES)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
 
//load customer
 $DBHandle  = DbConnect();

$smarty->display('main.tpl');
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HEAD runat="server">
    <link rel="shortcut icon" href="templates/default/images/adore.ico">
    <title>..:: Billing Solution: CallingCard, CallBack & VOIP Billing Solution ::..</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   
    <link href="templates/default/css/main.php" rel="stylesheet" type="text/css">
    <!--<link href="templates/default/css/newcss.css" rel="stylesheet" type="text/css">  -->
    <link href="templates/default/css/invoice.css" rel="stylesheet" type="text/css">
    <link href="templates/default/css/receipt.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">    
        var IMAGE_PATH = "templates/default/images/";
    </script>
    
    
     <link href="css/bootstrap.min.css" rel="stylesheet">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>      
     <script src="js/bootstrap.min.js"></script>
     </head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
  
  <h2>Mobile Top Up Reports</h2>
<?php                  
         $sql="SELECT `username`,`amt`,`msdest`,`trans_time` FROM `cc_card`,`cc_friend_recharge` ad WHERE cc_card.`id` =  `card_id` AND cc_card.`username` = '" . $_SESSION["pr_login"] ."' ";
         $result=mysql_query($sql);
          
 ?>
        <br>
  <table width="65%" class="voucher_table2" align="center" style="margin-left:200px">
  <tr class="callhistory_td11">
            <td align="center" colspan=2 ><font color="#ffffff">- Mobile Top up Reports &nbsp; -</font></b></td>
  </tr>
  </table>
  <br><br>
  <center>
<table border="0" cellpadding="2" cellspacing="2" width="75%">
 <tbody>
<tr class="form_head">

<td class="tableBody" style="padding: 2px;" align="center" width="5%">Id</td>

<td class="tableBody" style="padding: 2px;" align="center" width="10%">Account No</td>
<td class="tableBody" style="padding: 2px;" align="center" width="10%">Mobile No</td>
<td class="tableBody" style="padding: 2px;" align="center" width="10%">Amount(GBP)</td>
<td class="tableBody" style="padding: 2px;" align="center" width="10%">Time</td> 
</tr>  




</tr>   
      
<?php
$i=1;
while($rows=mysql_fetch_array($result))
{
?>

<tr bgcolor="#F2F2EE" onmouseover="bgColor='#FFDEA6'" onmouseout="bgColor='#F2F2EE'">
<td valign="top" align="center" class="tableBody"><?php echo $i;$i++;?></td>
<td valign="top" align="center" class="tableBody"><?php echo $rows['username']; ?></td>

<td valign="top" align="center" class="tableBody"><?php echo $rows['msdest']; ?></td>
<td valign="top" align="center" class="tableBody"><?php echo $rows['amt']; ?></td>
<td valign="top" align="center" class="tableBody"><?php echo $rows['trans_time'];?></td>
</tr>
 
<?php
}

?>
</tbody>
</table>
</center>
</body>
        