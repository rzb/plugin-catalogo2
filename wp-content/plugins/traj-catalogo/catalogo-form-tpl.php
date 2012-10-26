<?php 

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$option = $_POST['option'];
if ($option == "edit_trab") {
	$trab			= trajCatalogoDBops::getTrabalho($_POST['itemID']);
	$chosenChaves	= trajCatalogoDBops::getChaveIDsByTrab($trab->id);
}

$chaves 			= trajCatalogoDBops::getAllChaves();

?>

<form class="form-horizontal" id="trabForm">
	
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
 <?php if ($option != "filter_trab") : ?>	
	<input type="hidden" name="id" value="<?php echo $trab->id; ?>" />

	<div class="control-group">
		<label class="control-label" for="autor">Autor</label>
		<div class="controls">
			<input type="text" name="autor" id="autor" value="<?php echo $trab->autor; ?>" />
	    </div>
	</div>
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
 	<div class="control-group">
    	<label class="control-label" for="arquivo">Arquivo para download</label>
    	<div class="controls">	
    		<input type="file" name="arquivo" id="arquivo" value="<?php echo $trab->arquivos; ?>"/>
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