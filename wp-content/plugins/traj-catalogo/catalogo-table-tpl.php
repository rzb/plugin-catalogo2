<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$trabalhos = trajCatalogoDBops::getAllTrabalhos();    

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