<?php
require_once('DB/db.php');
require_once('class/class-goods.php');
require_once('class/class-sorts.php');
header('Content-Type:application/json');

$ay = [
"sorts" => [],
"goods" => []
];

$db = new DB();
if (!$db)
	return "error";
$res = $db->GetAllSorts();
for ($i=0; $i<count($res); $i++) {
	$obj = new CSorts();
	$obj->sid = $res[$i]['s_id'];
	$obj->sname = $res[$i]['s_name'];
	$obj->spath = $res[$i]['s_path'];
	$obj->bAd = $res[$i]['s_bAd'];
	$ay["sorts"][] = $obj;
}
$res = $db->GetAllGoods();
for ($i=0; $i<count($res); $i++) {
	$obj = new CGoodsItem();
	$obj->id = $res[$i]['g_id'];
	$obj->name = $res[$i]['g_name'];
	$obj->price = $res[$i]['g_price'];
	$obj->discount = $res[$i]['g_discountid'];
	$obj->desc = $res[$i]['g_desc'];
	$obj->path = $res[$i]['i_name'];
	$obj->sort = $res[$i]['g_sortid'];
	$obj->pricetype = $res[$i]['g_pricetype'];
	
	$ay["goods"][] = $obj;
}
echo json_encode($ay);

?>
