<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$total = trajCatalogoDBops::getTotalTrabalhos();
$totalPags = ceil($total / 15);

if(isset( $_POST['pagina'] )) {
	if( $_POST['pagina'] > $totalPags ) {
		$pagina = $totalPags;
	} else {
		$pagina = $_POST['pagina'];
	}
} else {
	$pagina = 1;
}

$offset = ($pagina-1) * 15;
$trabalhos = trajCatalogoDBops::getAllTrabalhos($offset);   
$last = $offset + sizeof($trabalhos);

?>

<table class="table-padrao table-catalogo table-admin" id="table_publicacoes">
	<caption>Trabalhos publicados</caption>
	<thead>
		<tr>
			<th class="th-autor">Autor</th>
			<th class="th-titulo">Título</th>
			<th class="th-revista">Revista</th>
			<th class="th-data th-data_criacao">Incluído em</th>
			<th class="th-data th-data_modificacao">Modificado em</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
				<input type="button" value="Novo" class="edit-table" id="new_trab" />
				<input type="button" value="Alterar" class="edit-table" id="edit_trab" />
				<input type="button" value="Excluir" class="edit-table" id="del_trab" />
				<div class="tfoot-info table-pagination" >
					<input type="button" class="pagination" id="first_pag" value="Primeira" <?php if($offset === 0) echo 'disabled="disabled"'; ?> />
					<input type="button" class="pagination" id="previous_pag" value="Anterior" <?php if($offset === 0) echo 'disabled="disabled"'; ?> />
					<input type="text" class="pagination" id="custom_pag" value="<?php echo $pagina; ?>" />
					<input type="button" class="pagination" id="next_pag" value="Próxima" <?php if($last == $total) echo 'disabled="disabled"'; ?> />
					<input type="button" class="pagination" id="last_pag" value="Última" <?php if($last == $total) echo 'disabled="disabled"'; ?> />
					<input type="hidden" id="current_pag" value="<?php echo $pagina; ?>" />
					<input type="hidden" id="total_pag" value="<?php echo $totalPags; ?>" />
				</div>
				<div class="tfoot-info table-results" >Publicação <span><?php echo $offset+1; ?></span> à <span><?php echo $last; ?></span> de <span><?php echo $total; ?></span></div>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php if ($trabalhos) foreach ($trabalhos as $trab) { ?>
		<tr class="catalogo" id="item-<?php echo $trab->id; ?>">
			<td class="td-autor"><?php echo $trab->autor; ?></td>
			<td class="td-titulo"><?php echo $trab->titulo; ?></td>
			<td class="td-revista"><?php echo $trab->revista; ?></td>
			<td class="td-data td-data_criacao"><?php echo $trab->data_criacao; ?></td>
			<td class="td-data td-data_modificacao"><?php echo $trab->data_modificacao; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>