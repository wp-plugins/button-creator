<?php
/*
Plugin Name: Button Creator
Description: An easy way to create a submit button for your Wordpress site.
Version: 1.0
Author: Jeff Bulllins
Author URI: http://www.thinklandingpages.com
*/

class ThinkButtonCreator {

    private $plugin_path;
    private $plugin_url;
    private $l10n;
    private $thinkButtonCreator;

    function __construct() 
    {	
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->l10n = 'wp-settings-framework';
        add_action( 'admin_menu', array(&$this, 'admin_menu'), 99 );
        
        // Include and create a new WordPressSettingsFramework
        require_once( $this->plugin_path .'wp-settings-framework.php' );
        $settings_file = $this->plugin_path .'settings/settings-general.php';
        
        $this->thinkButtonCreator = new WordPressSettingsFramework( $settings_file, '_think_button', $this->get_think_buttonsettings() );
        // Add an optional settings validation filter (recommended)
        //add_filter( $this->thinkButtonCreator->get_option_group() .'_settings_validate', array(&$this, 'validate_settings') );
        
        add_action( 'init', array(&$this, 'think_register_shortcodes'));
        add_action( 'wp_enqueue_scripts', array(&$this,'think_button_stylesheet' ));
    }
    
    function admin_menu()
    {
        $page_hook = add_menu_page( __( 'Button Creator', $this->l10n ), __( 'Button Creator', $this->l10n ), 'update_core', 'Button Creator', array(&$this, 'settings_page') );
        add_submenu_page( 'Button Creator', __( 'Settings', $this->l10n ), __( 'Settings', $this->l10n ), 'update_core', 'Button Creator', array(&$this, 'settings_page') );
    }
    
    function settings_page()
	{
	    // Your settings page
	    
	    ?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>Button Creator</h2>
			
			<h3>Buttons available in this version</h3>
			<p>Create this button by placing the shortcode tag <code>[think_button/]</code> on your page or post</p>	
			<?php $this->think_button_stylesheet(); ?>			
			
			<?php 
			//$this->think_button_shortcode();
			echo do_shortcode('[think_button/]');
			// Output your settings form
			$this->thinkButtonCreator->settings(); 
			?>
			
		</div>
		<?php
		
		// Get settings
		//$settings = thinkButtonCreator_get_settings( $this->plugin_path .'settings/settings-general.php' );
		//echo '<pre>'.print_r($settings,true).'</pre>';
		
		// Get individual setting
		//$setting = thinkButtonCreator_get_setting( thinkButtonCreator_get_option_group( $this->plugin_path .'settings/settings-general.php' ), 'general', 'text' );
		//var_dump($setting);
	}
	
	function validate_settings( $input )
	{
	    // Do your settings validation here
	    // Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
    	return $input;
	}
	
	
        
        function get_think_buttonsettings(){
        	$wpsf_settings[] = array(
		    'section_id' => 'general',
		    'section_title' => 'Button Creator Settings',
		    //'section_description' => 'Some intro description about this section.',
		    'section_order' => 5,
		    'fields' => array(
		      		        
		        )
		        
        
    );
    return $wpsf_settings;
        }
        
        function think_button_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'button_class' => 'gray_think_button',
			'text' => 'Submit',
			'url' => '',
		), $atts ) );
		ob_start();
		echo '<a href="'.$url.'">';
		echo '<span class="'.$button_class.'">'.$text.'</span></a>';
		return ob_get_clean();
	}
	function think_register_shortcodes(){
		add_shortcode( 'think_button', array(&$this, 'think_button_shortcode') );
		
	}
	
	function think_button_stylesheet() {
        	wp_register_style( 'think-button-style', plugin_dir_url(__FILE__).'css/button.css' );
        	wp_enqueue_style( 'think-button-style' );
    	}
	

}
new ThinkButtonCreator();

?>