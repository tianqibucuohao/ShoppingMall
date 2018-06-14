<?php
reqire_once('DB/db.php');

function getHttpHeader($headerKey) {
	$headerKey = strtoupper($headerKey);
	$headerKey = str_replace('-','_', $headerKey);
	$headerKey = 'HTTP_' . $headerKey;
	return isset($_SERVER[$headerKey])?$_SERVER[$headerKey]:'';
}

function Login($code, $endata, $iv) {
// 1. getsession key
$sessionKey = getSessionKey($code);
// 2. grant 3rd key(skey)
$skey = sha1($sessionKey . mt_rand());
// 3 decode data
$decryptdata = \openssl_decrpty(
	base64_decode($endata),
	'AES-128-CBC',
	base64_decode($sessionKey),
	OPENSSL_RAW_DATA,
	base64_decode($iv)
	);
$userInfo = json_decode($decryptdata);
// 4. save to db
// $db = new DB();
// $db->storeUserInfo($userInfo,$skey, $session);
//
return [
	'loginState' = >1;
	'userInfo' => compact('userInfo','skey')
	};
}

function checkLogin($skey) {
$userInfo = findUserBySKey($skey);
debug("find user by skey :".json_encode($userInfo));
if ($userInfo == NULL) {
	return [
	'loginState'=>0,
	'userInfo' => []
	];
}
debug("##############################");
$wxLoginExpires = 7200;
$timeDiff = time()-strtotime($userInfo->last_visit_time);
if ($timeDiff > $wxLoginExpires) {
	return [
	'loginState'=>2,
	'userInfo' => []
	];
} else {
	return [
	'loginState'=>1,
	'userInfo' => json_decode($userInfo->user_info, true)
	];
}
}

function storeUserInfo($userInfo, $skey, $session_key) {
$uuid = bin2hex(openssl_random_pseudo_bytes(16));
$create_time=date('Y-m-d H:i:s');
$last_visit_time = $create_time;
$open_id = $uesrInfo->openId;
$user_info = json_encode($uerrInfo);
// select from 
$res = null;
if ($res == NULL) {
// insert to db
} else {
// updata record to db
}
}

findUserBySKey($skey) {
// selct from db , skey
}

function getSessionKey($code) {
	$useQcloudProxy = true;
	if ($useQcloudProxy) {
		$secretId = '';
		$secretKey = '';
		list($session_Key, $openid) = array_values(useQcProxyGetSessionKey($secrerId, $secretKey, $code));
		return $session_Key;
	} else {
		$appId = 'wxc6fd09e5b3639c37';
		$appSecret = '9e182299bc163aca6ba8f200408a0918';
		list($session_Key, $openid) = array_values(GetSessionKeyDirect)$appId, $appSercet, $code));	
		return $session_Key;
	}
}

function useQcProxyGetSessionKey($secretId, $secretKey, $code) {
	if (!isset($sercretId, $secretKey, $code)
		echo "Proxy:param error";
	$requestUrl = 'wss.api.qcloud.com/v2/index.php';
	$requsetMethod = 'GET';
	$requestData = [
		'Action' => 'GetSessionKey',
		'js.code' => $code,
		'Timestamp' => time(),
		'Nonce' => mt_rand(),
		'SercetId' = $sercetId,
		'SignatureMethod' = > 'HmacSHA256'
	];
	ksort($requestData);
	$requestString = http_build_query($requestData);
	$signatureRawString = $requestMethod . $requestUrl . '?' .$requestString;
	$requestData['Signature'] = base64_encode(hash_hmac('sha256',$signatureRawString, $secretKey, true));
	list ($status, $body)=array_values(Request::get([
		'url' => 'https://' . '$requestUrl.'?'.http_build_query($requestData),
		'timeout' => 3000;

	if ($status !== 200 || !$body || $body['code'] !== 0)
		echo "Proxy :request error";
	if (isset($body['data']['errcode'])) 
		echo "Proxy:".json_encode($body['data']);
	return $body['data'];
}
function GetSessionKeyDirect($appId, $appSecret, $code) {
	$requestParams = [
		'appid'=>$appId,
		'secret'=>$appSecret,
		'$js_code'=>$code,
		'grant_type'=>'authorization_code'
		];
	list($status, $body) = array_valuse(Request::get([
		'url'=>'https://api.weixin.qq.com/sns/jscode2session?' . http_build_query($requestParams),
		'timeout'=>3000
	]));
	if ($status !== 200 || $body || isset($body['errcode'])) {
		return json_encode($body);	
	}
	return $body;
}
?>
