<?php
require_once('DB/db.php');
require_once('class/class-goods.php');

header('Content-Type:application/json');

$param = $_SERVER['QUERY_STRING'];
$sl= strpos($param,"sortid=");
$id= substr($param,$l+7);

$ay = [
"data"=>[]
];
$db = new DB();
if (!$db)
	return "error";
$res = $db->GetLittleSort($id);
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
	$ay["data"][] = $obj;
}
echo json_encode($ay);

?>
