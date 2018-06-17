<?php
require_once('./util.php');

#$ret = file_get_content('php://input');
#echo $ret;
try {
	$code = getHttpHeader('code');
	$endata = getHttpHeader('x-encoder-data');
	$iv = getHttpHeader('x-iv');
#	echo 'code='.$code. "\n";
#	echo 'endata='.$endata. "\n";
#	echo 'iv='.$iv. "\n";
	if (empty($code) || empty($endata) || empty($iv)) {
		return ['err'=>0, 'msg'=>'param error'];
	}
	$loging = new CLogin();
	$rtn = $loging->Login($code, $endata, $iv);
	echo $rtn;
} catch(Exception $e) {
	return	[
	'loginStatus' => 1,
	'error' => $e->getMessage()
	];
}

?>
