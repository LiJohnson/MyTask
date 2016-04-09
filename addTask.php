<?php
session_start();
include 'config.php';
header('Content-Type:text/html; charset=utf-8;');

$basePath = dirname(__file__);
include_once $basePath.'/lib/MyLogin.php';
include_once $basePath.'/lib/BaseDao.php';
include_once dirname(__file__).'/table.php';


$l = new MyLogin();
//$l->setDebug();
$eid = "";
$l->login();
//$l->updateClientInfo();
$userInfo = $l->getUserInfo();

//var_dump($userInfo);


?>
<!DOCTYPE html>
<html>
<head>
	<title>add Task</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
	getScript('jquery.js,bootstrap.js,jquery.plugin.js');
	getCss('bootstrap.min.css');
?>
<script>
  $(function(){
	var now = new Date()*1;now = now - now % 60000 + 60000;
	var setTime = function(offset){
		offset = (offset || 0)*1000;
		$("[name=time]").val( $.formatDate(now+offset) );
	};
	setTime(60);
	
	$("[type=range][time]").change(function(){
		setTime( this.value );
	});
	var $lat = $("[name=lat]");
	var $long = $("[name=long]");
	$("[type=range][lat]").change(function(){
		$lat.val(this.value);
	});
	$("[type=range][long]").change(function(){
		$long.val(this.value);
	});
	
	$(".btn[locate]").click(function(){
		navigator.geolocation.getCurrentPosition(function(g){
			g = g || {}; g = g.coords ||{};
			$lat.val(g.latitude);
			$long.val(g.longitude);
			$("[type=range][lat]").val(g.latitude);
			$("[type=range][long]").val(g.longitude);

		},function(e){
			$.box(e.message);
		});
		return false;
	});

	$("input:file").change(function() {
		$("input[name=pic]").val(this.value||"")
	});
  });
</script>
<style>
td[title]{text-align: right;}
tr td input{width:95%;}
tr:last-child td input{width:auto;}
.add-on input[type=range]{width: 200px;}
.pic{width: 90%;}
.pic .add-on{position: relative;overflow: hidden;cursor: pointer;}
.pic input[type=file]{position: absolute;top:0;left: 0;opacity: 0;}
</style>
</head>
  <body>
  	<div class=container >
    <form method="post" enctype="multipart/form-data">
		<table class='table table-bordered table-hovered' style='width:500px'>
			<tr><td> </td></tr>
			<tr><td title>用户:</td><td><?php echo $userInfo['name'] ;?></td></tr>
			<tr><td title>内容:</td><td><input name='text' type=text required ></td></tr>
			<tr><td title>图片:</td><td>
				<div class="input-append pic">
					<input name='pic' type='url' >
					<span class="add-on">
						<i class="icon-camera" >
							<input type='file' name='file' >
						</i>
					</span>
				</div>
			</td></tr>
			<tr><td title rowspan=2 >日期:</td><td><label class="checkbox inline" style="width: 100px;" ><input type=checkbox name=now value=now checked />立马发布</label><input name='time' type=text ></td></tr>
			<tr><td ><input type=range min=60 max=86400 step=60 value=60 time ></td></tr>
			<tr><td title rowspan=2 >位置:<br><button class='btn btn-inverse btn-mini' locate>定位</button> </td>
				<td>
				<div class="input-prepend input-append">
					<span class="add-on">
						纬度 <input type=range min=-90 max=90 step=0.000001 lat />
					</span>
					<input class="span2" type="text" name=lat >
				</div>
				</td>
			</tr>
			<tr>
				<td>
				<div class="input-prepend input-append">
					<span class="add-on">
						经度 <input type=range min=-180.00 max=180.00 step=0.000001 long />
					</span>
					<input class="span2" type="text" name=long >
					
				</div>
				
				</td>
			</tr>
			<tr><td></td><td><input type='submit' class='btn btn-primary'> </td></tr>

		</table>

		</form>
	</div>
</body>
</html>
<?php

$eid =  $userInfo['id'];

function getPic(){

	if( $_FILES['file']['tmp_name'] ){
		return $_FILES['file']['tmp_name'];
	}

	if( strlen($_POST['pic']) > 0 ){
		return $_POST['pic'];
	}

	return false;
}

if( strlen( $_POST['text']) )
{
		if( $_POST['now'] == 'now' )
		{
			$c = new MyClientV2();
			$pic = getPic();
			$res = 5;
			if( $pic )
			{
				$res = $c->upload($_POST['text'],$pic,$_POST['lat'] , $_POST['long']);
			}
			else
			{
				$res = $c->update($_POST['text'],$_POST['lat'] , $_POST['long']);
			}
			var_dump($res);
			echo $res['mid']?$res['mid']:'false';
		}
		else
		{
			$m = new BaseDao();
			$m->setTable('tod_task');
			$task['time'] = $_POST['time'];
			$task['text'] = $_POST['text'];
			$task['pic']  = $_POST['pic'];
			$task['eid'] = $eid;
			if( $task['eid'] == null )
			{
				echo "erroe";
				session_unregister();
				session_destroy();
				exit;
			}
			var_dump($m->save($task));
		}
}
?>
