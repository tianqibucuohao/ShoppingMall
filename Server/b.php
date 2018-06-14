<?php
require_once('./util.php');
#$ret = file_get_content('php://input');
#echo $ret;
try {

	$code = getHttpHeader('code');
	$endata = getHttpHeader('x-encoder-data');
	$iv = getHttpHeader('x-iv');
	echo $code;
} catch(Exception $e) {
	return	[
	'loginStatus' => 1,
	'error' => $e->getMessage()
	];
}

?>
