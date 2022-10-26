<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';  


include_once "function.php";        

if (! has_rights (ACX_ADMINISTRATOR)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
 $DBHandle  = DbConnect(); 
 $smarty->display('main.tpl');
 
 
 
     if(isset($_GET["page"]))
    $page = (int)$_GET["page"];
    else
    $page = 1;

    $setLimit = 15; 
    $pageLimit = ($page * $setLimit) - $setLimit;
    $sql="SELECT * FROM cc_card where status = 1 LIMIT ".$pageLimit." , ".$setLimit;
        $result=mysql_query($sql);
           $total_results = mysql_num_rows($result);    
          
        if($result === FALSE) { 
        die(mysql_error()); // TODO: better error handling
        }

        $count=mysql_num_rows($result);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Billing</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>      
     <script src="js/bootstrap.min.js"></script> 
     <script language="javascript">
            function validate()
            {
            var chks = document.getElementsByName('checkbox[]');
            var hasChecked = false;
            for (var i = 0; i < chks.length; i++)
            {
            if (chks[i].checked)
            {
            hasChecked = true;
            break;
            }
            }
            if (hasChecked == false)
            {
           // alert("Please select at least one.");
            return false;
            }
            return true;
            }
            </script>
            
          <script type="text/javascript">
          
checked=false;
function checkedAll (form1) {var aa= document.getElementById('form12'); if (checked == false)
{   
checked = true
}
else
{
checked = false
}for (var i =0; i < aa.elements.length; i++){ aa.elements[i].checked = checked;}
}
</script>  
   
<script>
function allDelete()
{                                                                                                                     
    var sh1=document.getElementById('checkbox[]');
    if(sh1==true)
    {
     alert("are you want to delete all account ");
    }
    else
    {
     alert("Select at least one account ");
    }
}
</script> 



    <style type="text/css">
    .navi {
    width: 500px;
    margin: 5px;
    padding:2px 5px;
    border:1px solid #eee;
    }

    .show {
    /*color: blue;  */
    margin: 5px 0;
    padding: 3px 5px;
    cursor: pointer;
    font: 15px/19px Arial,Helvetica,sans-serif;
    }
    .show a {
    text-decoration: none;
    }
    .show:hover {
    text-decoration: underline;
    }


    ul.setPaginate li.setPage{
    padding:15px 10px;
    font-size:14px;
    }

    ul.setPaginate{
    margin:0px;
    padding:0px;
    height:100%;
    overflow:hidden;
    font:12px 'Tahoma';
    list-style-type:none;    
    }  

    ul.setPaginate li.dot{padding: 3px 0;}

    ul.setPaginate li{
    float:left;
    margin:0px;
    padding:0px;
    margin-left:5px;
    }



    ul.setPaginate li a
    {
    background: none repeat scroll 0 0 #ffffff;
    border: 1px solid #cccccc;
    color: #999999;
    display: inline-block;
    font: 15px/25px Arial,Helvetica,sans-serif;
    margin: 5px 3px 0 0;
    padding: 0 5px;
    text-align: center;
    text-decoration: none;
    }    

    ul.setPaginate li a:hover,
    ul.setPaginate li a.current_page
    {
    background: none repeat scroll 0 0 #0d92e1;
    border: 1px solid #000000;
    color: #ffffff;
    text-decoration: none;
    }

    ul.setPaginate li a{
    color:black;
    display:block;
    text-decoration:none;
    padding:5px 8px;
    text-decoration: none;
    }             
    </style>    
           
  </head>
  <body>
  
     <?php                  
 
          
        
        ?>
  <h2 style="margin-left:100px;"> Delete Multiple Records  </h2>      
<table width="600" border="0" cellspacing="1" cellpadding="0" align="center" style="margin-left:100px;">

<td><form name="form1" id="form1" method="post" action="" onSubmit="return validate();">
<table class="table  table-bordered table-hover"">
<tr>
 
<!--<td colspan="4"><strong>Delete Multiple Records..</strong> </td>       -->
</tr>

<tr>
<!--<td><input type="checkbox" name="checkbox[]" id="checkbox[]" value="allchk" onclick="checkedAll(form1);"></td>   -->
<td></td>
<td style=" width:10%"><strong>Id</strong></td>
<td style=" width:30%"><strong>UserName</strong></td>
<td style=" width:20%"><strong>Password</strong></td>
<td style=" width:30%"><strong>LoginId</strong></td>
<td style=" width:30%"><strong>Lastname</strong></td> 
<td style=" width:30%"><strong>Balance</strong></td> 
<td style=" width:30%"><strong>Language</strong></td> 
<td style=" width:30%"><strong>Country</strong></td> 

  
</tr>   
      
<?php
while($rows=mysql_fetch_array($result)){
?>

<tr>
<td><input name="checkbox[]" type="checkbox" id="checkbox[]" 
value="<?php echo $rows['id']; ?>"></td>
<td><?php echo $rows['id']; ?></td>
<td><?php echo $rows['username']; ?></td>
<td><?php echo $rows['uipass']; ?></td>
<td><?php echo $rows['useralias']; ?></td>
<td><?php echo $rows['lastname']; ?></td>
<td><?php echo $rows['credit']; ?></td>
<td><?php echo $rows['language']; ?></td>  
<td><?php echo $rows['country']; ?></td>
</tr>   
 
<?php
}
?>
  <?php
echo displayPaginationBelow($setLimit,$page);
  ?> 
<div style="margin-left:83%">

<input name="delete" type="submit" id="delete" value="Delete" class="cssbutton_big" onclick="allDelete();" ></div> 
<?php
//alert("are you want delete all account");
// Check if delete button active, start this
if(isset($_POST['delete'])){
for($i=0;$i<count($_POST['checkbox']);$i++){
$del_id=$_POST['checkbox'][$i];
$sql = "DELETE FROM cc_card WHERE id='$del_id'";
$sql1 = "DELETE FROM cc_sip_buddies WHERE id_cc_card='$del_id'"; 
$sql2 = "DELETE FROM cc_iax_buddies WHERE id_cc_card='$del_id'";  
$result = mysql_query($sql);
$result = mysql_query($sql1);
$result = mysql_query($sql2);
}
// if successful redirect to delete_multiple.php
if($result)
{
echo "<meta http-equiv=\"refresh\" content=\"0;URL=\">";
}
}   

  echo "<b>-List- $total_results Records\n</b>";    
mysql_close();
?>   
</table>
</form>
</td> 
</tr>  
</table>
  </body>
</html>
   
     