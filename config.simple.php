<?php

define( 'WB_AKEY' , '******' );
define( 'WB_SKEY' , '******' );
define( 'MY_DB_HOST' , 'lcs.com');
define( 'MY_DB_USER' , 'lcs');
define( 'MY_DB_PASS' , 'lcs');
define( 'MY_DB_NAME', 'gelivable');
define( 'MY_KV_FILE' , '/tmp/data.kv');
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