<?php
/*	Template Name: Byt Home page
 * The Front Page template file.
 *
 * This is the template of the page that can be selected to be shown as the front page.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
	global $currency_symbol;
	
	get_header();  
	
	$enable_car_rentals = of_get_option('enable_car_rentals', 1); 
	$enable_accommodations = of_get_option('enable_accommodations', 1); 
	$enable_tours = of_get_option('enable_tours', 1); 
	
 	get_template_part('includes/parts/post', 'latest'); 
	
	if ($enable_accommodations) {
		global $accommodation_review_fields;
		$accommodation_review_fields = list_accommodation_review_fields();
		get_template_part('includes/parts/accommodation', 'latest'); 
	}

	if ($enable_tours) {
		global $tour_review_fields;
		$tour_review_fields = list_tour_review_fields();
		get_template_part('includes/parts/tour', 'latest'); 
	}
	
	get_template_part('includes/parts/location', 'latest'); 

	wp_reset_postdata();
	get_sidebar('home-footer');
	get_footer(); 
?>