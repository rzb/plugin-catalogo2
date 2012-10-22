<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');

class trajCatalogoDBops {
	
	public static function getAllChaves() {
		global $wpdb;
		// contando chaves...
		$totalChaves = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . TRAJ_PALAVRAS_TABLE ) );
		// trazendo as chaves
		if ($totalChaves > 0) {
			$chaves = $wpdb->get_results(	"SELECT *
									 FROM " . TRAJ_PALAVRAS_TABLE . "
									 ORDER BY palavra ASC ", OBJECT_K );
			return $chaves;
		} else {
			return FALSE;
		}
	}
	
	public static function getAllTrabalhos($offset=NULL, $limit=NULL) {
		global $wpdb;
		// contando publicações...
		$totalTrabs = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . TRAJ_TRABALHOS_TABLE ) );
		// trazendo as publicações
		if ($totalTrabs > 0) {
			if (!$offset) $offset = 0;
			if (!$limit) $limit = 20;
			$trabalhos = $wpdb->get_results( "SELECT *
										  FROM " . TRAJ_TRABALHOS_TABLE . "
					ORDER BY autor ASC
					LIMIT $limit
					OFFSET $offset", OBJECT_K );
			return $trabalhos;
		} else {
			return FALSE;
		}
	}
	
	public static function getTrabalho($id) {
	
		global $wpdb;
	
		$trabalho = $wpdb->get_row( "SELECT *
								 FROM " . TRAJ_TRABALHOS_TABLE . "
				WHERE id = '$id'");
	}
	
	public static function setTrabalho( $stuff ) {
		global $wpdb;
		$now = NowDatetime();
		if ( $stuff != NULL ) {
	
			$stuff["dados"]["data_criacao"] = $now;
			$stuff["dados"]["data_modificacao"] = $now;
	
			return $wpdb->insert( TRAJ_TRABALHOS_TABLE, $stuff["dados"]);
	
		} else {
			return FALSE;
		}
	}
	
	public static function editTrabalho( $stuff ) {
		global $wpdb;
		$now = NowDatetime();
		if ( $stuff != NULL ) {
	
			$stuff["dados"]["data_modificacao"] = $now;
	
			return $wpdb->update( TRAJ_TRABALHOS_TABLE, $stuff["dados"], $stuff["where"]);
	
		} else {
			return FALSE;
		}
	
	
	}
	
	public static function delTrabalho( $stuff ) {
		global $wpdb;
		$now = NowDatetime();
		if ( $stuff != NULL ) {
			return $wpdb->delete( TRAJ_TRABALHOS_TABLE, $stuff["where"]);
		} else {
			return FALSE;
		}
	}
	
}

?>