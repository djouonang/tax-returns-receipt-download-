<?php

add_action('admin_menu', 'admin_option');

function admin_option() {
	
	 $page_title = 'Tax Receipts';
	 $menu_title = 'Tax Receipts';
	 $capability = 'edit_posts';
     $menu_slug = 'receipt_download';
     $function = 'receipt_setting_form';
     $icon_url = '';
     $position = 20;
	 
	    $page_title_create_formentry = 'Form Entries';
	 $submenu_title_create_formentry = 'Form Entries';
	 $submenuformentry_slug = 'form_entry';
	 $page_callback__formentry = 'form_entry';
	 
	  $page_title_create_formentry1 = 'Upload Receipts';
	 $submenu_title_create_formentry1 = 'Upload Receipts';
	 $submenuformentry_slug1 = 'upload_receipts';
	 $page_callback__formentry1 = 'upload_receipts';
  
    $page_title_create_formentry2 = 'View Receipts';
	 $submenu_title_create_formentry2 = 'View Receipts';
	 $submenuformentry_slug2 = 'view_receipts';
	 $page_callback__formentry2 = 'view_receipts';
	 

	 add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
  
  //rename sub menu that is formed by add_menu_page function
  
  	 add_submenu_page($menu_slug, 'Settings', 'Settings', $capability, $menu_slug  );

	 
	 add_submenu_page($menu_slug, $page_title_create_formentry, $submenu_title_create_formentry, 'edit_posts', $submenuformentry_slug, $page_callback__formentry);
	
     add_submenu_page($menu_slug, $page_title_create_formentry1, $submenu_title_create_formentry1, 'edit_posts', $submenuformentry_slug1, $page_callback__formentry1);

    add_submenu_page($menu_slug, $page_title_create_formentry2, $submenu_title_create_formentry2, 'edit_posts', $submenuformentry_slug2, $page_callback__formentry2);
  
}
require_once("backend.php");
require_once("uploadreceipt.php");
require_once("backendformentry.php");
require_once("form.php");
require_once("view_receipt.php");