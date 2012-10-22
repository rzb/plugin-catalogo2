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
			
			<div id="loading">Carregando...</div>
			
			<div id="trabs_placeholder"></div>
			
			<div id="chaves_placeholder"></div>
			
			<script type="text/javascript">

				jQuery(document).ready(function(){

					// ajax loading indicator 
					jQuery("#loading").bind("ajaxStart", function(){
						jQuery(this).show();
					}).bind("ajaxComplete", function(){
						jQuery(this).hide();
					});
					
					jQuery('#trabs_placeholder').load('<?php echo plugins_url("catalogo-table-tpl.php",__FILE__); ?>', function(response, status, xhr) {
						if (status == "error") {
					    	var msg = 'Desculpe, mas ocorreu um erro. Reporte-o ao administrador: ';
					    	jQuery(this).html(msg + xhr.status + ' ' + xhr.statusText);
						}
				
						// selecionando publicação 
						var itemID = null;
						jQuery('tr.catalogo').click(function() {
							jQuery('tr.catalogo').removeClass('selected');
							jQuery(this).addClass('selected');
							itemID = jQuery(this).attr('id').split('-');
							itemID = itemID[1];
						});
					  	// identificando opção clicada 
					  	jQuery('.edit-table').click(function() {
							var option = jQuery(this).attr('id');
							var data = null;

							jQuery('<div id="catalogo_dialog"></div>').dialog({
								'dialogClass' : 'wp-dialog',
								'modal' : true,
								'autoOpen' : false,
								'closeOnEscape' : true,
								'height' : 460,
								'width' : 620,
								'buttons' : [
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
										    		location.reload();
										    	}
											});

											jQuery(this).dialog('close');
										}
									}
								],
								'open' : function() { 
									jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(1)').focus(); 
									jQuery(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(0)').blur(); 
								}
							});

							if (option!="del_trab") {

								if (option=="edit_trab" && !itemID) {
									alert('Selecione uma publicação!');
								} else {
									data = {itemID : itemID, option : option};				  	
									jQuery('#catalogo_dialog').load('<?php echo plugins_url("catalogo-form-tpl.php",__FILE__); ?>', data).dialog('open');

								}
								
							} else {

								if(!itemID) {
									alert('Selecione uma publicação!');
								} else {
									data = {id : itemID};
									jQuery('#catalogo_dialog').dialog("option", "buttons", {
											'Salvar' : function() {
												jQuery.post('<?php echo plugins_url("catalogo-actions.php",__FILE__); ?>', data, function(data) {
											    	if(data=="error") {
												    	alert("Ocorreu um erro ao tentar realizar a ação!");
											    	} else {
											    		location.reload();
											    	}
												});

												jQuery(this).dialog('close');
											}
										});
									jQuery('#catalogo_dialog').html('Tem certeza que deseja deletar a publicação? Essa ação não pode ser revertida.').dialog('open');
								}

							}
						});
					});
					
				});
				
			</script>
			
			<?php
		}
	}
	
	public static function catUserInterface() {
		echo "Hello user!";
	}
	
	public static function loadScripts() {
		wp_enqueue_script( 'jquery' );
	}
	
	public static function getAllTrabalhos() {
		global $wpdb;	
		// contando publicações...
		$totalTrabs = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . TRAJ_TRABALHOS_TABLE ) );
		// trazendo as publicações
		if ($totalTrabs > 0) {
			if (!$offset) $offset = 0;
			$trabalhos = $wpdb->get_results( "SELECT * 
											  FROM " . TRAJ_TRABALHOS_TABLE . "
											  ORDER BY autor ASC
											  LIMIT 20
											  OFFSET $offset", OBJECT_K );
			return $trabalhos;
		} else {
			return FALSE;
		}
	}
	
	public static function prepareModal($dados = NULL){
	
		if ($dados === NULL) {
			$modalHeader = "Nova publicação";
		} else {
			$modalHeader = "Editar publicação";
		}
		?>
		
		<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel"><?php echo $modalHeader; ?></h3>
			</div>
			<div class="modal-body">
				
				<form class="form-horizontal" id="trabForm">
					<div class="control-group">
						<label class="control-label" for="autor">Autor</label>
						<div class="controls">
							<input type="text" id="autor" value="<?php echo $dados->autor; ?>" />
					    </div>
					</div>
					<div class="control-group">
					    <label class="control-label" for="titulo">Título</label>
					    <div class="controls">
					    	<input type="password" id="titulo" value="<?php echo $dados->titulo; ?>" />
					    </div>
					</div>
				 	<div class="control-group">
				    	<label class="control-label" for="revista">Revista</label>
				    	<div class="controls">	
				    		<input type="text" id="revista" value="<?php echo $dados->revista; ?>" />
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="resumo">Resumo</label>
				    	<div class="controls">	
				    		<textarea rows="5" id="resumo"><?php echo $dados->resumo; ?></textarea>
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="volume">Volume</label>
				    	<div class="controls">	
				    		<input type="text" id="volume" value="<?php echo $dados->volume; ?>" />
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="revista">Número</label>
				    	<div class="controls">	
				    		<input type="text" id="numero" value="<?php echo $dados->numero; ?>" />
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="primeira_pag">Primeira Página</label>
				    	<div class="controls">	
				    		<input type="text" id="primeira_pag" value="<?php echo $dados->primeira_pag; ?>" />
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="ultima_pag">Última Página</label>
				    	<div class="controls">	
				    		<input type="text" id="ultima_pag" value="<?php echo $dados->ultima_pag; ?>" />
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="ano">Ano</label>
				    	<div class="controls">	
				    		<input type="text" id="ano" value="<?php echo $dados->ano; ?>" />
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="chaves">Palavras-chave</label>
				    	<div class="controls">	
				    		<input type="text" id="chaves" value="<?php echo $dados->palavras; ?>" />
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="fotocopias">Fotocópias</label>
				    	<div class="controls">	
				    		<input type="text" id="fotocopias" value="<?php echo $dados->fotocopias; ?>" />
				    	</div>
				 	</div>
				 	<div class="control-group">
				    	<label class="control-label" for="arquivo">Arquivo para download</label>
				    	<div class="controls">	
				    		<input type="file" id="arquivo" value="<?php echo $dados->arquivos; ?>"/>
				    	</div>
				 	</div>
				</form>
				
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
				<button class="btn btn-primary">Salvar alterações</button>
			</div>
		</div>
		
		<?php
	}
	
} // fim da classe trajCatalogo

// ativa e desativa o plugin
register_activation_hook( __FILE__, array( 'trajCatalogo', 'install' ) );
register_deactivation_hook( __FILE__, array( 'trajCatalogo', 'uninstall' ) );

add_action( 'wp_enqueue_scripts', array( 'trajCatalogo', 'loadScripts' ) );

$__trajCatalogo = new trajCatalogo();

?>