<?php

session_start();

$con = mysqli_connect('localhost','root','','registedatabase');

$con ->set_charset('utf8');

$errors = array();

if(!$con){
    echo 'ბაზასთან დაკავშირება ვერ ხერხდება !!!';
}

?>