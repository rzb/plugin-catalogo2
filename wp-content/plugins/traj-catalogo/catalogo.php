<?php 
/*
 Plugin Name: Trajettoria Catalogo
Plugin URI: http://www.trajettoria.com
Description: Plugin para catalogar publicações e filtrar buscas por palavras-chave
Author: Trajettoria
Version: 1.0
Author URI: http://www.trajettoria.com
*/

require_once('inc/util.php');
require_once('catalogo-config.php');

class trajCatalogo {
	
	/*
	 * FUNCTION: __construct
	 * DESCRIPTION: construtor
	 */
	public function __construct() {
		$this->initialize();
	}
	
	/*
	 * FUNCTION: install
	 * DESCRIPTION: chamada na ativação do plugin do WordPress
	 */
	public static function install() {}
	
	/*
	 * FUNCTION: uninstall
	 * DESCRIPTION: chamada na desativação do plugin do WordPress
	 */
	public static function uninstall() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	
	/*
	 * FUNCTION: initialize
	 * DESCRIPTION: chamada toda vez que o site é carregado com o plugin ativo. Prepara o ambiente de execução.
	 */
	public function initialize() {
		
		// shortcodes
		add_shortcode('trajettoria-catalogo-adm', array('trajCatalogo', 'catAdminInterface') );
		add_shortcode('trajettoria-catalogo-user', array('trajCatalogo', 'catUserInterface') );
		
		// css
		wp_register_style( 'catalogo-style-css', plugins_url( '/css/style-catalogo.css', __FILE__ ), FALSE );
		wp_enqueue_style( 'catalogo-style-css' );
		wp_register_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css', TRUE );
		wp_enqueue_style( 'jquery-ui-css' );
		wp_register_style( 'bootstrap-css', plugins_url( '/css/bootstrap.css', __FILE__ ), FALSE );
		wp_enqueue_style( 'bootstrap-css' );
		
		
		// js
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-dialog');
		wp_register_script( 'bootstrap-js', plugins_url( '/js/bootstrap.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'bootstrap-js' );
		wp_register_script( 'catalogo-js', plugins_url( 'js/catalogo.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'catalogo-js' );
	}
	
	public static function catAdminInterface() {
		
		if ( current_user_can('manage_options') ) {
			
			?>
			
			<div id="container">
				
				<div id="loading">Carregando...</div>
			
				<div id="trabs_placeholder"></div>
				
				<div id="chaves_placeholder"></div>
			
			</div>
			
			<script type="text/javascript">

				jQuery(document).ready(function(){
	
					jQuery.ajaxSetup ({
					    // Disable caching of AJAX responses 
					    cache: false
					});
	
					jQuery('<div id="catalogo_dialog"></div>').dialog({
						'dialogClass' : 'wp-dialog',
						'modal' : true,
						'autoOpen' : false,
						'closeOnEscape' : true,
						'height' : 460,
						'width' : 620,
						'zIndex' : 9999,
						'open' : function() { 
							jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(1)').focus(); 
							jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(0)').blur(); 
						}
					});
	
					// AJAX loading indicator 
					jQuery("#loading").bind("ajaxStart", function(){
						jQuery(this).show().siblings().invisible();
					}).bind("ajaxComplete", function(){
						jQuery(this).hide().siblings().visible();
					});
					
					var pluginURL = '<?php echo plugin_dir_url(dirname(__FILE__) . '/catalogo.php'); ?>';
					
					loadPublicacoes({
								url			:	pluginURL,
								editable	:	true
					});
					loadChaves(pluginURL);	
				});
			
			</script>
			
			<?php
		}
	}
	
	public static function catUserInterface() {
		
		?>
			
			<div id="container">
				
				<div id="loading">Carregando...</div>
			
				<div id="pesquisa_placeholder"></div>
			
				<div id="trabs_placeholder"></div>
			
			</div>
			
			<script type="text/javascript">

				jQuery(document).ready(function(){
	
					jQuery.ajaxSetup ({
					    // Disable caching of AJAX responses 
					    cache: false
					});
	
					jQuery('<div id="catalogo_dialog"></div>').dialog({
						'dialogClass' : 'wp-dialog',
						'modal' : true,
						'autoOpen' : false,
						'closeOnEscape' : true,
						'height' : 460,
						'width' : 620,
						'zIndex' : 9999,
						'open' : function() { 
							jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(1)').focus(); 
							jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(0)').blur(); 
						}
					});
	
					// ajax loading indicator 
					jQuery("#loading").bind("ajaxStart", function(){
						jQuery(this).show().siblings().invisible();
					}).bind("ajaxComplete", function(){
						jQuery(this).hide().siblings().visible();
					});
					
					var pluginURL = '<?php echo plugin_dir_url(dirname(__FILE__) . '/catalogo.php'); ?>';
					
					loadPublicacoes({
						url:pluginURL
					});
				});
			
			</script>
			
			<?php

	}
	
} // fim da classe trajCatalogo

// ativa e desativa o plugin
register_activation_hook( __FILE__, array( 'trajCatalogo', 'install' ) );
register_deactivation_hook( __FILE__, array( 'trajCatalogo', 'uninstall' ) );

$__trajCatalogo = new trajCatalogo();

?>