<?php

include dirname(__FILE__) . "/../Models/Artist.php";

class ArtistController
{
	public function show($id)
	{	
		if (!extension_loaded('imagick'))    echo 'imagick not installed';

		echo "string";
//$image = new Imagick();
		
	}
}