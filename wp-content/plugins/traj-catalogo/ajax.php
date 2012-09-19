<?php 

require_once('../../../wp-load.php');

if ( current_user_can('manage_options') ) {

	global $wpdb;
	
	$option = $_GET['option'];
	$now = NowDatetime();
	
	switch ($option){
		case "new":
			$data = array(
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
						"data_criacao" => $now,
						"data_modificacao" => $now,
						"fotocopias" => $_GET['fotocopias'],
						"arquivos" => $_GET['arquivos'],
						"downloads_count" => 0
					);
			
			$res = $wpdb->insert(trajCatalogo::TRAJ_TRABALHOS_TABLE, $data);
			if($res) echo "ok";
			break;
			
		case "edit":
			// filtrar o passo via query string
			// passo 1: recuperar dados existentes
			// passo 2: atualizar dados
			break;
			
		case "del":
			$data = array(
						"id" => $_GET['id']
					);
			
			$res = $wpdb->delete(trajCatalogo::TRAJ_TRABALHOS_TABLE, $data);
			if($res) echo "ok";
			break;
			
		default:
			break;
	}
	
}

?>