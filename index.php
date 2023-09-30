<?php

// Files url to download
$urls = array(
    'http://test.com/Slider.jpg');

$save_to='images/';

$mh = curl_multi_init();
foreach ($urls as $i => $url) {
    $image_info = pathinfo($url);
    $extension = $image_info['extension'];
    $image_name = uniqid().".".$extension;
    $g=$save_to.$image_name;
    if(!is_file($g)){
        $conn[$i]=curl_init($url);
        $fp[$i]=fopen ($g, "w");
        curl_setopt ($conn[$i], CURLOPT_FILE, $fp[$i]);
        curl_setopt ($conn[$i], CURLOPT_HEADER ,0);
        curl_setopt($conn[$i],CURLOPT_CONNECTTIMEOUT,60);
        curl_multi_add_handle ($mh,$conn[$i]);
    }
}
do {
    $n=curl_multi_exec($mh,$active);
}
while ($active);
foreach ($urls as $i => $url) {
    curl_multi_remove_handle($mh,$conn[$i]);
    curl_close($conn[$i]);
    fclose ($fp[$i]);
}
curl_multi_close($mh);
?>