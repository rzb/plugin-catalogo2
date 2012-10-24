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

					// ajax loading indicator 
					jQuery("#loading").bind("ajaxStart", function(){
						jQuery(this).show().siblings().hide();
					}).bind("ajaxComplete", function(){
						jQuery(this).hide().siblings().show();
					});
					
					loadPublicacoes();
					loadChaves();
					
					function loadPublicacoes(pagina) {
						if (!pagina) pagina = 1;
						// tabela de publicações 
						jQuery('#trabs_placeholder').load('<?php echo plugins_url("catalogo-table-tpl.php",__FILE__); ?>', {pagina : pagina}, function(response, status, xhr) {
							if (status == "error") {
						    	var msg = 'Desculpe, mas ocorreu um erro. Reporte-o ao administrador: ';
						    	jQuery(this).html(msg + xhr.status + ' ' + xhr.statusText);
							} else {
					
								// selecionando publicação 
								var itemID = null;
								jQuery('tr.catalogo').click(function() {
									var item = jQuery(this);
									if (item.hasClass('selected')) {
										jQuery('tr.catalogo').removeClass('selected');
										itemID = null;
									} else {
										jQuery('tr.catalogo').removeClass('selected');
										item.addClass('selected');
										itemID = item.attr('id').split('-');
										itemID = itemID[1];
									}
								});

								// trocando de página pelos botões 
								jQuery('.pagination').unbind('click').bind('click', function() {
									var option = jQuery(this).attr('id');
									switch (option) {
										case 'first_pag':
											pagina = 1;
											break;
										case 'previous_pag':
											pagina -= 1;
											if(pagina < 1) pagina = 1;
											break;	
										case 'next_pag':
											pagina += 1;
											break;
										case 'last_pag':
											pagina = jQuery('#total_pag').val();
											break;
										default:
											return;
											break;
									}

									loadPublicacoes(pagina);
								});

								// trocando de página pelo valor digitado 
								jQuery('#custom_pag').keypress(function(e) {
								    if(e.which == 13) {
								        var input = jQuery(this).val();
								        var gtOneREG = /^[1-9][0-9]*$/;
								        if(gtOneREG.test(input)) pagina = input;
								        loadPublicacoes(pagina);
								    }
								});
								
							  	// identificando opção clicada 
							  	jQuery('#table_publicacoes input.edit-table').unbind('click').bind('click', function() {
									var option = jQuery(this).attr('id');
									var data = null;
									var dlg = jQuery('#catalogo_dialog');
									
									switch (option) {
										case 'del_trab':
											if(!itemID) {
												alert('Selecione uma publicação!');
											} else {
												data = {id : itemID, option : option};
												dlg.dialog("option", "title", "Confirmar ação");
												dlg.dialog("option", "buttons", [
													{
														'text'	: 'Cancelar',
														'class' : 'button',
														'click' : function() {
															jQuery(this).dialog('close');
														}
													},
													{
														'text'	: 'Ok',
														'class' : 'button-primary',
														'click' : function() {
															jQuery.post('<?php echo plugins_url("catalogo-actions.php",__FILE__); ?>', data, function(data) {
														    	if(data=="error") {
															    	alert("Ocorreu um erro ao tentar realizar a ação!");
														    	} else {
														    		option = undefined;
														    		loadPublicacoes(pagina);
														    	}
															});
			
															jQuery(this).dialog('close');
														}
													}
												]);
												
												dlg.html('Tem certeza que deseja deletar a publicação? Essa ação não pode ser revertida.').dialog('open');
											}
											break;
											
										case 'edit_trab':
											if(!itemID) {
												alert('Selecione uma publicação!');
											} else {
												data = {itemID : itemID, option : option};
												dlg.dialog("option", "title", "Alterar publicação");
												dlg.dialog("option", "buttons", [
													{
														'text' : 'Cancelar',
														'class' : 'button',
														'click' : function() {
															jQuery(this).dialog('close');
														}
													},
													{
														'text' : 'Salvar',
														'class' : 'button-primary',
														'click' : function() {
															// publicar conteúdo e recarregar tabela de catálogos 
															jQuery.post('<?php echo plugins_url("catalogo-actions.php",__FILE__); ?>', jQuery('#trabForm').serialize(), function(data) {
														    	if(data=="error") {
															    	alert("Ocorreu um erro ao tentar realizar a ação!");
														    	} else {
														    		loadPublicacoes(pagina);
														    	}
															});
				
															jQuery(this).dialog('close');
														}
													}
												]);
												dlg.load('<?php echo plugins_url("catalogo-form-tpl.php",__FILE__); ?>', data).dialog('open');
											}
											break;
											
										case 'new_trab':
											data = {option : option};
											dlg.dialog("option", "title", "Nova publicação");
											dlg.dialog("option", "buttons", [
													{
														'text' : 'Cancelar',
														'class' : 'button',
														'click' : function() {
															jQuery(this).dialog('close');
														}
													},
													{
														'text' : 'Salvar',
														'class' : 'button-primary',
														'click' : function() {
															// publicar conteúdo e recarregar tabela de catálogos 
															jQuery.post('<?php echo plugins_url("catalogo-actions.php",__FILE__); ?>', jQuery('#trabForm').serialize(), function(data) {
														    	if(data=="error") {
															    	alert("Ocorreu um erro ao tentar realizar a ação!");
														    	} else {
														    		loadPublicacoes(pagina);
														    	}
															});
				
															jQuery(this).dialog('close');
														}
													}
												]);
											dlg.load('<?php echo plugins_url("catalogo-form-tpl.php",__FILE__); ?>', data).dialog('open');
											break;
									}
								});
							
							}
						});
					
					}

					// tabela de palavras-chave 
					function loadChaves() {
						jQuery("#chaves_placeholder").load('<?php echo plugins_url("catalogo-chaves-table-tpl.php",__FILE__); ?>', function(response, status, xhr) {
							if (status == "error") {
						    	var msg = 'Desculpe, mas ocorreu um erro. Reporte-o ao administrador: ';
						    	jQuery(this).html(msg + xhr.status + ' ' + xhr.statusText);
							} else {
								// selecionando palavra-chave 
								var chaveID = null;
								jQuery('tr.catalogo-chave').unbind('click').bind('click', function() {
									jQuery('tr.catalogo-chave').removeClass('selected');
									jQuery(this).addClass('selected');
									chaveID = jQuery(this).attr('id').split('-');
									chaveID = chaveID[1];
								});
								// identificando opção clicada 
								jQuery('#table_chaves input.edit-table').unbind('click').bind('click', function(event) {
									var option	= jQuery(this).attr('id');
									var data	= null;
									var linha	= jQuery('tr.selected');
									var input	= jQuery('tr#chaveForm');
									
									event.preventDefault;
		
									switch (option) {
										case 'new_chave':
											jQuery('tr').show().filter(input).appendTo('#table_chaves').children('td.td-id').html('');
											jQuery('#new_palavra').val('');
											break;
										case 'edit_chave':
											if(!chaveID){
												alert('Selecione uma publicação!');
											} else {
												// mostra todas as linhas menos a linha a ser editada 
												jQuery('tr').show().filter(linha).hide();
												// coloca a linha input antes da linha a ser editada 
												linha.before(input).hide();
												// mostra o ID da chave na céluda ID da linha de input 
												input.children('td.td-id').html(chaveID);
												// pré-popula o input da palavra a editar 
												jQuery('#new_palavra').val(linha.children('.td-palavra').text());
											}
											break;
										case 'del_chave':
											if(!chaveID){
												alert('Selecione uma publicação!');
											} else {
												data = {chaveID : chaveID, option : option};
												jQuery.post('<?php echo plugins_url("catalogo-actions.php",__FILE__); ?>', data, function(data) {
											    	if(data=="error") {
												    	alert("Ocorreu um erro ao tentar realizar a ação!");
											    	} else {
											    		loadChaves();
											    	}
												});
											}
											break;
									}
		
									jQuery('#send_chave').unbind('click').bind('click', function() {
										// @todo validar newPalavra 
										var newPalavra = jQuery('#new_palavra').val();
										data = {chaveID : chaveID, option : option, palavra : newPalavra};
										jQuery.post('<?php echo plugins_url("catalogo-actions.php",__FILE__); ?>', data, function(data) {
									    	if(data=="error") {
										    	alert("Ocorreu um erro ao tentar realizar a ação!");
									    	} else {
									    		loadChaves();
									    	}
										});
									});
									
									jQuery('#cancel_chave').click(function() {
										// mostra todas as linhas menos a linha a ser editada 
										jQuery('tr').show().filter(input).hide();
										chaveID = null;
									});

								});
	
							}
							
						});

					}
					
				});
				
			</script>
			
			<?php
		}
	}
	
	public static function catUserInterface() {
		echo "Hello user!";
	}
	
} // fim da classe trajCatalogo

// ativa e desativa o plugin
register_activation_hook( __FILE__, array( 'trajCatalogo', 'install' ) );
register_deactivation_hook( __FILE__, array( 'trajCatalogo', 'uninstall' ) );

$__trajCatalogo = new trajCatalogo();

?>