<?php

class PHPClan_Subscriber
{	
	public $list;
	public $email;
	public $subscriber_id;

	public function __construct(PHPClan_Aweber $aweber)
	{
		$this->aweber = $aweber->aweber;
		
		$this->account = $aweber->account;
		
	}

	public function move()
	{
		$subscriber = $this->subscriber = $this->find();

		if(!$subscriber){
			return;
		}

		$list = $this->get_list();
		
		try{
			
			$subscriber->move($list);

		}catch(AWeberAPIException $exc){
			
			//We want to catch the exception - but we won't do anything.
			//That's because we don't want to stop the redirection
		}
		
	}

	public function copy()
	{
		$this->move();

		if(!$this->subscriber){
			return;
		}
		
		$this->resubscribe();
	}
	
	public function find($id=false)
	{
		if($id){

		} else {
			$params = array(
					"email" => $this->email
				);
		}

		$subscribers = $this->account->findSubscribers($params);
		$subscriber = $subscribers[0];

		if($subscriber){

			$this->subscriber_id = $subscriber->id;
			$this->subscriber = $subscriber;
		}

		return $subscriber;

	}
	
	public function resubscribe()
	{
		$params = array(

	    		"subscriber_id" => $this->subscriber_id,
	    		//"email" => $this->email
	    	);
		
	    /*$subscribers = $this->account->findSubscribers($params);

	  	$subscriber = $subscribers[0];*/
	   	$this->subscriber->status = "subscribed";
	   	$this->subscriber->save();


		
	}

	public function get_list()
	{
		$found_lists = $this->account->lists->find(array( 'name' => $this->list )); 
	    $destination_list = $found_lists[0];

	    return $destination_list;
	}
}