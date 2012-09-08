<?php
$basePath = dirname(__file__)."/../../";
include_once $basePath."class/BaseDao.php";
include_once $basePath."class/MyClientV2.php";
include_once dirname(__file__)."/table.php";

$uid = "2088440923";

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
$f = "Y-n-d G:i:s" ;
function getTime()
{
  return date("Y-n-d G:i:s");
}
$condition = "and `time` >= '".date($f , time()-300)."' and  `time` < '".date($f , time()+300) ."' and `done` = 0" ;

$m = new BaseModel();
$t = new Task();
//$m->printSQL = true;
$list = $m->getModelList($t,$condition);

if( $list == false )exit ;

foreach ( $list as $t )
{
	$client = new Client();
	$client->id = $t['eid'] ;
	$client = new MyClient($m->getOneModel($client));
	
        $text = "";
        if( strlen( $t['uid'] ) > 0 )
        {
            $host = $client->show_user_by_id($t['uid']);
            $text = "@".$host['screen_name']." ";
        }
        $text .= $t['text']  ;
	$ms  ;
	if( strlen($t['pic']) )
	{
		$ms = $client->upload($text, $t['pic']) ;
	}
	else 
	{
		$ms = $client->update($text);
	}
        
        if( isset($ms['error']) )
        {
        	print_r($ms);
        }
        else
        {
        	$task = new Task();
                $task->done = 1 ;
        	$m->update( $task , " and `id`='".$t['id']."'" );
                echo $t['id']."&";
        }
}


?>

