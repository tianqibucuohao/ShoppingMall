<?php
/*
desc:商品
*/

class CGoodsItem{
	// id from gooodstbl.g_id
	public $id;
	// name from goodstbl.g_name
	public $name;
	// price from goodstbl.g_price
	public $price;
	// discount from discount.
	public $discount;
	// desc from goodstbl.desc
	public $desc;
	// bAd from goodstbl.bAd
	public $bAd;
	// sort from sorttbl.s_id
	public $sort;
	// path is array, from imgpathtbl.i_path
	public $path;
	// price type
	public $pricetype;	
};
?>
