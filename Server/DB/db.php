<?php

class DB {
protected $dbhost;
protected $dbuser;
protected $dbpwd;
protected $dbname;
protected $dbh;
function __construct(){
	$this->dbhost = "192.168.199.100";
	$this->dbuser = "zyy";
	$this->dbpwd = "jb12cm";
	$this->dbname = "testdb";
	$this->db_connect();
}
function __destruct(){
	if ($this->dbh)
		mysql_close($this->dbh);	
}
public function db_connect() {
	$this->dbh =  mysql_connect($this->dbhost, $this->dbuser, $this->dbpwd);
	if ($this->dbh)
		mysql_select_db($this->dbname, $this->dbh);
	else
		echo "db_connect error:" . mysql_error();
}

/*
 执行sql
 返回当前result set 
*/
public function Query($sql){
	$arry=array();
	if (!$this->dbh)
	{
		echo "conn is over<br />";
		return "disconn";
	}
	$result = mysql_query($sql,$this->dbh);
#	echo $sql;
	if ($result){
		$i=0;
		while ($rol=mysql_fetch_array($result))
		{
			array_push($arry,$rol);
			$i++;
		}	

	}
	return $arry;
}

/*
查询当前推荐分类
*/
public function GetAdSort(){
	$sql = "select s_id,s_name, s_path from sorttbl where s_bAd = 1";
	$arry = array();
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}
/*
查询当前推荐商品
*/
public function GetAdGoods() {
	$sql = "select a.g_id, a.g_name, a.g_price, a.g_discountid, a.g_desc ,a.g_pricetype, b.i_name from goodstbl a, imgpathtbl b where a.g_bAd=1 and a.g_id = b.gs_id group by b.gs_id";
	$arr = array();
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}
/*
查询当前小分类
*/
public function GetLittleSort($id) {
	$arry = array();
	if (!is_numeric($id))
		return $arry;
	$sql = "select a.g_id, a.g_name, a.g_price,a.g_sortid,a.g_discountid, a.g_desc,a.g_pricetype, b.i_name from goodstbl a, imgpathtbl b  where a.g_sortid =" . $id ." and a.g_id = b.gs_id group by b.gs_id";
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}
/*
查询当前商品的图片资源路径
*/
public function GetGoodsImgpath($id) {
	$arry = array();
	if (!is_numeric($id))
		return $arry;
	$sql = "select i_id, i_name from imgpathtbl where gs_id=".$id;
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}
/*
查询所有商品分类
*/
public function GetAllSorts() {
	$arry = array();
	$sql = "select s_id, s_name, s_path, s_bAd from sorttbl";
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}
/*
查询所有商品
*/
public function GetAllGoods() {
	$arry = array();
	$sql = "select a.g_id, a.g_name, a.g_price, a.g_sortid, a.g_discountid, a.g_desc, a.g_pricetype,a.g_bAd, b.i_name from goodstbl a, imgpathtbl b where a.g_id = b.gs_id group by b.gs_id";
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}
/*
增加新订单
*/
public function NewBills($ukey, $count, $sum, $ddate){
	$sts = 1;
	$note = "";
	$arr = array();
	if ($ukey == "") {
		return $arr;
	}
	
	$ins = "insert into billlisttbl(b_uuid, b_count, b_total, b_status, b_datetime, b_note, b_dnow) value('%s',%d, %.2f, %d, '%s', '%s', now());";
	$sql = sprintf($ins, $ukey, $count, $sum, $sts, $ddate, $note);
	if (!$this->dbh){
		return ['err'=>"error"];
	}
	$this->Query($sql);
	$sql = "select b_id from billlisttbl where b_uuid='".$ukey."'";
#	echo $sql;
	$res = $this->Query($sql);
	return $res;
}
/*
增加订单内所有内容
*/
public function BillsDetail($arrSql) {
	$ary = array();	
	if ($arrSql == ""){
		return $ary;
	}
	if (!$this->dbh) {
		return $ary;
	}
	$this->Query($arrSql);
}
/*
取消当前订单
*/
public function CnlBills($id, $ukey){
	if ($id == -1 || $id == 0) {
		return MsgToClient(0, 'idx errot');
	}
	$upd = "update billlisttbl set b_status=%d where b_uuid='%s'";
	$sql = sprintf($upd, $id, $ukey);
	if (!$this->dbh){
		return MsgToClient(0, 'data error');
	}
	$this->Query($sql);
}
/*
查询当前用户的历史订单
*/
public function GetHistoryAllBills($ukey, $strDate, $endDate) {
	# 取回所有成功订单id ，某时段内
	# 成功取消的订单不查询
	if ($ukey == "") {
		return MsgToClient(0, 'can`t find history');
	}
	$ins = "";
	$sql = "";
	if ($strDate == "" || $endDate == "") {
		#所有
		$ins = "select b_id, b_count, b_total, b_note from billlisttbl where b_status = 1 and b_uuid='%s'";
		$sql = sprintf($ins, $ukey);
	}else {
		#时段内 - 不会写
		$ins = "";
	}
	if (!$this->dbh) {
		return MsgToClient(0, 'data error');
	}
	$res = $this->Query($sql);
	return $res;
}
/*
查询当前用户的当前历史订单所有内容
*/
public function GetHistoryAllBillsDetail($bid) {
	#取回详细订单内容
	$ary = array();
	if ($ukey == "") {
		return MsgToClient(0, 'can`t find history');
	}
	
	if (!$this->dbh) {
		return MsgToClient(0, 'data error');
	}
	$ins = "select g_id, g_name, g_count, g_price, g_pricetype, g_total from order_details where b_id = %d";
	$sql = sprintf($ins, $bid);
	$res = $this->Query($sql);
	return $res;
}
/*
返回内容格式
*/
protected function MsgToClient($id, $msg){
	return json_decode(['err'=>$id, 'msg'=>$msg]);
}

/*
检查当前用户key有效性
*/
public function CheckSessionKey() {
	$sql = "select count(*) from userstlb where openid ='" .$openid. "'";
	if (!$this->dbh) {
		return "error";	
	}
	$res= $this->Query($sql);
	$bret = false;
	if (count($res) != 0) {
		// 已存在key
	} else {
		// 不存在key
	}
	
}
/*
保存当前用户key
*/
public function storeUserInfo($nickname, $skey, $sessionKey, $openid) {
	//$uuid = bin2hex(openssl_random_pseudo_bytes(16));
#	$create_time = date('Y-m-d H:i:s');
#	$last_visit_time = $create_time;
	$sql = "select count(*) from userstlb where openid ='" .$openid. "'";
#	echo "storeUserInfo:". $sql;
	if (!$this->dbh) {
		return "error";	
	}
	$res = $this->Query($sql);
	$bret = false;
	if (count($res)!=0) {
		$bret = true;
	}
	
	$sql = "";
	if ($bret) {
		$fmt = "insert into userstbl(u_nickname, agree_time, is_vip, openid, sessionKey, randKey, last_visit_time) value('%s', now(), 0, '%s', '%s', '%s', now())";
		$sql = sprintf($fmt, $nickname, $openid, $sessionKey, $skey);
	} else {
		$fmt = "update userstbl set u_nickname='%s', randKey = '%s', last_visit_time=now(), sessionKey='%s' where openid = '%s'";
		$sql = sprintf($fmt, $nickname, $skey, $sessionKey, $openid);
	}
	#echo "storeUserInfo2:". $sql;
	$res = $this->Query($sql);
	return $res;
}


};
?>


