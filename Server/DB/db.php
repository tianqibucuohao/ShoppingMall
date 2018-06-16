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
//	else
//		echo "disconn";
}
public function db_connect() {
	$this->dbh =  mysql_connect($this->dbhost, $this->dbuser, $this->dbpwd);
	if ($this->dbh)
		mysql_select_db($this->dbname, $this->dbh);
	else
		echo "db_connect error:" . mysql_error();
}

public function Query($sql){
	$arry=array();
	if (!$this->dbh)
	{
		echo "conn is over<br />";
		return "disconn";
	}
//	else 
//		echo "conn is alive<br />";
	$result = mysql_query($sql,$this->dbh);
#	echo $sql;
	if ($result){
		$i=0;
		while ($rol=mysql_fetch_array($result))
		{
			array_push($arry,$rol);
			$i++;
		}	

//		echo "<br />not null, array.size=". count($arry);
	}
#	else { 
#		echo "null";
#	}
//	echo "<br />########<br />";
	return $arry;
}

public function GetAdSort(){
	$sql = "select s_id,s_name, s_path from sorttbl where s_bAd = 1";
	$arry = array();
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}

public function GetAdGoods() {
	$sql = "select a.g_id, a.g_name, a.g_price, a.g_discountid, a.g_desc ,a.g_pricetype, b.i_name from goodstbl a, imgpathtbl b where a.g_bAd=1 and a.g_id = b.gs_id group by b.gs_id";
	$arr = array();
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}

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

public function GetAllSorts() {
	$arry = array();
	$sql = "select s_id, s_name, s_path, s_bAd from sorttbl";
	if (!$this->dbh)
		return $arry;
	$ret = $this->Query($sql);
	return $ret;
}

public function GetAllGoods() {
	$arry = array();
	$sql = "select a.g_id, a.g_name, a.g_price, a.g_sortid, a.g_discountid, a.g_desc, a.g_pricetype,a.g_bAd, b.i_name from goodstbl a, imgpathtbl b where a.g_id = b.gs_id group by b.gs_id";
	if (!$this->dbh)
		return $arry;
#	$ret = $this->Query($sql);
#	return $ret;
}
public function NewBills($ukey, $count, $sum, $ddate){
	$sts = 1;
	$note = "";
	$arr = array();
	if ($ukey == "") {
		return $arr;
	}
	
	$ins = "insert into billlisttbl(b_uuid, b_count, b_total, b_status, b_datetime, b_note, b_dnow) value('%s',%d, %.2f, %d, '%s', '%s', now());";
	$sql = sprintf($ins, $ukey, $count, $sum, $sts, $ddate, $note);
#	echo 'ins==>'.$sql;
#	$b["sql"] = $sql;
#	echo json_encode($b);
	if (!$this->dbh){
		return ['err'=>"error"];
	}
	$this->Query($sql);
	$sql = "select b_id from billlisttbl where b_uuid='".$ukey."'";
#	echo $sql;
	$res = $this->Query($sql);
	return $res;
}
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

protected function MsgToClient($id, $msg){
	return json_decode(['err'=>$id, 'msg'=>$msg]);
}
/*
 function storeUserInfoToDB($userinfo, $skey, $sessionKey) {
	$uuid = bin2hex(openssl_random_pseudo_bytes(16));
	$create_time = date('Y-m-d H:i:s');
	$last_visit_time = $create_time;
	$open_id = $userinfo->OpenId;
	$user_info = json_encode($userinfo);
	$sql = "select count(*) from cSessionInfo where open_id =" .$open_id;
	if (!$this->dbh) {
		return "error";	
	}
	$res = $this->Query($sql);
	$bret = false;
	if (!$res) {
		$bret = true;
	}
	$sql = "";
	if ($bre) {
	$sql = "insert into cSessionInfo('open_id','uuid','skey','create_time','last_visit_time','session_key','user_info') values (".$open_id .",".$uuid.",".$skey."," .$create_time.",".$last_visit_time .",". $sessionKey .",". $user_info .")";
	} else {
	$sql = "update cSessionInfo set uuid=".$uuid.", skey=".$skey.", last_visit_time=".$last_visit_time.", session_key=".$sessionKey.", user_info=".$user_ifon;
	}
	$res = $this->Query($sql);
	return $res;
}
*/

};
?>


