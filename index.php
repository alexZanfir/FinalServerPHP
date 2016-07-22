<?php
    function getUrlJSON($valor){
        //$start=  microtime(TRUE);
         // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $valor); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);
        //echo "getUrlJSON: ".((microtime(true) - $start)*1000)."ms<br>";
        return json_decode($output, true);
    }
    
    function getCityFromClientIp(){
        $ip= getClientIP();
        $url = 'http://ip-api.com/json/'. $ip . '?fields=city';
        $data=getUrlJSON($url);
        if(isset($data["city"])){

            return $data["city"];
        }else{
            die('Unable to guess location.');
        }
    }
    
    function getTemperature($city=false){
        //$start= microtime(true);
        if($city===false){
            $city = getCityFromClientIp();
        }
        $url = 'http://api.openweathermap.org/data/2.5/weather?q=' . urlencode($city) . '&units=metric&APPID=f7010dceedc9ad58857b3c276cad96fe';
        $data=getUrlJSON($url);
        if(isset($data["main"])){
            return "It's ".$data["main"]["temp"]."º in ".$data["name"]."! ";//(".((microtime(true) - $start)*1000)."ms".")");
        }else{
            die('Unable to fetch weather.');
        }
    }
    
    function getClientIP()
    {
        //$start= microtime(true);
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            //echo "getClientIP: ".((microtime(true) - $start)*1000)."ms<br>";
            return $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            //echo "getClientIP: ".((microtime(true) - $start)*1000)."ms<br>";
            return $forward;
        }
        else
        {
            //echo "getClientIP: ".((microtime(true) - $start)*1000)."ms<br>";
            return $remote;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test</title>
    </head>
    <body>
        <p><?php echo (isset($_GET["q"]) && trim($_GET["q"])!="") ? getTemperature($_GET["q"]): getTemperature(); ?></p>
        <form><input name="q" placeholder="City name" /><button>Go</button></form>
    </body>
</html>