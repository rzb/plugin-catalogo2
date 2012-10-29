<?php 

require_once('../../../wp-load.php');
require_once('catalogo-config.php');
require_once('catalogo-db-ops.php');
require_once('inc/util.php');
	
function prepareData(){

	$palavraIDs = @implode(",", $_POST['selected_chaves']);
	
	$stuff["dados"] = array(
			"autor" => $_POST['autor'],
			"titulo" => $_POST['titulo'],
			"revista" => $_POST['revista'],
			"resumo" => $_POST['resumo'],
			"volume" => $_POST['volume'],
			"numero" => $_POST['numero'],
			"primeira_pag" => $_POST['primeira_pag'],
			"ultima_pag" => $_POST['ultima_pag'],
			"ano" => $_POST['ano'],
			"palavra_ids" => $palavraIDs,
			"fotocopias" => $_POST['fotocopias'],
			"arquivos" => $_POST['arquivos']
	);
	$stuff["where"] = array( "id" => $_POST['id'] );
	
	return $stuff;
}
	
if ( current_user_can('manage_options') ) {

	$dados	= prepareData();
	
	$option		= $_POST['option'];
	$chaveID	= $_POST['chaveID'];
	$palavra	= $_POST['palavra'];
	
	switch ($option) {
		case "new_trab":
			$res = trajCatalogoDBops::setTrabalho( $dados );
			if(!res)
				echo "error";
			break;
		case "edit_trab":
			$res = trajCatalogoDBops::editTrabalho( $dados );
			if(!res)
				echo "error";			
			break;
			
		case "down_trab":
			$res = trajCatalogoDBops::increaseDlCount( $_POST['itemID'] );
			if(!res)
				echo "error";
			break;
			
		case "del_trab":
			// @TODO pegar coluna arquivos do trabalho passando ID (implementar função em dp-ops)
			$res = trajCatalogoDBops::delTrabalho( $dados );
			if(!res)
				echo "error";
			break;
			
		case "new_chave":
			$res = trajCatalogoDBops::setChaves( $palavra );
			if(!res)
				echo "error";
			break;	
			
		case "edit_chave":
			$res = trajCatalogoDBops::editChave( array("palavra" => $palavra), array("id" => $chaveID) );
			if(!res)
				echo "error";
			break;
			
		case "del_chave":
			$res = trajCatalogoDBops::delChave( array("id" => $chaveID) );
			if(!res)
				echo "error";
			break;
			
		default:
			break;
	}
	
}

?>