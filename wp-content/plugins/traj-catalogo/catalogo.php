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

class trajCatalogo {
	
	// configurações
	const TRAJ_PALAVRAS_TABLE = 'traj_palavras';
	const TRAJ_TRABALHOS_TABLE = 'traj_trabalhos';
	
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
		
		// js
		
	}
	
	public static function catAdminInterface() {
		
		if ( current_user_can('manage_options') ) {
			
			global $wpdb;
			// contando publicações...
			$totalTrabs = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . self::TRAJ_TRABALHOS_TABLE ) );
			// trazendo as publicações
			if ($totalTrabs > 0) {
				if (!$offset) $offset = 0;
				$trabalhos = $wpdb->get_results( "SELECT * 
												  FROM " . self::TRAJ_TRABALHOS_TABLE . "
												  ORDER BY autor ASC
												  LIMIT 20
												  OFFSET $offset", OBJECT_K );
			}
			
			?>
			
			<table class="table-padrao table-catalogo table-admin">
				<thead>
					<tr>
						<th class="th-autor">Autor</th>
						<th class="th-titulo">Título</th>
						<th class="th-revista">Revista</th>
						<th class="th-resumo">Resumo</th>
						<th class="th-volume">Volume</th>
						<th class="th-numero">Número</th>
						<th class="th-1st-pag">1°pág.</th>
						<th class="th-last-pag">Últ.pág.</th>
						<th class="th-ano">Ano</th>
						<th class="th-chaves">Palavras-chave</th>
						<th class="th-fotocopias">Fotocópias</th>
						<th class="th-arquivos">Arquivos</th>
						<th class="th-data th-data_criacao">Incluído em</th>
						<th class="th-data th-data_modificacao">Modificado em</th>
						<th class="th-downloads">Downloads</th>
						<th class="th-opcoes">Opções</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($trabalhos) foreach ($trabalhos as $trab) { ?>
					<tr class="catalogo item-<?php echo $trab->id; ?>">
						<td class="td-autor"><?php echo $trab->autor; ?></td>
						<td class="td-titulo"><?php echo $trab->titulo; ?></td>
						<td class="td-revista"><?php echo $trab->revista; ?></td>
						<td class="td-resumo"><?php echo $trab->resumo; ?></td>
						<td class="td-volume"><?php echo $trab->volume; ?></td>
						<td class="td-numero"><?php echo $trab->numero; ?></td>
						<td class="td-1st-pag"><?php echo $trab->primeira_pag; ?></td>
						<td class="td-last-pag"><?php echo $trab->ultima_pag; ?></td>
						<td class="td-ano"><?php echo $trab->ano; ?></td>
						<td class="td-chaves"><?php echo $trab->palavras_ids; ?></td>
						<td class="td-fotocopias"><?php echo $trab->fotocopias; ?></td>
						<td class="td-arquivos"><?php echo $trab->arquivos; ?></td>
						<td class="td-data td-data_criacao"><?php echo $trab->data_criacao; ?></td>
						<td class="td-data td-data_modificacao"><?php echo $trab->data_modificacao; ?></td>
						<td class="td-downloads"><?php echo $trab->downloads; ?></td>
						<td class="td-opcoes">
							<button class="btn editar">Editar</button>
							<button class="btn deletar">Deletar</button>
						</td>
					</tr>
					<?php } ?>
					<tr class="catalogo hidden">
						<td class="td-autor"><input type="text" name="edit_autor" id="edit_autor" /></td>
						<td class="td-titulo"><input type="text" name="edit_titulo" id="edit_titulo" /></td>
						<td class="td-revista"><input type="text" name="edit_revista" id="edit_revista" /></td>
						<td class="td-resumo"><input type="text" name="edit_resumo" id="edit_resumo" /></td>
						<td class="td-volume"><input type="text" name="edit_volume" id="edit_volume" /></td>
						<td class="td-numero"><input type="text" name="edit_numero" id="edit_numero" /></td>
						<td class="td-1st-pag"><input type="text" name="edit_primeira_pag" id="edit_primeira_pag" /></td>
						<td class="td-last-pag"><input type="text" name="edit_ultima_pag" id="edit_ultima_pag" /></td>
						<td class="td-ano"><input type="text" name="edit_ano" id="edit_ano" /></td>
						<td class="td-chaves"><button name="set_edit_chaves" id="set_edit_chaves">chaves</button><input type="hidden" id="edit_chaves" value="" /></td>
						<td class="td-fotocopias"><input type="text" name="edit_fotocopias" id="edit_fotocopias" /></td>
						<td class="td-arquivos"><input type="file" name="edit_arquivos" id="edit_arquivos" /></td>
						<td class="td-data tf-data_criacao">---</td>
						<td class="td-data tf-data_modificacao">---</td>
						<td class="td-downloads">---</td>
						<td class="td-opcoes">
							<button class="btn publicar">Publicar</button>
							<button class="btn cancelar">Cancelar</button>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr class="catalogo new-item">	
						<td class="tf-autor"><input type="text" name="new_autor" id="new_autor" /></td>
						<td class="tf-titulo"><input type="text" name="new_titulo" id="new_titulo" /></td>
						<td class="tf-revista"><input type="text" name="new_revista" id="new_revista" /></td>
						<td class="tf-resumo"><input type="text" name="new_resumo" id="new_resumo" /></td>
						<td class="tf-volume"><input type="text" name="new_volume" id="new_volume" /></td>
						<td class="tf-numero"><input type="text" name="new_numero" id="new_numero" /></td>
						<td class="tf-1st-pag"><input type="text" name="new_primeira_pag" id="new_primeira_pag" /></td>
						<td class="tf-last-pag"><input type="text" name="new_ultima_pag" id="new_ultima_pag" /></td>
						<td class="tf-ano"><input type="text" name="new_ano" id="new_ano" /></td>
						<td class="tf-chaves"><button name="set_new_chaves" id="set_new_chaves">chaves</button><input type="hidden" id="new_chaves" value="" /></td>
						<td class="tf-fotocopias"><input type="text" name="new_fotocopias" id="new_fotocopias" /></td>
						<td class="tf-arquivos"><input type="file" name="new_arquivos" id="new_arquivos" /></td>
						<td class="tf-data tf-data_criacao">---</td>
						<td class="tf-data tf-data_modificacao">---</td>
						<td class="tf-downloads">---</td>
						<td class="tf-opcoes">
							<button class="btn confirmar">Confirmar</button>
						</td>
					</tr>
				</tfoot>
			</table>
			
			<script type="text/javascript">

				jQuery(document).ready(function(){

					var ajaxFileUrl = "<?php echo plugins_url("ajax.php",__FILE__); ?>";

					jQuery(".publicar").click(function(){
						var autor = jQuery("#new_autor").val();
						var titulo = jQuery("#new_titulo").val();
						var revista = jQuery("#new_revista").val();
						var resumo = jQuery("#new_resumo").val();
						var volume = jQuery("#new_volume").val();
						var numero = jQuery("#new_numero").val();
						var primeira_pag = jQuery("#new_primeira_pag").val();
						var ultima_pag = jQuery("#new_ultima_pag").val();
						var ano = jQuery("#new_ano").val();
						var chaves = jQuery("#new_chaves").val();
						var fotocopias = jQuery("#new_fotocopias").val();
						var arquivos = jQuery().val("#new_arquivos");
						jQuery.ajax({
							url: ajaxFileUrl+"?option=new&autor="+autor+
														"&titulo="+titulo+
														"&revista="+revista+
														"&resumo="+resumo+
														"&volume="+volume+
														"&numero="+numero+
														"&primeira_pag="+primeira_pag+
														"&ultima_pag="+ultima_pag+
														"&ano="+ano+
														"&chaves="+chaves+
														"&fotocopias="+fotocopias+
														"&arquivos="+arquivos,
							dataType: "html"
						}).done(function(data){
							if (data=="ok"){
								alert("Nova publicação salva com sucesso!");
							} else {
								alert("Ocorreu um erro ao tentar salvar a publicação.");
							}
						});
						
					});

					jQuery(".editar").click(function(){
						var id = getItemId(this);
						jQuery(".item-"+id).html("ha ha ha");
						jQuery.ajax({
							url: ajaxFileUrl+"?option=edit&id="+id,
							dataType: "html"
						}).done(function(data){
							if (data=="ok"){
								alert("Publicação editada com sucesso!");
							} else {
								alert("Ocorreu um erro ao tentar editar a publicação.");
							}
						});
					});

					jQuery(".deletar").click(function(){
						var id = getItemId(this);
						jQuery.ajax({
							url: ajaxFileUrl+"?option=del&id="+id,
							dataType: "html"
						}).done(function(data){
							if (data=="ok"){
								alert("Publicação deletada com sucesso!");
							} else {
								alert("Ocorreu um erro ao tentar deletar a publicação.");
							}
						});
					});

					function getItemId(element){
						var temp = jQuery(element).closest("tr").attr("class").split("-");
						var id = temp[1];
						return id;
					}
					
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
	
} // fim da classe trajCatalogo

// ativa e desativa o plugin
register_activation_hook( __FILE__, array( 'trajCatalogo', 'install' ) );
register_deactivation_hook( __FILE__, array( 'trajCatalogo', 'uninstall' ) );

add_action( 'wp_enqueue_scripts', array( 'trajCatalogo', 'loadScripts' ) );

$__trajCatalogo = new trajCatalogo();

?>