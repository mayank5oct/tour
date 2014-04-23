<?php
/*	Template Name: Accommodation list by accommodation type
 * The template for displaying the accommodation list by accommodation type
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
get_header('accommodation'); 
byt_breadcrumbs();
get_sidebar('under-header');

global $post;
$accommodation_id = $post->ID;
$page_custom_fields = get_post_custom( $accommodation_id);
$is_self_catered = $page_custom_fields['accommodation_type_archive_is_self_catered'];

$accommodation_types = wp_get_post_terms($accommodation_id, 'accommodation_type', array("fields" => "all"));

$accommodation_type_id = 0;
if (count($accommodation_types) > 0) {
	$accommodation_type_id = $accommodation_types[0]->term_id;
}

global $currency_symbol;
$accommodation_review_fields = list_accommodation_review_fields();

if ( get_query_var('paged') ) {
	$paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
	$paged = get_query_var('page');
} else {
	$paged = 1;
}
$posts_per_page = of_get_option('accommodations_archive_posts_per_page', 12);

if ($is_self_catered)
	$query = list_paged_self_catered($posts_per_page, $paged, 0, 'post_title', 'ASC', $accommodation_type_id);
else
	$query = list_paged_hotels($posts_per_page, $paged, 0, 'post_title', 'ASC', $accommodation_type_id);

?>
	<section class="full">
		<h1><?php the_title(); ?></h1>
		<div class="deals clearfix">
		<?php 
		if ( $query->have_posts() ) {
			while ($query->have_posts()) {
				global $post, $accommodation_class;
				$query->the_post();
				$accommodation_class = 'one-fourth';
				get_template_part('includes/parts/accommodation', 'item');
			}
		?>
			<nav class="page-navigation bottom-nav">
				<!--back up button-->
				<a href="#" class="scroll-to-top" title="<?php _e('Back up', 'bookyourtravel'); ?>"><?php _e('Back up', 'bookyourtravel'); ?></a> 
				<!--//back up button-->
				<!--pager-->
				<div class="pager">
					<?php byt_display_pager($query->max_num_pages); ?>
				</div>
			</nav>
		<?php } // end if ( $query->have_posts() ) ?>
		</div><!--//deals clearfix-->
	</section>
<?php
	wp_reset_postdata();
	wp_reset_query();
get_footer(); 
?>


