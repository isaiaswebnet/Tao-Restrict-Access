<?php

if ( ! class_exists( 'Tao_Page_Template' ) ) :

class Tao_Page_Template {

    /**
     * Identificação para o plugin
    */
    protected $tao_slug;

	/**
	 * Instância a classe.
	*/
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	*/
	protected $templates;


	/**
	 * Returns an instance of this class.
	*/
	public static function get_instance() {

		if( null == self::$instance ) {
			self::$instance = new Tao_Page_Template();
		}

		return self::$instance;

	}

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	private function __construct() {

		$this->templates = array();
		
		add_filter('page_attributes_dropdown_pages_args', array( $this, 'register_page_templates' ) );

		add_filter('wp_insert_post_data', array( $this, 'register_page_templates' ) );

		add_filter('template_include', array( $this, 'view_page_template') );
		
		add_filter('single_template', array( $this, 'view_single_template') );

		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		$this->templates = array( 'restrita-page.php' => __( 'Área Restrita', $this->tao_slug ) );

		$templates = wp_get_theme()->get_page_templates();
		$templates = array_merge( $templates, $this->templates );

	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 */
	public function register_page_templates( $atts ) {

		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		$templates = wp_cache_get( $cache_key, 'themes' );
		if ( empty( $templates ) ) {
			$templates = array();
		}

		wp_cache_delete( $cache_key , 'themes');

		$templates = array_merge( $templates, $this->templates );

		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}

	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_page_template( $template ) {

		global $post;

		if ( !isset( $post ) ) return $template;

		if ( ! isset( $this->templates[ get_post_meta( $post->ID, '_wp_page_template', true ) ] ) ) {
			return $template;
		} 
		
		$file = plugin_dir_path( __FILE__ ) . 'template/' . get_post_meta( $post->ID, '_wp_page_template', true );

		if( file_exists( $file ) ) {
			return $file;
		} 
		
		return $template;

	} 
	
	/**
	* Single template 'Área Restrita'
	*/
	public function view_single_template($single) {

		if(file_exists(plugin_dir_path( __FILE__ ) . 'template/single-area_restrita.php'))
			return plugin_dir_path( __FILE__ ) . 'template/single-area_restrita.php';

		return $single;
	}
	
	 /**
	 * deactivate the template
	 */
	 static function deactivate( $network_wide ) {
		foreach($this as $value) {
			restrita-page::delete_tempslate( $value );
		}
		
	} 
	
	 /**
	 * Delete Templates from Theme
	 */
	 public function delete_template( $filename ){				
		$theme_path = get_template_directory();
		$template_path = $theme_path . '/' . $filename;  
		if( file_exists( $template_path ) ) {
			unlink( $template_path );
		}

		wp_cache_delete( $cache_key , 'themes');
	 }

	/**
	* Retrieves and returns the slug of this plugin
	*/
	 public function get_locale() {
		return $this->tao_slug;
	}

}

endif;
