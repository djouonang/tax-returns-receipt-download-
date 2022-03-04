<?php

// require(__DIR__ . '/wp-load.php');
include_once('functions.php');


function front_form(){
  
  echo " <p> Preparing your tax return? Complete the form on this page and we’ll email you your charitable tax receipt(s) for all donations you made in the selected year.</p>";
   
  if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

         send_notification();
    
 }else{
?>

 <div class="container">

      
        <div>
  

		
<form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post" > 
			
		
			  <div  id="receiving_response"></div>
			 <fieldset>
			<label for="email"><strong>Email</strong><span class="label-color">*</span></label>
			
			 <div  class="input-type">
			 
			<input class="form-styling" id="email" type="text" name="email" placeholder="" value="" required/>
            <p class="placeholdertext">You must enter the email address used when making donations. If you changed email addresses in a calendar year, you must complete this form once for each email address you used to make a donation with.</p>
			</div>
              </fieldset>
              
               <fieldset>
		<label for="Choosecountry"><strong>Choose year</strong><span class="label-color">*</span></label>
		 <div  class="input-type">
			 <select id="dropfield" class="drop_fielddown"  name="chooseyear"  style="width: 70%"  ">
               <option  value="Please select" selected disabled>Please select</option>
                <option  value="2017">2017</option>
				<option  value="2018">2018</option>
				<option  value="2019">2019</option>
				 <option  value="2020">2020</option>
				  <option  value="2021">2021</option>
				 </select>
				 </div>
  </fieldset>
              
		<?php  include('captcha.php');  ?>

		    <input type="submit" class="submit-primaryy" name="newsubmit" value="Submit your Request" />
               
            </form>
     </div>
</div>

<?php
}
}



function send_notification(){

 global $wpdb;
 
 $captchaResult = $_POST["captchaResult"];
	$firstNumber = $_POST["firstNumber"];
	$secondNumber = $_POST["secondNumber"];
	
	$checkTotal = $firstNumber + $secondNumber;

if ($captchaResult == $checkTotal) {
  
	   $email = $_POST['email'];

	   $chooseyear = $_POST['chooseyear'];
       
       $gettime = get_the_date( 'l F j, Y' );
	   
 $table_name = $wpdb->prefix.'receipt_form_entry';
 
 $result_check = $wpdb->insert( $table_name, array(
 
     'year' => $_POST['chooseyear'],
	 'email' => $_POST['email'],
     'request_time' => $gettime
	
	 
   ) );
       
	$startdate = $chooseyear.'/1/1';
	$enddate = $chooseyear.'/12/31';
	$year = $chooseyear;
	$time1 = strtotime($startdate);
	$date1 = date('Y-m-d',$time1);
	$time2 = strtotime($enddate);
	$date2 = date('Y-m-d',$time2);
	$column_names = $wpdb->get_col("DESC wp_receipts", 0);
	$query = "SELECT wp_receipts.* FROM wp_receipts WHERE wp_receipts.Email = '".$email."' AND wp_receipts.Date>='".$date1."' AND wp_receipts.Date<='".$date2."'";
	$info = $wpdb->get_results($query);
	$subject = 'Your Charitable Tax Receipts for ' . $year;
	$headers = "From: noreply@nasimco.org\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
      
    $getreceipt = getreceiptdata();
     
      $organisation_name = $getreceipt->organisation_name;
   $organisation_address = $getreceipt->organisation_address;
   $registered_charity_number = $getreceipt->registered_charity_number;
   $template = $getreceipt->template;
    $paragraph = $getreceipt->paragraph;
      $location = $getreceipt->location;
      $image_id = $getreceipt->logo_id;
         $logo = $getreceipt->logo;
     
   //  $getreceiptinfo = getreceipt();
     
	if(count($info) != 0){
		$randomurl = md5( strval( time() ) );
		$zippath = ABSPATH.'/receipts/zipfiles/'.$year.'-nasimco-tax-receipts-'.$randomurl.'.zip';

		$zip = new ZipArchive();
		$file = $zippath;
		$zip->open($file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
		for($n=0;$n<count($info);$n++){
			$userinfo = (array) $info[$n];
        
			if ( !defined('ABSPATH') )
			define('ABSPATH', dirname(__FILE__) . '/');
            
           
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
          
      
  
         $attachement_url = wp_get_attachment_url($image_id);
         $logo_url = wp_get_attachment_url($logo);
       /*
         $name = $getreceiptinfo->Name;
         $receiptnumber = $getreceiptinfo->Number;
         $amount = $getreceiptinfo->Amount;
         $address = $getreceiptinfo->Address;
         $receiptissuedate = $getreceiptinfo->Date;
       */
       
            ob_start();
  if($template == 'Default Template'):
    include RECEIPT_DIR.'includes/templates/default.php';
    else:
   include RECEIPT_DIR.'includes/templates/custom.php';
   endif;
    
    $attachement_url = wp_get_attachment_url($image_id);
    
    $html = $html;
 		
    $pdf->writeHTML($html, true, 0, true, 0);
			$pdf->lastPage();
			$date = date('Y-m-d-h-i-s', time());
			$pdfname = $date.$n;
		 $pdfpath = ABSPATH.'/receipts/'.$pdfname.'.pdf';
			$pdf->Output($pdfpath, 'F');
            		ob_end_clean();

			$zip->addFile($pdfpath, $pdfname.'.pdf');
		}
		$zip->close();
		$files = glob(ABSPATH.'/receipts/*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file)) {
			unlink($file); // delete file
		  }
		}
        
        $email = $_POST['email'];
  
        $token = md5($email).rand(10,9999);
  
        $expFormat = mktime(
     date("H"), date("i"), date("s"), date("m") ,date("d"), date("Y")
     );
 
    $expdate = date("Y-m-d H:i:s",$expFormat);
    
    $downloadlink = get_home_url().'/receipts/zipfiles/'.$year.'-   nasimco-tax-receipts-'.$randomurl.'.zip';
        
        $homeurl = get_home_url().'/download-link/';
        $temporallink = add_query_arg( 'code', $token, $homeurl );
          
		$currentdatetime = date('Y-m-d h:i:s', time());
		$wpdb->insert('Ziplinks_history', array(
			'Time' => $currentdatetime,
			'FileName' => $year.'-nasimco-tax-receipts-'.$randomurl
		));
		$body = "<html>";
		$body .= "<body>";
		$body .= "<p>As-salāmu ʿalaykum,</p><p>Below, please find a link to download your tax receipt(s) for all donations you made in ". $year ." to NASIMCO.</p><p><a  href='".$temporallink."'>Click here to download your receipts →</a></p><p>For any questions or concerns, please contact our secretariat office - secretariat@nasimco.org</p><p>We thank you once again for your generous contributions, may Allah (SWT) reward you.</p><p>Thanks,<br/>
NASIMCO Team</p>";
		$body .= "</body>";
		$body .= "<html>";
	}
	else{
		$body = "<html>";
		$body .= "<body>";
		$body .= "<p>As-salāmu ʿalaykum,</p><p>We are unable to to find any records with the given email address. If you believe this is a mistake, please contact our secretariat office - secretariat@nasimco.org</p><p>We thank you once again for your generous contributions, may Allah (SWT) reward you.</p><p>Thanks,<br/>
NASIMCO Team</p>";
		$body .= "</body>";
		$body .= "<html>";
	}
    
		
		if(wp_mail( $email, $subject, $body, $headers ) ) //send mail an show success message 
       {
		  download_token_insert($token, $expdate, $downloadlink);
		   $success = '<p id="receiptconfirmation_text">Please check your inbox for a link to download your receipts. The link will expire in 24 hours.<br/>
           To request more receipts, please <a href="'.wp_get_referer().'">click here →</a></p>';
           echo $success;
       }elseif(!wp_mail( $email, $subject, $body, $headers )){
		
		   $nosuccess = '<div class="alert alert-warning">Error submitting form</div>';
           echo $nosuccess;
       }

       
} elseif($captchaResult == '') {
	echo '<div class="alert alert-warning">Please enter captcha.please <a href=""'.wp_get_referer().'"">click here →</a> to return to form</div>';
}else{
		echo '<div class="alert alert-warning">Wrong captcha.please <a href=""'.wp_get_referer().'"">click here →</a> to return to form</div>';
	
}
//wp_die();

}



function receipt_shortcode() {

wp_enqueue_script('cf_form');
wp_enqueue_style('cf-css2');
wp_enqueue_style('cf-css1');
    ob_start();
	
    front_form();
   

    return ob_get_clean();
}

add_shortcode( 'receipt_download', 'receipt_shortcode' );

function page_shortcode(){

$uniqueid = $_GET['code'];

     $expFormat = mktime(
     date("H"), date("i"), date("s"), date("m") ,date("d"), date("Y")
     );
 
    $currenttime = date("Y-m-d H:i:s",$expFormat);
    
     $currenttime = strtotime($currenttime);
    
 $valid_period = 1440; // 24 hours
     
 $geturl = get_token($uniqueid);

 $url = $geturl->url;
 
 $timestamp = $geturl->date;


  $timestamp =  date('Y-m-d H:i:s',strtotime('+24 hours',strtotime($timestamp)));

  $timestamp = strtotime($timestamp);

 if(!empty($url)){
if($timestamp > $currenttime){

 echo $url= "<p><a  href='".$url."'>Click here to download your receipts →</a></p>";
 
}else{
delete_token($uniqueid);
 echo '<div class="error">';
    echo 'Downloak link has expired!';
    echo '</div>';

}
 }else{
 
  echo '<div class="error">';
    echo 'Downloak link has expired!';
    echo '</div>';
    
 }

}

add_shortcode( 'downloadlink_page', 'page_shortcode' );