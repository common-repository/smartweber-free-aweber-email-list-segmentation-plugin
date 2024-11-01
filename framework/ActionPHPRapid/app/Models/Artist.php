<?php

include "../model/class-model.php";

class Artist extends ActionPHPRapid_Model
{
	public function __construct()
	{
		$this->table('people');
	}
}