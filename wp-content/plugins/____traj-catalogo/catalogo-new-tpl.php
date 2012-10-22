<?php 

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$chaves = trajCatalogoDBops::getAllChaves();

?>

<form class="catalogo-modal" id="new_trabalho">

	<label for="new_autor">Autor</label><input type="text" name="new_autor" id="new_autor" />
	
	<label for="new_titulo">Título</label><input type="text" name="new_titulo" id="new_titulo" />
	
	<label for="new_revista">Revista</label><input type="text" name="new_revista" id="new_revista" />
	
	<label for="new_resumo">Resumo</label><input type="text" name="new_resumo" id="new_resumo" />
	
	<label for="new_volume">Volume</label><input type="text" name="new_volume" id="new_volume" />
	
	<label for="new_numero">Número</label><input type="text" name="new_numero" id="new_numero" />
	
	<label for="new_primeira_pag">1ª Página</label><input type="text" name="new_primeira_pag" id="new_primeira_pag" />
	
	<label for="new_ultima_pag">Última Página</label><input type="text" name="new_ultima_pag" id="new_ultima_pag" />
	
	<label for="new_ano">Ano</label><input type="text" name="new_ano" id="new_ano" />
	
	<label for="new_chaves">Chaves</label>
	<select name="new_chaves" multiple="multiple">
	<?php if($chaves) foreach ($chaves as $c): ?>
		<option id="<?php echo $c->id; ?>" value="<?php echo $c->palavra; ?>"><?php echo $c->palavra; ?></option>
	<?php endforeach; ?>
	</select>
	
	<input type="text" name="new_fotocopias" id="new_fotocopias" />
	
	<input type="file" name="new_arquivos" id="new_arquivos" />
	
	<input type="button" value="Enviar" class="send" />
	
</form>


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