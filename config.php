<?php

if( !defined("SAE_TMP_PATH") ) //SAE_TMP_PATH 为sae的预定义变量，用来区分本地环境还是sae环境
{
	define(	"SRC_PATH" , 'http://lcs.com/sae/gtbcode/1/');
}
else
{
	define(	"SRC_PATH" , 'http://1.gtbcode.sinaapp.com/');
}