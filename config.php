<?php

if( !defined("SAE_TMP_PATH") ) //SAE_TMP_PATH 为sae的预定义变量，用来区分本地环境还是sae环境
{
	define(	"SRC_PATH" , 'http://lcs.com/sae/gtbcode/1/');
}
else
{
	define(	"SRC_PATH" , 'http://1.gtbcode.sinaapp.com/');
}

function getScript($str)
{
	$path = SRC_PATH.'load.php?c=1&type=js&load='.$str;
	echo "<script src='$path' ></script>";
}
function getCss($str,$isTotal=true)
{
	if(!$isTotal)return getCss2($str);
	
	$path = SRC_PATH.'load.php?c=1&type=css&load='.$str;
	echo "<link rel='stylesheet' href='$path' ></link>";
	
}
function getCss2($str)
{
	$path = SRC_PATH.'css/';
	$files = preg_split("/,/" , $str);
	foreach( $files as $f )
	{
		echo "<link rel='stylesheet' href='$path$f' ></link>";
	}
}

