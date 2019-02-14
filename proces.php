<?php

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$msg='';

if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($con,$_POST['username']);
    $password = mysqli_real_escape_string($con,$_POST['password']);
    $password_2 = $_POST['password_2'];
    $ip = get_client_ip();

    if(empty($username)){
        array_push($errors,'<style>#err_1{border: 2px red dashed;}</style>');
    }

    if(empty($password)){
        array_push($errors,'<style>#err_2{border: 2px red dashed;}</style>');
    }

    if(empty($password_2)){
        array_push($errors,'<style>#err_3{border: 2px red dashed;}</style>');
    }

    if($password != $password_2){
        array_push($errors,'პაროლები არ ემთხვევა');
    }

    if(!empty($password and strlen($password) != 8 )){
        array_push($errors,'პაროლი უნდა შედგებოდეს 8 ციფრისგან');
    }

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($con,$sql);
    if(mysqli_num_rows($result)){
        array_push($errors,'ესეთი მომხმარებელი უკვე არსებობს');
    }

    if(count($errors) == 0 ){
        $password = md5($password);
        $sql = "INSERT INTO users (ip,username,password) VALUES ('$ip','$username','$password')";
        if(mysqli_query($con,$sql)){
            $msg = "რეგისტრაცია წარმატებულია";
        }
    }

}


if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($con,$_POST['username']);
    $password = mysqli_real_escape_string($con,$_POST['password']);

    if(empty($username)){
        array_push($errors,'მომხმარებლის ველი ცარიელია');
    }

    if(empty($password)){
        array_push($errors,'პაროლის ველი ცარიელია');
    }

    if(count($errors) == 0 ){
        $password = md5($password);
        $sql = "SELECT * FROM users WHERE username='$username' and password='$password'";
        $result = mysqli_query($con,$sql);
        if(mysqli_num_rows($result)){
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];
            if($username == 'admin'){
                header('location:admin.php');
            }else{
                header('location:home.php');
            }
        }else{
            array_push($errors,'მომხმარებლის სახელი ან პაროლი არასწორია');
        }
    }


}


if(isset($_POST['add'])){
    $saxeli = mysqli_real_escape_string($con,$_POST['saxeli']);
    $gvari = mysqli_real_escape_string($con,$_POST['gvari']);
    $profesia = mysqli_real_escape_string($con,$_POST['profesia']);
    $asaki = mysqli_real_escape_string($con,$_POST['asaki']);
    $mobiluri = mysqli_real_escape_string($con,$_POST['mobiluri']);
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    if(empty($saxeli)){
        array_push($errors,'<style>#err_4{border: 2px red dashed;}</style>');
    }

    if(empty($gvari)){
        array_push($errors,'<style>#err_5{border: 2px red dashed;}</style>');
    }

    if(empty($profesia)){
        array_push($errors,'<style>#err_6{border: 2px red dashed;}</style>');
    }

    if(empty($asaki)){
        array_push($errors,'<style>#err_7{border: 2px red dashed;}</style>');
    }

    if(empty($mobiluri)){
        array_push($errors,'<style>#err_8{border: 2px red dashed;}</style>');
    }

    if(count($errors) == 0 ){
        $sql = "INSERT INTO informacia (user_id,username,saxeli,gvari,profesia,asaki,mobiluri) VALUES ('$user_id','$username','$saxeli','$gvari','$profesia','$asaki','$mobiluri')";
        mysqli_query($con,$sql);
    }
}

if(isset($_POST['delete'])){
    $sql = "DELETE FROM informacia WHERE id='".$_POST['deleteid']."'";
    mysqli_query($con,$sql);
}
?>