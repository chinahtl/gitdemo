<?php  
  
class AsynchronousCall  
{  
    //用此函数进行调用  
    //func为  
    public static function Call($func,$post_data)  
    {  
          
      
        AsynchronousCall::init();  
        $baseURL = "";  
        $data = array('data'=> json_encode($post_data), 'key' => AsynchronousCall::$key);  
          
          
        return AsynchronousCall::Request(AsynchronousCall::$baseurl.':'.AsynchronousCall::$port.'/'.$func, $data);  
    }  
    //用这个函数来测试调用的Data是否合法，并返回传入的数据  
    /*格式如下： 
    { 
        error: 0 (如果不合法，返回 1，合法返回0) 
        desc:""(如果不合法，这个是解释) 
        data:{ 
            (这里是传入的数据) 
        } 
    } 
    */  
    public static function CheckData()  
    {  
          
        AsynchronousCall::init();  
          
          
                  
        if(!isset($_POST['data']))  
        {  
            return '';  
        }  
        if($_POST['key'] != AsynchronousCall::$key )  
        {  
            return '';  
        }  
        $rst = $_POST['data'];  
          
        return $rst;  
    }  
  
    //调用秘钥，用于防止黑客入侵  
    private static $key;  
    private static $baseurl;  
    private static $port;  
  
    private static function init()  
    {  
        AsynchronousCall::$key = "http://blog.csdn.net/qq43599939";  
        AsynchronousCall::$baseurl = $_SERVER['HTTP_HOST'];  
        AsynchronousCall::$port = "80";  
    }  
  
    private static function Request($url, $post_data = array(), $cookie = array()){  
        $method = "POST";    
        $url_array = parse_url($url);  
          
          
        $port = isset($url_array['port'])? $url_array['port'] : 80;   
        try  
        {  
          $fp = fsockopen($url_array['host'], $port, $errno, $errstr, 30);   
        }  
        catch (Exception $exception2)  
        {  
          return;  
        }  
        
        if (!$fp){  
                return FALSE;  
        }  
        $end = "\r\n";  
        if(isset($url_array['query']))  
        {  
          $getPath = $url_array['path'] ."?".$url_array['query'];  
        }  
        else  
        {  
          $getPath = $url_array['path'];  
        }  
          
        if(!empty($post_data)){  
                $method = "POST";  
        }  
        $header = $method . " " . $getPath;  
        $header .= " HTTP/1.1$end";  
        $header .= "Host: ". $url_array['host'] . "$end"; //HTTP 1.1 Host  
        /**//* 
        $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 \r\n"; 
        $header .= "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,q=0.5 \r\n"; 
        $header .= "Accept-Language: en-us,en;q=0.5 "; 
        $header .= "Accept-Encoding: gzip,deflate\r\n"; 
         */  
        $header .= "Connection: Close$end";  
        if(!empty($cookie)){  
                $_cookie = strval(NULL);  
                foreach($cookie as $k => $v){  
                        $_cookie .= $k."=".$v."; ";  
                }  
                $cookie_str =  "Cookie: " . base64_encode($_cookie) ." \r\n";//  
                $header .= $cookie_str;  
        }  
          
        if(!empty($post_data)){  
                $_post = strval(NULL);  
                foreach($post_data as $k => $v){  
                        $_post .= $k."=".$v."&";  
                }  
                $post_str  = "Content-Type: application/x-www-form-urlencoded$end";//POST  
                $post_str .= "Content-Length: ". strlen($_post) ."$end$end";//POST  
                $post_str .= $_post."$end"; //  
                $header .= $post_str;  
        }  
        
        $header .= "$end";  
          
        fputs($fp, $header);  
        fclose($fp);  
        return true;  
    }  
}  