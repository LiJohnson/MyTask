<?php
$basePath = dirname(__file__);
include 'config.php';
if( $action = $_POST['action'] ){
	session_start();
	include_once $basePath.'/lib/MyClientV2.php';

	if( $action == 'name' ){
		$client =  new MyClientV2($_POST);
		$_SESSION['token'] = $_POST;
		echo json_encode($client->getUserInfo());
	}else if( $action == 'clean' ){
		 $_SESSION['token'] = NULL;
	}
	else{
		$client =  new MyClientV2();

		if( $_FILES['pic']['tmp_name'] ){
			$res = $client->upload($_POST['text'], $_FILES['pic']['tmp_name'] , $_POST['lat'] , $_POST['long']);
		}
		else{
			$res = $client->update($_POST['text'],$_POST['lat'] , $_POST['long']);
		}
		$res['user'] = NULL;
		var_dump($res);
		echo "<a href=''>back</a>";
	}

	exit;
}

if( $_GET['login'] ){
	include_once $basePath.'/lib/MyLogin.php';
	$l = new MyLogin();
	$l->login();
	$token = json_encode($_SESSION['token']);
	echo "<script> localStorage.wbToken = '$token'; location.href='?' </script>";
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>weibo</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
					<form method="post" enctype="multipart/form-data" class="form-horizontal">
						<div class="form-group">
							<label for="user" class="col-sm-2 control-label">user</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" readonly="readonly" id="user" name="user" />
							</div>
						</div>
						<div class="form-group">
							<label for="text" class="col-sm-2 control-label">text</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="text" placeholder="text" name="text" required="required" />
							</div>
						</div>
						<div class="form-group">
							<label for="pic" class="col-sm-2 control-label">pic</label>
							<div class="col-sm-10">
								<input type="file" class="form-control" id="pic" name="pic" />
								<img src=""/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">location</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="range" class="form-control" name="lat" min="-90" max="90" step="0.000001" />
									<span class="input-group-addon"></span>
								</div>
								<div class="input-group">
									<input type="range" class="form-control" name="long"  min="-180" max="180" step="0.000001" />
									<span class="input-group-addon"></span>
								</div>
								<a class="btn location glyphicon glyphicon-map-marker"></a>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-2 ">
								<input type='submit' class='btn btn-primary' name="action" value="post" />
								<a href="" class="btn btn-danger clean">clean</a>
							</div>
						</div>
					</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			var post = function(action,param){
				if( $.type(param) == 'array' ){
					param.push({
						name:'action',
						value:action
					});
				}else{
					param.action = action;
				}
				return $.post('',param,function(){},'json');
			};
			$('form').on('change',':file',function(){
				$(this).siblings('img').prop('src',URL.createObjectURL(this.files[0]));
			}).on('change','[type=range]',function(){
				$(this).siblings('span').text(this.value);
			}).on('click','.btn.location',function(){
				navigator.geolocation.getCurrentPosition(function(g){
					alert(g);
				},function(e){
					alert(e);
				});
				return false;
			}).on('submit',function(){
			}).on('click','.btn.clean',function(){
				if(!confirm('sure?'))return false;
				localStorage.wbToken = '';
				post('clean',{});
				return false;
			});

			var token = JSON.parse( localStorage.wbToken || '{}' );
			post('name',token).success(function(data){
				$('input[name=user]').val(data.name);
			}).error(function(){
				confirm('login') && (location.href='?login=login');
			});
		});
	</script>
</body>
</html>