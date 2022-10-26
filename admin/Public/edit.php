<?php
// including the database connection file
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

$DBHandle  = DbConnect();
$inst_table  = new Table();
if(isset($_POST['update']))
{	
    $id = $_POST['id'];
	//$id = mysqli_real_escape_string($DBHandle, $_POST['id']);
	//$id = $inst_table -> SQLExec($DBHandle, $_POST['id']);

	
	 $project_name = $_POST['project_name'];
	//$project_name = mysqli_real_escape_string($DBHandle, $_POST['project_name']);
	//$project_name = $inst_table -> SQLExec($DBHandle, $_POST['project_name']);
	
	
	 $project_description = $_POST['project_description'];
    // $project_description = mysqli_real_escape_string($DBHandle, $_POST['project_description']);
	//$project_description = $inst_table -> SQLExec($DBHandle, $_POST['project_description']);
	
	
	$project_status = $_POST['project_status'];
	//$project_status = mysqli_real_escape_string($DBHandle, $_POST['project_status']);
     //$project_status = $inst_table -> SQLExec($DBHandle, $_POST['project_status']);	

	 $starting_date = $_POST['starting_date'];
	 //$starting_date = strtotime($starting_date);
   // $starting_date = date("Y-m-d h:i:s", $starting_date);
	 
	$ending_date = $_POST['ending_date'];
	//$ending_date = strtotime($ending_date);
   // $ending_date = date("Y-m-d h:i:s", $ending_date);
	
	
	$notes = $_POST['notes'];
	
	// checking empty fields
	if(empty($project_name) || empty($project_description) || empty($project_status)  || empty($starting_date) || empty($ending_date)) {	
			
		if(empty($project_name)) {
			echo "<font color='red'>Project Name field is empty.</font><br/>";
		}
		
		if(empty($project_description)) {
			echo "<font color='red'>Project Description field is empty.</font><br/>";
		}
		
		if(empty($project_status)) {
			echo "<font color='red'>Project Status field is empty.</font><br/>";
		}	

       if(empty($starting_date)) {
			echo "<font color='red'>Project Starting date field is empty.</font><br/>";
		}	
		
       if(empty($ending_date)) {
			echo "<font color='red'>Project Ending date field is empty.</font><br/>";
		}	

} else {	
		//updating the table
		$result = "UPDATE users1 SET project_name='$project_name',project_description='$project_description',project_status='$project_status' ,starting_date='$starting_date',ending_date='$ending_date',notes = '$notes' WHERE id=$id";
		$result_info  = $inst_table -> SQLExec($DBHandle, $result);
		//redirectig to the display page. In our case, it is index.php
		header("Location: new_monthly_project_report.php");
	}
}
?>
<?php
//getting id from url
$id = $_GET['id'];

//selecting data associated with this particular id
$result = "SELECT * FROM users1 WHERE id=$id";
$result_info  = $inst_table -> SQLExec($DBHandle, $result);

foreach($result_info as $res)
{
	$project_name = $res['project_name'];
	$project_description = $res['project_description'];
	$project_status = $res['project_status'];
	$starting_date = $res['starting_date'];
	//$starting_date = strtotime($starting_date);
    //$starting_date = date("Y-m-d h:i:s", $starting_date);
	
	$ending_date = $res['ending_date'];
	//$ending_date = strtotime($ending_date);
    //$ending_date = date("Y-m-d h:i:s", $ending_date);
	
	$notes = $_POST['notes'];
}
?>
<html>
<head>	
	<title>Edit Data</title>
</head>
 <link id="jquiCSS" rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />  
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript">  
                </script>  
                <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js" type="text/javascript">  
                    </script>  
                    <script src="Script/evol.colorpicker.js" type="text/javascript">  
                        </script>  
                        <link href="CSS/evol.colorpicker.css" rel="stylesheet" type="text/css" />  
                        <script> 
						$(document).ready(function()  
                             {  
                                $('#txtpickcolor1').colorpicker  
                                ({  
                                    initialHistory: ['#ff0000', '#000000', 'red']  
                                }).on('change.color', function(evt, color)   
                                 {  
                                    $('#div1').css('background-color', color);  
                                }).on('mouseover.color', function(evt, color)  
                                {  
                                    if (color)  
                                    {  
                                        $('#div1').css('background-color', color);  
                                    }  
                                });
								</script>  
								
								
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>
<script>
     new DatePicker('.picker', {
            pickerClass: 'starting_date ',
            allowEmpty: true
        }); 
</script>
  
  
  <script>
     new DatePicker('.picker', {
            pickerClass: 'ending_date ',
            allowEmpty: true
        }); 
</script>
  
						
<style>
.description {
  color: #4d0000;
}
p.ex {
	padding-top: 25px;
	 }
</style>
<body style="background-color:#eac086">
	<p align="center" class="ex"><a href="new_monthly_project_report.php"><h2 align="center">Home</h2></a></p>
	<br/><br/>
	<center>
	<form name="form1" method="post" action="edit.php">
		<table width="36%" border="1" style="text-align:center; >
			<div class="row">
			<tr align="center"> 
				<td><p class="description"><b>Project Name</b></p></td>
				<td><input type="text" name="project_name"  size="55"   height="50" value="<?php echo $project_name;?>"/></td>
			</tr>
			</div>
			
			<div class="row">
			<tr align="center">
				<td><p class="description"><b>Project Description</b></p></td>
				<td><textarea name="project_description" rows="17" cols="48"><?php echo $project_description;?></textarea></td>
			</tr>
			</div>
			
			
			
			<div class="row">
			<tr align="center">
				<td><p class="description"><b>Add Notes</b></p></td>
				<td><textarea name="notes" rows="5" cols="48"><?php echo $notes;?></textarea></td>
			</tr>
			</div>
			
			<div id="div1">
			<tr align="center">
				<td><p class="description" id="txtpickcolor1" ><b>Project Status</b></p></td>
				<td><select name="project_status" style="width:400px";value="<?php echo $project_status;?>">
				<option value="New" rows="17" height="50">New</option>
				<option value="Working" size="55" height="50">Working</option>
				<option value="Pending" size="55" height="50">Pending</option>
				<option value="Closed"  size="55" height="50">Closed</option></select>
				</td>
			</tr>
			</div>
			
			<div class="row">
			<tr align="center">
				<td><p class="description"><b>Starting Date</b></p></td>
				<td><input type="datetime-local" name="starting_date" class="starting_date" size="55"  width="48"  cols="48" value="<?php echo $starting_date;?>"/></td>
			</tr>
			</div>
			
			<div class="row">
			<tr align="center">
				<td><p class="description"><b>Ending Date</b></p></td>
				<td><input type="datetime-local" name="ending_date" class="ending_date"  size="55"  width="48"  cols="48" value="<?php echo $ending_date;?>"/></td>
			</tr>
			</div>
			
			<div><tr>
				<td><input type="hidden" name="id" value=<?php echo $_GET['id'];?>></td>
				<td align="right"><input type="submit" name="update" value="Update"></td>
			</tr>
			</div>
		</table>
	</form>
	</center>
</body>
</html>
