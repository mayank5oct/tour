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
	<?php 
		wp_register_script('google-maps','http://maps.google.com/maps/api/js?sensor=false',	'jquery','1.0',true);
		wp_enqueue_script( 'google-maps' );	
		wp_register_script('infobox',get_template_directory_uri() . '/js/infobox.js','jquery','1.0',true);
		wp_enqueue_script( 'infobox' );
		wp_register_script(	'contact', get_template_directory_uri() . '/js/contact.js', 'jquery', '1.0',true);
		wp_enqueue_script( 'contact' );
	?>
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
	
	global $currency_symbol;
	$currency_obj = find_currency_object($current_currency);
	$currency_symbol = $currency_obj->currency_symbol;

?>	
		window.currentCurrency = '<?php echo $current_currency;?>';
		window.defaultCurrency = '<?php echo $default_currency; ?>';
		window.currencySymbol = '<?php echo $currency_symbol; ?>';
<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) { ?>
		window.currentLanguage = '<?php echo ICL_LANGUAGE_CODE; ?>';
<?php } ?>
		window.themePath = '<?php echo get_template_directory_uri(); ?>';
	</script>
	<?php
	 /* Contact form related stuff */
	 $business_address_latitude =  of_get_option('business_address_latitude', '');
	 $business_address_longitude =  of_get_option('business_address_longitude', '');
	 $contact_company_name = of_get_option('contact_company_name', '');
	 $contact_phone_number = of_get_option('contact_phone_number', '');
	 $contact_address_street = of_get_option('contact_address_street', '');
	 $contact_address_city = of_get_option('contact_address_city', '');
	 $contact_address_country = of_get_option('contact_address_country', '');	 
	 $company_address = '<strong>' . $contact_company_name . '</strong>';
	 $company_address .= (!empty($contact_address_street) ? ',' . $contact_address_street : '');
	 $company_address .= (!empty($contact_address_city) ? ',' . $contact_address_city : '');
	 $company_address .= (!empty($contact_address_country) ? ',' . $contact_address_country : '');
	 
	 if (!empty($business_address_longitude) && !empty($business_address_latitude)) {
	 ?>
	 
	<script>
 		window.business_address_latitude = '<?php echo $business_address_latitude; ?>';
		window.business_address_longitude = '<?php echo $business_address_longitude; ?>';
		window.company_address = '<?php echo $company_address; ?>';
	</script>
	<?php } ?>	
<?php
	 $my_account_page_id =  get_current_language_page_id(of_get_option('my_account_page', ''));
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

	 wp_head(); ?>	
</head>
<body <?php body_class(); ?>>
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

	<!--main-->
	<div class="main" role="main" id="primary">		
		<div class="wrap clearfix">
			<!--main content-->
			<div class="content clearfix <?php echo (!empty($business_address_longitude) && !empty($business_address_latitude) ? '' : 'empty'); ?>" id="content">