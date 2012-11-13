<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

if($_POST['filter'] == 'false') {
	$filters = NULL;
} else {
	if (!empty($_POST['filter']['chaves'])) $filters['chaves']	= @explode(',',$_POST['filter']['chaves']);
	if (!empty($_POST['filter']['autor']))	$filters['autor']	= $_POST['filter']['autor'];
}

$limit = 15; // alterar para $_POST['limit'] caso queira dar ao usuário a opção de entrar com limite de resultados por página

$total = trajCatalogoDBops::getTotalTrabalhos($filters); // alterar query entrando parâmetro filter caso haja
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

$trabalhos = trajCatalogoDBops::getAllTrabalhos($offset, $limit, $filters);   
$last = $offset + sizeof($trabalhos);

if($_POST['editable'] === "true") $editable = TRUE;
else $editable = FALSE;

?>

<table class="table table-condensed table-catalogo table-admin" id="table_publicacoes">
	<caption>Trabalhos publicados</caption>
	<thead>
		<tr>
			<th class="th-autor">Autor</th>
			<th class="th-titulo">Título</th>
			<th class="th-revista">Revista</th>
		<?php if($editable) : ?>
			<th class="th-data th-data_criacao">Incluído em</th>
			<th class="th-data th-data_modificacao">Modificado em</th>
			<td class="td-downloads_count"><attr title="Contagem de Downloads">dl's</attr></td>
		<?php else : ?>
			<th class="th-primeira_pag"><attr title="Primeira Página">1ªpag.</attr></th>
			<th class="th-ultima_pag"><attr title="Última Página">Ú.pag.</attr></th>
			<th class="th-ano">Ano</th>
		<?php endif; ?>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6">
				<div class="tfoot-info table-edit">
				<?php if($editable) : ?>
					<button class="edit-table btn btn-inverse" id="new_trab"><i class="icon-plus-sign icon-white"></i></button>
					<button class="edit-table btn btn-inverse" id="edit_trab"><i class="icon-edit icon-white"></i></button>
					<button class="edit-table btn btn-inverse" id="del_trab"><i class="icon-trash icon-white"></i></button>		|		
				<?php endif; ?>
					<button class="edit-table btn btn-inverse" id="filter_trab"><i class="icon-search icon-white"></i></button>
					<button class="edit-table btn btn-inverse" id="down_trab"><i class="icon-download icon-white"></i></button>
				</div>
				<div class="tfoot-info table-pagination" >

					<button class="btn paginate" id="first_pag" <?php if($offset === 0) echo 'disabled="disabled"'; ?>><i class="icon-fast-backward"></i></button>
					<button class="btn paginate" id="previous_pag" <?php if($offset === 0) echo 'disabled="disabled"'; ?>><i class="icon-step-backward"></i></button>
					<input type="text" class="paginate input-paginate" id="custom_pag" value="<?php echo $pagina; ?>" />
					<button class="btn paginate" id="next_pag" <?php if($last == $total) echo 'disabled="disabled"'; ?>><i class="icon-step-forward" ></i></button>
					<button class="btn paginate" id="last_pag" <?php if($last == $total) echo 'disabled="disabled"'; ?>><i class="icon-fast-forward"></i></button>

					<input type="hidden" id="current_pag" value="<?php echo $pagina; ?>" />
					<input type="hidden" id="total_pag" value="<?php echo $totalPags; ?>" />
				</div>
				<!--
				<div class="tfoot-info table-results" >Publicação <span><?php echo $offset+1; ?></span> à <span><?php echo $last; ?></span> de <span><?php echo $total; ?></span></div>
				-->
				<div class="tfoot-info table-results" >Página <span><?php echo $pagina; ?></span> de <span><?php echo $totalPags; ?></span>. Total de publicações: <span><?php echo $total; ?></span></div>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php if ($trabalhos) foreach ($trabalhos as $trab) : ?>
		<tr class="catalogo" id="item-<?php echo $trab->id; ?>">
			<td class="td-autor"><?php echo $trab->autor; ?>
				<?php if(!empty($trab->arquivos)) : ?>
					<input type="hidden" class="download-link" value="<?php echo plugin_dir_url(dirname(__FILE__) . '/catalogo.php') . 'uploads/' . $trab->arquivos; ?>" />
					<input type="hidden" class="file-name" value="<?php echo $trab->arquivos; ?>" />
				<?php endif; ?>
			</td>
			<td class="td-titulo"><?php echo $trab->titulo; ?></td>
			<td class="td-revista"><?php echo $trab->revista; ?></td>
		<?php if($editable) : ?>
			<td class="td-data td-data_criacao"><?php echo date_to_br($trab->data_criacao); ?></td>
			<td class="td-data td-data_modificacao"><?php echo date_to_br($trab->data_modificacao); ?></td>
			<td class="td-downloads_count"><?php echo $trab->downloads_count; ?></td>
		<?php else : ?>
			<td class="td-primeira_pag"><?php echo $trab->primeira_pag; ?></td>
			<td class="td-ultima_pag"><?php echo $trab->ultima_pag; ?></td>
			<td class="td-ano"><?php echo $trab->ano; ?></td>
		<?php endif; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<script type="text/javascript">

	jQuery('#down_trab').tooltip();
	// continuar função download 

</script>