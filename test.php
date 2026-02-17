<?php
echo "TEsted ";exit();
$order_id = "PCR84-100";
$order_id = strtolower($order_id);
//$order_id = str_replace("pcr", "", $order_id);

//check whethere there is examinee id
/*if (strpos($order_id, '-') !== false) {
    echo 'true';
}
else
{
	echo 'false';
}*/

if(substr($order_id, 0, 3) !== "spcr")
{
    echo 'wrong';
}
else
{
	echo 'yes';
}
