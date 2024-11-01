<?php 

class ActionPHPRapid_Controller
{
	public function jsonResponse($value)
	{

		if(is_a($value, 'ActionPHPRapid_Model')){
			$value = $this->obj2array($value);

			$value = $value['__attributes'];
			
		}
		return json_encode($value);
	}

	private function obj2array ( &$Instance ) {
    $clone = (array) $Instance;
    $rtn = array ();
    $rtn['___SOURCE_KEYS_'] = $clone;

    while ( list ($key, $value) = each ($clone) ) {
        $aux = explode ("\0", $key);
        $newkey = $aux[count($aux)-1];
        $rtn[$newkey] = &$rtn['___SOURCE_KEYS_'][$key];
    }

    return $rtn;
	}
}