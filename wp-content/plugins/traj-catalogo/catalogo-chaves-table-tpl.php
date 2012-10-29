<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$chaves = trajCatalogoDBops::getAllChaves();

?>

<table class="table-padrao table-catalogo table-admin" id="table_chaves" >
	<caption>Palavras-chave</caption>
	<thead>
		<tr>
			<th class="th-id">ID</th>
			<th class="th-palavra">Palavra</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2">
				<button class="edit-table btn btn-inverse" id="new_chave"><i class="icon-plus-sign icon-white"></i></button>
				<button class="edit-table btn btn-inverse" id="edit_chave"><i class="icon-edit icon-white"></i></button>
				<button class="edit-table btn btn-inverse" id="del_chave"><i class="icon-trash icon-white"></i></button>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php if ($chaves) foreach ($chaves as $c) : ?>
		<tr class="catalogo-chave" id="chave-<?php echo $c->id; ?>">
			<td class="td-id"><?php echo $c->id; ?></td>
			<td class="td-palavra"><?php echo $c->palavra; ?></td>
		</tr>
		<?php endforeach; ?>
		<tr id="chaveForm" style="display:none;">
			<td class="td-id"></td>
			<td>
				<input type="text" id="new_palavra" />
				<input type="button" id="send_chave" value="Salvar" />
				<input type="button" id="cancel_chave" value="Cancelar" />
			</td>
		</tr>
	</tbody>
</table>