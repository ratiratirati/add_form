<?php

function get_ip_address() {
    // check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    // check for IPs passing through proxies
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // check if multiple ips exist in var
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                if (validate_ip($ip))
                    return $ip;
            }
        } else {
            if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];

    // return unreliable ip since all else failed
    return $_SERVER['REMOTE_ADDR'];
}

if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($con,$_POST['username']);
    $password = mysqli_real_escape_string($con,$_POST['password']);
    $password_2 = $_POST['password_2'];
    $ip = get_ip_address();

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
        $result = mysqli_query($con,$sql);
        if($result){
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];
            if($username == 'admin'){
                header('location:admin.php');
            }else{
                header('location:home.php');
            }
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