<script type='text/javascript' src="../_scripts/jquery.js"></script>
<?php

$keyword = 'apache';
$keyword = str_replace(' ', '+', $keyword);

$url = "http://1337day.com/search";
$fields = array(
    'dong' => urlencode("$keyword"),
    'sumbit' => urlencode('Submit')
);

$fields_string = '';

foreach ($fields as $key => $value)
    $fields_string .= $key . '=' . $value . '&';
rtrim($fields_string, '&');


$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_PROXY, "proxy.van.sap.corp");
curl_setopt($curl, CURLOPT_PROXYPORT, 8080);
curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);

$buffer = curl_exec($curl);
curl_close($curl);

if (empty($buffer)) {
    die("Error: cURL buffer empty from: $url");
}

echo $buffer.'<div id="test"></div>';
?>
