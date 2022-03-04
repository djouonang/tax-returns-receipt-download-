<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Entry_List_Table extends WP_List_Table {
	
	var $example_data = array(
  array('ID' => 1,
        'email' => 'example',
        'year' => '2021',
        'request_time' => '2021',
       // 'payment_amount' => '2021',
       // 'payment_date' => '2021',
       // 'payment_status' => 'Paid',
       // 'entry_date' => '2021', 
        //'user' => '2021',
       // 'transaction_id' => '978-0982514542'
       )
);

function get_columns(){
  $columns = array(
    'cb'        => '<input type="checkbox"  />',
    'email' => 'Email',
    'year'    => 'Year',
     'request_time' => 'Request Time',
//    'payment_amount'      => 'Payment Amount',
//	'payment_date'      => 'Payment Date',
//	'payment_status'      => 'Payment Status',
//	'entry_date'      => 'Entry Date',
//	'transaction_id'      => 'Transaction Id',
//	'user'      => 'User'
  );
  return $columns;
}

   /** Text displayed when no form entry is available */
public function no_items() {
  
echo 'No form entries found.';
  
}
  
function prepare_items() {

 $columns = $this->get_columns();
  $hidden = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array($columns, $hidden, $sortable);
  
$this->process_bulk_action();

$per_page = $this->get_items_per_page( 'formentries_per_page', 5 );
$current_page = $this->get_pagenum();
$total_items = $this->record_count();

$this->set_pagination_args( [
'total_items' => $total_items, //WE have to calculate the total number of items
'per_page' => $per_page //WE have to determine how many items to show on a page
] );

$this->items = $this->get_formentries( $per_page, $current_page );
  /*
$this->_column_headers = $this->get_column_info();


*/
}

function get_sortable_columns() {
  $sortable_columns = array(
    'email'  => array('email',false),
    'year' => array('year',false),
    'request_time' => array('request_time',false),
  //  'payment_date'   => array('payment_date',false),
  //  'user'   => array('user',false),
  );
  return $sortable_columns;
}
  
/**
* Method for name column
*
* @param array $item an array of DB data
*
* @return string
*/
  
function column_email( $item ) {

// create a nonce


$delete_nonce = wp_create_nonce( 'receipt_delete_formentry' );

$title = '<strong>' . $item['email'] . '</strong>';

$actions = [
'delete' => sprintf( '<a href="?page=%s&action=%s&record=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )
];

return $title . $this->row_actions( $actions );
  
}
  
function column_default( $item, $column_name ) {
  switch( $column_name ) { 
    case 'email':
    case 'year':
    case 'request_time':
//    case 'payment_amount':
//	case 'payment_date':
//	case 'payment_status':
//	case 'entry_date':
//	case 'transaction_id':
  //  case 'user':
      return $item[ $column_name ];
    default:
      return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
  }
}
  

  
 /**
* Returns an associative array containing the bulk action
*
* @return array
*/
  

  
/**
* get records from the database.
*/
 
  
   public function get_formentries( $per_page = 5, $page_number = 1 ) {

global $wpdb;
    
    
	$table_name = $wpdb->prefix . 'receipt_form_entry';
	

$sql = "SELECT * FROM $table_name ORDER BY id DESC";

if ( ! empty( $_REQUEST['orderby'] ) ) {
$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
}

$sql .= " LIMIT $per_page";

$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

$result = $wpdb->get_results( $sql, 'ARRAY_A' );

return $result;
}
  
/**
* Delete a record.
* @param int $id using ID
*/
  
 public function delete_formentry( $id ) {
global $wpdb;

  $table_name = $wpdb->prefix . 'receipt_form_entry';
  
$wpdb->delete( $table_name, [ 'id' => $id ],[ '%d' ]);
}
  
  /**
* Returns the count of records in the database.
*
* @return null|string
*/
  
public function record_count() {
  
global $wpdb;

  $table_name = $wpdb->prefix . 'receipt_form_entry';
  
$sql = "SELECT COUNT(*) FROM $table_name";

return $wpdb->get_var( $sql );
}
  
    /**
* Render the bulk edit checkbox
*
* @param array $item
*
* @return string
*/
  
function column_cb( $item ) {
return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']);
}
 
  public function get_bulk_actions() {
$actions = [
'bulk-delete' => 'Delete'
];

return $actions;
}
  
  public function process_bulk_action() {

//Detect when a bulk action is being triggered...
if ( 'delete' === $this->current_action() ) {

// In our file that handles the request, verify the nonce.
 $nonce = esc_attr( $_REQUEST['_wpnonce'] );

if ( ! wp_verify_nonce( $nonce, 'receipt_delete_formentry' ) ) {
die( 'Unable to delete' );
}
else {
self::delete_formentry( absint( $_GET['record'] ) );


  // show admin notice
       echo '<div class="notice notice-success is-dismissible"><p>Form entry deleted.</p></div>';
}

}

// If the delete bulk action is triggered
if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
) {

$delete_id = esc_sql( $_POST['bulk-delete'] );

// loop over the array of record IDs and delete them
foreach ( $delete_id as $id ) {
self::delete_formentry( $id );

}


  // show admin notice
       echo '<div class="notice notice-success is-dismissible"><p>Form entries deleted.</p></div>';
}
}
}