<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/08138e4907.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Alatsi&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="script.js"></script>
        <title>Altimer</title>
    </head>
    <body>
        <header>
            <?php
                include("header.php");
                if($login == 0){
                    echo "
                <script>
                    $('.circle').hover(function(){
                        $('.login-div').show();
                    },function(){
                        setTimeout(function(){
                            if($('.login-div').is(':hover') != true && $('.circle').is(':hover') != true){
                                $('.login-div').hide();
                            }
                        }, 2000);
                    });
                </script>";
                }else{
                    echo "
                <script>
                    $('.circle').hover(function(){
                        $('.info-div').show();
                    },function(){
                        setTimeout(function(){
                            if($('.info-div').is(':hover') != true && $('.circle').is(':hover') != true){
                                $('.info-div').hide();
                            }
                        }, 2000);
                    });
                </script>";
                }
            ?>
        </header>
        <div class='login-div' style='display:none;'>
        <form action="index.php?home=1&loginsuccess=1" method="post">
        <input type="text" name="email" placeholder="Email">
        <br>
        <br>
        <input type="password" name="password" placeholder="Password">
        <br>
        <br>
        <input type="submit" name="submit" value="Submit">
        <button><a href="index.php?signup=1">Sign Up</a></button>
        </form>
        </div>

        <?php
            if(isset($user_id)){
                $result_name = mysqli_query($conn,"SELECT * FROM users WHERE id=".$user_id);
                while($row_name = mysqli_fetch_assoc($result_name)){
                    $firstName = $row_name['firstname'];
                    $lastName = $row_name['lastname'];
                }

                $imagearray = array();
                $likearray = array();
                $likeXimagearray = array();
                $result_images = mysqli_query($conn,"SELECT * FROM uploads WHERE user_id=".$user_id);
                while($row_image = mysqli_fetch_assoc($result_images)){
                    $id = $row_image['id'];
                    array_push($imagearray,$id);
                }
                $result_like = mysqli_query($conn,"SELECT * FROM usersxuploads");
                while($row_like = mysqli_fetch_assoc($result_like)){
                    $like = $row_like['image_id'];
                    array_push($likearray,$like);
                }
                foreach($imagearray as $value){
                    if(in_array($value,$likearray)){
                        array_push($likeXimagearray,$value);
                    }
                }

                $result_friend = mysqli_query($conn,"SELECT * FROM friends WHERE user_id=".$user_id." OR to_user_id=".$user_id);
                $result_friend_count = mysqli_num_rows($result_friend);

                $result_likes_num = sizeof($likeXimagearray);
            }
            
        ?>

        <div class='info-div' style='display:none;'>
        <p><a style='color:black;text-decoration:none;' href='index.php?profile=<?=$user_id?>'><b><?=ucfirst($firstName)?> <?=ucfirst($lastName)?></b></a></p>
        <br>
        <p><a style='color:black;text-decoration:none;' href='index.php?showprofile=1&showfriends=1'>Friends: <?=$result_friend_count?></a></p>
        <br>
        <p>Likes: <?=$result_likes_num?></p>
        <br>
        <button><a href="index.php?home=1&logout=1">Logout</a></button>
        </div>
        <style>
        .circle{
            <?php
                $sql = "SELECT image FROM users WHERE id=".$user_id;
                $result = mysqli_query($conn,$sql);
                while($row = mysqli_fetch_assoc($result)){
                    $image = $row['image'];
                }
                if(empty($image)){
                    $image = "noImage.png";
                }
            ?>
            background-image:url("profile_pic/<?=$image?>");
            background-size: 100%;
        }
        .circle_profile{
            <?php
            $user = "";
            if(isset($_GET['profile'])){
                $user = $_GET['profile'];
                $sql = "SELECT image FROM users WHERE id=".$user;
                $result = mysqli_query($conn,$sql);
                while($row = mysqli_fetch_assoc($result)){
                    $image = $row['image'];
                }
                if(empty($image)){
                    $image = "noImage.png";
                }
            }
            ?>
            background-image:url("profile_pic/<?=$image?>");
            background-size:100%;
        }
        </style>
        <div id="contentdiv">
            <?php
                if(isset($_GET['home'])){
                    include("home.php");
                }elseif(isset($_GET['showprofile'])){
                    include("showprofile.php");
                }elseif(isset($_GET['profile'])){
                    include("profile.php");
                }elseif(isset($_GET['editprofile'])){
                    include("editprofile.php");
                }elseif(isset($_GET['upload'])){
                    include("upload.php");
                }elseif(isset($_GET['login'])){
                    include("login.php");
                }elseif(isset($_GET['signup'])){
                    include("signup.php");
                }elseif(isset($_GET['legal'])){
                    include("legal.php");
                }elseif(isset($_GET['search'])){
                    include("search.php");
                }elseif(isset($_GET['support'])){
                    include("support.php");
                }elseif(isset($_GET['messages'])){
                    include("messages.php");
                }elseif(isset($_GET['editpost'])){
                    include("editpost.php");
                }elseif(isset($_GET['comment'])){
                    include("comment.php");
                }
                else{
                    echo "ERROR!";
                }
            ?>
        </div>
    </body>
</html>