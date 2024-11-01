<?php

class ActionPHPRapid_DB
{
	private $config_file;
	private $query;

	public function __construct($table)
	{
		$this->table = $table;

		if($this->table){

			$this->config_file = RAPID_APP_PATH . "/config/db-config.php";
			$this->config();
		}
	}

	public function config()
	{	
		//We need to get our configuration file
		include $this->config_file;

		$this->host = $config['host'];
		$this->username = $config['username'];
		$this->database = $config['database'];
		$this->password = $config['password'];

		//print_r($config);die();
		//So let's get our connection
		$this->cxn = $this->cxn();
	}

	public function cxn($type='mysql')
	{
		$cxn = mysqli_connect($this->host, $this->username, $this->password, $this->database);

		return $cxn;
	}

	public function save($model)
	{	
		$id_field = $model['id_field'];
		if(isset($model['attributes'][$id_field])){
			//We have a set id - so this will be an update task
			return $this->update( $model );

		} else {
			//There is no set id - this means we're going to insert a new value

			return $this->insert($model);
		}
	}

	protected function insert($model)
	{	
		$statement = "INSERT INTO " . $this->table . " ";
		$fields = "";
		$values = "";
		$bind = "";

		foreach($model['attributes'] as $attribute => $value){


				$fields .= $attribute . ", ";
				$values .= "?, ";
				$bind .= "s";

				$bind_params[0] = $bind;
				$bind_params[$attribute] = & $model['attributes'][$attribute];

		//	print_r($attribute . ' => ' . $value . "\n");
		}

		
		//We will then remove trailing commas

		$fields = trim($fields, ", ") ;
		$values = trim($values, ", ") ;

		$statement .= "(" . $fields . ")";
		$statement .= " VALUES " . "(" . $values . ")" ;

		//OK, so then we want to prepare our statement
		$prepared = $this->cxn->prepare($statement);

		call_user_func_array(array($prepared, 'bind_param'), $bind_params);
		$prepared->execute();
	//print_r(get_defined_vars()); die();

		return $prepared->insert_id;
	}

	public function update($model)
	{ 
		$statement = "UPDATE " . $this->table . " SET ";
		$to_update = "";

		$id_field = $model['id_field'];
		$id = $model['attributes'][$id_field];

		//Our bind types
		$bind = "";
		foreach($model['attributes'] as $attribute => $value){

			if($attribute != $id_field){
				$to_update .= "$attribute=?, ";

				$bind .= "s";
				$bind_params[0] = $bind;

				$bind_params[$attribute] = & $model['attributes'][$attribute];

			}


		}

		//So we add the id of the row to be updated
		$bind_params[$id_field] = & $model['attributes'][$id_field];
		$bind .= "i"; //Our id should be an integer
		$bind_params[0] = $bind;

		//Remove trailing comma
		$to_update = trim($to_update, ", ");
		$statement .= $to_update;
        $statement .= " WHERE $id_field = ?";


		$prepared = $this->cxn->prepare($statement);


        call_user_func_array(array($prepared, 'bind_param'), $bind_params);

        $prepared->execute();

        return $id;

	}

	public function find($value, $field='id')
	{
		$query = "SELECT * FROM " . $this->table . " WHERE $field = ?";

		$prepared = $this->cxn->prepare($query);
//print_r(get_defined_vars());
		$prepared->bind_param( "s", $value );
		$prepared->execute();
		$prepared->store_result();
		$meta = $prepared->result_metadata();
		while ($field = $meta->fetch_field()) {
		  $parameters[] = &$row[$field->name];
		}
		
		$items = array();
		call_user_func_array(array($prepared, 'bind_result'), $parameters);

		while ($prepared->fetch()) {
		  foreach($row as $key => $val) {
		    $x[$key] = $val;
		  }
		  $items[] = $x;
		
		}

		if(!$items){
			return false;
		}

		if( count($items) == 1){
			
			return $items[0];
		}
		return $items;


	}

	public function all()
	{
		$query = "SELECT * FROM " . $this->table ;

		$prepared = $this->cxn->prepare($query);

		$prepared->execute();
		$prepared->store_result();
		$meta = $prepared->result_metadata();
		while ($field = $meta->fetch_field()) {
		  $parameters[] = &$row[$field->name];
		}
		
		call_user_func_array(array($prepared, 'bind_result'), $parameters);

		while ($prepared->fetch()) {
		  foreach($row as $key => $val) {
		    $x[$key] = $val;
		  }
		  $items[] = $x;
		
		}

		if( count($items) == 1){
			
			return $items[0];
		}
		return $items;


	}

	public function delete($id, $id_field='id')
	{
		$statement = "DELETE FROM " . $this->table . " WHERE $id_field = ? LIMIT 1";

		$prepared = $this->cxn->prepare($statement);
		$prepared->bind_param( "i", $id);//Let's make sure we can only delete 'id' with integer values 

		$deleted = $prepared->execute();

		if($deleted){
			return $id;
		}

	
	}

}