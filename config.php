<?php
if( !defined("SAE_TMP_PATH") ) //SAE_TMP_PATH 为sae的预定义变量，用来区分本地环境还是sae环境
{
	// app name ： developing
	/**/
	define( "WB_AKEY" , '3600693014' );
	define( "WB_SKEY" , '22325d36c32bc46cb553e87afc1b01be' );
	define(	"SRC_PATH" , 'http://lcs.com/sae/gtbcode/1/') ;

	define( "MY_DB_HOST" , "lcs.com");
	define( "MY_DB_USER" , "lcs");
	define( "MY_DB_PASS" , "lcs");

	define( 'MY_DB_NAME', 'gelivable');

	define( 'MY_KV_FILE' , 'd:/KV/data.kv');

}
else
{
	
	//app name : 给力
	/**/
	define( "WB_AKEY" , '522446840' );
	define( "WB_SKEY" , '3c86c51f3095b49d97b08f00c85cad23' );
	/*
	/*app name : theotherdoor*
	define( "WB_AKEY" , '2514193462' );
	define( "WB_SKEY" , '6a957336c809666320421b44307b8a28' );
	/**/
	define(	"SRC_PATH" , 'http://1.gtbcode.sinaapp.com/');
	define( 'MY_DB_NAME', 'gelivable');
}

function getScript($str)
{
	$path = SRC_PATH.'load.php?c=1&type=js&load='.$str;
	echo "<script src='$path' ></script>";
}
function getCss($str,$isTotal=true)
{
	$path = SRC_PATH.'css/';
	$files = preg_split("/,/" , $str);
	foreach( $files as $f )
	{
		echo "<link rel='stylesheet' href='$path$f' ></link>";
	}
	
}

if( !defined("SAE_TMP_PATH") ) //SAE_TMP_PATH 为sae的预定义变量，用来区分本地环境还是sae环境
{
	define(	"SRC_PATH" , 'http://lcs.com/sae/gtbcode/1/');
}
else
{
	define(	"SRC_PATH" , 'http://1.gtbcode.sinaapp.com/');
}