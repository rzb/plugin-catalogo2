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
			// se o filtro de palavras-chave foi entrado...
			if (is_array($filters)) {
				// inicializa a variável que fará com que a próxima consulta comece de onde a última parou
				$dyamicOffset = $offset;
				// inicializa array de id's de registros (trabalhos/publicações...) que vai ser usado na consulta final
				$ids = array();
				/* THE LOOP: 
				 * roda enquanto ambas as condições forem verdadeiras:
				 * - o array de id's (número de registros) for menor que o limite de registros esperado => se já tenho a quantidade de id's que espero, não continua
				 * - o "offset dinâmico" for menor ou igual ao total de registros da tabela 			=> se não há mais registros a testar, não continua
				 * */
				while ((sizeof($ids) < $limit) && ($dyamicOffset <= $totalTrabs)) {
					// traz o id e a string de chaves separadas por vírgula de cada registro, començando a apatir do novo valor de offset
					$trabalhos = $wpdb->get_results("SELECT id, palavra_ids 
												  	 FROM " . TRAJ_TRABALHOS_TABLE . "
													 LIMIT $dyamicOffset, $limit", OBJECT_K );
					/* FILTRAGEM:
					 * para cada registro retornado, explode suas chaves para um array e verifica se ele contêm todas as chaves do filtro entrado
					 * - se em algum momento uma chave do filtro não corresponder a uma chave do registro, pula para o próximo trabalho
					 * - se todas as chaves do filtro passarem, adiciona o ID do registro atual ao array de id's
					 * */
					foreach ($trabalhos as $key => $trab) {
						$chaves = explode(',', $trab->palavra_ids);
						foreach ($filters as $f) {
							if (!in_array($f, $chaves)) continue 2;
						}
						$ids[] = $trab->id;
					}
					// faz a próxima consulta começar de onde parou a última
					$dyamicOffset += $limit; 
				}
				// junta todos os ID's numa string, separados por vírgula
				$ids = implode(",",$ids);
				// finalmente, recupera todos os registros que correspondem aos id's
				$trabalhos = $wpdb->get_results("SELECT *
										  	 	 FROM " . TRAJ_TRABALHOS_TABLE . "
												 WHERE id in (" .$ids. ") 
												 LIMIT $offset, $limit", OBJECT_K );
			// se nenhum filtro foi entrado, realiza consulta normalmente
			} else {
		
				$trabalhos = $wpdb->get_results("SELECT *
										  	 	 FROM " . TRAJ_TRABALHOS_TABLE . "
											 	 LIMIT $offset, $limit", OBJECT_K );
			}
			// retorna os registros filtrados
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
	
	public static function getTotalTrabalhos() {
		global $wpdb;
		
		$total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM " .TRAJ_TRABALHOS_TABLE));
		
		return $total;
	}
	
	public static function getTotalChaves() {
		global $wpdb;
		
		$total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM " .TRAJ_PALAVRAS_TABLE));
		
		return $total;
	}
	
}

?>