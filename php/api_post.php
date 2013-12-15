<?php
include_once('cexapi.class.php');
$Filename = "options.csv";
$username = "";
$api = "";
$secret = "";
$row = 0;
$test = "";

$file = fopen("options.csv","r");
$data = fgetcsv($file, 1000, ",");$username = $data[1];
$data = fgetcsv($file, 1000, ",");$api = $data[1];
$data = fgetcsv($file, 1000, ",");$secret = $data[1];
fclose($file);

$api = new cexapi($username, $api, $secret);
$bal=json_encode($api -> balance());
$bal=json_decode($bal);
$NMC = $bal -> NMC -> available;
$BTC = $bal -> BTC -> available;

$order=json_encode($api -> ticker("GHS\BTC"));
$order=json_decode($order);
$price = $order  -> asks[0][0];
$amount = $order  -> asks[0][1];
//echo $BTC . ',' . $price . ',' . $amount;

if($BTC/$price >= $amount)
{
$bought=json_encode($api -> place_order('buy', $amount, $price, 'GHS/BTC'));
$bought=json_decode($bought);
$finished = $bought -> amount;
$finishedprice = $bought -> price;
echo $finished . ',' . $finishedprice;
}
else
{
$bought=json_encode($api -> place_order('buy', ($BTC/$price), $price, 'GHS/BTC'));
$bought=json_decode($bought);
$finished = $bought -> amount;
$finishedprice = $bought -> price;
echo $finished . ',' . $finishedprice;
}
?>