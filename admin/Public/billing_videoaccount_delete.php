<?php
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include './form_data/FG_var_card_videoim.inc';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CUSTOMER)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}
//including the database connection file
include_once("config.php");
$smarty->display('main.tpl');   

//fetching data in descending order (lastest entry first)
//$result = mysql_query("SELECT * FROM users ORDER BY id DESC"); // mysql_query is deprecated
$result = mysqli_query($mysqli, "SELECT * FROM cc_card where video_status ='1' ORDER BY id DESC"); // using mysqli_query instead
?>

<html>
<head>	
	<title>Homepage</title>
	<style>
	a {
    color: #f51010;
}
td{
	
	text-align:center;
}
	</style>
	
</head>
 <div class="widget-box">
										 
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Account Information</h5>
	   </div>
<body>  
<div class="widget-content nopadding">                                   
	<table width="100%" border="1" >

	<tr bgcolor="#CCCCCC" align="center">
        <td>ID</td>
        <td>Account Number</td>
        <td>Login</td>
        <td>Password</td>
		<td>Last Name</td>    
        <td>Balance</td>  
        <td>Email</td>  
		<td>Action</td>
	</tr>
	<?php 
	//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
	while($res = mysqli_fetch_array($result)) { 		
		echo "<tr>";
        echo "<td>".$res['id']."</td>";
        echo "<td>".$res['username']."</td>";
		echo "<td>".$res['useralias']."</td>";
		echo "<td>".$res['uipass']."</td>";
		echo "<td>".$res['lname']."</td>";	
        echo "<td>".$res['credit']."</td>";  
        echo "<td>".$res['email']."</td>";   
		echo "<td><a href=\"billing_videocard_delete.php?username=$res[username]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";		
	}
	?>
	</table>
	</div>
</body>
</div>
</html>
<?php
$smarty->display('footer.tpl');
