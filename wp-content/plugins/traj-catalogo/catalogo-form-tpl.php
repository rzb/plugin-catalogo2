<?php 

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');

$chaves = trajCatalogoDBops::getAllChaves();

$option = $_POST['option'];
if ($option == "edit_trab")
	$dados	= trajCatalogoDBops::getTrabalho($_POST['itemID']);

?>

<form class="form-horizontal" id="trabForm">

	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	
	<input type="hidden" name="id" value="<?php echo $dados->id; ?>" />

	<div class="control-group">
		<label class="control-label" for="autor">Autor</label>
		<div class="controls">
			<input type="text" name="autor" id="autor" value="<?php echo $dados->autor; ?>" />
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="titulo">Título</label>
	    <div class="controls">
	    	<input type="text" name="titulo" id="titulo" value="<?php echo $dados->titulo; ?>" />
	    </div>
	</div>
 	<div class="control-group">
    	<label class="control-label" for="revista">Revista</label>
    	<div class="controls">	
    		<input type="text" name="revista" id="revista" value="<?php echo $dados->revista; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="resumo">Resumo</label>
    	<div class="controls">	
    		<textarea rows="5" id="resumo"><?php echo $dados->resumo; ?></textarea>
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="volume">Volume</label>
    	<div class="controls">	
    		<input type="text" name="volume" id="volume" value="<?php echo $dados->volume; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="revista">Número</label>
    	<div class="controls">	
    		<input type="text" name="numero" id="numero" value="<?php echo $dados->numero; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="primeira_pag">Primeira Página</label>
    	<div class="controls">	
    		<input type="text" name="primeira_pag" id="primeira_pag" value="<?php echo $dados->primeira_pag; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="ultima_pag">Última Página</label>
    	<div class="controls">	
    		<input type="text" name="ultima_pag" id="ultima_pag" value="<?php echo $dados->ultima_pag; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="ano">Ano</label>
    	<div class="controls">	
    		<input type="text" name="ano" id="ano" value="<?php echo $dados->ano; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="chaves">Palavras-chave</label>
    	<div class="controls">
    		<select name="new_chaves" multiple="multiple">
			<?php if($chaves) foreach ($chaves as $c): ?>
				<option id="<?php echo $c->id; ?>" value="<?php echo $c->palavra; ?>"><?php echo $c->palavra; ?></option>
			<?php endforeach; ?>
			</select>
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="fotocopias">Fotocópias</label>
    	<div class="controls">	
    		<input type="text" name="fotocopias" id="fotocopias" value="<?php echo $dados->fotocopias; ?>" />
    	</div>
 	</div>
 	<div class="control-group">
    	<label class="control-label" for="arquivo">Arquivo para download</label>
    	<div class="controls">	
    		<input type="file" name="arquivo" id="arquivo" value="<?php echo $dados->arquivos; ?>"/>
    	</div>
 	</div>
</form>