<?php

class AweberController extends ActionPHPRapid_Controller
{
	public function connect()
	{
		require_once( RAPID_APP_PATH . '../vendor/aweber_api/aweber_api.php' );
 
     try {
        
        $data = json_decode(json_encode($_POST));

        $auth = $data->aweber_auth;

	     // We'll use the method from AweberAPI to get an array containing the credentials we need to connect
	     $aweber_auth = AWeberAPI::getDataFromAweberID($auth);
	     
	     // Let's get the credentials from the $aweber_auth array
 
		$consumerKey = $aweber_auth[0];
		$consumerSecret = $aweber_auth[1];
		$accessKey = $aweber_auth[2];
		$accessSecret = $aweber_auth[3];
 
                //And now we will store the credentials 
		update_option('smartweber_aweber_consumerKey', $consumerKey); 
		update_option('smartweber_aweber_consumerSecret', $consumerSecret);
		update_option('smartweber_aweber_accessKey', $accessKey);
		update_option('smartweber_aweber_accessSecret', $accessSecret);
	
 		echo "success";
 		
	} catch(AWeberAPIException $exc) {
 
        print "<h3-->AWeberAPIException:";
        print "
		<ul>
			<li> Type: $exc-&gt;type              
		";
		        print "
		<ul>
			<li> Msg : $exc-&gt;message           
		";
		        print "</li>
			<li> Docs: $exc-&gt;documentation_url 
		";
		        print "
		 
		<hr />
		 
		";
 
	
 
   		}
 
	}
}