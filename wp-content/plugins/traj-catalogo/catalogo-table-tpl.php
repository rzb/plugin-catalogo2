<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$limit = 15; // alterar para $_POST['limit'] caso queira dar opção a usuário de entrar com limite de resultados por página

$total = trajCatalogoDBops::getTotalTrabalhos(); // alterar query entrando parâmetro filter caso haja
$totalPags = ceil($total / $limit);


if($_POST['filter'] == 'false') $filters = NULL;
else $filters = explode(',',$_POST['filter']);


if(isset( $_POST['page'] )) {
	if( $_POST['page'] > $totalPags ) {
		$pagina = $totalPags;
	} else {
		$pagina = $_POST['page'];
	}
} else {
	$pagina = 1;
}

$offset = ($pagina-1) * $limit;

$trabalhos = trajCatalogoDBops::getAllTrabalhos($offset, $limit, $filters);   
$last = $offset + sizeof($trabalhos);

if($_POST['editable'] === "true") $editable = TRUE;
else $editable = FALSE;


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
			<?php if($editable) : ?>
				<input type="button" value="Novo" class="edit-table" id="new_trab" />
				<input type="button" value="Alterar" class="edit-table" id="edit_trab" />
				<input type="button" value="Excluir" class="edit-table" id="del_trab" />
			<?php endif; ?>
				<input type="button" value="Filtrar" class="edit-table" id="filter_trab" />
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
		<?php if ($trabalhos) foreach ($trabalhos as $trab) : ?>
		<tr class="catalogo" id="item-<?php echo $trab->id; ?>">
			<td class="td-autor"><?php echo $trab->autor; ?></td>
			<td class="td-titulo"><?php echo $trab->titulo; ?></td>
			<td class="td-revista"><?php echo $trab->revista; ?></td>
			<td class="td-data td-data_criacao"><?php echo $trab->data_criacao; ?></td>
			<td class="td-data td-data_modificacao"><?php echo $trab->data_modificacao; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>