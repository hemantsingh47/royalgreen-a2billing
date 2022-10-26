<html>
<head>
	<title>Add Data</title>
</head>

<body style="background-color:#eac086">
<style>
p.ex {
	padding-top: 5px;
	 
}
.center { text-align: center; }
</style>
<?php
//including the database connection file
include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

//print_r($_POST);                   
       
$DBHandle  = DbConnect();
$inst_table  = new Table();
if(isset($_POST['Submit'])) {	
	//$project_name = mysqli_real_escape_string($DBHandle, $_POST['project_name']);
	//$project_description = mysqli_real_escape_string($DBHandle, $_POST['project_description']);
	//$project_status = mysqli_real_escape_string($DBHandle, $_POST['project_status']);
	
	
	$project_name = $_POST['project_name'];
	$project_description = $_POST['project_description'];
	$project_status = $_POST['project_status'];
	$starting_date = $_POST['starting_date'];
	
	$starting_date = strtotime($starting_date);
    $starting_date = date("Y-m-d h:i:s", $starting_date);
          
	
	
	$ending_date = $_POST['ending_date'];
	$ending_date = strtotime($ending_date);
    $ending_date = date("Y-m-d h:i:s", $ending_date);
	
	$notes = $_POST['notes'];
	
		
	// checking empty fields
	if(empty($project_name) || empty($project_description) || empty($project_status) || empty($starting_date) || empty($ending_date) ) {
				
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
			echo "<font color='red'>Project Status field is empty.</font><br/>";
		}
		
		if(empty($ending_date)) {
			echo "<font color='red'>Project Status field is empty.</font><br/>";
		}
		
		//link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";
	} else { 
		// if all the fields are filled (not empty) 
			
		//insert data to database
		
		

		
		$result = "INSERT INTO users1(project_name,project_description,project_status,starting_date,ending_date,hours,notes) VALUES('$project_name','$project_description','$project_status','$starting_date','$ending_date','','$notes')";
		
		$result_info  = $inst_table -> SQLExec($DBHandle, $result);
		
		//display success message
		?>
		<div><p class="ex"><h3 style="color:green;" align="center"><b>Data added successfully</b></h3></p></div>
		<div class="center">
		<?php
		echo "<br/><a href='new_monthly_project_report.php'>View Result</a>";
	}
}
?>
</div>
</body>
</html>
