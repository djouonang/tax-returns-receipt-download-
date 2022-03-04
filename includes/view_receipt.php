<?php
   
  include_once('functions.php');

include_once('view_receipt_table.php');

   function view_receipts(){
 
      $myListTable = new View_Receipt_Table();
  
  echo '<div class="wrap"><h2>List of Receipts</h2>'; 
  
  $myListTable->prepare_items(); 
  
   ?>	
     
  <form id="record-list-form" method="POST">					
			<?php   $myListTable->display(); ?>					
		</form>

     <?php 
  
     
 
 }