<?php
$basePath = dirname(__file__)."/../../";
include_once $basePath ."class/BaseDao.php";
include_once $basePath ."class/MyClientV2.php";
include_once $basePath ."class/MyTable.php";
include_once  dirname(__file__)."/table.php";

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$dao = new BaseDao("gelivable");
//$dao->printSQL = 1;



$records = $dao->getModelList(new Record());
foreach( $records as $r )
{
	main($r['uid'] , $r['mid']);
}

?>


<?php

function main( $uid , $mid )
{
	global $dao ;
	$client = new Users();
	$client->id = $uid ;///'2511455374';//'2088440923';        
	
	$client = new MyClientV2($dao->getOneModel($client));
	
	$tmp = $client->show_user_by_id($uid);
	if(isset($tmp['error']))return;
	
	$since_id = $since_id = getSinceCommentID($mid);
	

	$page = 1 ;
	$end = true;
 
	while($end  )
	{
		$com = $client->get_comments_by_sid($mid , $page ,10 );

		$end = $com['next_cursor'] != 0 && handleCommend($com , $since_id);
		$page++;
	}
	$com = $client->get_comments_by_sid($mid,1,1);
	$com = isset($com['comments'])? $com['comments'] : $com ;
	//var_dump($com);
	updateCommentID( $mid , $com[0]['id'] );
}

function handleCommend( $com ,$since_id = null)
{
	if( isset($com['comments']) ) $com = $com['comments'] ;
	
	foreach( $com as $k => $v )
	{
		//echo "$v[id] > $since_id<br>";
		if( $since_id == null || $v['id'] <= $since_id )
			return false;
		if( $v['user']['id'] != '1579303275' )
			continue;
		echo $v[id]."<br>";
		addTask( $v ) ;
	}
	return true;
}

function addTask( $data )
{
	global $dao ;
	$text = $data['text'];
		
	if( preg_match('/^[\d][^\.]*\./' , $text , $date) > 0 )
	{
		$date = substr($date[0] ,0,strlen($date[0])-1);	
		
		$task = new Task();
		$task->time = $date;
		$task->uid = $data['user']['id'];
		$task->eid = $data['status']['user']['id'];
		
		$task->text = substr($data['text'] ,strlen($date)+1);
		
		$dao->save($task);
	}
}

function getSinceCommentID( $mid )
{
	global $dao ;
	$record = new Record();
	$record->mid = $mid;
	$ms = $dao->getOneModel( $record );
	return $ms['cid'];
}

function updateCommentID( $mid , $cid )
{
	//echo $cid."<br>";
	global $dao ;
	$record = new Record();
	$record->cid = $cid."" ;        
	return  $dao->update($record , "and `mid` = '" .$mid."'");
}

?>


