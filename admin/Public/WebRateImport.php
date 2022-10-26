<?php

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (! has_rights (ACX_CALL_REPORT)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

//getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'lst_time','trunks'));

$DBHandle  = DbConnect();
$instance_table = new Table();

//     Initialization of variables    ///////////////////////////////
  

// #### HEADER SECTION
$smarty->display('main.tpl');

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
			Website Rates Import                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Rates                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_webrate_show.php?atmenu=tariffgroup&section=6" class="kt-subheader__breadcrumbs-link">
                            Website Rates                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="WebRateImport.php?atmenu=tariffgroup&section=6" class="kt-subheader__breadcrumbs-link">
                            Import Website Rate                        </a>
                                        <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
                    
        </div>
        
    </div>
</div>

<!-- end:: Subheader -->

<div class="kt-portlet">
<div class="kt-portlet__head">
	<div class="kt-portlet__head-label">
		<h1 class="kt-portlet__head-title">
			<?php echo gettext(' Import Website Rates'); ?>
		</h1>
	</div>
</div>

 <br>


	<div class="kt-portlet__body">
		<?php
			if(isset($_POST['submit']))
			  {
				 if(isset($_FILES) && $_FILES["filename"]['error']==0)
			  {
					if (($_FILES["filename"]["type"] == "application/vnd.ms-excel")) 
					{

					if ($_FILES["filename"]["error"] > 0) 
					{
					echo "error uploading the file";
					}
					else 
					{
						$query_deleterecords = "TRUNCATE TABLE cc_website_rates"; //empty the table of its current records
						$result_otp_info = $instance_table -> SQLExec($DBHandle, $query_deleterecords);
						if (is_uploaded_file($_FILES['filename']['tmp_name'])) 
						{
							echo "<h1>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h1>";
							//echo "<h2>Displaying contents:</h2>";
							//readfile($_FILES['filename']['tmp_name']);
						}

						//Import uploaded file to Database
						$handle = fopen($_FILES['filename']['tmp_name'], "r");

						while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
						{
							$instance_sub_table = new Table('cc_website_rates', 'id,countrycode,countryprefix,countryname,landline_rate,mobile_rate,sms_rate');
							$id_cc_card = $instance_sub_table->Add_table($DBHandle, "NULL,'".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."'", null, null, 'id');
						
							//$import="INSERT into cc_website_rate(id,countrycode,countryprefix,countryname,landline_rate,mobile_rate,sms_rate) values('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[25]."','".$data[6]."','".$data[7]."')";
							//mysql_query($import);
					}

					fclose($handle);

					//print "Import done";
					}
					}
					else 
						{
						echo "<h4>" . "File ". $_FILES['filename']['name'] ." is not a csv File." . "</h4>";   
						echo "<h4>"."Please import csv file only"."</h4>";
						print"<table border='1'>";
						print "Upload new csv by browsing to file and clicking on Upload<br />\n";

						//print "<form enctype='multipart/form-data' action='WebRateImport.php' method='post'>";

						//print "File name to import:<br />\n";

					//	print "<input size='50' type='file' name='filename'><br />\n";

						//print "<input type='submit' name='submit' value='Upload' class='btn btn-line-parrot'></form>";
						print "</table>";
						}
			}
			else
			{
				echo "<h1>There is No files...</h1>";
				echo "<h1>Please Import csv file.</h1>";
				print"<table border='1'>";
				print "Upload new csv by browsing to file and clicking on Upload<br />\n";

				print "<form enctype='multipart/form-data' action='WebRateImport.php' method='post'>";

				//print "File name to import:<br />\n";

				//print "<input size='50' type='file' name='filename'><br />\n";

				//print "<input type='submit' name='submit' value='Upload' class='btn btn-line-parrot'></form>";
				print "</table>";
			} 
			  
			  }
			  
			 

			?>

            <div class="kt-widget13" style="width: 90%; margin: 0 auto;"> 
			<h5> <i class="flaticon-upload"></i><?php echo gettext(" Upload new csv by browsing to file and clicking on Upload");?></h5>
			</div>
          <div class="kt-widget13__item" style="width: 90%; margin: 0 auto;">
            <form enctype='multipart/form-data' action='WebRateImport.php' method='post' class="form-horizontal">
                    <div class="form-group">
                            <div class="custom-file">
                                <input class="custom-file-input"  type='file' name='filename'>
                                <label class="custom-file-label" for="customFile"></label>
                            </div>
					</div>
					<div class="form-group" style="text-align:right">
                            <div class="kt-form__actions">
                                 <input type='submit' name='submit' value='Upload' class='btn btn-primary btn-small'>
								 <a href="billing_entity_webrate_show.php"><input type='submit' name='cancel' value='Cancel' class='btn btn-danger btn-small'></a>
                            </div>
					</div>
                 </form>                   

          </div>
          
</div>
</div>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>