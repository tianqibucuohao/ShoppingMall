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
//	echo $sql;
	if ($result){
		$i=0;
		while ($rol=mysql_fetch_array($result))
		{
			array_push($arry,$rol);
			$i++;
		}	

//		echo "<br />not null, array.size=". count($arry);
	}
	else { 
		echo "null";
	}
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
	$ret = $this->Query($sql);
	return $ret;
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


