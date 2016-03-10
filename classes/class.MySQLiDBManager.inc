<?php

/*

Description: 		MySQLi Database Class wrapper.
Author: 			Mc Clynrey Arboleda
Company: 			Cre8ive Innovation
Website:			www.cre8ive-devs.com
Email:				mcclynrey@gmail.com

*/

class MySQLiDBManager{
	
	private $p_HostName;
	private $p_Password;
	private $p_PortNumber;
	private $p_SchemaName;
	private $p_UserName;
	
	private $p_ErrorCode;
	private $p_ErrorMessage;
	
	private $p_DBConnection;
	
	private $p_query;
	private $p_numrows;
	private $p_lastid;
	private $p_result;
	
	//const SETTINGS_FILENAME = '../../hrconf/dbconf.php';
	const SETTINGS_FILENAME = '../.env';
	const SETTINGS_DB_FIELD = 'database';
	
	//Initialization of Database Object
	function MySQLiDBManager($hostName="", $portNumber="", $databaseName="", $userName="", $password=""){
		
		if(strlen($hostName)<=0){
			
			//Get the Default Connection
			
			$file = self::SETTINGS_FILENAME;
			if(!file_exists($file)) {
				//$file = ".env";
				$file = "../../.env";
			} 
			$dbfield = self::SETTINGS_DB_FIELD;

    		if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');
			
				$this->p_HostName = $settings[$dbfield]['host'];
				$this->p_Password = $settings[$dbfield]['password'];
				$this->p_PortNumber = $settings[$dbfield]['port'];
				$this->p_SchemaName = $settings[$dbfield]['schema'];
				$this->p_UserName = $settings[$dbfield]['uname'];
			
		}else{
			
			//Use the Provided Connection
			
			$this->p_HostName = $hostName;
			$this->p_Password = $password;
			$this->p_PortNumber = $portNumber;
			$this->p_SchemaName = $databaseName;
			$this->p_UserName = $userName;
			
		}
		
	}
	
	//Connection Method
	function Connect(){
		
		$this->p_DBConnection = new mysqli($this->p_HostName, $this->p_UserName, $this->p_Password, $this->p_SchemaName);
		
		$this->p_ErrorMessage = "";
		$this->p_ErrorCode = "";
		
		if(mysqli_connect_errno()){
			$this->p_ErrorMessage = mysqli_connect_error();
			$this->p_ErrorCode = mysqli_connect_errno();
			return false;
			die();
		}
		
		return true;
		
	}
	
	//Getting Connection Method
	function getConnectionLink(){
		
		return $this->p_DBConnection;
		
	}
	
	//Close Connection Method
	function Close(){
		
		$this->p_ErrorMessage = "";
		$this->p_ErrorCode = "";
		
		$this->p_DBConnection->close();
		
	}
	
	//Get Number of Rows
	function GetNumberOfRows(){
		
		return $this->p_numrows;
		
	}
	
	//Get Last Inserted MYSQLI ID
	function GetLastInsertedID(){
		
		return $this->p_lastid;
		
	}
	
	//Get Query
	function GetQuery(){
		
		return $this->p_query;
		
	}
	
	//INSERT METHOD
	
	function InsertData($sqlQuery){
		
		$this->p_query="";
		$this->p_query = $sqlQuery;
		$this->p_numrows=0;
		
		$this->p_result=$this->p_DBConnection->query($this->p_query);
		
		if(!$this->p_result){
			
			$this->p_ErrorMessage = $this->p_DBConnection->error;
			$this->p_ErrorCode = $this->p_DBConnection->errno;
			return;
			die();
			
		}
		
		$this->p_lastid = $this->p_DBConnection->insert_id;
		
		return true;
		
	}
	
	//UPDATE AND DELETE METHOD
	
	function ExecuteQuery($sqlQuery){
		
		$this->p_query="";
		$this->p_query = $sqlQuery;
		$this->p_numrows=0;
		
		$this->p_result=$this->p_DBConnection->query($this->p_query);
		
		if(!$this->p_result){
			
			$this->p_ErrorMessage = $this->p_DBConnection->error;
			$this->p_ErrorCode = $this->p_DBConnection->errno;
			return;
			die();
			
		}
		
		$this->p_lastid = "";
		
		return true;
		
	}
	function loginModule($sqlQuery){
		$this->p_query="";
		$this->p_query = $sqlQuery;
		$this->p_numrows=0;
		$this->p_result=$this->p_DBConnection->query($this->p_query);
		if(!$this->p_result){

			$this->p_ErrorMessage = $this->p_DBConnection->error;
			$this->p_ErrorCode = $this->p_DBConnection->errno;
			return;
			die();
			
		}
		
		$this->p_numrows=$this->p_result->num_rows;
		$rows=array();
		if($this->p_result->num_rows > 0) {
			
			while($row= $this->p_result->fetch_assoc()) {
				
				//echo stripslashes($row['title'].'<br/>');	
				$rows[]=$row;
				
			}
			
			
		}
		
		return $rows;
		
	}
	//Read Query Method
	function ReadData($sqlQuery){
		
		$this->p_query="";
		$this->p_query = $sqlQuery;
		$this->p_numrows=0;
		
		$this->p_result=$this->p_DBConnection->query($this->p_query);
		
		if(!$this->p_result){

			$this->p_ErrorMessage = $this->p_DBConnection->error;
			$this->p_ErrorCode = $this->p_DBConnection->errno;
			return;
			die();
			
		}
		
		$this->p_numrows=$this->p_result->num_rows;
		$rows=array();
		if($this->p_result->num_rows > 0) {
			
			while($row= $this->p_result->fetch_assoc()) {
				
				//echo stripslashes($row['title'].'<br/>');	
				$rows[]=$row;
				
			}
			
			
		}
		
		return $rows;
		
	}
	
	//Get Error Message
	function GetErrorMessage(){
		
		return $this->p_ErrorMessage;
		
	}
	
	//Get Error Number/Code
	
	function GetErrorNumber(){
		
		return $this->p_ErrorCode;
		
	}
	
	//Check if Record Exists
	
	function IsRecordExists($sqlQuery){
		
		$result=false;
		
		$this->p_query="";
		$this->p_query = $sqlQuery;
		$this->p_numrows=0;
		
		$this->p_result=$this->p_DBConnection->query($this->p_query);
		
		if(!$this->p_result){
			
			$this->p_ErrorMessage = $this->p_DBConnection->error;
			$this->p_ErrorCode = $this->p_DBConnection->errno;
			return;
			die();
			
		}
		
		$this->p_numrows=$this->p_result->num_rows;
		
		if($this->p_numrows>0){
			$result=true;
		}
		
		return $result;
		
		
	}
	
	//Query a Single Value
	function getValue($sqlQuery, $fieldName){
		
		$result="";
		
		$this->p_query="";
		$this->p_query = $sqlQuery;
		$this->p_numrows=0;
		
		$this->p_result=$this->p_DBConnection->query($this->p_query);
		
		if(!$this->p_result){
			
			$this->p_ErrorMessage = $this->p_DBConnection->error;
			$this->p_ErrorCode = $this->p_DBConnection->errno;
			return;
			die();
			
		}
		
		$this->p_numrows=$this->p_result->num_rows;

		if($this->p_result->num_rows > 0) {
			
			while($row= $this->p_result->fetch_assoc()) {
				
				//echo stripslashes($row['title'].'<br/>');	
				$result=$row[$fieldName];
				
			}
			
			
		}
		
		return $result;
		
	}
	
	//Query a Multiple Values
	function getMultipleValues($sqlQuery, $fieldNames, $delimeter){
		
		$result="";
		
		$this->p_query="";
		$this->p_query = $sqlQuery;
		$this->p_numrows=0;
		
		$this->p_result=$this->p_DBConnection->query($this->p_query);
		
		if(!$this->p_result){
			
			$this->p_ErrorMessage = $this->p_DBConnection->error;
			$this->p_ErrorCode = $this->p_DBConnection->errno;
			return;
			die();
			
		}
		
		$this->p_numrows=$this->p_result->num_rows;

		if($this->p_result->num_rows > 0) {
			
			while($row= $this->p_result->fetch_assoc()) {
				
				$data=explode($delimeter,$fieldNames);

				for($i=0;$i<count($data);$i++){
					
					if($i==(count($data)-1)){
						
						$result.=$row[$data[$i]];
						
					}else{
						
						$result.=$row[$data[$i]].$delimeter;
						
					}
					
					
				}
				
				
			}
			
			
		}
		
		return $result;
		
	}
	
	//TRANSACTION PART
	function beginTransaction(){
		
		$this->p_DBConnection->autocommit(FALSE);
		
	}
	
	function commitChanges(){
		
		$this->p_DBConnection->commit();
		$this->p_DBConnection->autocommit(TRUE);
		
	}
	
	function rollbackChanges(){
		
		$this->p_DBConnection->rollback();
		$this->p_DBConnection->autocommit(TRUE);
		
	}
	
	
}

?>