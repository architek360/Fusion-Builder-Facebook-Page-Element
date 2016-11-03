<?php
/**
 * Plugin Name: Fusion Builder Facebook Element
 * Plugin URI: https://www.twoinfinity.com.au
 * Description: Creates a page builder element that displays the Facebook page widget.
 * Version: 1.0
 * Author: Ryan Phillips
 * Author URI: https://www.twoinfinity.com.au
 *
 * @package Fusion Builder Facebook Element
 */

// Plugin Folder Path.
if ( ! defined( 'FB_FACEBOOK_PLUGIN_DIR' ) ) {
	define( 'FB_FACEBOOK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

register_activation_hook( __FILE__, array( 'FB_Facebook', 'activation' ) );

if ( ! class_exists( 'FB_Facebook' ) ) {

	/**
	 * The main plugin class.
	 */
	class FB_Facebook {

		/**
		 * The one, true instance of this object.
		 *
		 * @static
		 * @access private
		 * @since 1.0
		 * @var object
		 */
		private static $instance;
		
		/**
		 * An array of the shortcode arguments.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 * @var array
		 */
		public static $atts;

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 */
		public static function get_instance() {

			// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
			if ( null === self::$instance ) {
				self::$instance = new FB_Facebook();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0
		 */
		public function __construct() {

			//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_shortcode( 'fusion_facebook_page', array( $this, 'fusion_facebook_page' ) );

		}

		/**
		 * Enqueue scripts & styles.
		 *
		 * @access public
		 * @since 1.0
		 */
		public function enqueue_scripts() {

			

		}

		/**
		 * Returns the content.
		 *
		 * @access public
		 * @since 1.0
		 * @param array  $atts    The attributes array.
		 * @param string $content The content.
		 * @return string
		 */
		public function fusion_facebook_page( $atts ) {
			
			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'app_api'         			=> '',
					'page_url'        			=> 'http://www.facebook.com/facebook',
					'page_name'                	=> 'Facebook',
					'width'        				=> '',
					'height'        			=> '',
					'information_type'        	=> '',
					'show_small_header'        	=> '',
					'adapt_container_width'    	=> '',
					'hide_cover_photo'         	=> '',
					'show_friend_faces'			=> '',
				), $atts
			);
			
			self::$atts = $defaults;
			
			$html = '<div id="fb-root"></div>';
			$html .= '<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=' . $atts['app_api'] . '";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, "script", "facebook-jssdk"));</script>';
			
			$html .= '<div class="fb-page" data-href="' . $atts['page_url'] . '" data-width="' . $atts['width'] . 'px" data-height="' . $atts['height'] . 'px" data-tabs="' . $atts['information_type'] . '" data-small-header="' . $atts['show_small_header'] . '" data-adapt-container-width="' . $atts['adapt_container_width'] . '" data-hide-cover="' . $atts['hide_cover_photo'] . '" data-show-facepile="' . $atts['show_friend_faces'] . '">';
				$html .= '<blockquote cite="' . $atts['page_url'] . '" class="fb-xfbml-parse-ignore">';
					$html .= '<a href="' . $atts['page_url'] . '">' . $atts['page_name'] . '</a>';
				$html .= '</blockquote>';
			$html .= '</div>';

			return $html;
		}
		
		/**
		 * Processes that must run when the plugin is activated.
		 *
		 * @static
		 * @access public
		 * @since 1.0
		 */
		public static function activation() {
			if ( ! class_exists( 'FusionBuilder' ) ) {
				$message = '<style>#error-page > p{display:-webkit-flex;display:flex;}#error-page img {height: 120px;margin-right:25px;}.fb-heading{font-size: 1.17em; font-weight: bold; display: block; margin-bottom: 15px;}.fb-link{display: inline-block;margin-top:15px;}.fb-link:focus{outline:none;box-shadow:none;}</style>';
				$message .= '<span><span class="fb-heading">Sample Addon for Fusion Builder could not be activated</span>';
				$message .= '<span>Sample Addon for Fusion Builder can only be activated if Fusion Builder 1.0 or higher is activated. Click the link below to install/activate Fusion Builder, then you can activate this plugin.</span>';
				$message .= '<a class="fb-link" href="' . admin_url( 'admin.php?page=avada-plugins' ) . '">' . esc_attr__( 'Go to the Avada plugin installation page', 'Avada' ) . '</a></span>';
				wp_die( wp_kses_post( $message ) );
			}
		}
	}

	/**
	 * Instantiate FB_Facebook class.
	 */
	function sample_addon_activate() {
		FB_Facebook::get_instance();
	}

	add_action( 'wp_loaded', 'sample_addon_activate', 10 );
}

/**
 * Map shortcode to Fusion Builder.
 *
 * @since 1.0
 */
function map_facebook_page_with_fb() {

	// Map settings for parent shortcode.
	fusion_builder_map(
		array(
			'name'          => __( 'Facebook Page', 'fusion-builder' ),
			'shortcode'     => 'fusion_facebook_page',
			'icon'          => 'fa fa-facebook',
			'preview'       => FB_FACEBOOK_PLUGIN_DIR . 'preview/fusion-builder-facebook-page-element-preview.php',
			'preview_id'    => 'fusion-builder-block-module-facebook-page-template',
			'params'        => array(
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Facebook App API', 'fusion-builder' ),
					'description' => __( 'Enter the API key for your Facebook Application.', 'fusion-builder' ),
					'param_name'  => 'app_api',
					'value'       => esc_attr__('')
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Facebook Page URL', 'fusion-builder' ),
					'description' => __( 'Enter the URL to the Facebook page.', 'fusion-builder' ),
					'param_name'  => 'page_url',
					'value'       => esc_attr__('https://www.facebook.com/')
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Facebook Page Name', 'fusion-builder' ),
					'description' => __( 'Enter the name of the Facebook page.', 'fusion-builder' ),
					'param_name'  => 'page_name',
					'value'       => esc_attr__('')
				),
				array(
					'type'             => 'dimension',
					'remove_from_atts' => true,
					'heading'          => __( 'Widget Dimensions', 'fusion-builder' ),
					'description'      => __( 'The dimensions of the Facebook page widget (without px). The minimum width is 180px, the maxmimum is 500px. The minimum height is 70px.', 'fusion-builder' ),
					'param_name'       => 'widget_dimensions',
					'value'            => array(
						esc_attr__('width')  => '350px',
						esc_attr__('height') => '300px',
					),
					'default'          => array(
						esc_attr__('width')  => '350px',
						esc_attr__('height') => '300px',
					),
				),
				array(
					'type'        => 'checkbox_button_set',
					'heading'     => __( 'Information to Display', 'fusion-builder' ),
					'description' => __( 'Select what information you would like to display.', 'fusion-core' ),
					'param_name'  => 'information_type',
					'value'       => array(
						esc_attr__( 'Timeline', 'fusion-builder' )	=> 'timeline',
						esc_attr__( 'Events', 'fusion-builder' ) 	=> 'events',
						esc_attr__( 'Messages', 'fusion-builder' )	=> 'messages'
					),
					'default' => 'timeline',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => esc_attr__( 'Use Small Header', 'fusion-builder' ),
					'description' => esc_attr__( 'If set to \'yes\', the smaller header will be used. If set to \'no\', the large header will be used.', 'fusion-builder' ),
					'param_name'  => 'show_small_header',
					'value'       => array(
						esc_attr__( 'Yes', 'fusion-builder' ) => 'yes',
						esc_attr__( 'No', 'fusion-builder' )  => 'no',
					),
					'default'     => 'yes',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => __( 'Adapt to plugin container width', 'fusion-builder' ),
					'description' => __( 'Attempt to force the Facebook widget to fill the width of the container.', 'fusion-builder' ),
					'param_name'  => 'adapt_container_width',
					'value'       => array(
						esc_attr__( 'Yes', 'fusion-builder' ) => 'yes',
						esc_attr__( 'No', 'fusion-builder' )  => 'no',
					),
					'default'     => 'yes',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => __( 'Hide Cover Photo', 'fusion-builder' ),
					'description' => __( 'Show or hide the page\s cover photo.', 'fusion-builder' ),
					'param_name'  => 'hide_cover_photo',
					'value'       => array(
						esc_attr__( 'Yes', 'fusion-builder' ) => 'yes',
						esc_attr__( 'No', 'fusion-builder' )  => 'no',
					),
					'default'     => 'yes',
				),
				array(
					'type'        => 'radio_button_set',
					'heading'     => __( "Show Friend's Faces", 'fusion-builder' ),
					'description' => __( 'Show the profile photo of friends who have liked this page.', 'fusion-builder' ),
					'param_name'  => 'show_friend_faces',
					'value'       => array(
						esc_attr__( 'Yes', 'fusion-builder' ) => 'yes',
						esc_attr__( 'No', 'fusion-builder' )  => 'no',
					),
					'default'     => 'yes',
				),
			),
		)
	);

}

add_action( 'fusion_builder_before_init', 'map_facebook_page_with_fb', 3 );
