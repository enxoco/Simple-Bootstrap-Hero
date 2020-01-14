<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

$customizer_color_panel = __DIR__ . '/includes/customizer/color_options/color-panels.php';
if ( file_exists( $customizer_color_panel ) ){
	require_once(__DIR__ . '/includes/customizer/color_options/color-panels.php');
}


   
/** Include custom fields
 * 
 */
 

 // Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', get_stylesheet_directory() . '/includes/acf/' );
define( 'MY_ACF_URL', get_stylesheet_directory_uri() . '/includes/acf/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );
include_once( MY_ACF_PATH . 'load-custom-fields-class.php' );
// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}

// (Optional) Hide the ACF admin menu item.
add_filter('acf/settings/show_admin', 'my_acf_settings_show_admin');
function my_acf_settings_show_admin( $show_admin ) {
    return true;
}


	
	/**
	 * 
	 * Load our plugin update checker dependency
	 * 
	 */

	require 'plugin-update-checker/plugin-update-checker.php';
	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/enxoco/Simple-Bootstrap-Hero',
		__FILE__,
		'simple-bootstrap-hero'
	);
	
	//Optional: If you're using a private repository, specify the access token like this:
	$myUpdateChecker->setAuthentication('your-token-here');
	
	//Optional: Set the branch that contains the stable release.
	$myUpdateChecker->setBranch('master');
/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		$args = array(
			'post_type'=>'hero_page'
		);
		wp_list_pages( $args ); 

		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}


	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['foo']   = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['menu'] = new \Timber\Menu( 'primary' );
		$context['site']  = $this;
		if ( function_exists( 'the_custom_logo' ) ) {

			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
			$context['logo'] = $logo[0];
			// $context['menu_color'] = theme_mod('menu_nav_link_color_picker');
		   }
		   $menu_color = get_theme_mod('menu_nav_link_color_picker');
		   $context['menu_text_color'] = $menu_color;
		   $context['nav_bg_color'] = get_theme_mod('menu_nav_background_color_picker');

		return $context;
	}


	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Add colors option
		add_theme_support( 'colors' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
		add_theme_support( 'custom-logo' );

	   		function bwpy_customizer( $wp_customize ) {

			// add "Content Options" section
			$wp_customize->add_section( 'bwpy_content_options_section' , array(
				'title'      => __( 'Content Options', 'bwpy' ),
				'priority'   => 100,
			) );


            $wp_customize->add_section( 'bwpy_color_options_section' , array(
                'title'      => __( 'Color Options', 'bwpy' ),
                'priority'   => 100,
			) );
			$wp_customize->add_control( 'bwpy_color_options_section', array(
				'title'		=> __('New options here', 'bwpy'),
				'type'		=>	'dropdown-pages',
			) );

			$wp_customize->add_setting( 'navbar_height', array(
				'default' => '',

			) );

			$wp_customize->add_control( 'navbar_height', array(
				'label'		=> __( 'Nav Bar Height', 'bwpy'),
				'section'	=> 'bwpy_content_options_section',
				'helper'	=> __( 'In PX' ),
				'priority'	=>	10,
				'type'		=>	'dropdown-pages',
				'input_attrs'       => array(
					'min'  => 0.5,
					'step' => 0.01,
					'max'  => 5,
			),
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( 
				$wp_customize, 
				'bwpy_color_options_section', 
				array(
					'label'      => __( 'Header Color', 'bwpy' ),
					'section'    => 'bwpy_content_options_section',
					'settings'   => 'your_setting_id',
						'priority'   => 1
				)
			));
			
			// add setting for page comment toggle checkbox
			$wp_customize->add_setting( 'bwpy_page_comment_toggle', array( 
				'default' => 1 
			) );
			
			// add control for page comment toggle checkbox
			$wp_customize->add_control( 'bwpy_page_comment_toggle', array(
				'label'     => __( 'Show comments on pages?', 'bwpy' ),
				'section'   => 'bwpy_content_options_section',
				'priority'  => 10,
				'type'      => 'checkbox'
			) );
		}
		add_action( 'customize_register', 'bwpy_customizer' );
// var_dump($color_arr);

	}
	

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		
		return $twig;
	}

	

}

new StarterSite();
