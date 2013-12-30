<?php
$VERSION = '0.10 beta';
$file = fopen("options.csv","r");
$data = fgetcsv($file, 1000, ",");$username = $data[1];
$data = fgetcsv($file, 1000, ",");$key = $data[1];
$data = fgetcsv($file, 1000, ",");$secret = $data[1];
fclose($file);
balance:
$timer = explode(' ', microtime());
$Balance['BTC']['AVAILABLE'] = 0;
$Balance['BTC']['ORDERS'] = 0;
$Balance['GHS']['AVAILABLE'] = 0;
$Balance['GHS']['ORDERS'] = 0;
$Balance['IXC']['AVAILABLE'] = 0;
$Balance['IXC']['ORDERS'] = 0;
$Balance['DVC']['AVAILABLE'] = 0;
$Balance['DVC']['ORDERS'] = 0;
$Balance['NMC']['AVAILABLE'] = 0;
$Balance['NMC']['ORDERS'] = 0;
$Balance['LTC']['AVAILABLE'] = 0;
$Balance['LTC']['ORDERS'] = 0;
$Price['BTC']['GHS'] = 0;
$Price['GHS']['BTC'] = 0;
$High ['BTC']['GHS'] = 0;
$Low  ['BTC']['GHS'] = 0;
$Last ['BTC']['GHS'] = 0;
$Price['BTC']['NMC'] = 0;
$Price['NMC']['BTC'] = 0;
$High ['BTC']['NMC'] = 0;
$Low  ['BTC']['NMC'] = 0;
$Last ['BTC']['NMC'] = 0;
$Price['BTC']['LTC'] = 0;
$Price['LTC']['BTC'] = 0;
$High ['BTC']['LTC'] = 0;
$Low  ['BTC']['LTC'] = 0;
$Last ['BTC']['LTC'] = 0;
$Price['NMC']['GHS'] = 0;
$Price['GHS']['NMC'] = 0;
$High ['NMC']['GHS'] = 0;
$Low  ['NMC']['GHS'] = 0;
$Last ['NMC']['GHS'] = 0;
$array['BALANCE'] = array('type'=>'balance');
$balance=json_encode(cexio_query('https://cex.io/api/balance/', $array['BALANCE'], $username , $key, $secret, $timer));
$balance=json_decode($balance);
$Balance['BTC']['AVAILABLE'] = $balance -> BTC -> available;
$Balance['BTC']['ORDERS'] = $balance -> BTC -> orders;
$Balance['GHS']['AVAILABLE'] = $balance -> GHS -> available;
$Balance['GHS']['ORDERS'] = $balance -> GHS -> orders;
$Balance['IXC']['AVAILABLE'] = $balance -> IXC -> available;
$Balance['IXC']['ORDERS'] = $balance -> IXC -> orders;
$Balance['DVC']['AVAILABLE'] = $balance -> DVC -> available;
$Balance['DVC']['ORDERS'] = $balance -> DVC -> orders;
$Balance['NMC']['AVAILABLE'] = $balance -> NMC -> available;
$Balance['NMC']['ORDERS'] = $balance -> NMC -> orders;
$Balance['LTC']['AVAILABLE'] = $balance -> LTC -> available;
$Balance['LTC']['ORDERS'] = $balance -> LTC -> orders;
$timer = $balance -> timestamp;
if ($Balance['BTC']['AVAILABLE'] > 0)
{
    goto btc_price_ghs;
}
if ($Balance['NMC']['AVAILABLE'] > 0)
{
    goto btc_price_ghs;
} else
{
    echo $Balance['BTC']['AVAILABLE'].','.$Balance['GHS']['AVAILABLE'].','.$Balance['IXC']['AVAILABLE'].','.$Balance['DVC']['AVAILABLE'].','.$Balance['NMC']['AVAILABLE'].','.$Balance['LTC']['AVAILABLE'];
    die();
}

btc_price_ghs:
$array['BALANCE'] = array('type'=>'GHS_BTC');
$balance=json_encode(cexio_query('https://cex.io/api/ticker/GHS/BTC/', $array['BALANCE'], $username , $key, $secret, $timer));
$balance=json_decode($balance);
$timer = $balance -> timestamp;
$Price['BTC']['GHS'] = $balance -> ask;
$Price['GHS']['BTC'] = $balance -> bid;
$High ['BTC']['GHS'] = $balance -> high;
$Low  ['BTC']['GHS'] = $balance -> low;
$Last ['BTC']['GHS'] = $balance -> last;
goto btc_price_nmc;

btc_price_ltc:
$array['BALANCE'] = array('type'=>'LTC_BTC');
$balance=json_encode(cexio_query('https://cex.io/api/ticker/LTC/BTC/', $array['BALANCE'], $username , $key, $secret, $timer));
$balance=json_decode($balance);
$timer = $balance -> timestamp;
$Price['BTC']['LTC'] = $balance -> ask;
$Price['LTC']['BTC'] = $balance -> bid;
$High ['BTC']['LTC'] = $balance -> high;
$Low  ['BTC']['LTC'] = $balance -> low;
$Last ['BTC']['LTC'] = $balance -> last;
//echo $Price['BTC']['LTC'];
goto nmc_price_ghs;

nmc_price_ghs:
$array['BALANCE'] = array('type'=>'GHS_NMC');
$balance=json_encode(cexio_query('https://cex.io/api/ticker/GHS/NMC/', $array['BALANCE'], $username , $key, $secret, $timer));
$balance=json_decode($balance);
$timer = $balance -> timestamp;
$Price['NMC']['GHS'] = $balance -> ask;
$Price['GHS']['NMC'] = $balance -> bid;
$High ['NMC']['GHS'] = $balance -> high;
$Low  ['NMC']['GHS'] = $balance -> low;
$Last ['NMC']['GHS'] = $balance -> last;
//echo $Price['BTC']['LTC'];
goto btc_best_price;

btc_price_nmc:
$array['BALANCE'] = array('type'=>'NMC_BTC');
$balance=json_encode(cexio_query('https://cex.io/api/ticker/NMC/BTC/', $array['BALANCE'], $username , $key, $secret, $timer));
$balance=json_decode($balance);
$timer = $balance -> timestamp;
$Price['BTC']['NMC'] = $balance -> ask;
$Price['NMC']['BTC'] = $balance -> bid;
$High ['BTC']['NMC'] = $balance -> high;
$Low  ['BTC']['NMC'] = $balance -> low;
$Last ['BTC']['NMC'] = $balance -> last;
goto btc_price_ltc;


btc_best_price:
// 0.00644003	0.12274597	0.00079049
//price           nmcamnt         total btc
//btc amount divided by price will equal amount of nmc bought.
//echo "hi";
if($Balance['BTC']['AVAILABLE'] > 0)
{
    
    if(($Balance['BTC']['AVAILABLE']/$Price['BTC']['GHS'])>= (($Balance['BTC']['AVAILABLE']/$Price['BTC']['NMC'])/$Price['NMC']['GHS']))
    {
     goto btc_buy_ghs;
    }
    else
    {
     goto btc_buy_nmc;    
    }

}
else
{
if($Balance['NMC']['AVAILABLE'] > 0)
{
 
 if(($Balance['NMC']['AVAILABLE']/$Price['NMC']['GHS'])>=(($Balance['NMC']['AVAILABLE']*$Price['NMC']['BTC'])/$Price['BTC']['GHS']))
 {
  goto nmc_buy_ghs;  
 }
 else
 {
  goto nmc_buy_btc;  
 }
}
else
{
 goto balance;   
}
}
btc_buy_ghs:
$array['BALANCE'] = array('type'=>'buy',
                                    'amount'=>($Balance['BTC']['AVAILABLE']/$Price['BTC']['GHS']),
                                    'price'=>$Price['BTC']['GHS']);
$balance=json_encode(cexio_query('https://cex.io/api/place_order/GHS/BTC/', $array['BALANCE'], $username , $key, $secret, $timer));
$balance=json_decode($balance);
$timer = $balance -> timestamp;
goto balance;

btc_buy_nmc:
$array['BALANCE'] = array('type'=>'buy',
                                    'amount'=>($Balance['BTC']['AVAILABLE']/$Price['BTC']['NMC']),
                                    'price'=>$Price['BTC']['NMC']);
$balance=json_encode(cexio_query('https://cex.io/api/place_order/NMC/BTC/', $array['BALANCE'], $username , $key, $secret, $timer));
$balance=json_decode($balance);
$timer = $balance -> timestamp;
goto balance;

btc_buy_ltc:

nmc_buy_ghs:
$array['BALANCE'] = array('type'=>'buy',
                                    'amount'=>($Balance['NMC']['AVAILABLE']/$Price['NMC']['GHS']),
                                    'price'=>$Price['NMC']['GHS']);
$balance=json_encode(cexio_query('https://cex.io/api/place_order/GHS/NMC/', $array['BALANCE'], $username , $key, $secret, $timer));

$balance=json_decode($balance);
$timer = $balance -> timestamp;
goto balance;

nmc_buy_btc:
$array['BALANCE'] = array('type'=>'sell',
                                    'amount'=>$Balance['NMC']['AVAILABLE'],
                                    'price'=>$Price['NMC']['BTC']);
$balance=json_encode(cexio_query('https://cex.io/api/place_order/NMC/BTC/', $array['BALANCE'], $username , $key, $secret, $timer));

$balance=json_decode($balance);
$timer = $balance -> timestamp;
goto balance;

/*Function Definition
 *
 *
 *
 *
*/
function cexio_query($path, array $req = array(),$username , $key, $secret, $mt ) {
 
    $mt = explode(' ', microtime());
    
  
        $sign = strtoupper(hash_hmac("sha256", $mt[1] . $username . $key, $secret));
        
        $req['key'] = $key;
        $req['signature'] = $sign;
        $req['nonce'] = $mt[1];
        
        # generate the POST data string
        $post_data = http_build_query($req, '', '&');
  
        # generate the extra headers
        $headers = array(
                'key: '.$key,
                'signature: '.$sign,
                'nonce: '.$mt[1]
        );
     
     
    // our curl handle (initialize if required)
    static $ch = null;
    if (is_null($ch)) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; CEX.IO PHP client v'.$VERSION.'; '.php_uname('s').'; PHP/'.phpversion().')');
    }
    curl_setopt($ch, CURLOPT_URL, $path);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  //echo $post_data;
    // run the query
    $res = curl_exec($ch);
    if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
    $dec = json_decode($res, true);
    if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
    return $dec;
}

?>