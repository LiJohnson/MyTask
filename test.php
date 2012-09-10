<?php
$basePath = dirname(__file__)."/../../";
include_once $basePath ."class/BaseDao.php";
include_once $basePath ."class/MyClientV2.php";
include_once $basePath ."class/MyTable.php";
include_once  dirname(__file__)."/table.php";

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

$mid = "3386118012024131";
$uid = "2088440923";
//echo "<textarea style='margin-left: 2px; margin-right: 2px; width: 1274px; margin-top: 2px; margin-bottom: 2px; height: 728px;' >";
$dao = new BaseDao("gelivable");
$dao->printSQL = 1;
$client = new Users();
$client->id ='2088440923' ;//'2511455374';        

$client = new MyClientV2($dao->getOneModel($client));

var_dump($client->comments_timeline());

exit;
$m = new BaseDao();
$records = $m->getModelList(new Record());
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

	$since_id = $since_id = getSinceCommentID($mid);
	echo "asd:$since_id<br>";
	$max_id = 0 ;

	for( $end = true ; $end ; )
	{
		$com = $client->get_comments_by_sid($mid , 1 ,10 );

		handleCommend($com , $since_id);

		$max_id = $com['since_id'];
		$end = $max_id > 0 ;
			
	}
	
	$com = $client->get_comments_by_sid($mid,1,1);
	$com = isset($com['comments'])? $com['comments'] : $com ;
	var_dump($com);
	updateCommentID( $mid , $com[0]['id'] );
}

function handleCommend( $com ,$since_id = null)
{
	//var_dump($com);
	if( isset($com['comments']) ) $com = $com['comments'] ;
	foreach( $com as $k => $v )
        {
        echo "$v[id] > $since_id<br>";
          if( $since_id != null && $v['id'] > $since_id )
          {
        	if( $v['user']['id'] != '1579303275' )continue;
        	addTask( $v ) ;
			//var_dump($v);
          }
        }
}

function addTask( $data )
{
	global $dao ;
	$text = $data['text'];
		
	//echo preg_match('/\./',$text) ."be<>";
		
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
	echo $cid."<br>";
	global $dao ;
	$record = new Record();
	$record->cid = $cid."" ;        
	return  $dao->update($record , "and `mid` = '" .$mid."'");
}

?>



<!--
Array
(
    [comments] => Array
        (
            [0] => Array
                (
                    [created_at] => Fri Dec 02 11:47:03 +0800 2011
                    [id] => 3386121013101184
                    [text] => 6666666666666666666666666
                    [source] => <a href="http://weibo.com" rel="nofollow">新浪微博</a>
                    [user] => Array
                        (
                            [id] => 1579303275
                            [idstr] => 1579303275
                            [screen_name] => _吉他男_
                            [name] => _吉他男_
                            [province] => 44
                            [city] => 6
                            [location] => 广东 佛山
                            [description] => .
                            [url] => http://blog.sina.com.cn/a5984
                            [profile_image_url] => http://tp4.sinaimg.cn/1579303275/50/5612227077/1
                            [profile_url] => lijohnson
                            [domain] => lijohnson
                            [weihao] => 
                            [gender] => m
                            [followers_count] => 428
                            [friends_count] => 174
                            [statuses_count] => 1757
                            [favourites_count] => 85
                            [created_at] => Sat Jul 03 00:00:00 +0800 2010
                            [following] => 
                            [allow_all_act_msg] => 
                            [geo_enabled] => 1
                            [verified] => 
                            [verified_type] => 220
                            [remark] => 
                            [allow_all_comment] => 1
                            [avatar_large] => http://tp4.sinaimg.cn/1579303275/180/5612227077/1
                            [verified_reason] => 
                            [follow_me] => 
                            [online_status] => 1
                            [bi_followers_count] => 71
                            [lang] => zh-cn
                        )

                    [mid] => 2121112029904445
                    [idstr] => 3386121013101184
                    [status] => Array
                        (
                            [created_at] => Fri Dec 02 11:35:08 +0800 2011
                            [id] => 3386118012024131
                            [mid] => 3386118012024131
                            [idstr] => 3386118012024131
                            [text] => //@--林大PPPPPPPP: 各位·亲···有木有什么好看的动漫啊~~~麻烦介绍介绍······谢谢啦``哈哈``(要那种人物画的好看型的)  ···· - 原文地址：http://t.cn/SLFH20
                            [source] => <a href="http://q.weibo.com/" rel="nofollow">新浪微群</a>
                            [favorited] => 
                            [truncated] => 
                            [in_reply_to_status_id] => 
                            [in_reply_to_user_id] => 
                            [in_reply_to_screen_name] => 
                            [thumbnail_pic] => http://ww4.sinaimg.cn/thumbnail/6e6d86aetw1dn6ym4hopuj.jpg
                            [bmiddle_pic] => http://ww4.sinaimg.cn/bmiddle/6e6d86aetw1dn6ym4hopuj.jpg
                            [original_pic] => http://ww4.sinaimg.cn/large/6e6d86aetw1dn6ym4hopuj.jpg
                            [geo] => 
                            [user] => Array
                                (
                                    [id] => 2088440923
                                    [idstr] => 2088440923
                                    [screen_name] => 吉他男的女秘书
                                    [name] => 吉他男的女秘书
                                    [province] => 44
                                    [city] => 6
                                    [location] => 广东 佛山
                                    [description] => I live in the world , but I am not real . 我虽然活在这个世界上，但却未曾真正的存在过。
                                    [url] => 
                                    [profile_image_url] => http://tp4.sinaimg.cn/2088440923/50/5617099978/0
                                    [profile_url] => pipanv
                                    [domain] => pipanv
                                    [weihao] => 
                                    [gender] => f
                                    [followers_count] => 909
                                    [friends_count] => 103
                                    [statuses_count] => 7239
                                    [favourites_count] => 3
                                    [created_at] => Fri Apr 15 00:00:00 +0800 2011
                                    [following] => 
                                    [allow_all_act_msg] => 
                                    [geo_enabled] => 1
                                    [verified] => 
                                    [verified_type] => -1
                                    [remark] => 
                                    [allow_all_comment] => 1
                                    [avatar_large] => http://tp4.sinaimg.cn/2088440923/180/5617099978/0
                                    [verified_reason] => 
                                    [follow_me] => 
                                    [online_status] => 1
                                    [bi_followers_count] => 91
                                    [lang] => zh-cn
                                )

                            [annotations] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 906972
                                            [appid] => 49
                                            [name] => 美女校花*模特空姐
                                            [title] => 美女校花*模特空姐
                                            [url] => http://q.weibo.com/906972?source=weibosource
                                            [skey] => 
                                            [server_ip] => 10.73.13.55
                                        )

                                )

                            [reposts_count] => 0
                            [comments_count] => 0
                            [mlevel] => 0
                            [visible] => Array
                                (
                                    [type] => 0
                                    [list_id] => 0
                                )

                        )

                )

            [1] => Array
                (
                    [created_at] => Fri Dec 02 11:43:39 +0800 2011
                    [id] => 3386120165727564
                    [text] => lcslcs
                    [source] => <a href="http://weibo.com" rel="nofollow">新浪微博</a>
                    [user] => Array
                        (
                            [id] => 1579303275
                            [idstr] => 1579303275
                            [screen_name] => _吉他男_
                            [name] => _吉他男_
                            [province] => 44
                            [city] => 6
                            [location] => 广东 佛山
                            [description] => .
                            [url] => http://blog.sina.com.cn/a5984
                            [profile_image_url] => http://tp4.sinaimg.cn/1579303275/50/5612227077/1
                            [profile_url] => lijohnson
                            [domain] => lijohnson
                            [weihao] => 
                            [gender] => m
                            [followers_count] => 428
                            [friends_count] => 174
                            [statuses_count] => 1757
                            [favourites_count] => 85
                            [created_at] => Sat Jul 03 00:00:00 +0800 2010
                            [following] => 
                            [allow_all_act_msg] => 
                            [geo_enabled] => 1
                            [verified] => 
                            [verified_type] => 220
                            [remark] => 
                            [allow_all_comment] => 1
                            [avatar_large] => http://tp4.sinaimg.cn/1579303275/180/5612227077/1
                            [verified_reason] => 
                            [follow_me] => 
                            [online_status] => 1
                            [bi_followers_count] => 71
                            [lang] => zh-cn
                        )

                    [mid] => 2121112029763229
                    [idstr] => 3386120165727564
                    [status] => Array
                        (
                            [created_at] => Fri Dec 02 11:35:08 +0800 2011
                            [id] => 3386118012024131
                            [mid] => 3386118012024131
                            [idstr] => 3386118012024131
                            [text] => //@--林大PPPPPPPP: 各位·亲···有木有什么好看的动漫啊~~~麻烦介绍介绍······谢谢啦``哈哈``(要那种人物画的好看型的)  ···· - 原文地址：http://t.cn/SLFH20
                            [source] => <a href="http://q.weibo.com/" rel="nofollow">新浪微群</a>
                            [favorited] => 
                            [truncated] => 
                            [in_reply_to_status_id] => 
                            [in_reply_to_user_id] => 
                            [in_reply_to_screen_name] => 
                            [thumbnail_pic] => http://ww4.sinaimg.cn/thumbnail/6e6d86aetw1dn6ym4hopuj.jpg
                            [bmiddle_pic] => http://ww4.sinaimg.cn/bmiddle/6e6d86aetw1dn6ym4hopuj.jpg
                            [original_pic] => http://ww4.sinaimg.cn/large/6e6d86aetw1dn6ym4hopuj.jpg
                            [geo] => 
                            [user] => Array
                                (
                                    [id] => 2088440923
                                    [idstr] => 2088440923
                                    [screen_name] => 吉他男的女秘书
                                    [name] => 吉他男的女秘书
                                    [province] => 44
                                    [city] => 6
                                    [location] => 广东 佛山
                                    [description] => I live in the world , but I am not real . 我虽然活在这个世界上，但却未曾真正的存在过。
                                    [url] => 
                                    [profile_image_url] => http://tp4.sinaimg.cn/2088440923/50/5617099978/0
                                    [profile_url] => pipanv
                                    [domain] => pipanv
                                    [weihao] => 
                                    [gender] => f
                                    [followers_count] => 909
                                    [friends_count] => 103
                                    [statuses_count] => 7239
                                    [favourites_count] => 3
                                    [created_at] => Fri Apr 15 00:00:00 +0800 2011
                                    [following] => 
                                    [allow_all_act_msg] => 
                                    [geo_enabled] => 1
                                    [verified] => 
                                    [verified_type] => -1
                                    [remark] => 
                                    [allow_all_comment] => 1
                                    [avatar_large] => http://tp4.sinaimg.cn/2088440923/180/5617099978/0
                                    [verified_reason] => 
                                    [follow_me] => 
                                    [online_status] => 1
                                    [bi_followers_count] => 91
                                    [lang] => zh-cn
                                )

                            [annotations] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 906972
                                            [appid] => 49
                                            [name] => 美女校花*模特空姐
                                            [title] => 美女校花*模特空姐
                                            [url] => http://q.weibo.com/906972?source=weibosource
                                            [skey] => 
                                            [server_ip] => 10.73.13.55
                                        )

                                )

                            [reposts_count] => 0
                            [comments_count] => 0
                            [mlevel] => 0
                            [visible] => Array
                                (
                                    [type] => 0
                                    [list_id] => 0
                                )

                        )

                )

        )

    [hasvisible] => 
    [previous_cursor] => 0
    [next_cursor] => 3386120081829796
    [total_number] => 3
)


-->