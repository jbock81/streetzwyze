<?php
/*include_once("clsCashVaultAPI.php");
$cv=new CashVaultAPI();
var_dump($cv->getTwitterFollowers());
*/
require_once("Abraham/autoload.php");
require_once("Abraham/TwitterOAuth/Config.php");
require_once("Abraham/TwitterOAuth/Util.php");
require_once("Abraham/TwitterOAuth/Util/JsonDecoder.php");
require_once("Abraham/TwitterOAuth/Request.php");
require_once("Abraham/TwitterOAuth/Token.php"); 
require_once("Abraham/TwitterOAuth/Consumer.php");
require_once("Abraham/TwitterOAuth/SignatureMethod.php");
require_once("Abraham/TwitterOAuth/HmacSha1.php");
require_once("Abraham/TwitterOAuth/Response.php"); 
require_once("Abraham/TwitterOAuth/TwitterOAuth.php"); 
use Abraham\TwitterOAuth\TwitterOAuth;
$connection = new TwitterOAuth('kUGSRIOtauftl3qTaqKS5MgR0', '8Rk71bnTu4yzoZZZxI6ytgXy4MYZjrbPoxOMlQ1QgomrUivXdx', '3055919992-aVCkoS3R5w9955muHKcOzszzQGD0QZ11BdncKLB', 'PdZ7QKtyi5DxZq2kZMcxQ5XJkuXyCkqMd83S777G7WH1t');
// Empty array that will be used to store followers.
$profiles = array();
// Get the ids of all followers.
$ids = $connection->get('followers/ids');
// Chunk the ids in to arrays of 100.
$ids_arrays = array_chunk($ids->ids, 100);
// Loop through each array of 100 ids.
foreach($ids_arrays as $implode) {
  // Perform a lookup for each chunk of 100 ids.
  $results = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
  // Loop through each profile result.
  foreach($results as $profile) {
    // Use screen_name as key for $profiles array.
    $profiles[$profile->id] = $profile->name;
  }
}
// Array of user objects.
echo "<pre>";
print_r($profiles);
echo "</pre>";

$params=array(
			'text' => 'helloworld',
			'user_id' =>86078941
		);
		
		//$msg=$connection->post('direct_messages/new',$params);
		//var_dump($msg);
?>