<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Db_lib {
	
    function __construct(){
        
    }
    
	/**
	 * Prep_db_data
	 *
	 * Removes fields from given array with respect of a database table.
	 * Fields that are not present in table will be removed from the array.
	 * and returns the modified array
	 *
	 * @access	public
	 * @param	Array	the given array
	 * @param	String	the respective tablename
	 * @param	Boolian flag for removing the empty fields from array
	 * @return	Array
	 */
	function prep_db_data($array, $tablename, $remove_empty_data = FALSE) { //removes data from array which dont match with table fields
		$fields = $this->get_fields ( $tablename );
		foreach ( $array as $key => $value ) {
			if (! in_array ( $key, $fields )) { //checks if field exists in database table
				unset ( $array [$key] );
			}
		}
		
		if ($remove_empty_data) {
			$array = $this->remove_empty_data ( $array );
		}
		return $array;
	}
	
	function prep_where($key_value = FALSE, $key_name = FALSE) {
		$ci = &get_instance ();
		if ($key_value) {
			if (! is_array ( $key_value )) {
				$key_value = array ($key_name => $key_value );
			}
			$ci->db->where ( $key_value );
		}
	}
	
	/*
	 * Removes empty fields from an array or object
	 *
	 * @return object or array
	 *
	 */
	function remove_empty_data($data) {
		foreach ( $data as $key => $value ) {
			if (empty ( $value )) { //checks if field is empty
				if (is_array ( $data ))
					unset ( $data [$key] );
				elseif (is_object ( $data ))
					unset ( $data->$key );
			}
		}
		return $data;
	}
	
	function get_fields($tablename, $attribute = "Field") {
		$ci = &get_instance ();
		$list = $ci->db->query ( "SHOW COLUMNS FROM $tablename" );
		$list = $list->result_array ();
		foreach ( $list as $val ) {
			$ret [] = $val [$attribute];
		}
		return $ret;
	}
	
	function insert($table, $data) {
		$CI = &get_instance ();
//		$user_id = $CI->session->userdata ( "user_id" );
//		$user_type = $CI->session->userdata ( "user_type" );
//		$cr_by = $user_type . " " . $user_id;
//		$cr_date = date ( "Y-m-d h:i:s" );
//		$data = array_merge ( $data, compact ( "cr_by", "cr_date" ) );
		$data = $this->prep_db_data ( $data, $table );
		return $CI->db->insert ( $table, $data );
	}
	
	function update($table, $data) {
		$CI = &get_instance ();
//		$user_id = $CI->session->userdata ( "user_id" );
//		$user_type = $CI->session->userdata ( "user_type" );
//		$up_by = $user_type . " " . $user_id;
//		$up_date = date ( "Y-m-d h:i:s" );
//		$data = array_merge ( $data, compact ( "up_by", "up_date" ) );
		$data = $this->prep_db_data ( $data, $table );
		return $CI->db->update ( $table, $data );
	}
	
	function delete($table) {
		$CI = &get_instance ();
//		$user_id = $CI->session->userdata ( "user_id" );
//		$user_type = $CI->session->userdata ( "user_type" );
//		$up_by = $user_type . " " . $user_id;
//		$up_date = date ( "Y-m-d h:i:s" );
		$data = array ("is_deleted" => 1 );
//		$data = array_merge ( $data, compact ( "up_by", "up_date" ) );
		$data = $this->prep_db_data ( $data, $table );
		return $CI->db->update ( $table, $data );
	}
	
	/**
	 * Get
	 *
	 * Gets a table where is_deleted field is 0.
	 * Calls the CI_CB_active_record with modified where clauses
	 *
	 * @access	public
	 * @param	String	the given tablename
	 * @return	CI_DB_mysql_result
	 */
	function get($table) {
		$CI = &get_instance ();
		//$CI->db->where ( $table . ".isdeleted", 0 );
		return $CI->db->get ( $table );
	}
	
	//backup_tables('localhost','username','password','blog');
	

	/* backup the db OR just a table */
	function backup_tables($tables = '*') {
		$return = "";
		if ($tables == '*') {
			$tables = array ();
			$result = mysql_query ( 'SHOW TABLES' );
			while ( $row = mysql_fetch_row ( $result ) ) {
				$tables [] = $row [0];
			}
		} else {
			$tables = is_array ( $tables ) ? $tables : explode ( ',', $tables );
		}
		
		//cycle through
		foreach ( $tables as $table ) {
			$ci = &get_instance ();
			
			$result = mysql_query ( 'SELECT * FROM ' . $table );
			$num_fields = mysql_num_fields ( $result );
			
			$row2 = mysql_fetch_row ( mysql_query ( 'SHOW CREATE TABLE ' . $table ) );
			$table_type = (count ( $row2 ) <= 2) ? "TABLE" : "VIEW";
			if ($table_type == "VIEW") {
				$return .= 'DROP VIEW IF EXISTS ' . $table . ';';
				$str = "\n\n" . $row2 [1] . ";\n\n";
				$return .= preg_replace ( "@(ALGORITHM.*DEFINER )@i", "", $str );
			}

			elseif ($table_type == "TABLE") {
				$return .= 'DROP TABLE IF EXISTS ' . $table . ';';
				$return .= "\n\n" . $row2 [1] . ";\n\n";
				
				for($i = 0; $i < $num_fields; $i ++) {
					while ( $row = mysql_fetch_row ( $result ) ) {
						$return .= 'INSERT INTO ' . $table . ' VALUES(';
						for($j = 0; $j < $num_fields; $j ++) {
							$row [$j] = addslashes ( $row [$j] );
							$row [$j] = preg_replace ( "@\n@", "\\n", $row [$j] );
							if (isset ( $row [$j] )) {
								$return .= '"' . $row [$j] . '"';
							} else {
								$return .= '""';
							}
							if ($j < ($num_fields - 1)) {
								$return .= ',';
							}
						}
						$return .= ");\n";
					}
				}
			}
			$return .= "\n\n\n";
		}
		return $return;
	}

}