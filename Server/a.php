<?php
require_once("DB/db.php");
require_once("class/class-goods.php");
require_once("class/class-sorts.php");
header('Content-type:application/json');
$db=new DB();
$ay = [
"sort" => [],
"data" => []
];
if (is_object($db)){
	$ary=$db->GetAdGoods();
//	echo '<br />';
//	$ab=array();
	for ($i=0;$i<count($ary);$i++)
	{
		$obj = new CGoodsItem();
		$obj->id = $ary[$i]['g_id'];
		$obj->name = $ary[$i]['g_name'];
		$obj->price = $ary[$i]['g_price'];
		$obj->sort = $ary[$i]['s_name'];
		$obj->discount = $ary[$i]['g_discountid'];	
		$obj->desc = $ary[$i]['g_desc'];
		$obj->pricetype = $ary[$i]['g_pricetype'];
		$obj->path = $ary[$i]['i_name'];
		$ay["data"][] = $obj;
	}
//	echo '<br />';
	$ret = $db->GetAdSort();
	for ($i=0;$i<count($ret);$i++) {
		$obj = new CSorts();
		$obj->sid = $ret[$i]['s_id'];		
		$obj->sname = $ret[$i]['s_name'];
		$obj->spath = $ret[$i]['s_path'];
		$obj->bAd = $ret[$i]['s_bAd'];
		$ay["sort"][] = $obj;
	}
	echo json_encode($ay);
//	echo '<br />';
}
else {
	echo "hi,sb";
}
?>
