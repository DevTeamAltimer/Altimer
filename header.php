<?php
session_start();
$placeholder = array();
$_SESSION['liked'] = $placeholder;
$conn = new mysqli("localhost","root","","altimer");
include_once("functions.php");
$login = 0;

if(!(isset($_SESSION['user_id']))){
    $login = 0;
}else{
    $user_id = $_SESSION['user_id'];
    $login = 1;
}

if(isset($_GET['success'])){
    $mail = $_POST['email'];
    $mail = strtolower($mail);
    $pw = $_POST['password'];
    if(empty($mail) || empty($pw)){
        echo "<script>alert('Missing fields Error!')</script>";
    }else{
        $result_email = mysqli_query($conn,"SELECT email FROM users WHERE email='$mail'");
        $resultcheck_email = mysqli_num_rows($result_email);
        $result_pw = mysqli_query($conn,"SELECT password FROM users WHERE password='$pw'");
        if($resultcheck_email > 0){
            $resultcheck_pw = mysqli_num_rows($result_pw);
            $resultcheck_pw = mysqli_num_rows($result_pw);
                if($resultcheck_pw > 0){
                    $sql = "SELECT * FROM users WHERE email='$mail'";
                    $result = mysqli_query($conn,$sql);
                    while($row = mysqli_fetch_assoc($result)){
                        $id = $row['id'];
                        $_SESSION['user_id'] = $id;
                    }
                    header("Refresh:1,url=index.php?home=1");
                }else{
                    header("Refresh:0,url=index.php?login=1");
                    echo "<script>alert('False password...')</script>";
                }
        }else{
            header("Refresh:0,url=index.php?login=1");
            echo "<script>alert('No user found...')</script>";
        }
    }
}

if(!(isset($_GET['login'])) && !(isset($_GET['signup'])) && !(isset($_GET['support'])) && !(isset($_GET['legal']))){
    if(!(isset($_SESSION['user_id']))){
        header("Refresh:0;url=index.php?signup=1");
    }
}

?>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<div id="headerTopBar" class="d-flex shadow specialBox align-items-center">
        <i class="fas fa-home fa-3x mr-auto pl-4"></i>
        <?php
        if(isset($_SESSION['user_id'])){
            echo "<a href='index.php?upload=1'><i class='fas fa-chevron-up'></i></a>";
        }
        ?>        

        <i class="fas fa-question-circle fa-3x mr-4"></i>
        <a href="index.php?messages=1"><i class="far fa-comment-dots fa-3x mr-4"></i></a>
        <?php
            if(isset($notification) > 0){
                echo "<span class='badge'>0</span>";
            }
        ?>
        <a id='searchsymbol'><i class='fas fa-search fa-3x mr-4'></i></a>
        
        <div class='header-right'>
            <div class='searchbox'>
                <form action='index.php?search=1' method='post'>
                <input id='searchField' type='text' placeholder='search...' name='search'>
                <input type='submit' value='search' name='submit'>
                </form>
            </div>
        </div>

        <a href='index.php?showprofile=1'><span class='circle circleHeader mr-3'></span></a>

</div>


