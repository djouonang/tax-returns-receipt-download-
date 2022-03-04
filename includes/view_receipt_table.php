<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class View_Receipt_Table extends WP_List_Table {
	

  
function get_columns(){
  $columns = array(
    'cb'        => '<input type="checkbox"  />',
    'Number' => 'Receipt No',
    'Name'    => 'Name',
     'Email' => 'Email',
    'Date'    => 'Date',
     'Amount' => 'Amount',
    'PDF'      => 'PDF Download link'
  );
  return $columns;
}

   /** Text displayed when no form entry is available */
public function no_items() {
  
echo 'No receipts  found.';
  
}
  
function prepare_items() {

 $columns = $this->get_columns();
  $hidden = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array($columns, $hidden, $sortable);
  
$this->process_bulk_action();

$per_page = $per_page = $this->get_items_per_page( 'receipt_per_page', 5 );
$current_page = $this->get_pagenum();
$total_items = $this->record_count();

$this->set_pagination_args( [
'total_items' => $total_items, //WE have to calculate the total number of items
'per_page' => $per_page //WE have to determine how many items to show on a page
] );

$this->items = $this->get_receiptentries( $per_page, $current_page );
 
  /*
$this->_column_headers = $this->get_column_info();

*/
  
}

function get_sortable_columns() {
  $sortable_columns = array(
    'Number'  => array('Number',false),
    'Date' => array('Date',false),
  //  'request_time' => array('request_time',false),
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
  
  
  
function column_default( $item, $column_name ) {
 
  switch( $column_name ) { 
   
      case 'Number':
      return $item[ $column_name ];
      break;
      
    case 'Date':
      return $item[ $column_name ];
      break;
    case 'Amount':
      return $item[ $column_name ];
      break;
     case 'Name':
      return $item[ $column_name ];
      break; 
    case 'Email':
      return $item[ $column_name ];
      break;
    case 'PDF':
  /*
      $downloadlink = get_home_url().'/receipts/zipfiles/'.$pdfname.'.pdf';
     
		
        
        $download_url = "<a class='clickme' data-email='".$item[ 'Email' ]."' data-date='".$item[ 'Date' ]."' data-id='".$item[ 'id' ]."' href='".$downloadlink."'>Download Receipt →</a>";
        return $download_url;
     
      break;
     
    default:
    return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
      */
      
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
        download_file();
   
         }
      
   
      ?>
       <form method="post" action="">
  <input type="hidden" id="fname1" name="id" value=" <?php echo $item[ 'id' ]  ?>">
  <input type="hidden" id="fname2" name="newemail" value="<?php echo $item[ 'Email' ]  ?>">
        
<input type="hidden" id="fname2" name="date" value="<?php echo $item[ 'Date' ]  ?>">
        
  <button  class="text-primaryy" name="submit_form" type="submit">Download Receipt</button>
</form> 
  <?php  

  }
}

  
/**
* get records from the database.
*/
 
  
public function get_receiptentries( $per_page, $page_number = 1 ) {

global $wpdb;
    
    
	$table_name = $wpdb->prefix . 'receipts';
	

$sql = "SELECT * FROM $table_name ORDER BY id DESC";

if ( ! empty( $_REQUEST['orderby'] ) ) {
$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
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
  
 public function delete_receiptentry( $id ) {
global $wpdb;

  $table_name = $wpdb->prefix . 'receipts';
  
$wpdb->delete( $table_name, [ 'id' => $id ],[ '%d' ]);
}
  
  /**
* Returns the count of records in the database.
*
* @return null|string
*/
  
public function record_count() {
  
global $wpdb;

$table_name = $wpdb->prefix . 'receipts';
  
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
  
   /**
* Returns an associative array containing the bulk action
*
* @return array
*/
  
  public function process_bulk_action() {

//Detect when a bulk action is being triggered...
if ( 'delete' === $this->current_action() ) {

// In our file that handles the request, verify the nonce.
  
 $nonce = esc_attr( $_REQUEST['_wpnonce'] );

if ( ! wp_verify_nonce( $nonce, 'receipt_delete_formentry' ) ) {
die( 'Unable to delete' );
}
else {
self::delete_receiptentry( absint( $_GET['record'] ) );


  // show admin notice
       echo '<div class="notice notice-success is-dismissible"><p>Receipt entry deleted.</p></div>';
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
       echo '<div class="notice notice-success is-dismissible"><p>Receipt entries deleted.</p></div>';
}
}
}