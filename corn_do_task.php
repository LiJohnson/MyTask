<?php
$basePath = dirname(__file__)."/../../";
include_once $basePath."class/BaseDao.php";
include_once $basePath."class/MyClientV2.php";
include_once $basePath ."class/MyTable.php";
include_once dirname(__file__)."/table.php";

$uid = "2088440923";

function getTime($offset=0){
	return date("Y-n-d G:i:s" , time() + $offset);
}

$condition = "and  `time` < '".getTime(60) ."' and `done` = 0" ;

$dao = new BaseDao("gelivable");
//$dao->printSQL = true;
$t = new Task();

$list = $dao->getModelList($t,$condition);

if( $list == false ){
	myLog($condition);
	exit ;
}

foreach ( $list as $t ){

	$user = new Users();
	$user->id = $t['eid'] ;
	$client = new MyClientV2($dao->getOneModel($user));

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
        
	if( isset($ms['error']) ){
		myLog($ms);
	}else{
		$task = new Task();
		$task->done = 1 ;
		$dao->update( $task , " and `id`='".$t['id']."'" );
		myLog( $t['id']."&");
	}
}

function myLog($log){
	$file = defined("SAE_MYSQL_DB") ? "saestor://wp/log/task.log" : "f:/task.log";
	$log =  var_export($log,1);
	echo $log;
	file_put_contents( $file , getTime() . ":\n" . $log. "\n" . substr( file_get_contents($file) , 0 , 1024*100 ) );
}

?>

