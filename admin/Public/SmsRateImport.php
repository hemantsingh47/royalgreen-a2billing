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

 <style type="text/css">
 h1 
 {
        font-size: 1.25rem;
    text-align: center;
    color: #1c9300;
 }
</style>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
											
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main" style="margin-top:0px;">
            
            <h3 class="kt-subheader__title">
                Import SMS Rates                           </h3>
            
                            <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                        <a href="" class="kt-subheader__breadcrumbs-link">
                            Rates                        </a>
                                            <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="billing_entity_smsrate_show.php?atmenu=tariffgroup&section=6 " class="kt-subheader__breadcrumbs-link">
                            SMS Rates                        </a>
							  <span class="kt-subheader__breadcrumbs-separator"></span>
                        <a href="SmsRateImport.php?atmenu=tariffgroup&section=6" class="kt-subheader__breadcrumbs-link">
                            Import SMS Rate                        </a>
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
			<?php echo gettext(' Import SMS Rates'); ?>
		</h1>
	</div>
</div>

 <br>

                                
<?php

//include "connection.php"; //Connect to Database

/*$deleterecords = "TRUNCATE TABLE cc_sms_rate"; //empty the table of its current records
mysql_query($deleterecords);*/

//Upload File
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
        else {
        $deleterecords = "TRUNCATE TABLE cc_sms_rate"; //empty the table of its current records
          mysql_query($deleterecords);


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
         $import_data = "INSERT into cc_sms_rate(id,countrycode,countryprefix,countryname,charge) values('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."')";
         $import_sms_rates = $instance_table->SQLExec($DBHandle, $import_data);
        /*mysql_query($import) or die(mysql_error());*/
        //mysql_query($import);
        //print_r($import_sms_rates);
        }

        fclose($handle);

        //print "Import done";
        }
        }
        else 
            {
            echo "<h1>" . "File ". $_FILES['filename']['name'] ." is not a csv File." . "</h1>";   
            echo "<h1>"."Please import csv file only"."</h1>";
            print"<table border='1'>";
            print "Upload new csv by browsing to file and clicking on Upload<br />\n";

            //print "<form enctype='multipart/form-data' action='SmsRateImport.php' method='post'>";

            //print "File name to import:<br />\n";

           // print "<input size='50' type='file' name='filename'><br />\n";

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

    print "<form enctype='multipart/form-data' action='SmsRateImport.php' method='post'>";

   // print "File name to import:<br />\n";

    //print "<input size='50' type='file' name='filename'><br />\n";

    //print "<input type='submit' name='submit' value='Upload' class='btn btn-line-parrot'></form>";
    print "</table>";
} 
  
  }
  
 

?>
                           
	<div class="kt-widget13" style="width: 90%; margin: 0 auto;"> 
			<h5> &nbsp; &nbsp; <i class="flaticon-upload"></i><?php echo gettext(" Upload new csv by browsing to file and clicking on Upload");?></h5>
			</div>
          <div class="kt-widget13__item" style="width: 90%; margin: 0 auto;">
            <form enctype='multipart/form-data' action='SmsRateImport.php' method='post' class="form-horizontal">
                    <div class="form-group">
                            <div class="custom-file">
                                <input class="custom-file-input"  type='file' name='filename'>
                                <label class="custom-file-label" for="customFile"></label>
                            </div>
					</div>
					<div class="form-group">
                            <div class="kt-form__actions" style="text-align:right">
                                 <input type='submit' name='submit' value='Upload' class='btn btn-primary btn-small'>
								 <a href="billing_entity_smsrate_show.php"><input type='submit' name='cancel' value='Cancel' class='btn btn-danger btn-small'></a>
                            </div>
					</div>
                 </form>                   

          </div>
   </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		
 <?php
// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>