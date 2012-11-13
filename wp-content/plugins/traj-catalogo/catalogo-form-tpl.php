<?php 

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$option = $_POST['option'];
if ($option == "edit_trab") {
	$trab			= trajCatalogoDBops::getTrabalho($_POST['itemID']);
	$autor			= $trab->autor;
	$chosenChaves	= trajCatalogoDBops::getChaveIDsByTrab($trab->id);
	$file			= $trab->arquivos;
} elseif ($option == "filter_trab" && is_array($_POST['filters'])) {
	if (isset($_POST['filters']['autor']))	$autor			= $_POST['filters']['autor'];
	if (isset($_POST['filters']['chaves']))	$chosenChaves	= explode(',', $_POST['filters']['chaves']);
}

$chaves 			= trajCatalogoDBops::getAllChaves();

?>

<form class="form-horizontal" id="trabForm">
	
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	
	<div class="control-group">
		<label class="control-label" for="autor">Autor</label>
		<div class="controls">
			<input type="text" name="autor" id="autor" value="<?php echo stripslashes(sanitize_text_field($autor)); ?>" />
	    </div>
	</div>
 <?php if ($option != "filter_trab") : ?>	
	<input type="hidden" name="id" value="<?php echo $trab->id; ?>" />

	<div class="control-group">
	    <label class="control-label" for="titulo">Título</label>
	    <div class="controls">
	    	<input type="text" name="titulo" id="titulo" value="<?php echo $trab->titulo; ?>" />
	    </div>
	</div>
 	<div class="control-group">
    	<label class="control-label" for="revista">Revista</label>
    	<div class="controls">	
    		<input type="text" name="revista" id="revista" value="<?php echo $trab->revista; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="resumo">Resumo</label>
    	<div class="controls">	
    		<textarea rows="5" id="resumo"><?php echo $trab->resumo; ?></textarea>
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="volume">Volume</label>
    	<div class="controls">	
    		<input type="text" name="volume" id="volume" value="<?php echo $trab->volume; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="revista">Número</label>
    	<div class="controls">	
    		<input type="text" name="numero" id="numero" value="<?php echo $trab->numero; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="primeira_pag">Primeira Página</label>
    	<div class="controls">	
    		<input type="text" name="primeira_pag" id="primeira_pag" value="<?php echo $trab->primeira_pag; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="ultima_pag">Última Página</label>
    	<div class="controls">	
    		<input type="text" name="ultima_pag" id="ultima_pag" value="<?php echo $trab->ultima_pag; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="ano">Ano</label>
    	<div class="controls">	
    		<input type="text" name="ano" id="ano" value="<?php echo $trab->ano; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="fotocopias">Fotocópias</label>
    	<div class="controls">	
    		<input type="text" name="fotocopias" id="fotocopias" value="<?php echo $trab->fotocopias; ?>" />
    	</div>
 	</div>
 	
 	<input type="hidden" name="arquivos" id="arquivos" />
 	
 	<div class="control-group">
    	<label class="control-label" for="fineuploader">Arquivo para download</label>
    	<div class="controls">
    		<div id="fineuploader" class="btn btn-success">
				<i class="icon-upload icon-white"></i> Selecione ou arraste um arquivo
			</div>
			<input type="hidden" class="uploaded-files" <?php if(!empty($file)) echo 'value="' .$file. '"'; ?> />
			<div id="fineuploader_messages">
			<?php if(!empty($file)) : ?>
				<div id="file-fineuploader" class="alert alert-success" style="margin: 20px 0 0">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<i class="icon-ok"></i> “<?php echo $file; ?>”.
				</div>
			<?php endif; ?>
    		</div>
    	</div>
 	</div>
 <?php endif; ?>
 	<div class="control-group">
    	<label class="control-label" for="chaves">Palavras-chave disponíveis</label>
    	<div class="controls">
    		<select name="chaves[]" id="chaves" multiple="multiple">
			<?php if($chaves) foreach ($chaves as $c): ?>
				<?php if(is_array($chosenChaves) && in_array($c->id, $chosenChaves)) continue; ?>
				<option value="<?php echo $c->id; ?>" ><?php echo $c->palavra; ?></option>
			<?php endforeach; ?>
			</select>
		</div>
 	</div>
 	<div class="control-group">
    	<div class="controls">
			<button class="btn" id="add_chave">adicionar</button>
			<button class="btn" id="rem_chave">remover</button>
		</div>
 	</div>
	<div class="control-group">
    	<label class="control-label" for="selected_chaves">Palavras-chave selecionadas</label>
    	<div class="controls">		
			<select name="selected_chaves[]" id="selected_chaves" multiple="multiple">
			<?php if($chaves) foreach ($chaves as $c): ?>
				<?php if(is_array($chosenChaves) && in_array($c->id, $chosenChaves)) : ?>
					<option value="<?php echo $c->id; ?>" ><?php echo $c->palavra; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
			</select>
    	</div>
 	</div>
</form>



<script type="text/javascript">
	jQuery('#add_chave').click(function(e){
		e.preventDefault();
		jQuery('#chaves option').filter(":selected").appendTo('#selected_chaves');
		
		var mySelect = jQuery('#selected_chaves');
		var myOptions = jQuery('#selected_chaves option');
		sortAfterMoved(mySelect, myOptions);
	});

	jQuery('#rem_chave').click(function(e){
		e.preventDefault();
		jQuery('#selected_chaves option').filter(":selected").appendTo('#chaves');

		var mySelect = jQuery('#chaves');
		var myOptions = jQuery('#chaves option');
		sortAfterMoved(mySelect, myOptions);
	});

function sortAfterMoved(select, options) {

	options.sort(function(a,b) {
	    if (a.text > b.text) return 1;
	    else if (a.text < b.text) return -1;
	    else return 0;
	});

	select.empty().append( options );
}
  
</script>