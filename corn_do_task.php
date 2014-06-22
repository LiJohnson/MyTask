<?php
include 'config.php';

$basePath = dirname(__file__)."/../../";
include_once $basePath."class/BaseDao.php";
include_once $basePath."class/MyClientV2.php";
include_once dirname(__file__)."/table.php";

$uid = "2088440923";

function getTime($offset=0){
	return date("Y-n-d G:i:s" , time() + $offset);
}

$condition = "and  `time` < '".getTime(60) ."' and `done` = 0" ;

$taskDao = new BaseDao("gelivable");
$userDao = new BaseDao("gelivable");
$taskDao->setTable('task');
$userDao->setTable('users');


$list = $taskDao->getModelList(array(),$condition);

if( $list == false ){
	myLog($condition);
	exit ;
}

foreach ( $list as $t ){

	$user = $userDao->getOneModel(array('id' => $t['eid'] ));
	var_dump($user);
	if(!$user)continue;

	$client = new MyClientV2($user);

	$text = "";
	if( strlen( $t['uid'] ) > 0 ){
		$host = $client->show_user_by_id($t['uid']);
		$text = "@".$host['screen_name']." ";
	}

	$text .= $t['text']  ;

	if( strlen($t['pic']) ){
		$ms = $client->upload($text, $t['pic']) ;
	}else {
		$ms = $client->update($text);
	}
    
    $task = array();
	if( isset($ms['error']) ){
		myLog($ms);
		$task['done'] = 2 ;
	}else{
		$task['done'] = 1 ;
		myLog( $t['id']."&");
	}
	$taskDao->setDebug();
	$taskDao->update( $task , " `id`='".$t['id']."'" );
}

function myLog($log){
	$file = defined("SAE_MYSQL_DB") ? "saestor://wp/log/task.log" : "f:/task.log";
	$log =  var_export($log,1);
	echo $log;
	file_put_contents( $file , getTime() . ":\n\t" . $log. "\n" . substr( file_get_contents($file) , 0 , 1024*100 ) );
}

?>

