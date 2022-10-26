<?php
$files = glob('uploads/*' ); 
foreach( $files as $file )
{ 
    if( is_file( $file ) ) 
    {
        unlink( $file );
        if( !@unlink( $file ) ) 
        { 
            //Handle your errors 
        } 
    } 
}

?>

<?php
//turn on php error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$name     = $_FILES['file']['name'];
	$tmpName  = $_FILES['file']['tmp_name'];
	$error    = $_FILES['file']['error'];
	$size     = $_FILES['file']['size'];
    $ext	  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
  
	switch ($error) {
		case UPLOAD_ERR_OK:
			$valid = true;
			//validate file extensions
			if ( !in_array($ext, array('jpg','jpeg','png','gif')) ) {
				$valid = false;
				$response = 'Invalid file extension.';
			}
			//validate file size
			if ( $size/1024 > 50 ) {
				$valid = false;
				$response = 'File size is exceeding maximum allowed size 50 KB.';
			}
			//upload file
			if ($valid) {
				$targetPath =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR. 'uploads' . DIRECTORY_SEPARATOR. $name;
                $temp = explode(".",$_FILES["file"]["name"]); 
                $newfilename = 'logo'. '.' .end($temp);
                //$newfilename = 'logo'. '.' .'png';
				//move_uploaded_file($tmpName,$targetPath .$newfilename);
                move_uploaded_file($tmpName,"uploads/".$newfilename); 
                //echo "<script type='text/javascript'>alert(\"Logo Updated Successfully---\")</script>";
                //window.location = "callshop_logo_upload.php";
                //header( 'Location: callshop_logo_upload.php' ) ;
                $response = 'Logo add successfully.';
                header("Location: callshop_logo_upload.php?Message=" . urlencode($response));
                exit;
			}
			break;
		case UPLOAD_ERR_INI_SIZE:
			$response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
			break;
		case UPLOAD_ERR_PARTIAL:
			$response = 'The uploaded file was only partially uploaded.';
			break;
		case UPLOAD_ERR_NO_FILE:
			$response = 'No file was uploaded.';
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			$response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
			break;
		case UPLOAD_ERR_CANT_WRITE:
			$response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
			break;
		default:
			$response = 'Unknown error';
		break;
	}
    header("Location: callshop_logo_upload.php?Message=" . urlencode($response));
	//echo $response;
}
?>
<?php
$temp = explode(".",$_FILES["file"]["name"]); 
$newfilename = 'logo'. '.' .end($temp); 
move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $newfilename);
?>
