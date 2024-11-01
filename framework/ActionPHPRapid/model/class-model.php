<?php


//@todo refactor and make sure properties and methods are set properly
class ActionPHPRapid_Model
{
	private $__attributes = array();
	private $__new_attributes = array();
	protected $db;
	protected $id_field;
	protected $table;


	public function __construct()
	{	
		//We want to set up the database
		$this->db( $table );

	}

	public function table($table, $id_field='id')
	{
		$this->table = $table;

		//The field that represents the id of our model
		$this->id_field = $id_field;

		//Now will also set up the database for the table
		$this->db($table);
	}

	protected function db($table)
	{
		$this->db = new ActionPHPRapid_DB( $table );
	}

	//@todo complete the magic method setup
	public function __set($attr, $value)
	{	
		$this->set($attr, $value);
	}

	//@todo complete the magic method setup
	public function __get($attr)
	{
		return $this->get($attr);
	}

	public function find($id, $field='id')
	{	
		$_model = $this->db->find($id, $field);

		$this->__attributes = $_model;

		return  $_model;
	}

	public function attributes()
	{
		return $this->__attributes();
	}

	public function set($attr, $value)
	{
		$this->__new_attributes[$attr] = $value;
	}

	/**
	 * Gets the requested attrubute
	 * @param  mixed $attr the attribute we want to get
	 * @return mixed       the value of the requested attribute
	 */
	public function get($attr)
	{	
		if(isset($this->__attributes[$attr])){

			return $this->__attributes[$attr];
			
		}
	}


	public function all()
	{
		return $this->db->all();
	}

	/**
	 * Calls the database class and sends the attributes to be saved
	 * @return object returns an instance of the model
	 */
	public function save()
	{	
		if( empty( $this->__new_attributes)){
			return $this;
		}

		$model = $this->model()	;
		//Please notice that we are only saving newly added attributes
		$saved_model_id = $this->db->save($model);
		
		//print_r($saved_model_id); die();
		//Now, we will save the id field for our model, as well as set the id - in 
		// case it wasn't set already.
		$this->set( $this->id_field, $saved_model_id );

		//We will assign the values of new attributes to the old attributes 
		// array. We do it so that we can use them in the rest of our script.
		// At this point we're trusting that everything was saved correctly
		
		foreach ($this->__new_attributes as $key => $value) {
			
			$this->__attributes[$key] = $value;

		}

		return $this;

	}

	public function delete($id, $id_field="id")
	{
		 $this->db->delete($id, $id_field);
	}

	public function assign($property, $value)
	{
		$this->__attributes[$property] = $value;
	}

	public function model()
	{
		//So it's time to create our model
		$model['id_field'] = $this->id_field;

		$model['attributes'] = $this->__new_attributes;

		if(isset($this->__attributes[$this->id_field])){
			$model['attributes'][$this->id_field] = $this->__attributes[$this->id_field] ;
		}

		return $model;
	}
}