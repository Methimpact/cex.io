<?
 include_once('cexapi.class.php');
 $UN = $_GET['user'];
 $API = $_GET['api'];
 $SECRET = $_GET['secret'];

 $api = new cexapi($UN, $API, $SECRET);
/* 
 echo "Ticker:\n\r";
 echo json_encode($api -> ticker("GHS\BTC"));
 echo "\n\rBalance:\n\r";
 echo json_encode($api -> balance());
 echo "\n\rstrlen(json(open_orders)): \r\n";
 echo strlen(json_encode($api -> open_orders()));
 echo "\n\r";
*/
//$array1 = json_encode($api -> balance());
//$my_array = json_decode($array1);
//$my_array->places->place[0]->woe_name;
//echo json_decode($api -> balance());
//var_dump ($api -> balance());
$bal=json_encode($api -> balance());
$bal=json_decode($bal);
//print_r( $uname -> NMC -> available);
$NMC = $bal -> NMC -> available;
$BTC = $bal -> BTC -> available;
//echo $NMC . ',' . $BTC;
$order=json_encode($api -> ticker("GHS\BTC"));
$order=json_decode($order);
$price = $order  -> asks[0][0];
$amount = $order  -> asks[0][1];
echo $BTC . ',' . $price . ',' . $amount;
?>
