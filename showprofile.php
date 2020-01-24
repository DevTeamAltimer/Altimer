<?php
    $result = mysqli_query($conn,"SELECT * FROM users WHERE id=".$user_id);
    while($row = mysqli_fetch_assoc($result)){
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $gender = $row['gender'];
        $birthday_day = $row['birthday_day'];
        $birthday_month = $row['birthday_month'];
        $birthday_year = $row['birthday_year'];
        if(isset($row['location'])){
          $location = $row['location'];
        }else{
          $location = "EMPTY";
        }
    }
?>
<div class="showprofile">
<div class="gender">
<?php
    if($gender == "Male"){
        echo "<i class='fas fa-mars'></i>";
    }else{
        echo "<i class='fas fa-venus'></i>";
    }
?>
</div>
<h1 style="text-align:center;"><?=ucfirst($firstname)?> <?=ucfirst($lastname)?></h1>
<span class="circle showprofile_circle"></span>
<?php
    if(!(isset($_GET['showfriends']))){
        echo "
            <ul>
            <li>Birthday: ".$birthday_month.", ".$birthday_day." ".$birthday_year."</li>
            <br>
            <li>Location: ".$location."</li>
            <br>
            <button><a href='index.php?editprofile=1'>Edit profile</a></button>
            <button><a href='index.php?showprofile=1&showfriends=1'>Show friends</a></button>
            </ul>      
        ";
    }else{
        $friendquery = mysqli_query($conn,"SELECT * FROM friends WHERE user_id=".$user_id." OR to_user_id=".$user_id);
        $friendquerycount = mysqli_num_rows($friendquery);
        if($friendquerycount > 0){
            $imagearray = array();
            $userarray = array();

            $result_userId = mysqli_query($conn,"SELECT * FROM friends WHERE user_id=".$user_id." OR to_user_id=".$user_id);
            echo "<div class='userpost-div'>";
            while($row_userId = mysqli_fetch_assoc($result_userId)){
                $userId = $row_userId['user_id'];
                $toUserId = $row_userId['to_user_id'];

                if($userId == $user_id){
                    array_push($userarray,$toUserId);
                }

                if($toUserId == $user_id){
                    array_push($userarray,$userId);
                }
                
            }
            
            foreach($userarray as $value){
                $result = mysqli_query($conn,"SELECT * FROM users WHERE id=".$value);
                while($row = mysqli_fetch_assoc($result)){
                    $image = $row['image'];
                    array_push($imagearray,$image);
                }
            }
            $i = 0;
            foreach($imagearray as $imagevalue){
                echo "<a href='index.php?profile=".$userarray[$i]."'><img class='userposts' src='profile_pic/".$imagevalue."'></a>";
                $i++;
            }

            echo "</div>";
        }else{
            echo "<script>alert('No friends yet')</script>";
            header("Refresh:0,url=index.php?showprofile=1");
        }
        echo "
            <ul>
            </ul>
        ";
    }
?>
</div>
