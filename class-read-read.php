<?php
/**
 * Read / Read
 *
 * @package   Read_Read
 * @author    Edward McIntyre <edward@edwardmcintyre.com>
 * @license   GPL-2.0+
 * @link      https://github.com/twittem/
 * @copyright 2013 Edward McIntyre
 */

/**
 * Read_Read class
 *
 * @package Read_Read
 * @author  Edward McIntyre <edward@edwardmcintyre.com>
 */

class Read_Read {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @const   string
	 */
	const VERSION = '1.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'read-read';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		//Custom Columns
		add_action( 'manage_post_posts_custom_column' , array( $this, 'display_custom_columns' ), 10, 2 );
		add_filter( 'manage_post_posts_columns' , array( $this, 'add_custom_columns' ) );

		// Add read_time to post content
		add_filter( 'the_content', array( $this, 'add_read_time_to_content' ), 20 );

		// Load public-facing JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Creating Ajax call for WordPress  
		add_action( 'wp_ajax_nopriv_log_read', array( $this, 'log_read' ) );  
		add_action( 'wp_ajax_log_read', array( $this, 'log_read' ) );  
		add_action( 'wp_head', array( &$this, 'add_ajax_library' ) );

		//Log Pageview
		add_action( 'wp_head', array( &$this, 'log_pageview' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function activate( $network_wide ) {
		// TODO: Define activation functionality
	}
	
	public static function deactivate( $network_wide ) {
		// TODO: Define activation functionality
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
	}



	/**
	 * -------------------------
	 * Admin Functions
	 * -------------------------
	 */

	/**
	 * Add Read/Read columns to post admin
	 *
	 * @since    1.0.0
	 *
	 * @return 	 array    Array of columns names
	 */
	function add_custom_columns( $columns ) {
		return array_merge( $columns,
			array(
				'read_time' => __( 'Read Time', 'Read_Read' ),
				'views' => __( 'Views', 'Read_Read' ),
				'reads' => __( 'Reads', 'Read_Read' ),
				'read_ratio' => __( 'Read Ratio', 'Read_Read' ),
			)
		);
	}

	/**
	 * Add content to Read/Read columns in post admin
	 *
	 * @since    1.0.0
	 */
	function display_custom_columns( $column, $post_id ) {
	    switch ( $column ) {
	        case 'read_time' :
	            echo $this->get_read_time( $post_id );
	        break;

	        case 'views' :
	            echo $this->get_pageviews( $post_id );
	        break;

	        case 'reads' :
	            echo $this->get_read_count( $post_id );
	        break;

	        case 'read_ratio' :
	            echo $this->get_read_ratio( $post_id );
	        break;
	    }
	}




	/**
	* --------------------
	* Frontend Functions
	* --------------------
	*/

	/**
	 * Adds the WordPress Ajax Library to the frontend.
	 *
	 * @since     1.0.0
	 *
	 * @return    string    Sets javascript var for ajaxurl
	 */
	public function add_ajax_library() {
	 
	    $html = '<script type="text/javascript">';
	        $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
	    $html .= '</script>';
	 
	    echo $html;
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->plugin_slug . '-plugin-waypoints', plugins_url( 'js/lib/waypoints.min.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Adds read time before post content
	 *
	 * @since    1.0.0
	 */
	function add_read_time_to_content ( $content ) {

		global $post;

		$read_time = $this->get_read_time( $post->ID );
		
		if ( 'post' == get_post_type() ) {
			$new_content .= '<span class="read_time">' . $read_time . '</span><br>';
		}
		
		$new_content .= $content;
		   
		return $new_content;
	}




	/**
	 * -------------------------
	 * Utility Functions
	 * -------------------------
	 */

	/**
	 * Logs updated post read_count when function is triggerd from 
	 * frontend AJAX call. 
	 *
	 * @since    1.0.0
	 */
	public function log_read() {

		if ( isset($_REQUEST) ) {
			$post_id = $_REQUEST['ID'];
			$read_count = get_post_meta( $post_id, '_rr_read_count', ture );

			if ( empty( $read_count ) ){
				add_post_meta( $post_id, '_rr_read_count', 1 );
			} else {
				$read_count++;
				update_post_meta( $post_id, '_rr_read_count', $read_count );
			}
		}
		
		die();
	}

	/**
	 * Logs updated post pageview_count when single post loads
	 *
	 * @since    1.0.0
	 */
	public function log_pageview(){

		global $post;

		if( is_single() ){
			$pageview_count = get_post_meta( $post->ID, '_rr_pageviews', true );

			if( empty( $pageview_count ) ) {
				add_post_meta( $post->ID, '_rr_pageviews', 1 );
			} else {
				$pageview_count++;
				update_post_meta( $post->ID, '_rr_pageviews', $pageview_count );
			}
		}
	}

	/**
	 * Calculates the estimated read_time for the post
	 *
	 * @since    1.0.0
	 */
	public function calc_read_time($post_id) {

		$content = get_post_field( 'post_content', $post_id, 'display' );

		$word_count = str_word_count( strip_tags( $content ) );

		$read_time_raw = ($word_count / 180);

		$read_time_round = round($read_time_raw);

		$read_time_final = $read_time_round == 0 ? '1 min read' : $read_time_round . ' min read';

		$read_time = array(
			'value' => $read_time_final,
			'time' => time(),
		);

		$meta = get_post_meta( $post_id, '_rr_read_time', ture );
		
		if ( empty( $meta ) ) {
			add_post_meta( $post_id, '_rr_read_time', $read_time );
		} else {
			update_post_meta( $post_id, '_rr_read_time', $read_time );
		}

		return $read_time['value'];
	}

	/**
	 * Retrieves post pageview_count
	 *
	 * @since    1.0.0
	 *
	 * @return   string  Returns post pageview_count
	 */
	public function get_pageviews( $post_id ){
		$pageview_count = get_post_meta( $post_id, '_rr_pageviews', true );
		return empty( $pageview_count ) ? '0' : $pageview_count;
	}

	/**
	 * Retrieves post read_count
	 *
	 * @since    1.0.0
	 *
	 * @return   string  Returns post pageview_count
	 */
	public function get_read_count( $post_id ){
		$read_count = get_post_meta( $post_id, '_rr_read_count', true );
		return empty( $read_count ) ? '0' : $read_count;
	}

	/**
	 * Calculates & retruns post read_ratio
	 *
	 * @since    1.0.0
	 *
	 * @return   string  Returns post read_ratio
	 */
	public function get_read_ratio( $post_id ){
		$pageview_count = $this->get_pageviews( $post_id );
		$read_count = $this->get_read_count( $post_id );
		return empty( $pageview_count ) && empty( $read_count ) ? '0%' : round(( $read_count / $pageview_count ) * 100) . '%';
	}

	/**
	 * Retrieves post estimated read_time
	 * If no read_time exsists one will be calculated
	 * Checks if the read_time is current to the last modified time
	 *
	 * @since    1.0.0
	 *
	 * @return   string  Returns post estimated read_time
	 */
	public function get_read_time( $post_id ) {

		$meta = get_post_meta( $post_id, '_rr_read_time', ture );

		if ( empty( $meta ) ) {
			$read_time = $this->calc_read_time( $post_id );
		} else {
			if( get_the_modified_time('U', $post_id) > $meta['time'] ) {
				$read_time = $this->calc_read_time( $post_id );
			} else {
				$read_time = $meta['value'];
			}
		}
		
		return $read_time;
	}
}