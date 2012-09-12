<?php
session_start();
header("Content-Type:text/html; charset=utf-8");

$basePath = dirname(__file__)."/../../";
include_once $basePath."class/MyLogin.php";
include_once $basePath."class/BaseDao.php";
include_once dirname(__file__)."/table.php";


$l = new MyLogin();
//$l->setDebug();
$eid = "";
$l->login();

$userInfo = $l->getUserInfo();

//var_dump($userInfo);
$eid =  $userInfo['id'];


if( strlen( $_POST['text']) )
{
		$m = new BaseDao("gelivable");
		$task = new Task();
		//$m->printSQL = true;
        
  //  echo "<pre>";print_r($l);
        
		$task->time = $_POST['time'];
		$task->text = $_POST['text'];
		$task->pic  = $_POST['pic'];
		$task->eid = $eid;
		if( $task->eid == null )
		{
			echo "erroe";
			session_unregister();
			session_destroy();
			exit;
		}
        $m->save($task);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="http://598420668pic-pic.stor.sinaapp.com/js/jquery.js"></script>
<script src="http://598420668pic-pic.stor.sinaapp.com/js/jquery-ui.js"></script>
<link href="http://598420668pic-pic.stor.sinaapp.com/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css">
<script>
  $(function(){
    
    var _time = parseInt(new Date().getTime()/60000) ; 
    $("input[name='time1']").datepicker( {dateFormat:"yy-mm-dd",onClose: function(dateText, inst) { $("#t").val(dateText+" "+$("input[name='time2']").val()); }});
    $("div#time").slider({
                    orientation: "horizontal",
                    range: "min",
                    max: 1440,
                    step:1,
                    value: 0,
                    slide: function(e){
                            var time =  _time + parseInt( $("div#time").slider("value"));
                            
                      
                      str = parseInt(time/60)%24+":"+time%60;
                      $("input[name='time2']").val(str);
                      
                      var _d = new Date(time*60000);
                      
                      $("#t").val( _d.getFullYear()+"-"+(_d.getMonth()+1) +"-"+ _d.getDate()+" "+_d.getHours()+":"+_d.getMinutes() );
                    }
    });
        
    $("input[id!='t']").change(function(){});
  });
</script>

  <body>
    <form method="post" >   
      内容:<input name='text'><br>
      图片:<input name='pic'><br>
      日期:<input name='time1' readonly>时间:<input name='time2' readonly><br><input id='t' name='time'>
      <div id='time'></div>
      <br>
        <input type='submit'>

    </form>
  </body>
</html>

