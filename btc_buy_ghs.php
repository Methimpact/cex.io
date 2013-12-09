<?
 include_once('cexapi.class.php');
 $UN = $_GET['user'];
 $API = $_GET['api'];
 $SECRET = $_GET['secret'];
 $trade = $_GET['trade'];

  $api = new cexapi($UN, $API, $SECRET);
   $bal=json_encode($api -> balance());
   $bal=json_decode($bal);
   $BTC = $bal -> BTC -> available;
   $order=json_encode($api -> ticker("GHS\BTC"));
   $order=json_decode($order);
   $price = $order  -> asks[0][0];
   $amount = $order  -> asks[0][1];

 $nonce = time();
 $string = $nonce.$UN.$API;
   $hash = hash_hmac('sha256', $string, $SECRET); 
   $hash = strtoupper($hash);
  
 if($trade == "under")
 {
 $GHS = $BTC/$price;
 }
 else
 {
  $GHS = $amount;
 }
 $url = 'https://cex.io/api/place_order/GHS/BTC';
$myvars = 'key=' . $API . '&signature=' . $hash . '&nonce=' . $nonce . '&type=' . 'buy' . '&amount=' . $GHS . '&price=' . $price;

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
echo $response;
?>
