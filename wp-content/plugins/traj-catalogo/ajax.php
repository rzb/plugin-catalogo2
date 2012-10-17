<?php 

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');
	
function prepareData(){
	$stuff["dados"] = array(
			"autor" => $_GET['autor'],
			"titulo" => $_GET['titulo'],
			"revista" => $_GET['revista'],
			"resumo" => $_GET['resumo'],
			"volume" => $_GET['volume'],
			"numero" => $_GET['numero'],
			"primeira_pag" => $_GET['primeira_pag'],
			"ultima_pag" => $_GET['ultima_pag'],
			"ano" => $_GET['ano'],
			"palavra_ids" => $_GET['chaves'],
			"fotocopias" => $_GET['fotocopias'],
			"arquivos" => $_GET['arquivos'],
			"downloads_count" => 0
	);
	$stuff["where"] = array( "id" => $_GET['id'] );
	
	return $stuff;
}
	
/*
function generateTable() {
	$trabalhos = trajCatalogoDBops::getAllTrabalhos();
	
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
					<input type="button" class="btn editar" value="Editar" />
					<input type="button" class="btn deletar" value="Deletar" />
				</td>
			</tr>
	<?php } ?>
			<tr class="catalogo hidden" id="edit-trab">
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
				<td class="td-data td-data_criacao"><input type="hidden" value="" /></td>
				<td class="td-data td-data_modificacao"><input type="hidden" value="" /></td>
				<td class="td-downloads"><input type="hidden" value="" /></td>
				<td class="td-opcoes">
					<input type="button" class="btn confirmar" value="Confirmar" />
					<input type="button" class="btn cancelar" value="Cancelar" />
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
				<td class="tf-data tf-data_criacao"><input type="hidden" /></td>
				<td class="tf-data tf-data_modificacao"><input type="hidden" /></td>
				<td class="tf-downloads"><input type="hidden" /></td>
				<td class="tf-opcoes">
					<input type="button" class="btn publicar" value="Publicar" />
				</td>
			</tr>
		</tfoot>
	</table>	
	<?php 
}

function generateModal() {
	
}
*/
	
if ( current_user_can('manage_options') ) {
	
	$option = $_GET['option'];
	$dados = prepareData();
	
	switch ($option) {
		case "new":
			$res = trajCatalogoDBops::setTrabalho( $dados );
			if(!res)
				echo "error";
			break;
		case "edit":
			$res = trajCatalogoDBops::editTrabalho( $dados );
			if(!res)
				echo "error";			
			break;
			
		case "del":
			$res = trajCatalogoDBops::delTrabalho( $dados );
			if(!res)
				echo "error";
			break;
			
		default:
			break;
	}
	
}
		


?>