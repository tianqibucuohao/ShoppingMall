<?php
require_once('DB/db.php');
require_once('class/class-goods.php');

$ret = file_get_contents('php://input');
#echo $ret;
$rtn = [];
try{
$arr = json_decode($ret, true);
$db = new DB();
$ary = [
"goods"=>[]
];
if ($arr){
	if (count($arr)!=0){
		$key = $arr['key'];
		$bill = $arr['bill'];
		$sum = $arr['sum'];
		$dtime = $arr['date'];
		$len = count($bill);
#		echo 'key -->'+$key;
		if ($key == '') {
			$rtn = ['err'=>0,'msg'=>'key is emtpy'];
		}
		$ret = $db->NewBills($key, $len, $sum, $dtime);
		$len = count($ret);
		$bid = -1;
		if ($len > 0){
			$bid = $ret[0]['b_id'];
		}
		if ($bid == -1) {
			$rtn = ['err'=>0,'msg'=>$id];
		} else {
			$ins = "insert into order_details(g_id, g_name, g_count, g_price, g_pricetype, g_total, b_id) value(%d, '%s', %d, %.2f, '%s', %.2f, %d);";
			for ($i=0; $i<count($bill); $i++) {
				$id = $bill[$i]['id'];
				$name = $bill[$i]['name'];
				$count = $bill[$i]['count'];
				$price = $bill[$i]['price'];
				$pricetype = $bill[$i]['pricetype'];
				$total = $bill[$i]['sum'];
				$v = sprintf($ins, $id, $name, $count, $price, $pricetype, $total, $bid);
				
				$db->BillsDetail($v);
			}
			$rtn = ['err'=>1,
					'msg'=>"suc"];
		}
	}else {
	 $rtn = ['err'=>0,
		'msg'=>"no data"];
	}
}
} catch(Exception $e){
	$rtn = ['err'=>0,
		'msg'=>$e->getMessage()
		];
}
echo json_encode($rtn);
?>
