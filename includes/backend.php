<?php

include_once('functions.php');

function receipt_setting_form() {
  
 echo '<div class="settings-form-wrap">';
echo '<h3>General Settings</h3>';
     
  if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
   upload_file();
   
         }
  
$query = getreceiptdata();

 $organisation_name = $query->organisation_name;
 $organisation_address = $query->organisation_address;
 $paragraph = $query->paragraph;
 $receipt_template = $query->template;
 $user_array = explode(',', $organisation_address);
 $streetaddress = $user_array[0];
   $addressline = $user_array[1];
   $organisationcity = $user_array[2];
   $organisationstate = $user_array[3];
   $zipcode = $user_array[4];
   
 $registered_charity_number = $query->registered_charity_number;
 $location = $query->location;
 $file = $query->logo;
  $file_id = $query->logo_id;
    $logo_url = wp_get_attachment_url($file);
   $signature_url = wp_get_attachment_url($file_id);
		  ?>
       
     <div class="wrap">
  <form   action="<?php $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">

            <fieldset>
       <label for="organisationname"><h4>Choose Receipt Template:</h4></label>
        <div class="form-control">
         <select  class="form-styling" name="receipt_template" style="max-width: 100%; width:40%" id="receipt_template" >
     <?php
if($receipt_template !== ''){
  
  ?>
<option value="<?php echo $receipt_template;?>"
<?php  echo 'selected="selected"'; ?>>
<?php echo $receipt_template; ?></option>
  <?php
if($receipt_template !== 'Custom Template'){
  
  ?>
        <option value="Custom Template">Custom Template</option>;
    <?php
  }else{
    ?>
   <option value="Default Template">Default Template</option>;
       <?php

  
  }
    }else{
  ?>
 <option value="Default Template">Default Template</option>;
   <option value="Custom Template">Custom Template</option>;
    <?php
  }
  ?>
    </select>
		</div>
			</fieldset>
         
          <fieldset>
       <label for="organisationname"><h4>Paragragh:</h4></label>
        <div class="form-control">
	   <textarea id="" class="form-style" name="paragraphtext" rows="5" cols="74">
<?php echo  $paragraph; ?>
</textarea>
         <p><span class="infotext">Enter Paragraph to show on custom template of receipt</span> <p/>
		</div>
			</fieldset>
			
			 <br/>
         
            <fieldset>
    <label for="uploadlogo"><h4>Upload Logo:</h4></label>
       <div class="form-control">
	<input type="file" class="upload-styling" oninput="this.className = ''" name="uploadlogo" value="" accept="image/*" class="inputfile" autofocus>
 <p><span class="infotext">Upload logo to show on receipt</span> <p/>
      </div>
      <div class="form-group d-flex justify-content-center">
				<img src="<?php echo $logo_url ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
			</div>
			</fieldset> 
			<br/>

			<fieldset>
       <label for="organisationname"><h4>Organisation name:</h4></label>
        <div class="form-control">
	   <input class="form-styling" id="organisationname" type="text" name="organisationname" value="<?php echo  $organisation_name; ?>" placeholder="Enter name of organisation or company" required/>
		</div>
			</fieldset>
			
			 <br/>

         <label for="organisationaddress"><h4>Organisation Address:</h4></label>
           
         <fieldset>
  <div class="form-control">
          
	   <input class="form-styling"  type="text" name="streetaddress" value="<?php echo  $streetaddress; ?>" placeholder="Enter address to show on receipt" required/>
         <label for="streetaddress" style="display:block">Street Address</label>
				</div>
           </fieldset>
           
           <br/>
           
           </fieldset>
           <div class="form-control">
          
	   <input class="form-styling" type="text" name="addressline" value="<?php echo  $addressline; ?>" placeholder="Enter address to show on receipt" required/>
<label for="addressline" style="display:block"> Address Line 2</label>
				</div>
         </fieldset>
  
  <br/>
  
  <fieldset>
               <div class="form-control">

         <span style="display:inline-block">
    
    <input class="form-styling" id="address" type="text" name="organisationcity" style="width: 135%;" value="<?php echo  $organisationcity; ?>" placeholder="Enter address to show on receipt" required/>
  <label for="organisationcity" style="display:block">City</label>
</span>

<span style="display:inline-block;margin-left: 64px;">
   
    <input class="form-styling" type="text" name="organisationstate" style="width: 135%; " value="<?php echo  $organisationstate; ?>" placeholder="Enter state, region or province to show on receipt" required/>
     <label for="organisationstate"  style="display:block">State / Province / Region</label>
</span>
</div>
       </fieldset>
      
       <br/>
       
       <fieldset>
         <div class="form-control">
	   <input class="form-styling" type="text" name="zipcode" value="<?php echo $zipcode; ?>" placeholder="Enter address to show on receipt" required/>
       <label for="zipcode"  style="display:block">Zip Code</label>
				</div>
</fieldset>
         
         <br/>
         
			<fieldset>
       <label for="registeredcharitynumber"><h4>Registered Charity Number:</h4></label>
             
         <div class="form-control">

	   <input class="form-styling"  type="text" name="registeredcharitynumber" value="<?php echo  $registered_charity_number; ?>" placeholder="Enter registered charity number" required/>
			</div>
			</fieldset>
			 <br/>
			<fieldset>
       <label for="organisationaddress"><h4>Location Issued:</h4></label>
                    <div class="form-control">
	   <input class="form-styling" type="text" name="location" value="<?php echo  $location; ?>" placeholder="Enter location" required/>
					</div>
			</fieldset>
            <br/>
			<fieldset>
    <label for="uploadinfo"><h4>Upload Signature:</h4></label>
       <div class="form-control">
	<input type="file" class="upload-styling" oninput="this.className = ''" name="uploadinfo" value="" accept="image/*" class="inputfile" autofocus>
 <p><span class="infotext">Upload signature to show on receipt</span> <p/>
      </div>
      <div class="form-group d-flex justify-content-center">
				<img src="<?php echo $signature_url ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
			</div>
			</fieldset> 
			<br/>
  <button  class="text-primaryy" name="submit_form" type="submit">Save Settings</button>
</div>
</form>
</div>

<?php 
     }