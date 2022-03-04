<?php

include_once('functions.php');

include_once('entry_list_table.php');

function form_entry() {
	
  $myListTable = new Entry_List_Table();
  
  echo '<div class="wrap"><h2>Form Entries</h2>'; 
  
  $myListTable->prepare_items(); 
  
   ?>	
     
  <form id="record-list-form" method="POST">					
			<?php   $myListTable->display(); ?>					
		</form>

     <?php 
  
     }