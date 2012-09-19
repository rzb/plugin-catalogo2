<?php 
/*
 Plugin Name: Trajettoria Catalogo
Plugin URI: http://www.trajettoria.com
Description: Plugin para catalogar publicações e filtrar buscas por palavras-chave
Author: Trajettoria
Version: 1.0
Author URI: http://www.trajettoria.com
*/

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
		wp_register_style( 'catalogo-style-css', plugins_url( '/css/style-boletos.css', __FILE__ ), FALSE );
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
						<th class="th-data inclusao">Incluído em</th>
						<th class="th-data modificacao">Modificado em</th>
						<th class="th-downloads">Downloads</th>
						<th class="th-opcoes">Opções</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($trabalhos) foreach ($trabalhos as $trab) { ?>
					<tr>
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
						<td class="td-data td-inclusao"><?php echo $trab->data_criacao; ?></td>
						<td class="td-data td-modificacao"><?php echo $trab->data_modificacao; ?></td>
						<td class="td-downloads"><?php echo $trab->downloads; ?></td>
						<td class="td-opcoes">
							<button class="btn editar">Editar</button>
							<button class="btn deletar">Deletar</button>
						</td>
					</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="tf-autor"></td>
						<td class="tf-titulo"></td>
						<td class="tf-revista"></td>
						<td class="tf-resumo"></td>
						<td class="tf-volume"></td>
						<td class="tf-numero"></td>
						<td class="tf-1st-pag"></td>
						<td class="tf-last-pag"></td>
						<td class="tf-ano"></td>
						<td class="tf-chaves"></td>
						<td class="tf-fotocopias"></td>
						<td class="tf-arquivos"></td>
						<td class="tf-data tf-inclusao"></td>
						<td class="tf-data tf-modificacao"></td>
						<td class="tf-downloads"></td>
						<td class="tf-opcoes">
							<button class="btn adicionar">Adicionar</button>
						</td>
					</tr>
				</tfoot>
			</table>
			
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