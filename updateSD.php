<?php
/*** 缓存路径 ***/
$filePath = './data/cache.json';
/***API初始url */
$apiUrlsBase = [ 'http://127.0.0.1/server/', 'https://api.base.com/backup1', 'https://api.base.com/backup2' ];//******需要手工设置的　!!!!!!########### */
/**约定动作参数 */
$AC="";
if(isset($_GET['AC'])){ $AC=trim($_GET['AC']); $AC=str_replace(' ','',$AC); }

/*** 初始化 API 地址 apiUrl　和新增ggＩＰ, newGooglebotIPs***/
$apiUrl = "";
$newGooglebotIPs =[];
$updateTime="2022-11-11 11:00:00";
$nowTime = date("Y-m-d H:i:s");

if (file_exists($filePath)) { 
    $cacheData = file_get_contents($filePath);    
    $data = json_decode($cacheData, true); // 将缓存内容转换为数组或对象
    $newGooglebotIPs = $data['newGooglebotIPs'];  // 读取缓存中的newGooglebotIPs *** //addyyy
    $apiUrls = $data['newApiUrls']; // 获取newApiUrls列表数据
    $apiUrl = $apiUrls[0]; // 获取第一个URL作为默认请求的api
    $updateTime=$data['updateTime'];
}else{ // 初始url 程序自带　写死的******需要手工设置的　!!!!!!########### 
    $apiUrls = $apiUrlsBase;
    $apiUrl = $apiUrls[0];
    //检测 api列表　是否正常访问
    foreach ($apiUrls as $url) {
        $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false); curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); curl_setopt($ch, CURLOPT_TIMEOUT, 10); curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); curl_setopt($ch, CURLOPT_MAXREDIRS, 3); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);    
        if ($httpCode == 200) { $apiUrl = $url; break; }//重写可用网址
    }//end foreach
}//endif file_exists

/*** 获取远程api数据 ***/
function getAPIData($apiUrl,$GooglebotIPsNewList) {    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);//访问ＡＰＩ
    curl_setopt($ch, CURLOPT_POST, true);    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($GooglebotIPsNewList));//发送数据，
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    curl_setopt($ch, CURLOPT_HEADER, false);    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);    curl_setopt($ch, CURLOPT_MAXREDIRS, 3);    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_exec($ch);
    curl_close($ch);    
    $jsonResponse = curl_exec($ch);
    // $jsonResponse['updateTime'] = $nowTime;
    return $jsonResponse;
}

// 取得远程　更新/创建　本地json AC为动作控制器, 如果参数为数字即为心跳间隔时间，　如果为"DO"则强制执行更新，不考虑时间间隔
if ($AC>1){
    $timeDiff = strtotime($nowTime) - strtotime($updateTime);
    $minutesDiff = floor($timeDiff / 60);
    if ($minutesDiff >= $AC) {
        $newJson=getAPIData($apiUrl,$newGooglebotIPs)        
        file_put_contents($filePath, $newJson);
        echo "1";
    } 

}elseif($AC=="DO"){
    // echo "doing"; //
    $newJson=getAPIData($apiUrl,$newGooglebotIPs)
    file_put_contents($filePath, $newJson);
    echo "1";     
}



?>