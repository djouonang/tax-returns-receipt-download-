<?php
  
   function truncate_table($table_name){
  
  global $wpdb;
  
$delete = $wpdb->query("TRUNCATE TABLE $table_name");
  
  
  } 

add_action( 'wp_ajax_nopriv_download_file', 'download_file' );
add_action( 'wp_ajax_download_file', 'download_file' );

function download_file(){
  

    $getreceipt = getreceiptdata();
             
       $organisation_name = $getreceipt->organisation_name;
        $organisation_address = $getreceipt->organisation_address;
      $registered_charity_number = $getreceipt->registered_charity_number;
  
      $template = $getreceipt->template;
      $paragraph = $getreceipt->paragraph;
         $location = $getreceipt->location;
      $image_id = $getreceipt->logo_id;
       $logo = $getreceipt->logo;
  
  //from ajax script
   
      $id = trim($_POST['id']);
      $email = trim($_POST['newemail']);
      $date = trim($_POST['date']);
  
     $getreceiptinfo = getreceipt();
      
     $info = get_receipt_database($date, $email, $id);

      if(count($info) != 0){
	  
      $timestamp = strtotime($date);
      $year =  date("Y", $timestamp);
       
	
		$zippath = ABSPATH.'/receipts/zipfiles/';

		
        $file = $zippath;
		
			$userinfo = (array) $info[0];
            
           
			require_once('tcpdf_min/tcpdf.php');
                        
            
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
             
             
			$pdf->SetPrintHeader(false);
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			if (@file_exists(ABSPATH.'/lang/eng.php')) {
				require_once(ABSPATH.'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
          
			$pdf->SetFont('helvetica', '', 9);
			$pdf->AddPage();
          
          $index_link = $pdf->AddLink();
          $pdf->SetLink($index_link, 0, '*1');
          
      
  
      $attachement_url = wp_get_attachment_url($image_id);
       $logo_url = wp_get_attachment_url($logo);
       
         $name = $getreceiptinfo->Name;
         $receiptnumber = $getreceiptinfo->Number;
         $amount = $getreceiptinfo->Amount;
         $address = $getreceiptinfo->Address;
         $receiptissuedate = $getreceiptinfo->Date;
       
       
            ob_start();
         
  if($template == 'Default Template'):
    include RECEIPT_DIR.'includes/templates/default.php';
    else:
   include RECEIPT_DIR.'includes/templates/custom.php';
   endif;
    
    
 		
   $pdf->writeHTML($html, true, 0, true, 0);
          
	$pdf->lastPage();
          
	$date = date('Y-m-d-h-i-s', time());
	$pdfname = $date.$n;
		 $pdfpath =$zippath.$pdfname.'.pdf';
	     $pdf->Output($pdfpath, 'F');
            
          ob_end_clean();
        if (file_exists($pdfpath) && is_file($pdfpath)) {
header("Content-type:application/pdf");
    header('Content-Disposition: attachment; filename='.basename($pdfpath));
 //   header('Content-Length: ' . filesize($pdfpath));
    ob_clean();
    flush();
    readfile($pdfpath);
           }
	
		
		$files = glob(ABSPATH.'/receipts/*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file)) {
			unlink($file); // delete file
		  }
		}
        
       
     
      } 
}


function get_receipt_database($dateofreceipt, $email, $id){
  
global $wpdb;
  
  $query = "SELECT wp_receipts.* FROM wp_receipts WHERE wp_receipts.Email = '".$email."' AND wp_receipts.Date='".$dateofreceipt."' AND wp_receipts.id='".$id."'";
	$info = $wpdb->get_results($query);
  
  return $info;

}

function get_token($token){

   global $wpdb;
	   
	$table_name = $wpdb->prefix.'receipt_download_expire';
	
	$result = $wpdb->get_row("SELECT * FROM $table_name WHERE uniqueid = '$token'");
		 
	 return $result;

}

function download_token_insert($token, $expdate, $url){

  global $wpdb;
  
  $table_name = $wpdb->prefix.'receipt_download_expire';

  $num_row = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

  $result_check = $wpdb->insert( $table_name, array(
	
     'date' => $expdate,
     'uniqueid' => $token,
     'url' => $url,
	
    ));
    
}

function delete_token($token){

  global $wpdb;
  
    $table = $wpdb->prefix.'receipt_download_expire';
  
    $wpdb->delete( $table, array( 'uniqueid' => $token ) );

}


  function upload_csv_receipt(){
  
   global $wpdb;
	   
     $table_name =  $wpdb->prefix.'receipts';
    
    $num_row = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

	
   
    if($num_row !== 0):
      
      
      truncate_table($table_name);
              
              endif;
  
  	require_once( ABSPATH . 'wp-load.php');
  
  // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['uploadreceipt']['name']) && in_array($_FILES['uploadreceipt']['type'], $csvMimes)){	
	
    $new_file_name = strtolower($_FILES['uploadreceipt']['tmp_name']);
      
      // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['uploadreceipt']['tmp_name'], 'r');
	
 
      
       // Parse data from CSV file line by line
      
       fgetcsv($csvFile, 10000, ","); //skip first line
         
            while(($line = fgetcsv($csvFile, 10000, ",")) !== FALSE){
                // Get row data
                $type   = $line[0];
                $date  = $line[1];
                $number  = $line[2];
                $name  = $line[3];
                $fob  = $line[4];
                $account  = $line[5];
                $address  = $line[6];
                $email  = $line[7];
                $memo  = $line[8];
                $amount  = $line[9];
              
  
      //the file has passed the test
      //These files need to be included as dependencies when on the front end.
      require_once( ABSPATH . 'wp-admin/includes/image.php' );
      require_once( ABSPATH . 'wp-admin/includes/file.php' );
      require_once( ABSPATH . 'wp-admin/includes/media.php' );
       
      // Let WordPress handle the upload.
      // Remember, 'upload' is the name of our file input in our form above.
     

        
    $result_check = $wpdb->insert( $table_name, array(
	
     'Type' => $type,
	 'Date' => $date,
	 'Number' => $number,
	 'Name' => $name,
	 'Fob' => $fob,
      'Account' => $account,
      'Address' => $address,
      'Email' => $email,
      'Memo' => $memo,
      'Amount' => $amount

   ) );
  
  
   
   
   }
   
    }
	if($num_row !== 0):
   
  echo '<div class="notice is-dismissible notice-info">';
    echo 'csv uploaded successfully!';
    echo '</div>';
	else:
	
	echo '<div class="error">';
    echo 'Error uploaing csv!';
    echo '</div>';
	
	endif;
 
   }

function upload_csv(){
  
    global $wpdb;
	   	   
     $table_name =  $wpdb->prefix.'receipts';
  
  	require_once( ABSPATH . 'wp-load.php');
  
  // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['uploadreceiptcsv']['name']) && in_array($_FILES['uploadreceiptcsv']['type'], $csvMimes)){	
	
    $new_file_name = strtolower($_FILES['uploadreceiptcsv']['tmp_name']);
      
      // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['uploadreceiptcsv']['tmp_name'], 'r');
	
       // Parse data from CSV file line by line
      
           fgetcsv($csvFile, 10000, ","); //skip first line
         
            while(($line = fgetcsv($csvFile, 10000, ",")) !== FALSE){
                // Get row data
                $type   = $line[0];
                $date  = $line[1];
                $number  = $line[2];
                $name  = $line[3];
                $fob  = $line[4];
                $account  = $line[5];
                $address  = $line[6];
                $email  = $line[7];
                $memo  = $line[8];
                $amount  = $line[9];
              
  
      //the file has passed the test
      //These files need to be included as dependencies when on the front end.
      require_once( ABSPATH . 'wp-admin/includes/image.php' );
      require_once( ABSPATH . 'wp-admin/includes/file.php' );
      require_once( ABSPATH . 'wp-admin/includes/media.php' );
       
      // Let WordPress handle the upload.
      // Remember, 'upload' is the name of our file input in our form above.
     


	$num_row = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");


	
        
    $result_check = $wpdb->insert( $table_name, array(
	
     'Type' => $type,
	 'Date' => $date,
	 'Number' => $number,
	 'Name' => $name,
	 'Fob' => $fob,
      'Account' => $account,
      'Address' => $address,
      'Email' => $email,
      'Memo' => $memo,
      'Amount' => $amount

   ) );
  
  
 
   }
    }
if($num_row !== 0):
   
   echo '<div class="notice is-dismissible notice-info">';
    echo 'csv uploaded successfully!';
    echo '</div>';
	else:
	
	 echo '<div class="error">';
    echo 'Error uploaing csv!';
    echo '</div>';
	
	endif;
 
  
  
   }
  
function upload_file() {
	 
	 	   global $wpdb;
	   
     $table_name = $wpdb->prefix.'receipt';
  
  	require_once( ABSPATH . 'wp-load.php');
     require_once( ABSPATH . 'wp-admin/includes/image.php' );
      require_once( ABSPATH . 'wp-admin/includes/file.php' );
      require_once( ABSPATH . 'wp-admin/includes/media.php' );
	  
if($_FILES['uploadinfo']['name']) {
	
	
    $new_file_name = strtolower($_FILES['uploadinfo']['tmp_name']);
	
    //can't be larger than 270 KB 
    if($_FILES['uploadinfo']['size'] > (270000)) {
      //wp_die generates a visually appealing message element
      wp_die('Your file size is to large.');
    }
    else {
     
       
      // Let WordPress handle the upload.
      // Remember, 'upload' is the name of our file input in our form above.
      $file_id = media_handle_upload( 'uploadinfo', 0 );
     
      
      if( is_wp_error( $file_id ) ) {
         wp_die('Error loading file!');
      } 
    }
  
}

if($_FILES['uploadlogo']['name']) {
	
	
    $new_file_name = strtolower($_FILES['uploadlogo']['tmp_name']);
	
    //can't be larger than 270 KB 
    if($_FILES['uploadlogo']['size'] > (270000)) {
      //wp_die generates a visually appealing message element
      wp_die('Your file size is to large.');
    }
    else {
     
       
      // Let WordPress handle the upload.
      // Remember, 'upload' is the name of our file input in our form above.
      $file = media_handle_upload( 'uploadlogo', 0 );
     
      
      if( is_wp_error( $file ) ) {
         wp_die('Error loading file!');
      } 
    }
  
}

	$num_row = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    
  $organisationaddress = $_POST['streetaddress'].','.$_POST['addressline'].','.$_POST['organisationcity'].','.$_POST['organisationstate'].','.$_POST['zipcode'];
    
	if($num_row == 0):
	
    $result_check = $wpdb->insert( $table_name, array(
	
     'organisation_name' => $_POST['organisationname'],
	 'organisation_address' => $organisationaddress,
	 'registered_charity_number' => $_POST['registeredcharitynumber'],
	 'location' => $_POST['location'],
     'paragraph' => $_POST['paragraphtext'],
     'template' => $_POST['receipt_template'],
	 'logo_id' => $file_id,
     'logo' => $file,
   ) );
  
   elseif(isset($file_id) == '' && isset($file) == ''):
  
   $result_check =   $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET organisation_name = %s, organisation_address = %s, registered_charity_number = %d, location = %s, paragraph = %s, template = %s WHERE id = %d", $_POST['organisationname'], $organisationaddress, $_POST['registeredcharitynumber'], $_POST['location'], $_POST['paragraphtext'], $_POST['receipt_template'],  1 ) );
  
   elseif(isset($file_id) == ''):
  
   $result_check =   $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET organisation_name = %s, organisation_address = %s, registered_charity_number = %d, location = %s, paragraph = %s, logo = %d, template = %s WHERE id = %d", $_POST['organisationname'], $organisationaddress, $_POST['registeredcharitynumber'], $_POST['location'], $_POST['paragraphtext'], $file, $_POST['receipt_template'], 1 ) );

 elseif(isset($file) == ''):
  
   $result_check =   $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET organisation_name = %s, organisation_address = %s, registered_charity_number = %d, location = %s, paragraph = %s, logo_id = %d, template = %s WHERE id = %d", $_POST['organisationname'], $organisationaddress, $_POST['registeredcharitynumber'], $_POST['location'], $_POST['paragraphtext'], $file_id, $_POST['receipt_template'],  1 ) );

   else:

  $result_check =   $wpdb->query( $wpdb->prepare( "UPDATE $table_name SET organisation_name = %s, organisation_address = %s, registered_charity_number = %d, location = %s, paragraph = %s, logo_id = %d, logo = %d, template = %s WHERE id = %d", $_POST['organisationname'], $organisationaddress, $_POST['registeredcharitynumber'], $_POST['location'], $_POST['paragraphtext'], $file_id, $file, $_POST['receipt_template'], 1 ) );
   
   endif;
   
   
   if(isset($result_check)):
   
   echo '<div class="notice is-dismissible notice-info">';
    echo 'Information saved!';
    echo '</div>';
	else:
	
	 echo '<div class="error">';
    echo 'Error saving information!';
    echo '</div>';
	
	endif;
  
  }

function getreceiptdata(){
	
	   global $wpdb;
	   
	$table_name1 = $wpdb->prefix.'receipt';
	
   $getreceipt = $wpdb->get_row("SELECT * FROM $table_name1 WHERE id = 1");
		 
	 return $getreceipt;
}

function getreceipt(){
	
	global $wpdb;
	   
	$table_name = $wpdb->prefix.'receipts';
	
	$result = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1");
		 
	return $result;
  
}

function getreceiptdate(){
	
	   global $wpdb;
	   
	$table_name = $wpdb->prefix.'receipt_form_entry';
	
	$result = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1");
		 
	 return $result->request_time;
}