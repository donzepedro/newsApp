<?php

function debug($arg){
    echo '<pre>' . print_r($arg, true) .  '</pre>';
}
function Checkemail($email) {
    return preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $email);
}
function clean_data($str){
    if(get_magic_quotes_gpc() == 1) { // если на сервере включен magic quotes, срабатывает "ручная" очистка
        $str = str_replace('\"', "&quot;", $str) ;
        $str = str_replace("\'", "&#039;", $str) ;
        $str = str_replace("<", "&lt;", $str) ;
        $str = str_replace(">", "&gt;", $str) ;
    } else { // если на сервере выключен magic quotes, срабатывает "ручная" очистка
        $str = htmlspecialchars(stripslashes($str));
    }
    return $str ;
}
function passencode($password){

    $salt='rf';
    return $password = hash('sha1',$password .$salt);

}
function CheckGuid($guid) {
    return preg_match("~^^[a-zA-Z0-9]+-[a-zA-Z0-9]+-[a-zA-Z0-9]+-[a-zA-Z0-9]+-[a-zA-Z0-9]+-[a-zA-Z0-9]+-[a-zA-Z0-9]+$~i", $guid);
}

?>