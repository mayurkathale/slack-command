<?php 
require_once 'vendor/autoload.php';
$hookUrl  = getenv('SLACK_WEBHOOK');
$settings = [
    'username' => 'AcomDeployBot',
    'channel' => '#test_comman_app',
];
$token = '12345613777138';
$env = array(
    'dev' => array('ud' => array(),
        'lan' => array('token' => $token , 'branch_name' => 'dev', 'job_name' => 'KBright-DEV-ENVIRONMENT'),
        'kbt' => array('token' => $token , 'branch_name' => 'dev', 'job_name' => 'KBright-DEV-ENVIRONMENT'),
        'cpw' => array('token' => $token , 'branch_name' => 'dev', 'job_name' => 'KBright-DEV-ENVIRONMENT'),
        'biot' => array('token' => $token , 'branch_name' => 'dev', 'job_name' => 'KBright-DEV-ENVIRONMENT'),
        'ysl' => array('token' => $token , 'branch_name' => 'dev', 'job_name' => 'KBright-DEV-ENVIRONMENT'),
        'scg'=> array('token' => $token , 'branch_name' => 'dev', 'job_name' => 'KBright-DEV-ENVIRONMENT')
    ),
    'uat' => array('ud','lan','kbt','cpw', 'biot', 'ysl', 'scg')
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
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://dev-acomm-jenkins.acommerce.hosting:8080/job/KBright-DEV-ENVIRONMENT/buildWithParameters?token=12345613777138&BRANCH_NAME=dev');
        $result = curl_exec($curl);
        curl_close($curl);
        $client->send('aww! hit me baby one more time. '.json_encode($env[$text[1]][$text[0]]));
    }
} else {
    $client->send('Wrong deploy command');
}

?>
