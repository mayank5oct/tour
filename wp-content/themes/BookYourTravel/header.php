<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage BookYourTravel
 * @since Book Your Travel 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7 ]>    <html class="ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 	 ]>    <html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="HandheldFriendly" content="True">
	<title><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></title>
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" />	
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<script type="text/javascript">
		window.themePath = '<?php echo get_template_directory_uri(); ?>';
	</script>
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<script type="text/javascript">	
<?php
	$current_user = wp_get_current_user();
	$default_currency = strtoupper(of_get_option('default_currency_select', 'USD'));
	$current_currency = $default_currency;
	
	global $currencies;
	
	$default_currency_list = array(
		'usd'=> '1',
		'gbp'=> '1',
		'eur'=> '1'
	);
	$possible_currencies = of_get_option('enabled_currencies', $default_currency_list);

	$enabled_currencies = array();
	foreach ($possible_currencies as $currency => $enabled) {
		if ($enabled == '1')
			$enabled_currencies[] = $currency;
	}
	
	if ($current_user->ID > 0){
	?>
		window.currentUserId = '<?php echo $current_user->ID;?>';
	<?php
		$user_currency = get_user_meta($current_user->ID, 'user_currency', true);

		if (!empty($user_currency) && in_array(strtolower($user_currency), $enabled_currencies))
			$current_currency = $user_currency;
	}
	$slider_speed =  of_get_option('slider_speed', '1000');
	
	global $currency_symbol;
	$currency_obj = find_currency_object($current_currency);
	$currency_symbol = $currency_obj->currency_symbol;

?>	
		window.sliderSpeed = '<?php echo $slider_speed; ?>';
		window.currentCurrency = '<?php echo $current_currency;?>';
		window.defaultCurrency = '<?php echo $default_currency; ?>';
		window.currencySymbol = '<?php echo $currency_symbol; ?>';
<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) { ?>
		window.currentLanguage = '<?php echo ICL_LANGUAGE_CODE; ?>';
<?php } ?>

	</script>
	
<?php
	 $my_account_page_id = get_current_language_page_id(of_get_option('my_account_page', ''));
	 $my_account_page = get_permalink($my_account_page_id);
	 
	$color_scheme_style_sheet = of_get_option('color_scheme_select', 'style');
	$logo_src = of_get_option('website_logo_upload', '');
	
	if (empty($logo_src)) {
		if (empty($color_scheme_style_sheet)) 
			$logo_src = get_template_directory_uri() . '/images/txt/logo.png';
		else if ($color_scheme_style_sheet == 'theme-strawberry')
			$logo_src = get_template_directory_uri() . '/images/themes/strawberry/txt/logo.png';
		else if ($color_scheme_style_sheet == 'theme-black')
			$logo_src = get_template_directory_uri() . '/images/themes/black/txt/logo.png';
		else if ($color_scheme_style_sheet == 'theme-blue')
			$logo_src = get_template_directory_uri() . '/images/themes/blue/txt/logo.png';
		else if ($color_scheme_style_sheet == 'theme-orange')
			$logo_src = get_template_directory_uri() . '/images/themes/orange/txt/logo.png';
		else if ($color_scheme_style_sheet == 'theme-pink')
			$logo_src = get_template_directory_uri() . '/images/themes/pink/txt/logo.png';
		else if ($color_scheme_style_sheet == 'theme-yellow')
			$logo_src = get_template_directory_uri() . '/images/themes/yellow/txt/logo.png';
		else if ($color_scheme_style_sheet == 'theme-navy')
			$logo_src = get_template_directory_uri() . '/images/themes/navy/txt/logo.png';
		else 
			$logo_src = get_template_directory_uri() . '/images/txt/logo.png';
	}

	$body_class = '';
	$frontpage_show_slider = of_get_option('frontpage_show_slider', '1');
	if (!$frontpage_show_slider)
		$body_class = 'noslider';
		
	wp_head(); 
?>	
</head>
<body <?php body_class($body_class); ?>>
	<!--header-->
	<header>
		<div class="wrap clearfix">
			<!--logo-->
			<h1 class="logo"><a href="<?php echo get_home_url(); ?>" title="<?php _e('Book Your Travel - home', 'bookyourtravel'); ?>"><img src="<?php echo $logo_src; ?>" alt="<?php _e('Book Your Travel - home', 'bookyourtravel'); ?>" /></a></h1>
			<!--//logo-->
			<!--ribbon-->
			<div class="ribbon">
				<nav>
					<ul class="profile-nav">
						<?php if (!is_user_logged_in()) { ?>
						<li class="active"><a href="#" title="<?php _e('My Account', 'bookyourtravel'); ?>"><?php _e('My Account', 'bookyourtravel'); ?></a></li>
						<li><a class="fn" onclick="toggleLightbox('login_lightbox');" href="javascript:void(0);" title="<?php _e('Login', 'bookyourtravel'); ?>"><?php _e('Login', 'bookyourtravel'); ?></a></li>
						<li><a class="fn" onclick="toggleLightbox('register_lightbox');" href="javascript:void(0);" title="<?php _e('Register', 'bookyourtravel'); ?>"><?php _e('Register', 'bookyourtravel'); ?></a></li>
						<?php } else {?>
						<li class="active"><a href="#" title="<?php _e('My Account', 'bookyourtravel'); ?>"><?php _e('My Account', 'bookyourtravel'); ?></a></li>
						<li><a class="fn" href="<?php echo $my_account_page; ?>" title="<?php _e('Dashboard', 'bookyourtravel'); ?>"><?php _e('Dashboard', 'bookyourtravel'); ?></a></li>
						<li><a class="fn" href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', 'bookyourtravel'); ?></a></li>
						<?php } ?>
					</ul>
					<ul class="currency-nav">
					<?php
						foreach ($enabled_currencies as $key => $value) {
							$currency_obj = find_currency_object($value);
							$currency_label = $currency_obj->currency_label;
					?>
						<li <?php if (strtolower($current_currency) == strtolower($value)) echo 'class="active"'; ?>><a class="currency <?php echo $value; ?>" href="#" title="<?php echo $currency_label; ?>"><?php echo $currency_label; ?></a></li>
					<?php } ?>
					</ul>
					<?php get_sidebar('header'); ?>	
				</nav>
			</div>
			<!--//ribbon-->			
			<!--search-->
			<div class="search">
				<form id="searchform" method="get" action="<?php echo home_url(); ?>">
					<input type="search" placeholder="<?php _e('Search entire site here', 'bookyourtravel'); ?>" name="s" id="search" /> 
					<input type="submit" id="searchsubmit" value="" name="searchsubmit"/>
				</form>
			</div>
			<!--//search-->		
			<!--contact-->
			<div class="contact">
				<span><?php _e('24/7 Support number', 'bookyourtravel'); ?></span>
				<span class="number"><?php echo of_get_option('contact_phone_number', ''); ?></span>
			</div>
			<!--//contact-->
		</div>
		<!--primary navigation-->
		<?php  if ( has_nav_menu( 'primary-menu' ) ) {
			wp_nav_menu( array( 
				'theme_location' => 'primary-menu', 
				'container' => 'nav', 
				'container_class' => 'main-nav',
				'container_id' => 'nav',
				'menu_class' => 'wrap'
			) ); 
		} else { ?>
		<nav class="main-nav">
			<ul class="wrap">
				<li class="menu-item"><a href="<?php echo home_url(); ?>"><?php _e('Home', "bookyourtravel"); ?></a></li>
				<li class="menu-item"><a href="<?php echo admin_url('nav-menus.php'); ?>"><?php _e('Configure', "bookyourtravel"); ?></a></li>
			</ul>
		</nav>
		<?php } ?>
		<!--//primary navigation-->
	</header>
	<!--//header-->
	<?php 
	
	if (is_front_page() && !is_home()) {
		get_sidebar('home-above-slider');

		if ($frontpage_show_slider && function_exists('show_sequence_slider_rotator')) {
			echo show_sequence_slider_rotator( 'homepage' ); 
		}
	}

	$custom_search_results_page_id = get_current_language_page_id(of_get_option('redirect_to_search_results', ''));
	$custom_search_results_page = get_permalink($custom_search_results_page_id);
	if (is_front_page() && !is_home() && !empty($custom_search_results_page)) { 
		
		$enable_hotel_search = of_get_option('enable_hotel_search', 1);
		$enable_tour_search = of_get_option('enable_tour_search', 1);
		$enable_self_catered_search = of_get_option('enable_self_catered_search', 1);
		$enable_car_rental_search = of_get_option('enable_car_rental_search', 1);

		$whats_count = 0;
		$form_box_counter = 2;
			
		if ($enable_hotel_search)
			$whats_count++;
		if ($enable_self_catered_search)
			$whats_count++;
		if ($enable_car_rental_search)
			$whats_count++;	
		if ($enable_tour_search)
			$whats_count++;	

		if ($whats_count <= 1)
			$form_box_counter = 1;
	?>
	<?php if ($whats_count > 0) { ?>
	<!--search-->
	<div class="main-search">
		<form id="main-search" method="get" action="<?php echo $custom_search_results_page; ?>">
			<?php if ($whats_count > 1) { ?>
			<!--column-->
			<div class="column radios">
				<h4><span>01</span> <?php _e('What?', 'bookyourtravel'); ?></h4>
				<?php if ($enable_hotel_search) {?>
				<script>window.visibleSearchFormNumber = 1;</script>
				<div class="f-item active" >
					<input type="radio" name="what" id="hotel" value="1" checked="checked" />
					<label for="hotel"> <?php _e('Hotel', 'bookyourtravel'); ?></label>
				</div>
				<?php } ?>
				<?php if ($enable_self_catered_search) { 
				if (!$enable_hotel_search) {
				?>
				<script>window.visibleSearchFormNumber = 2;</script>
				<?php } ?>
				<div class="f-item <?php echo $enable_hotel_search ? '' : 'active'?>" >
					<input type="radio" name="what" id="self_catered" value="2" <?php echo $enable_hotel_search ? '' : ' checked="checked"' ?> />
					<label for="self_catered"> <?php _e('Self Catering', 'bookyourtravel'); ?></label>
				</div>
				<?php } ?>
				<?php if ($enable_car_rental_search) {?>
				<div class="f-item" >
					<input type="radio" name="what" id="car_rental" value="3" />
					<label for="car_rental"> <?php _e('Rent a Car', 'bookyourtravel'); ?></label>
				</div>
				<?php } ?>
				<?php if ($enable_tour_search) {?>
				<div class="f-item" >
					<input type="radio" name="what" id="tour" value="4" />
					<label for="tour"> <?php _e('Tour', 'bookyourtravel'); ?></label>
				</div>
				<?php } ?>
			</div>
			<!--//column-->
			<?php } else {
				if ($enable_hotel_search) {
					echo '<input type="hidden" id="what" name="what" value="1" />';
					echo '<script>window.visibleSearchFormNumber = 1;</script>';
				} elseif ($enable_self_catered_search) {
					echo '<input type="hidden" id="what" name="what" value="2" />';
					echo '<script>window.visibleSearchFormNumber = 2;</script>';
				} elseif ($enable_car_rental_search) {
					echo '<input type="hidden" id="what" name="what" value="3" />';
					echo '<script>window.visibleSearchFormNumber = 3;</script>';
				} elseif ($enable_tour_search) {
					echo '<input type="hidden" id="what" name="what" value="4" />';
					echo '<script>window.visibleSearchFormNumber = 4;</script>';
				}
			} ?>			
			<div class="forms <?php echo ($whats_count <= 1) ? 'first' : ''?>" >
				<!--form accommodation-->
				<div class="form" id="form1">
					<!--column-->
					<div class="column">
						<h4><span>0<?php echo $form_box_counter; ?></span>  <?php _e('Where?', 'bookyourtravel'); ?></h4>
						<div class="f-item">
							<label for="term"><?php _e('Your destination', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('City, region, district or specific accommodation', 'bookyourtravel'); ?>" id="term" name="term" />
						</div>
					</div>
					<!--//column-->
					
					<!--column-->
					<div class="column twins">
						<h4><span>0<?php echo $form_box_counter + 1; ?></span> <?php _e('When?', 'bookyourtravel'); ?></h4>
						<div class="f-item datepicker">
							<label for="from"><?php _e('Check-in date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="from" name="from" /></div>
						</div>
						<div class="f-item datepicker">
							<label for="to"><?php _e('Check-out date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="to" name="to" /></div>
						</div>
					</div>
					<!--//column-->
				
					<!--column-->
					<div class="column twins last">
						<h4><span>0<?php echo $form_box_counter + 2; ?></span> <?php _e('Who?', 'bookyourtravel'); ?></h4>
						<div class="f-item spinner">
							<label for="rooms"><?php _e('Rooms', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="" id="rooms" name="rooms" />
						</div>
					</div>
					<!--//column-->
				</div>	
				<!--//form accommodation-->
				
				<!--form self-catered-->
				<div class="form" id="form2">
					<!--column-->
					<div class="column">
						<h4><span>0<?php echo $form_box_counter; ?></span>  <?php _e('Where?', 'bookyourtravel'); ?></h4>
						<div class="f-item">
							<label for="term2"><?php _e('Your destination', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('City, region, district or specific accommodation', 'bookyourtravel'); ?>" id="term2" name="term" />
						</div>
					</div>
					<!--//column-->
					
					<!--column-->
					<div class="column twins">
						<h4><span>0<?php echo $form_box_counter + 1; ?></span> <?php _e('When?', 'bookyourtravel'); ?></h4>
						<div class="f-item datepicker">
							<label for="from2"><?php _e('Check-in date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="from2" name="from" /></div>
						</div>
						<div class="f-item datepicker">
							<label for="to2"><?php _e('Check-out date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="to2" name="to" /></div>
						</div>
					</div>
					<!--//column-->
					
					<div class="column twins last">
						<h4><span>0<?php echo $form_box_counter + 2; ?></span> <?php _e('Who?', 'bookyourtravel'); ?></h4>
						<div class="f-item spinner">
							<label for="guests"><?php _e('Guests', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="" id="guests" name="guests" />
						</div>
					</div>

				</div>	
				
				<!--form car rental-->
				<div class="form" id="form3">
					<!--column-->
					<div class="column">
						<h4><span>0<?php echo $form_box_counter; ?></span>  <?php _e('Where?', 'bookyourtravel'); ?></h4>
						<div class="f-item">
							<label for="term3"><?php _e('Pick Up', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('I want to pick up car in', 'bookyourtravel'); ?>" id="term3" name="term" />
						</div>
						<div class="f-item">
							<label for="term4"><?php _e('Drop Off', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('I want to drop off car at', 'bookyourtravel'); ?>" id="term4" name="term_to" />
						</div>
					</div>
					<!--//column-->
					
					<!--column-->
					<div class="column two-childs">
						<h4><span>0<?php echo $form_box_counter + 1; ?></span> <?php _e('When?', 'bookyourtravel'); ?></h4>
						<div class="f-item datepicker">
							<label for="from3"><?php _e('Pick-up date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="from3" name="from" /></div>
							<select id="time_from" name="time_from">
								<option>00:00</option>
								<option>01:00</option>
								<option>02:00</option>
								<option>03:00</option>
								<option>04:00</option>
								<option>05:00</option>
								<option>06:00</option>
								<option>07:00</option>
								<option>08:00</option>
								<option>09:00</option>
								<option selected="selected">10:00</option>
								<option>11:00</option>
								<option>12:00</option>
								<option>13:00</option>
								<option>14:00</option>
								<option>15:00</option>
								<option>16:00</option>
								<option>17:00</option>
								<option>18:00</option>
								<option>19:00</option>
								<option>20:00</option>
								<option>21:00</option>
								<option>22:00</option>
								<option>23:00</option>
							</select>
						</div>
						<div class="f-item datepicker">
							<label for="to3"><?php _e('Drop-off date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="to3" name="to" /></div>
							<select id="time_to" name="time_to">
								<option>00:00</option>
								<option>01:00</option>
								<option>02:00</option>
								<option>03:00</option>
								<option>04:00</option>
								<option>05:00</option>
								<option>06:00</option>
								<option>07:00</option>
								<option>08:00</option>
								<option>09:00</option>
								<option selected="selected">10:00</option>
								<option>11:00</option>
								<option>12:00</option>
								<option>13:00</option>
								<option>14:00</option>
								<option>15:00</option>
								<option>16:00</option>
								<option>17:00</option>
								<option>18:00</option>
								<option>19:00</option>
								<option>20:00</option>
								<option>21:00</option>
								<option>22:00</option>
								<option>23:00</option>
							</select>

						</div>
					</div>
					<!--//column-->
					<?php

$car_types_args = array(
    'orderby'       => 'name', 
    'order'         => 'ASC',
    'hide_empty'    => true, 
    'fields'        => 'all', 
); 
						$car_types = get_terms(array('car_type'), $car_types_args);
					?>
					<!--column-->
					<div class="column twins last">
						<h4><span>0<?php echo $form_box_counter + 2; ?></span> <?php _e('Who?', 'bookyourtravel'); ?></h4>
						<div class="f-item spinner small">
							<label for="age"><?php _e('Driver\'s age?', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="" id="age" name="age" />
						</div>
						<?php if ($car_types && count($car_types) > 0) { ?>
						<div class="f-item">
							<label for="car_types"><?php _e('Car type?', 'bookyourtravel'); ?></label>
							<select name="car_types" id="car_types">
								<option selected="selected" value=""><?php _e('No Preference', 'bookyourtravel'); ?></option>
								<?php foreach ($car_types as $car_type) {
									echo "<option value='{$car_type->term_id}'>{$car_type->name}</option>";
								}?>
							</select>
						</div>
						<?php } ?>
					</div>
					<!--//column-->

				</div>	
				<!--//form car rental-->
				
				
				<!--form tour-->
				<div class="form" id="form4">
					<!--column-->
					<div class="column">
						<h4><span>0<?php echo $form_box_counter; ?></span>  <?php _e('Where?', 'bookyourtravel'); ?></h4>
						<div class="f-item">
							<label for="term5"><?php _e('Tour location', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="<?php _e('City, region, district or specific tour', 'bookyourtravel'); ?>" id="term5" name="term" />
						</div>
					</div>
					<!--//column-->
					
					<!--column-->
					<div class="column twins">
						<h4><span>0<?php echo $form_box_counter + 1; ?></span> <?php _e('When?', 'bookyourtravel'); ?></h4>
						<div class="f-item datepicker">
							<label for="from4"><?php _e('Start date', 'bookyourtravel'); ?></label>
							<div class="datepicker-wrap"><input type="text" placeholder="" id="from4" name="from" /></div>
						</div>
					</div>
					<!--//column-->
					
					<div class="column twins last">
						<h4><span>0<?php echo $form_box_counter + 2; ?></span> <?php _e('Who?', 'bookyourtravel'); ?></h4>
						<div class="f-item spinner">
							<label for="guests2"><?php _e('Guests', 'bookyourtravel'); ?></label>
							<input type="text" placeholder="" id="guests2" name="guests" />
						</div>
					</div>

				</div>	
				
				<!--//form tour-->

			</div>
			
			
			<input type="submit" value="<?php _e('Proceed to results', 'bookyourtravel'); ?>" class="search-submit" id="search-submit" />
		</form>
	</div>
	<!--//search-->
	<?php } ?>
	<?php } ?>
	
	<?php if (is_front_page() && !is_home()) {
		get_sidebar('home-below-slider');
	} ?>
	
	<!--main-->
	<div class="main" role="main" id="primary">		
		<div class="wrap clearfix">
			<!--main content-->
			<div class="content clearfix" id="content">