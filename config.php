<?php

//define( "WB_AKEY" , '2514193462' );
//define( "WB_SKEY" , '6a957336c809666320421b44307b8a28' );
//define( 'MY_DB_NAME', 'gelivable');

define( 'WB_AKEY' , '3600693014' );
define( 'WB_SKEY' , '22325d36c32bc46cb553e87afc1b01be' );
define( 'MY_DB_HOST' , 'lcs.com');
define( 'MY_DB_USER' , 'lcs');
define( 'MY_DB_PASS' , 'lcs');
define( 'MY_DB_NAME', 'gelivable');
define( 'MY_KV_FILE' , 'd:/KV/data.kv');
define(	'SRC_PATH' , 'http://gtbcode.lcs.io/');


function getScript($str){
	$path = SRC_PATH.'js/';
	foreach (preg_split('/,/', $str) as $js) {
		echo "<script src='{$path}${js}' ></script>";
	}
}

function getCss($str,$isTotal=true){
	$path = SRC_PATH.'css/';
	$files = preg_split("/,/" , $str);
	foreach( $files as $f ){
		echo "<link rel='stylesheet' href='$path$f' ></link>";
	}
}