<?php

include_once('functions.php');

function upload_receipts() {
  
   if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
     
  if (!empty($_FILES["uploadreceipt"]['name'])) {
    
   upload_csv_receipt();
    
         }elseif (!empty($_FILES["uploadreceiptcsv"]['name'])) {
    
   upload_csv();
    
         }else {
     echo '<div class="error">';
    echo 'Please upload csv!';
    echo '</div>';

     } 
   }
   
		  ?>
       
     <div class="wrap">
  <form   action="<?php $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">

<div class="settings-form-wrap">
<h3>Upload CSV Receipts to database - Option 1</h3>
			
			<fieldset>
    <label for="uploadinfo"><h4>Upload Receipts:</h4></label>
       <div class="form-control">
	<input type="file" class="upload-styling" oninput="this.className = ''" name="uploadreceipt" value="" accept="image/*" class="inputfile" autofocus>
 <p><span class="infotext">Upload receipt csv to database.This option will replace the entire database</span> <p/>
      </div> 
			</fieldset> 
			<br/>
 <h3>Upload CSV Receipts to database - Option 2</h3>

  <fieldset>
    <label for="uploadinfo"><h4>Upload Receipts:</h4></label>
       <div class="form-control">
	<input type="file" class="upload-styling" oninput="this.className = ''" name="uploadreceiptcsv" value="" accept="image/*" class="inputfile" autofocus>
 <p><span class="infotext">This option will append to the database (i.e. add additional rows)</span> <p/>
      </div>
			</fieldset> 
			<br/>
      
  <button  class="text-primaryy" name="submit_form" type="submit">Save Settings</button>
</div>
</form>
</div>

<?php 
     }