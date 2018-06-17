<?php
require_once('DB/db.php');
require_once('request.php');

function getHttpHeader($headerKey) {
	$headerKey = strtoupper($headerKey);
	$headerKey = str_replace('-','_', $headerKey);
	$headerKey = 'HTTP_' . $headerKey;
	return isset($_SERVER[$headerKey])?$_SERVER[$headerKey]:'';
}
class CLogin{
	public $body;
	public $bErr = false;
	public $session_key;
	public $openid;
	
	private function MsgToClient($id, $code) {
		return json_encode(['err:'=>$id, 'msg:'=>$code]);
	}
	public function Login($code, $endata, $iv) {
		// 1. getsession key
		$this->getSessionKey($code);
		$openid = '';
		$sessionKey = '';
		if (isset($this->body['errcode']) && isset($this->body['errmsg'])) {
			return $this->MsgToClient($this->body['errcode'], $this->body['errmsg']);
		} else if (isset($this->body['session_key']) && isset($this->body['openid'])){
			echo "sessionKey==empty =>".json_encode($this->body)."\n";
			$sessionKey = $this->body['session_key'];
			$openid = $this->body['openid'];
		} else {
			$this->MsgToClient(0, '404');
		}
		
		if (empty($sessionKey)) {
			$this->MsgToClient(0, 'get key error');
		}
		
		// 2. grant 3rd key(skey)
		$skey = sha1($sessionKey . mt_rand());
		// 3 decode data

		$decryptdata = openssl_decrpty(
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
		echo 
		'skey:'.$skey."\n" 
#		.'endata:'.$endata."\n"		
#		.'iv:'.$iv."\n"				
		.'sessionKey:'.$sessionKey."\n"			
#		.'session_Key:'.$this->body['session_key']."\n"	
#		.'this->openid:'.$this->body['openid']		
		. $userInfo
		 ;
		return [
				'loginState' =>1,
				'userInfo' => compact('userInfo','skey')
			];
	}

	private function checkLogin($skey) {
		$userInfo = $this->findUserBySKey($skey);
#		debug("find user by skey :".json_encode($userInfo));
		if ($userInfo == NULL) {
			return [
				'loginState'=>0,
				'userInfo' => []
			];
		}
#		debug("##############################");
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

	private function storeUserInfo($userInfo, $skey, $session_key) {
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

	private function findUserBySKey($skey) {
	// selct from db , skey
	}

	private function getSessionKey($code) {
		$useQcloudProxy = false;
		if ($useQcloudProxy) {
			$secretId = 'wxc6fd09e5b3639c37';
			$secretKey = 'b8f65b1960f2b73269adf3c7dd0e1516';
			list($this->session_key, $this->openid) = array_values($this->useQcProxyGetSessionKey($secrerId, $secretKey, $code));
			return $this->session_key;
		} else {
			$appId = 'wxc6fd09e5b3639c37';
			$appSecret = 'b8f65b1960f2b73269adf3c7dd0e1516';
			list($session_key, $openid) = array_values($this->GetSessionKeyDirect($appId, $appSecret, $code));	
			#echo 'array_values:'.json_encode($body);
			# list($this->session_key, $this->openid);
			#echo "this->session_key:".$this->session_key;
			return $session_key;
		}
	}

	private function useQcProxyGetSessionKey($secretId, $secretKey, $code) {
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
		list ($status, $this->body)=array_values(Request::get([
			'url' => 'https://'.$requestUrl.'?'.http_build_query($requestData),
			'timeout' => 3000]));

		if ($status !== 200 || !$this->body || $this->body['code'] !== 0)
			echo "Proxy :request error";
		if (isset($this->body['data']['errcode'])) 
			echo "Proxy:".json_encode($this->body['data']);
		return $this->body['data'];
	}
	private function GetSessionKeyDirect($appId, $appSecret, $code) {
		$requestParams = [
			'appid'=>$appId,
			'secret'=>$appSecret,
			'js_code'=>$code,
			'grant_type'=>'authorization_code'
			];
		$param = http_build_query($requestParams);
		$url = 'https://api.weixin.qq.com/sns/jscode2session?';
	#	echo "direct url:".$url.$param ."\n";
	#	$sFile = file_get_contents($url.$param, false, null);  
	#	echo "get contents:".$sFile ."\n";	
		
		list($status, $body) = array_values(Request::get([
			'url'=>$url . http_build_query($requestParams),
			'timeout'=>3000
			]));

	#	$ret = json_encode($sFile);
	#	echo "get contents:".$ret ."\n";

	#	echo 'GetSessionKeyDirect status=='.$status."\n";
	#	echo 'GetSessionKeyDirect body=='.$body['errcode']."\n";
		$this->body = $body;
	#	echo "GetSessionKeyDirect:".json_encode($this->body);
		if ($status !== 200 || $this->body || isset($this->body['errcode'])) {
			return json_encode($this->body);	
		}
		
		return $this->body;
	}
	/*
	// 用不上
	private function parseJson($json) {
		
		try{
			$is_json = json_decode($json);
			if (array_key_exists($is_json)) { 
				if (array_key_exists("errcode", $is_json)) {
					// some error
					return $json;
				} else if (array_key_exists("this->session_key", $is_json)) {
					
				}
			} else {
				return ['err'=>0, 'msg'=>'not json'];
			}
		}catch(Exception $e) {
			return ['err'=>0, 'msg'=>'not json'];
		}
	}
	*/
}
?>
