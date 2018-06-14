<?php
require_once('DB/db.php');

header('Content-Type:application/json');

$param = $_SERVER['QUERY_STRING'];
$sl= strpos($param,"gid=");
$id= substr($param,$l+4);

class imgClass{
public $id;
public $url;
}

$ay = [
"url"=>[]
];
$db = new DB();
if (!$db)
	return "error";
$res = $db->GetGoodsImgPath($id);
for ($i=0; $i<count($res); $i++) {
	$obj["id"] = $res[$i]['i_id'];
	$obj["url"] = $res[$i]['i_name'];
	$ay["url"][] = $obj;
}
echo json_encode($ay);

?>
