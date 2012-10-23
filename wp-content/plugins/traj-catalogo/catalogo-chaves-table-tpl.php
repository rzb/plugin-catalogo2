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
				<input type="button" value="Novo" class="edit-table" id="new_chave" />
				<input type="button" value="Alterar" class="edit-table" id="edit_chave" />
				<input type="button" value="Excluir" class="edit-table" id="del_chave" />
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
				<input type="button" id="send_chave" value="Criar" />
				<input type="button" id="cancel_chave" value="Cancelar" />
			</td>
		</tr>
	</tbody>
</table>