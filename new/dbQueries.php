<?php
include("database.php");
class dbQueries{
	function __construct($action,$data,$tablename,$validation,$connection){
		if($action == 'Insert'){
			$this->insertQuery($data,$tablename,$connection);
		}
		else if($action == 'Select'){
			$this->selectQuery($tablename,$data,$validation,$connection);
		}
		else if($action == 'Update'){
			$this->updateQuery($data,$tablename,$validation,$connection);
		}
		else if($action == 'Delete'){
			$this->deleteQuery($tablename,$validation,$connection);
		}
	}
	private function insertQuery($data,$table,$connection){
		$count =0;
		$fields;
		$datum;
		foreach($data as $column => $row){
			$fields .= ($count==0)?"`".$column."`":",`".$column."`";
			$datum .= ($count==0)?"'".$row."'":",'".$row."'";
			$count++;
		}
		$insertQuery = 'insert into '.$table.'('.$fields.')values('.$datum.')';
		$stat = (!mysqli_query($connection,$insertQuery))?'Error':'Success';
		$this->returnResult($insertQuery,'done',$stat);
	}
	
	
	//deleting item in database
	private function deleteQuery($tableName,$validation,$sqlConn){
		$query = "delete from $tableName where $validation";
		if($validation == "" || $validation ==null){
			$this->returnResult($query,'error','none');
		}else{
			$stat = (!mysqli_query($sqlConn,$query))?'Error':'Success';
			$this->returnResult($query,'done',$stat);
		}
	}
	
	
	
	//select query function ***(AUTO GENERATED FOR JQUERY RESULT)
	private function selectQuery($tableName,$data,$validation,$sqlConn){
		$val=array(array());
		$test = array();
		$count =0;
		$query = "select ";
		if(!$data){
			$query .="* ";
		}else{
			foreach($data as $column){
				$query .= ($count ==0)?$column:",".$column;
				$count++;
			}
		}
		$query .= " from $tableName ";
		$query .= (!$validation)?" ":'where '.$validation;
		$ctr = 0;
		$result = mysqli_query($sqlConn,$query);
		while($r=mysqli_fetch_field($result)){
			$test[$ctr] = $r->name;
			$ctr++;
		}
		$ctr = 0;
		while($r=mysqli_fetch_assoc($result)){
			for($i=0;$i<count($test);$i++){
				$val[$i][$ctr] = $r[$test[$i]];
			}
			$ctr++;
		}
		for($i=0;$i<count($test);$i++){
			$values=array();
			for($x=0;$x<count($val[$i]);$x++){
				$values[$x] =$val[$i][$x];
			}
			$test[$i] = array($test[$i] => $values);
		}
		$this->returnResult($test,$query	,'Success');
	}
	
	private function updateQuery($data,$table,$validation,$connection){
		$query = "";
		$count =0;
		$fields;
		$query = "update $table set ";
		if(!$validation){
			$this->returnResult('','No validation','Error');
		}
		else
		{
			foreach($data as $column => $row){
				$query .= ($count==0)?$column."='".$row."' ":",".$column."='".$row."' ";
				$count++;
			}
		}
		$query .= ($validation)?'where '.$validation:'';
		mysqli_query($connection,$query);
		$this->returnResult($query,'','');
	}
	
	
	
	private function returnResult($data,$message,$status){
		echo json_encode(array('result'=>$data,'message'=>$message,'status'=>$status));
	}

}
//$dbque = new dbQueries($_POST['action'],$_POST['datalist'],$_POST['table'],$_POST['validation'],$conn);
$dbq = new dbQueries('Select',array('client_id as r1','client_name as r2','site_name as r3','dept_name as r4','email as r5'),'client',"client_id=client_id",$conn);

?>