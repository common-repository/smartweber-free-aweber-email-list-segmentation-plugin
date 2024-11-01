<?php
 require_once RAPID_APP_PATH . '../vendor/aweber_api/aweber_api.php'  ;

class PHPClan_Aweber
{
	public $consumerKey;
	public $consumerSecret;
	public $accessKey;
	public $accessSecret;

	public function aweber()
	{
		$this->aweber = new AWeberAPI($this->consumerKey, $this->consumerSecret);

		$this->account = $this->aweber->getAccount($this->accessKey, $this->accessSecret);
	}

	public function lists()
	{
		$_lists = $this->account->lists;

		$lists = array();

		foreach ($_lists->data['entries'] as $list) {
			
			$_list = new stdClass();
			$_list->list_id = $list["unique_list_id"];
			$_list->name = $list["name"];

			$lists[] = $_list;
						

		}

		return $lists;
	}

}