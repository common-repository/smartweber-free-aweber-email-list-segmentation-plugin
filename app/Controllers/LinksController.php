<?php

include_once RAPID_APP_PATH . "Models/Link.php";


class LinksController extends ActionPHPRapid_Controller
{
	public function create()
	{
		$link = new Link;

		$data = file_get_contents("php://input");
		$data = json_decode($data);
		

		$name = trim($data->name);
		$url = trim($data->url);
		$list_id = trim($data->list_id);

		if(empty($name) || empty($list_id) || empty($url)){
			return;
		}

		$link->name = $name;
		$link->url = $url;
		$link->aweber_list = trim($data->list_id);
		$link->redirect_type = "copy";// trim($data->list_action);

		$link->code = $this->code($name.$url);

		$id = $link->save();

		echo $this->jsonResponse($link);
	}

	public function delete($id)
	{
		$link = new Link;
		$link->delete($id);
	}

	public function links()
	{
		$link = new Link;
		$links = $link->all();

		echo $this->jsonResponse($links);


	}

	public function update()
	{
		$link = new Link;
		$link->id = $id;
		$link->name = $name;
		$link->url = $url;

		$link->save();

	}

	public function code($salt="")
	{
		$time = microtime();

		$code = $salt . $time . uniqid();

		$code = md5($code);

		$code = substr($code, 0, 6);

		return $code;
	}
}