<?php
    function parseURL($url){
        
        if(strrpos($url,"qrassassin") != false){
            $query = parse_url($url);
            //If they try to send a non url
            if($query == false){
                return $url;
            }
            parse_str($query['query']);
            $killCode = $kill_code;
            return $killCode;
        }
        
        return $url;

    }


?>
