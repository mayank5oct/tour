<?php

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
 
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

add_action('admin_menu' , 'tour_booking_admin_page');
function tour_booking_admin_page() {
	$hook = add_submenu_page('edit.php?post_type=tour', 'BYT Booking management', 'Bookings', 'edit_posts', basename(__FILE__), 'tour_bookings_admin_display');

	add_action( "load-$hook", 'tour_bookings_add_screen_options');
}

function tour_bookings_set_screen_options($status, $option, $value) {
	if ( 'tour_bookings_per_page' == $option ) 
		return $value;
}
add_filter('set-screen-option', 'tour_bookings_set_screen_options', 10, 3);

function tour_bookings_admin_head() {
	$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
	if( 'theme_tour_schedule_booking_admin.php' != $page )
		return;

	tour_bookings_admin_styles();
}
add_action( 'admin_head', 'tour_bookings_admin_head' );		

function tour_bookings_admin_styles() {

	if (isset($_POST['from'])) 
		$date_from =  wp_kses($_POST['from'], '');
	if (isset($_POST['to'])) 
		$date_to =  wp_kses($_POST['to'], '');

	echo '<style type="text/css">';
	echo '.wp-list-table .column-Id { width: 100px; }';
	echo '.wp-list-table .column-TourName { width: 250px; }';
	echo '.wp-list-table .column-VacancyDay { width: 150px; }';
	echo '.wp-list-table .column-UserId { width: 50px; }';
	echo '.wp-list-table .column-RoomType { width: 150px; }';
	echo '.wp-list-table .column-Rooms { width: 70px; }';
	echo '</style>';
}

function tour_bookings_add_screen_options() {
	global $wp_tour_booking_table;
	$option = 'per_page';
	$args = array('label' => 'Bookings','default' => 50,'option' => 'tour_bookings_per_page');
	add_screen_option( $option, $args );
 	$wp_tour_booking_table = new tour_booking_admin_list_table();
}

function tour_bookings_admin_display() {
	echo '</pre><div class="wrap"><h2>BYT Bookings</h2> Booking management screen'; 
	global $wp_tour_booking_table;
	$wp_tour_booking_table->handle_form_submit();
	
	if (isset($_GET['view'])) {
		$wp_tour_booking_table->render_view_form(); 
	} else {	
		$wp_tour_booking_table->prepare_items(); 
		
	if (!empty($_REQUEST['s']))
		$form_uri = esc_url( add_query_arg( 's', $_REQUEST['s'], $_SERVER['REQUEST_URI'] ));
	else 
		$form_uri = esc_url($_SERVER['REQUEST_URI']);	
	?>
		<form method="get" action="<?php echo $form_uri; ?>">
			<input type="hidden" name="paged" value="1">
			<input type="hidden" name="post_type" value="tour">
			<input type="hidden" name="page" value="theme_tour_schedule_booking_admin.php">
			<?php
			$wp_tour_booking_table->search_box( 'search', 'search_id' );
			?>
		</form>
	<?php 		
		$wp_tour_booking_table->display();
	} 
}

/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 */
class tour_booking_admin_list_table extends WP_List_Table {

	private $options;
	private $lastInsertedID;
	
	/**
	* Constructor, we override the parent to pass our own arguments.
	* We use the parent reference to set some default configs.
	*/
	function __construct() {
		global $status, $page;	
	
		 parent::__construct( array(
			'singular'=> 'booking', // Singular label
			'plural' => 'bookings', // plural label, also this well be one of the table css class
			'ajax'	=> false // We won't support Ajax for this table
		) );
		
	}	

	function column_default( $item, $column_name ) {
		return $item->$column_name;
	}	
	
	function extra_tablenav( $which ) {
		if ( $which == "top" ){	
			//The code that goes before the table is here
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
		}
	}		
	
	function column_Customer($item) {
		return $item->first_name . ' ' . $item->last_name;	
	}
	
	function column_TourName($item) {
		return $item->tour_name;	
	}
	
	function column_RoomType($item) {
		return $item->room_type;	
	}
	
	function column_UserId($item) {
		return $item->user_id > 0 ? $item->user_id : __('n/a', 'bookyourtravel');	
	}
	
	function column_StartDate($item) {
		return date("d.m.Y", strtotime($item->start_date));	
	}
	
	function column_Price($item) {
		return $item->total_price;
	}
	
	function column_Created($item) {
		return $item->created;	
	}
	
	function column_Action($item) {
		return "<a href='edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php&view=" . $item->Id . "'>" . __('View', 'bookyourtravel') . "</a> | <form method='post' name='delete_booking_" . $item->Id . "' id='delete_booking_" . $item->Id . "' style='display:inline;'>
					<input type='hidden' name='delete_booking' id='delete_booking' value='" . $item->Id . "' />
					<a href='javascript: void(0);' onclick='confirm_delete(\"#delete_booking_" . $item->Id . "\", \"" . __('Are you sure?', 'bookyourtravel') . "\");'>" . __('Delete', 'bookyourtravel') . "</a>
				</form>";
	}	
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'Id'=>__('Id'),
			'Customer'=>__('Customer'),
			'StartDate'=>__('Start Date'),
			'TourName'=>__('Tour Name'),
			'Price'=>__('Price'),
			'Created'=>__('Created'),
			'UserId'=>__('UserId'),
			'Action'=>__('Action'),				
		);
	}	
		
	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'Id'=> array( 'Id', true ),
			'TourName'=> array( 'tours.post_title', true ),
			'StartDate'=> array( 'start_date', true ),
			'Price'=> array( 'total_price', true )
		);
		return $sortable_columns;
	}	
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $_wp_column_headers;
		
		$screen = get_current_screen();
		$user = get_current_user_id();
		$option = $screen->get_option('per_page', 'option');
		$per_page = get_user_meta($user, $option, true);
		if ( empty ( $per_page) || $per_page < 1 ) {
			$per_page = $screen->get_option( 'per_page', 'default' );
		}	

		if (!empty($_REQUEST['s'])) {
			$search_term = mysql_real_escape_string(strtolower($_REQUEST['s']));
		}

		$columns = $this->get_columns(); 
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);		
		
		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'Id';
		$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'ASC';

		/* -- Pagination parameters -- */
		//Number of elements in your table?
		$totalitems = list_tour_bookings_total_items(); //return the total number of affected rows
		//How many to display per page?
		//Which page is this?
		$paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
		//Page Number
		if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
		//How many pages do we have in total?
		$totalpages = ceil($totalitems/$per_page);

		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page" => $per_page,
		) );
		//The pagination links are automatically built according to those parameters

		/* -- Register the Columns -- */
		$columns = $this->get_columns();
		$_wp_column_headers[$screen->id]=$columns;

		/* -- Fetch the items -- */
		$this->items = list_paged_tour_bookings('', $orderby, $order, $paged, $per_page );
	}
	
	function handle_form_submit() {
		
		if (isset($_POST['delete_booking'])) {
			$booking_id = absint($_POST['delete_booking']);
			
			delete_tour_booking($booking_id);
			
			echo '<div class="updated" id="message" onclick="this.parentNode.removeChild(this)">';
			echo '<p>' . __('Successfully deleted booking!', 'bookyourtravel') . '</p>';
			echo '</div>';
		}
		
	}
	
	function render_view_form() {
		
		$booking_id = isset($_GET['view']) ? intval($_GET['view']) : 0;
		if ($booking_id > 0) {

			$booking = get_tour_booking($booking_id);
			
			if ($booking != null) {
				echo "<p><h3>" . __('View booking', 'bookyourtravel') . "</h3></p>";
				echo "<table cellpadding='3' cellspacing='3' class='form-table'>";
				echo "<tr>";
				echo "<th>" . __('First name', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->first_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Last name', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->last_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Email', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->email . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Address', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->address . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Town', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->town . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Zip', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->zip . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Country', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->country . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Special requirements', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->special_requirements . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Tour', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->tour_name . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Start date', 'bookyourtravel') . "</th>";
				echo "<td>" . date("d.m.Y", strtotime($booking->start_date)) . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Total price', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->total_price . "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<th>" . __('Created at', 'bookyourtravel') . "</th>";
				echo "<td>" . $booking->created . "</td>";
				echo "</tr>";
				echo "</table>";
				echo "<p><a href='edit.php?post_type=tour&page=theme_tour_schedule_booking_admin.php'>" . __('&laquo; Go back', 'bookyourtravel') . "</a></p>";
				
			}
		}
	}

}
?>
