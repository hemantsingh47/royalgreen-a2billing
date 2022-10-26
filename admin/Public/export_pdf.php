<?php

include '../lib/admin.defines.php';
require_once '../lib/iam_csvdump.php';
include '../lib/admin.module.access.php';

require('../lib/fpdf/mysql_table.php');

if (!has_rights(ACX_CALL_REPORT) && !has_rights(ACX_CUSTOMER)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array ( 'var_export', 'var_export_type' ));

if (strlen($var_export) == 0) {
    $var_export = 'pr_sql_export';
}


if (strlen($_SESSION[$var_export]) < 10) {
    echo gettext("ERROR PDF EXPORT");
} else {
    $log = new Logger();
    if (strcmp($var_export_type, "type_pdf") == 0) {
        
        $myfileName = "Dump_" . date("Y-m-d");
        //$log->insertLog($_SESSION["admin_id"], 2, "FILE EXPORTED", "A File in PDF Format is exported by User, File Name= " . $myfileName . ".pdf", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'], '');
        
        dump($_SESSION[$var_export], $myfileName, "pdf", DBNAME, USER, PASS, HOST, DB_TYPE);
        
    } 
    $log = null;
}
 function _get_browser_type()
    {
        $USER_BROWSER_AGENT="";

        if (preg_match('/OPERA/i', strtoupper($_SERVER["HTTP_USER_AGENT"]))) {
            $USER_BROWSER_AGENT='OPERA';
        } elseif (preg_match('/MSIE/i',strtoupper($_SERVER["HTTP_USER_AGENT"]))) {
            $USER_BROWSER_AGENT='IE';
        } elseif (preg_match('/OMNIWEB/i', strtoupper($_SERVER["HTTP_USER_AGENT"]))) {
            $USER_BROWSER_AGENT='OMNIWEB';
        } elseif (preg_match('/MOZILLA/i', strtoupper($_SERVER["HTTP_USER_AGENT"]))) {
            $USER_BROWSER_AGENT='MOZILLA';
        } elseif (preg_match('/FIREFOX/i', strtoupper($_SERVER["HTTP_USER_AGENT"]))) {
            $USER_BROWSER_AGENT='FIREFOX';
        } elseif (preg_match('/KONQUEROR/i', strtoupper($_SERVER["HTTP_USER_AGENT"]))) {
            $USER_BROWSER_AGENT='KONQUEROR';
        } elseif (preg_match('/CHROME/i', strtoupper($_SERVER["HTTP_USER_AGENT"]))) {
            $USER_BROWSER_AGENT='CHROME';
        } else {
            $USER_BROWSER_AGENT='OTHER';
        }

        return $USER_BROWSER_AGENT;
    }
 function _get_mime_type()
    {
        $USER_BROWSER_AGENT= _get_browser_type();

        $mime_type = ($USER_BROWSER_AGENT == 'IE' || $USER_BROWSER_AGENT == 'OPERA')
                       ? 'application/octetstream'
                       : 'application/octet-stream';

        return $mime_type;
    }

 function dump($query_string, $filename="dump", $ext="pdf", $dbname="mysql", $user="root", $password="", $host="localhost",$db_type="postgres")
    {
            $now = gmdate('D, d M Y H:i:s') . ' GMT';
            $USER_BROWSER_AGENT= _get_browser_type();

            if ($filename!="") {
                 header('Content-Type: ' . _get_mime_type());
                 header('Expires: ' . $now); 
                 if ($USER_BROWSER_AGENT == 'IE') {
                      header('Content-Disposition: inline; filename="' . $filename . '.' . $ext . '"');
                      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                      header('Pragma: public');
                 } else {
                      header('Content-Disposition: attachment; filename="' . $filename . '.' . $ext . '"');
                      header('Pragma: no-cache');
                 }
                 
                
                  if ($ext=="pdf") {
                      if ($db_type == "postgres") {

                          _generate_pdf_mysql($query_string, $dbname, $user, $password, $host,$filename . '.' . $ext);
                      } else {

                          _generate_pdf_mysql($query_string, $dbname, $user, $password, $host,$filename . '.' . $ext);
                      }
                 }
                 
            } else {
                 echo "<html><body><pre>";
                 echo htmlspecialchars(_generate_pdf_mysql($query_string, $dbname, $user, $password, $host,$filename . '.' . $ext));
                 echo "</PRE></BODY></HTML>";
            }
    }
    

     function _generate_pdf_mysql($query_string, $dbname="mysql", $user="root", $password="", $host="localhost",$filename)
    {


      if(!$conn= _db_connect_mysql($dbname, $user , $password, $host))
          die("Error. Cannot connect to Database.");
      else {
        $result = @mysql_query($query_string, $conn);
        if(!$result)
            die("Could not perform the Query: ".mysql_error());
        else {
            $file = "";
            $fval=array();
           // $crlf = $this->_define_newline();
           while($str= @mysql_fetch_array($result, MYSQL_ASSOC)){
            array_push($fval,$str);   
           }
           //print_r($fval);

           
        $pdf=new PDF_MySQL_Table();
        $pdf->AddPage();
        //First table: put all columns automatically
        $pdf->Table($query_string); 
        
        $pdf->Output('D',$filename);
         
        }
      }
    }
    
    //pdf finish
   function _db_connect($dbname="mysql", $user="root", $password="", $host="localhost")
    {
      $result = pg_connect("host=$host port=5432 dbname=$dbname user=$user password=$password");
      if (!$result) {     // If no connection, return 0

       return false;
      }

      return $result;
    }


    function _db_connect_mysql($dbname="mysql", $user="root", $password="", $host="localhost")
    {
      $result = @mysql_pconnect($host, $user, $password);
      if (!$result) {     // If no connection, return 0

       return false;
      }

      if (!@mysql_select_db($dbname)) {  // If db not set, return 0

       return false;
      }

      return $result;
    }   
    

