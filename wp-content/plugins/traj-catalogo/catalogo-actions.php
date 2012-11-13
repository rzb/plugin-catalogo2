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

function delFiles( $fileName ) {
	
	if ( $fileName != NULL ) {
		$dir = plugin_dir_path(__FILE__).'uploads'.DIRECTORY_SEPARATOR;
		echo $dir.$fileName;
		if(file_exists( $dir.$fileName )) 
			return unlink( $dir.$fileName );
	} else {
		return TRUE;
	}
}

	
if ( current_user_can('manage_options') ) {

	$dados	= prepareData();
	
	$option		= $_POST['option'];
	$chaveID	= $_POST['chaveID'];
	$palavra	= $_POST['palavra'];
	$fileName	= $_POST['fileName'];
	
	switch ($option) {
		case "new_trab":
			$res = trajCatalogoDBops::setTrabalho( $dados );
			if(!$res)
				echo "error";
			break;
		case "edit_trab":
			$res = trajCatalogoDBops::editTrabalho( $dados );
			if(!$res)
				echo "error";			
			break;
			
		case "down_trab":
			$res = trajCatalogoDBops::increaseDlCount( $_POST['itemID'] );
			if(!$res)
				echo "error";
			break;
			
		case "del_trab":
			$res1 = delFiles($fileName);
			$res2 = trajCatalogoDBops::delTrabalho( $dados );
			if(!$res1)
				echo "delfile";
            if(!$res2)
                echo "db";
			break;
			
		case "new_chave":
			$res = trajCatalogoDBops::setChaves( $palavra );
			if(!$res)
				echo "error";
			break;	
			
		case "edit_chave":
			$res = trajCatalogoDBops::editChave( array("palavra" => $palavra), array("id" => $chaveID) );
			if(!$res)
				echo "error";
			break;
			
		case "del_chave":
			$res = trajCatalogoDBops::delChave( array("id" => $chaveID) );
			if(!$res)
				echo "error";
			break;
			
		case "del_files":
			$res = delFiles($fileName);
			if(!$res)
				echo "error";
			break;
			
		default:
			break;
	}
	
}

?>