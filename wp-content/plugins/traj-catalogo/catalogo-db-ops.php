<?php

require_once('../../../wp-load.php');
require_once('catalogo-config.php');

class trajCatalogoDBops {
	
	public static function getAllChaves($offset=NULL, $limit=NULL) {
		global $wpdb;
		// contando chaves...
		$totalChaves = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . TRAJ_PALAVRAS_TABLE ) );
		// trazendo as chaves
		if ($totalChaves > 0) {
		    if (!$offset) $offset = 0;
            if (!$limit) $limit = 15;
			$chaves = $wpdb->get_results(	"SELECT *
									 		 FROM " . TRAJ_PALAVRAS_TABLE . "
									 		 ORDER BY palavra ASC 
									 		 LIMIT $offset, $limit", OBJECT_K );
			return $chaves;
		} else {
			return FALSE;
		}
	}
	
	public static function getAllChavesByTrab($trabID) {
		global $wpdb;
		
		$ids = $wpdb->get_var( 
			$wpdb->prepare(	
				"SELECT palavra_ids 
				 FROM ". TRAJ_TRABALHOS_TABLE ." 
				 WHERE id = $trabID"
			) 
		);
		
		$ids = substr_replace("'" . str_replace(",", "',", $ids) ,"",-1);
		
		$chaves = $wpdb->get_results(	
			"SELECT * 
			 FROM ". TRAJ_PALAVRAS_TABLE ." 
			 WHERE id IN (".$ids.")", 
			 OBJECT_K
		);
		
		return $chaves;
		
	}
	
	public static function getChaveIDsByTrab($trabID) {
		global $wpdb;
		
		$ids = $wpdb->get_var( 
			$wpdb->prepare(	
				"SELECT palavra_ids 
				 FROM ". TRAJ_TRABALHOS_TABLE ." 
				 WHERE id = $trabID"
			) 
		);
		
		$ids = explode(',', $ids);
		
		return $ids;
	}
	
	public static function getAllTrabalhos($offset=NULL, $limit=NULL, $filters=NULL) {
		global $wpdb;
		
		// contando TOTAL de registros
		$totalTrabs = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . TRAJ_TRABALHOS_TABLE ) );
		// se houver pelo menos 1 registro...
		if ($totalTrabs > 0) {
			// inicializa offset e limit caso não tenham sido entrados (ex.: primeiro carregamento)
			if (!$offset) $offset = 0;
			if (!$limit) $limit = 15;
			// monta cláusula WHERE caso haja filtros
			$where = self::queryFilter($filters);
			
			$trabalhos = $wpdb->get_results("SELECT *
										  	 FROM " . TRAJ_TRABALHOS_TABLE . " 
										  	 $where 
											 LIMIT $offset, $limit", OBJECT_K );
												 
			// retorna os registros
			return $trabalhos;
		// se não houver registros na tabela, não faz nada e retorna false
		} else {
			return FALSE;
		}
	}
	
	public static function getTrabalho($id) {
	
		global $wpdb;
	
		$trabalho = $wpdb->get_row( "SELECT *
									 FROM " . TRAJ_TRABALHOS_TABLE . "
									 WHERE id = " . $id, OBJECT );
		
		return $trabalho;
	}
	
	public static function setTrabalho( $stuff ) {
		global $wpdb;
		$now = NowDatetime();
		if ( $stuff != NULL ) {
	
			$stuff["dados"]["data_criacao"] = $now;
			$stuff["dados"]["data_modificacao"] = $now;
	
			return $wpdb->insert( TRAJ_TRABALHOS_TABLE, $stuff["dados"] );
	
		} else {
			return FALSE;
		}
	}
	
	public static function setChaves( $data ) {
		global $wpdb;
		
		if ( $data != NULL ) {
	
			$data = explode(',', $data);
			
			foreach ($data as $palavra) {
				$wpdb->insert( TRAJ_PALAVRAS_TABLE, array("palavra" => $palavra) );
			}
			
			return TRUE;
	
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
	
	public static function editChave( $data, $where ) {
		global $wpdb;
		
		if( $data != NULL && $where != NULL ) {
			return $wpdb->update( TRAJ_PALAVRAS_TABLE, $data, $where );
		} else {
			return FALSE;
		}
	}
	
	public static function delTrabalho( $stuff ) {
		global $wpdb;
		
		if ( $stuff != NULL ) {
			// unlink -> deletar arquivos
			return $wpdb->delete( TRAJ_TRABALHOS_TABLE, $stuff["where"]);
		} else {
			return FALSE;
		}
	}
	
	public static function delChave( $id ) {
		global $wpdb;
		
		if ( $id != NULL ) {
			return $wpdb->delete( TRAJ_PALAVRAS_TABLE, $id);
		} else {
			return FALSE;
		}
	}
	
	public static function getTotalTrabalhos($filters=NULL) {
		global $wpdb;
		
		$where = self::queryFilter($filters);
		
		$total = $wpdb->get_var("SELECT COUNT(id) FROM " .TRAJ_TRABALHOS_TABLE. " $where");
		
		return $total;
	}
	
	public static function getTotalChaves() {
		global $wpdb;
		
		$total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM " .TRAJ_PALAVRAS_TABLE));
		
		return $total;
	}
	
	public static function increaseDlCount( $id ) {
		global $wpdb;
		
		if ( $id != NULL ) {
			return $wpdb->query( "UPDATE " .TRAJ_TRABALHOS_TABLE. " SET downloads_count = downloads_count+1 WHERE id = $id" );
		} else {
			return FALSE;
		}
	}
	
	
	private static function queryFilter($filters) {
		global $wpdb;
		
		$where = "";
		// se pelo menos um filtro foi entrado...
		if (is_array($filters)) {
			// se há filtro por autor...
			if(isset($filters['autor'])) {
				$autorFilter	= "'%" .$filters['autor']. "%'";
			}
			// se há filtro por palavras-chave...
			if(isset($filters['chaves'])) {
				$chavesFilter	= $filters['chaves'];
				$ids			= array();				
				
				$trabalhos = $wpdb->get_results("SELECT id, palavra_ids 
											  	 FROM " . TRAJ_TRABALHOS_TABLE, OBJECT_K );

				foreach ($trabalhos as $key => $trab) {
					$chaves = explode(',', $trab->palavra_ids);
					foreach ($chavesFilter as $f) {
						if (!in_array($f, $chaves)) continue 2;
					}
					$ids[] = $trab->id;
				}

				$ids = implode(",",$ids);
				$chavesFilter = "($ids)";
			}
			
			if (isset($autorFilter) && isset($chavesFilter)) {
				$where = "WHERE autor LIKE $autorFilter AND id in $chavesFilter";
			} elseif (isset($autorFilter)) {
				$where = "WHERE autor LIKE $autorFilter";
			} else {
				$where = "WHERE id in $chavesFilter";
			}
		}	
		
		return $where;
	}
	
}

?>