<?php 
require_once 'vendor/autoload.php';
$hookUrl  = getenv('SLACK_WEBHOOK');
$settings = [
    'username' => 'AcomDeployBot',
    'channel' => '#test_comman_app',
];
$env = array(
    'dev' => array(
        'wordpress' => array('token' => '234dfs23fsdf' , 'job_name' => 'wordpress_blog')
    ),
    /*'uat' => array(
	'wordpress' => array('token' => '234dfs23fsdf' , 'job_name' => 'wordpress_blog')
    )*/
);
$client = new Maknz\Slack\Client($hookUrl, $settings);
$text = $_POST['text'];
$text = explode(' ', $text);
if(count($text) < 3) {
    if(!isset($env[$text[1]])) {
        $client->send('Unable to find environment.');
    } else if(!isset($env[$text[1]][$text[0]])) {
        $client->send('Unable to find project.');
    } else {
	$values = $env[$text[1]][$text[0]];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://mayurkathale.com:8080/job/'.$values["job_name"].'/buildWithParameters?token='.$values["token"].'&BRANCH='.$text[1]);
        $result = curl_exec($curl);
        curl_close($curl);
        $client->send('Deploying command');
    }
} else {
    $client->send('Wrong deploy command');
}

?>
