<?php 

require_once('../../../wp-load.php');

function prepareDBStuff(){
	$stuff["data"] = array(
			"autor" => $_GET['autor'],
			"titulo" => $_GET['titulo'],
			"revista" => $_GET['revista'],
			"resumo" => $_GET['resumo'],
			"volume" => $_GET['volume'],
			"numero" => $_GET['numero'],
			"primeira_pag" => $_GET['primeira_pag'],
			"ultima_pag" => $_GET['ultima_pag'],
			"ano" => $_GET['ano'],
			"palavra_ids" => $_GET['chaves'],
			"fotocopias" => $_GET['fotocopias'],
			"arquivos" => $_GET['arquivos'],
			"downloads_count" => 0
	);
	$stuff["where"] = array( "id" => $_GET['id'] );
	
	return $stuff;
}

if ( current_user_can('manage_options') ) {

	global $wpdb;
	
	$option = $_GET['option'];
	$now = NowDatetime();
	
	switch ($option){
		case "new":
			$stuff = prepareDBStuff();
			$stuff["data"]["data_criacao"] = $now;
			$stuff["data"]["data_modificacao"] = $now;
			$res = $wpdb->insert(trajCatalogo::TRAJ_TRABALHOS_TABLE, $stuff["data"]);
			if(!$res) echo "error";
			else echo $stuff["data"]["data_modificacao"];
			break;
			
		case "edit":
			$stuff = prepareDBStuff();
			$stuff["data"]["data_modificacao"] = $now;
			$res = $wpdb->update(trajCatalogo::TRAJ_TRABALHOS_TABLE, $stuff["data"], $stuff["where"]);
			if(!$res) echo "error";
			else echo $stuff["data"]["data_modificacao"];
			break;
			
		case "del":
			$stuff = prepareDBStuff();
			$res = $wpdb->delete(trajCatalogo::TRAJ_TRABALHOS_TABLE, $stuff["where"]);
			if (!$res) echo "error";
			break;
			
		default:
			break;
	}
	
}

?>