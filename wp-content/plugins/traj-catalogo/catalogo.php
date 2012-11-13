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
		wp_register_style('fineuploader-css', plugins_url( '/css/fileuploader.css', __FILE__ ), FALSE );
		wp_enqueue_style('fineuploader-css');
		wp_register_style( 'bootstrap-css', plugins_url( '/css/bootstrap.min.css', __FILE__ ), array('fineuploader-css') );
		wp_enqueue_style( 'bootstrap-css' );
		
		// js
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-dialog');
		wp_register_script('fineuploader-js', plugins_url( 'js/fileuploader.min.js', __FILE__ ) );
		wp_enqueue_script( 'fineuploader-js' );
		wp_register_script( 'bootstrap-js', plugins_url( '/js/bootstrap.min.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'bootstrap-js' );
		wp_register_script( 'catalogo-js', plugins_url( 'js/catalogo.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'catalogo-js' );
	}
	
	public static function catAdminInterface() {
		
		if ( current_user_can('manage_options') ) {
			/*
			 * @TODO: dar opção de deletar arquivo único clicando em um X no alert dos arquivos antigos (criar alert). 
			 * basta criar função que remova o input hidden relacionado ao arquivo, assim ele não será pego pela função 
			 * que transforma o valor dos inputs em string para passar para o servidor
			 * @TODO estilizar todo o plugin
			 */ 
			?>
			
			<div id="container">
			
				<div id="trabs_placeholder"></div>
				
				<div id="chaves_placeholder"></div>
			
			</div>
			
			<script type="text/javascript">
			
			var pluginURL = '<?php echo plugin_dir_url(dirname(__FILE__) . '/catalogo.php'); ?>';
			
			function createUploader() {
		        $fub = jQuery('#fineuploader');
			    $messages = jQuery('#fineuploader_messages');
			 
			    var uploader = new qq.FileUploaderBasic({
			      button: $fub[0],
			      action: pluginURL + 'catalogo-fineuploader.php',
			      debug: true,
			      onSubmit: function(id, fileName) {
			        $messages.html('<div id="file-fineuploader" class="alert" style="margin: 20px 0 0"></div>');
			      },
			      onUpload: function(id, fileName) {
			        jQuery('#file-fineuploader').addClass('alert-info')
			                        .html('<img src="' + pluginURL + 'img/loading.gif" alt="Inicializando. Por favor, aguarde."> ' +
			                              'Inicializando ' +
			                              '“' + fileName + '”');
			      },
			      onProgress: function(id, fileName, loaded, total) {
			        if (loaded < total) {
			          progress = Math.round(loaded / total * 100) + '% of ' + Math.round(total / 1024) + ' kB';
			          jQuery('#file-fineuploader').removeClass('alert-info')
			                          .html('<img src="' + pluginURL + 'img/loading.gif" alt="Em progresso. Por favor, aguarde."> ' +
			                                'Enviando ' +
			                                '“' + fileName + '” ' +
			                                progress);
			        } else {
			          jQuery('#file-fineuploader').addClass('alert-info')
			                          .html('<img src="' + pluginURL + 'img/loading.gif" alt="Salvando. Por favor, aguarde."> ' +
			                                'Salvando ' +
			                                '“' + fileName + '”');
			        }
			      },
			      onComplete: function(id, fileName, responseJSON) {
			      	if (responseJSON.success) {
			        	jQuery('#file-fineuploader').removeClass('alert-info')
			                          .addClass('alert-success')
			                          .html('<button type="button" class="close remove-file" data-dismiss="alert">x</button><i class="icon-ok"></i> ' +
			                                'Arquivo ' +
			                                '“' + fileName + '” salvo com sucesso.');
			          	$oldFile = jQuery('input.uploaded-files').val();
			          	if(typeof $oldFile != 'undefined' && $oldFile != '') {
			          		var data = {option : 'del_files', fileName : $oldFile};
			          		jQuery.post(pluginURL+'catalogo-actions.php', data, function(data){
			          			if(data != 'error') jQuery('input.uploaded-files').val(fileName);
			          			else alert(data);
			          		});
			          	} else {
			          		jQuery('input.uploaded-files').val(fileName);
			          	}
		        	} else {
		          		jQuery('#file-fineuploader').removeClass('alert-info')
		                          .addClass('alert-error')
		                          .html('<button type="button" class="close" data-dismiss="alert">×</button><i class="icon-exclamation-sign"></i> ' +
		                                'Erro ao salvar ' +
		                                '“' + fileName + '”: ' +
		                                responseJSON.error);
		        	}
			      }
			    });
			    
			}

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
					
					loadPublicacoes({
								url			:	pluginURL,
								editable	:	true
					});
					loadChaves({
					            url         :   pluginURL
					});	
					
				});
			
			</script>
			
			<?php
		}
	}
	
	public static function catUserInterface() {
		
		?>
			
			<div id="container">
			
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