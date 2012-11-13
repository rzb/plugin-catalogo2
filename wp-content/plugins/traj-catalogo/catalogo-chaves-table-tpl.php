<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$limit = 15; // alterar para $_POST['limit'] caso queira dar ao usuário a opção de entrar com limite de resultados por página

$total = trajCatalogoDBops::getTotalChaves();
$totalPags = ceil($total / $limit);

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

$chaves = trajCatalogoDBops::getAllChaves($offset, $limit); 
$last = $offset + sizeof($chaves);

?>

<table class="table-padrao table-condensed table-catalogo table-admin" id="table_chaves" >
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
                <div class="tfoot-info table-edit">
                    <button class="edit-table btn btn-inverse" id="new_chave"><i class="icon-plus-sign icon-white"></i></button>
                    <button class="edit-table btn btn-inverse" id="edit_chave"><i class="icon-edit icon-white"></i></button>
                    <button class="edit-table btn btn-inverse" id="del_chave"><i class="icon-trash icon-white"></i></button>
                </div>
                <div class="tfoot-info table-pagination" >
                    <button class="btn paginate_chave" id="first_pag_chave" <?php if($offset === 0) echo 'disabled="disabled"'; ?>><i class="icon-fast-backward"></i></button>
                    <button class="btn paginate_chave" id="previous_pag_chave" <?php if($offset === 0) echo 'disabled="disabled"'; ?>><i class="icon-step-backward"></i></button>
                    <input type="text" class="paginate_chave input-paginate" id="custom_pag_chave" value="<?php echo $pagina; ?>" />
                    <button class="btn paginate_chave" id="next_pag_chave" <?php if($last == $total) echo 'disabled="disabled"'; ?>><i class="icon-step-forward" ></i></button>
                    <button class="btn paginate_chave" id="last_pag_chave" <?php if($last == $total) echo 'disabled="disabled"'; ?>><i class="icon-fast-forward"></i></button>

                    <input type="hidden" id="current_pag_chave" value="<?php echo $pagina; ?>" />
                    <input type="hidden" id="total_pag_chave" value="<?php echo $totalPags; ?>" />
                </div>
                <!--
                <div class="tfoot-info table-results" >Publicação <span><?php echo $offset+1; ?></span> à <span><?php echo $last; ?></span> de <span><?php echo $total; ?></span></div>
                -->
                <div class="tfoot-info table-results" >Página <span><?php echo $pagina; ?></span> de <span><?php echo $totalPags; ?></span>. Total de chaves: <span><?php echo $total; ?></span></div>
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