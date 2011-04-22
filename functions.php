<?php

//
//  Custom Child Theme Functions
//

// Unleash the power of Thematic's dynamic classes
// 
define('THEMATIC_COMPATIBLE_BODY_CLASS', true);
define('THEMATIC_COMPATIBLE_POST_CLASS', true);

// Unleash the power of Thematic's comment form
//
define('THEMATIC_COMPATIBLE_COMMENT_FORM', true);

// Unleash the power of Thematic's feed link functions
//
define('THEMATIC_COMPATIBLE_FEEDLINKS', true);

/** 
 * Global variables
 */
global $imagesdir, $childtheme, $my_site, $childtheme_dir, $custom_php, $default_php, $custom_less, $default_less, $site_imagesdir;
$imagesdir = get_bloginfo('stylesheet_directory') . '/images';
$childtheme = get_bloginfo('stylesheet_directory');
$my_site = $GLOBALS[ 'blog_id' ];
remove_filter(‘comment_text’, ‘make_clickable’, 9);


/** 
 * Per site variables (optional)
 */
$childtheme_dir = '/home/eta/public_html/easttexaslocal.com/public/wp-content/themes/lessthematic.test';
$site_imagesdir = get_bloginfo('stylesheet_directory') . '/images'. $my_site;
$custom_php = $childtheme_dir . '/php/' . $my_site . '.php';
$default_php = $childtheme_dir . '/php/default.php';
$custom_less = $childtheme . '/less/site-' . $my_site . '.less';
$default_less = $childtheme . '/less/default.less';
$custom_css = $childtheme . '/css/site-' . $my_site . '.css';


function my_footer($thm_footertext) {?>
	<div id="credits"><a href="http://web-standard-design.com">Web Design</a> by Web Standard CSS. <span>SEO by <a href="http://assassinmarketing.com">Assassin Marketing</a></span></div>
	<div id="administrator-login"><a href="/wp-admin/">Administrator Login</a> - <a href="/site-map/">Site Map</a> - <a href="/privacy-policy/">Privacy Policy</a> - <a href="/terms-and-conditions/">Terms and Conditions</a></div>
	
<?php
}
add_filter('thematic_footertext', 'my_footer');



function extend_body_class($c) {
	/**
	 * Usage: Add .= instead of = and a space before subsequent body classes to prevent them running together.
	 */
	$c[] = 'site';
	if ( is_page() || is_front_page() ) {
		//$c[] .= 'one-column';
	}	

	//$c[] .= 'site-' . $GLOBALS['blog_id'];
	
	// Uncomment below line if using wp-multilingual plugin
	// $c[] .= ' lang-' . ICL_LANGUAGE_CODE;
	return $c;
}
add_filter('thematic_body_class', 'extend_body_class');


function insert_content_header2() {
	if(is_home() && !is_paged()) 
	{ ?>
	<img src="<?php echo $imagesdir; ?>/heading-content-home.png" alt="" />
	<?php 
echo test;
	}
}
//add_action('thematic_abovecontent','insert_content_header');

function pre_sidebar() { ?>
<div id="pre-sidebar" class="rounded-corners">
</div>
<?php
}
// add_action('thematic_abovemainasides', 'pre_sidebar');
function head_wrap_start() { ?>
	<div id="head_wrapper">
<?php
}

add_action('thematic_aboveheader', 'head_wrap_start', 1);

function header_quote() { ?>
	<div id="header-logo">
		<a href="/"><img src="/wp-content/themes/rosebud/images/logo.png" alt="Rosebud Wood Floors" /></a>
	</div>
	<!-- div id="header-quote" class="rounded-corners">
		<a href="/contact-us"><img src="/wp-content/themes/rosebud/images/free-quote.png" alt="Free Dallas Wood Floor Quote" /></a>
	</div -->
<?php
}
add_action('thematic_header', 'header_quote', 7);


/**
 *
 * Include Javascripts
 *
 */
if ( !is_admin() ) { // instruction to only load if it is not the admin area
   // register your script location, dependencies and version
   wp_register_script('lt_scripts',
       get_bloginfo('stylesheet_directory') . '/js/scripts.js',
       array('jquery'),
       '1.0' );
   // enqueue the script
   wp_enqueue_script('lt_scripts');
}

function kill_sidebar() {
	if (is_page()) {
		return FALSE;
	}
	else {
		return TRUE;
	}
}
//add_action('thematic_sidebar', 'kill_sidebar');

// Create a custom access div with the menu and search box
function childtheme_override_access() { 
	?>
	<div id="access">
		<div id="menu-wrap">
			<div class="skip-link"><a href="#content" title="<?php _e('Skip navigation to the content', 'thematic'); ?>"><?php _e('Skip to content', 'thematic'); ?></a></div><!-- .skip-link -->
			<?php if ((function_exists("has_nav_menu")) && (has_nav_menu(apply_filters('thematic_primary_menu_id', 'primary-menu')))) {
				echo  wp_nav_menu(thematic_nav_menu_args());
			} else {
				echo  thematic_add_menuclass(wp_page_menu(thematic_page_menu_args()));	
			}?>
			<div id="access-search">
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="20" tabindex="1" />
						<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Search', 'thematic') ?>" tabindex="2" />
					</div>
				</form>
			</div>		
		</div>
	</div><!-- #access -->
	<div class="clearfix"></div>
	<?php }




// Print Style Sheet
function print_styles() { ?>
	<link rel="stylesheet" href="<?php echo get_bloginfo('stylesheet_directory'); ?>/css/print.css" type="text/css" media="print" />
<?php }
add_action('wp_head','print_styles');

// Let's move the subsidiary widget area below the header
function move_subsidiaries($content) {
	$content['1st Subsidiary Aside']['action_hook'] = 'thematic_belowheader';
	$content['2nd Subsidiary Aside']['action_hook'] = 'thematic_belowheader';
	$content['3rd Subsidiary Aside']['action_hook'] = 'thematic_belowheader';
	return $content;
}
add_filter('thematic_widgetized_areas', 'move_subsidiaries');

// Now we need to unhook everything else that's related to the subsidiary widget area
function remove_relatedfunctions() {
    remove_action('widget_area_subsidiaries', 'thematic_subsidiaryopen', 10);
    remove_action('widget_area_subsidiaries', 'add_before_first_sub',20);
    remove_action('widget_area_subsidiaries', 'add_between_firstsecond_sub',40);
    remove_action('widget_area_subsidiaries', 'add_between_secondthird_sub',60);
    remove_action('widget_area_subsidiaries', 'add_after_third_sub',80);
    remove_action('widget_area_subsidiaries', 'thematic_subsidiaryclose', 200);
}
add_action('init', 'remove_relatedfunctions');

// And now we need to add these functions to thematic_header()
add_action('thematic_belowheader', 'thematic_subsidiaryopen', 15);
add_action('thematic_belowheader', 'add_before_first_sub',20);
add_action('thematic_belowheader', 'add_between_firstsecond_sub',40);
add_action('thematic_belowheader', 'add_between_secondthird_sub',60);
add_action('thematic_belowheader', 'add_after_third_sub',80);
add_action('thematic_belowheader', 'thematic_subsidiaryclose', 200);
function head_wrap_end() { ?>
	</div><!-- #head_wrap_end -->

<?php
}

add_action('thematic_belowheader', 'head_wrap_end', 300);


function below_header() {
	if( is_front_page() ) {
		?>
		<div id="slider-wrap">
			<div id="slider" class="rounded-corners">
					<a href="/" class="slide-1"><img src="images/slide-1.jpg" alt="" /></a>
					<a href="/" class="slide-2"><img src="images/slide-2.jpg" alt="" /></a>
					<a href="/" class="slide-3"><img src="images/slide-3.jpg" alt="" /></a>
			</div>
		</div>
		<?php
	}	
}

//add_action('thematic_header', 'below_header', 10);


function footer_scripts() { ?>

<!-- include Cycle plugin -->
<script type="text/javascript" src="http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.all.2.74.js"></script>

<!--  initialize the slideshow when the DOM is ready -->
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function() {
    jQuery('#slider, .slider').cycle({
		fx: 'scrollUp' // choose your transition type, ex: fade, scrollUp, shuffle, etc...
	});
});
</script>
</script>

<?php
}
function foot_wrap_start() { ?>
	<div id="foot_wrapper">
<?php
}

add_action('thematic_abovefooter', 'foot_wrap_start', 1);

function foot_wrap_end() { ?>
	</div><!-- #footer_wrap --> 
<?php
}

add_action('thematic_belowfooter', 'foot_wrap_end', 1);

add_action('wp_footer', 'footer_scripts');