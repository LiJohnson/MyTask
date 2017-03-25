<?php
$basePath = dirname(__file__);

function jsonResult($data){
	echo json_encode($data);
	exit();
}
if( $_POST['action'] ){
	session_start();
	include 'config.php';
	include_once $basePath.'/lib/MyClientV2.php';
	$client =  new MyClientV2($_POST);
}

if( $_POST['action'] == 'by_me' ){
	jsonResult($client->comments_by_me());
}else if( $_POST['action'] == 'to_me' ){
	jsonResult($client->comments_to_me());
}else if( $_POST['action'] == 'reply' ){
	jsonResult($client->reply($_POST['sid'],$_POST['text'],$_POST['cid']));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>weibo</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="114x114" href="icon.png" />
	<link rel="icon" sizes="114x114" href="icon.png" />

	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row ">
			<table class="table table-bordered table-hovered ">
				<thead>
					<tr>
					<th colspan="3">
						<a class="btn btn-default" data-comment="to_me">to_me</a>
  						<a class="btn btn-default" data-comment="by_me">by_me</a>
			      </th>
			    </tr>
			  </thead>
				<tbody class="comment-list" ></tbody>
			</table>
		</div>
	</div>

	<div class="modal fade reply-dialog" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">reply:<sub></sub></h4>
	      </div>
	      <div class="modal-body">
	      <div class="form-group">
		    <input type="text" class="form-control" name="text" placeholder="reply..."/>
		  </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary reply">reply</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script type="text/javascript">
		$(function(){
			const $list = $('.comment-list');
			const $dialog = $('.reply-dialog')
			const post = function(action,param){
				param = param || {};
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
			const render = function( comment ){
				return `<tr><td class='col-sm-2'>
							${comment.user.name}
							</td>
							<td class='col-sm-7'>
							${comment.text}
							</td>
							<td class='col-sm-3' >
							<a class='btn btn-info reply'
								data-sid='${comment.status.idstr}'
								data-cid='${comment.idstr}'
								data-text='${comment.text}'
							>reply</a>
							</td>
						</tr>
				`;
			};
			const load = function(type){
				$list.html('loading...');
				post(type).done( data => {
					$list.empty();
					data.comments.forEach( comment =>{
						$list.append( render(comment) );
					});
				});
			};
			$list.on('click','.reply',function(){
				let data = $(this).data();
				$dialog.modal('show');
				$dialog.find('input').val('');
				$dialog.find('sub').html(data.text);
				$dialog.find('.btn.reply').off('click').on('click',function(){
					data.text = $dialog.find('input').val();
					data.text && post('reply',data).done(comment=>{
						load('by_me');
					});
					$dialog.modal('hide');
				});

			});
			$('[data-comment]').click(function(){
				load($(this).data('comment'));
			});
			load('to_me');
		});
	</script>
</body>
</html>