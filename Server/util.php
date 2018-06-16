<?php
require_once('DB/db.php');

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
	echo 'sessionKey:'.$sessionKey.'\n';
	echo 'endata:'.$endata.'\n';
	echo 'iv:'.$iv.'\n';
	echo 'skey:'.$skey.'\n';
#	$decryptdata = \openssl_decrpty(
#		base64_decode($endata),
#		'AES-128-CBC',
#		base64_decode($sessionKey),
#		OPENSSL_RAW_DATA,
#		base64_decode($iv)
#		);
#	$userInfo = json_decode($decryptdata);
	// 4. save to db
	// $db = new DB();
	// $db->storeUserInfo($userInfo,$skey, $session);
	//
	return [
			'loginState' =>1,
			'userInfo' => compact('userInfo','skey')
		];
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
		return [
			'loginState'=>0,
			'userInfo' => []
		];
	} else {
		// updata record to db
		return [
				'loginState'=>0,
				'userInfo' => []
			];
	}
}

function findUserBySKey($skey) {
// selct from db , skey
}

function getSessionKey($code) {
	$useQcloudProxy = false;
	if ($useQcloudProxy) {
		$secretId = 'wxc6fd09e5b3639c37';
		$secretKey = 'b8f65b1960f2b73269adf3c7dd0e1516';
		list($session_Key, $openid) = array_values(useQcProxyGetSessionKey($secrerId, $secretKey, $code));
		return $session_Key;
	} else {
		$appId = 'wxc6fd09e5b3639c37';
		$appSecret = 'b8f65b1960f2b73269adf3c7dd0e1516';
		list($session_Key, $openid) = array_values(GetSessionKeyDirect($appId, $appSecret, $code));	
		return $session_Key;
	}
}

function useQcProxyGetSessionKey($secretId, $secretKey, $code) {
	if (!isset($sercretId, $secretKey, $code)) {
		 echo "Proxy:param error";
	}
	$requestUrl = 'wss.api.qcloud.com/v2/index.php';
	$requsetMethod = 'GET';
	$requestData = [
		'Action' => 'GetSessionKey',
		'js.code' => $code,
		'Timestamp' => time(),
		'Nonce' => mt_rand(),
		'SercetId' => $secretId,
		'SignatureMethod' => 'HmacSHA256'
	];
	ksort($requestData);
	$requestString = http_build_query($requestData);
	$signatureRawString = $requestMethod . $requestUrl . '?' .$requestString;
	$requestData['Signature'] = base64_encode(hash_hmac('sha256',$signatureRawString, $secretKey, true));
	echo "requestData:".$requestData."\n";
	echo "signatureRawString:".$signatureRawString."\n";
#	list ($status, $body)=array_values(Request::get([
#		'url' => 'https://'$requestUrl.'?'.http_build_query($requestData),
#		'timeout' => 3000]));

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
		'js_code'=>$code,
		'grant_type'=>'authorization_code'
		];
	$param = http_build_query($requestParams);
	$url = 'https://api.weixin.qq.com/sns/jscode2session?';
	echo "direct url:".$url.$param ."\n";
	$sFile = file_get_contents($url.$param, false, null);  
#	list($status, $body) = array_valuse(Request::get([
#		'url'=>$url . http_build_query($requestParams),
#		'timeout'=>3000
#	]));
	echo "get contents:".$sFile ."\n";
	$status = 0;
	$body = [];
	if ($status !== 200 || $body || isset($body['errcode'])) {
		return json_encode($body);	
	}
	return $body;
}
?>
