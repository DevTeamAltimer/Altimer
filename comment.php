<?php
    $image_id = $_GET['post'];
    $result = mysqli_query($conn,"SELECT * FROM uploads WHERE id=".$image_id);
    while($row = mysqli_fetch_assoc($result)){
        $image = $row['image'];
        $description = $row['description'];
        $user = $row['user_id'];
    }
    $resultUser = mysqli_query($conn,"SELECT * FROM users WHERE id=".$user);
    while($rowUser = mysqli_fetch_assoc($resultUser)){
        $firstName = $rowUser['firstname'];
        $lastName = $rowUser['lastname'];
    }

    if(isset($_POST['submit'])){
        $commentInsert = $_POST['comment'];
        
        mysqli_query($conn,"INSERT INTO comment (user_id,comment,image_id) VALUES (".$user_id.",'$commentInsert',".$image_id.")");
    }
?>

<div class="posts">
<p style="text-align:center;">
<img class="images" src="uploads/<?=$image?>">
</p>
<p><?=$description?></p>
<p>
Uploaded by <a class="usernames" href="index.php?profile=<?=$user?>"><?=ucfirst($firstName)?> <?=ucfirst($lastName)?></a>
</p>
</div>
<hr>
<div class='commentsectionParent'>
<div class='commentsection'>
<?php
    $commentContent = mysqli_query($conn,"SELECT * FROM comment WHERE image_id=".$image_id);
    $commentContentNum = mysqli_num_rows($commentContent);
    
    $commentUserArray = array();
    $commentArray = array();

    $profilePicArray = array();
    $firstNameArray = array();
    $lastNameArray = array();

    if($commentContentNum > 0){
        while($rowContent = mysqli_fetch_assoc($commentContent)){
            $commentUser = $rowContent['user_id'];
            $comment = $rowContent['comment'];
            array_push($commentUserArray,$commentUser);
            array_push($commentArray,$comment);
        }
        $i = 0;
        foreach($commentUserArray as $userId){
            if($commentUserArray[$i] == $user){
                if($i == count($commentUserArray) - 1){
                    echo "
                        <div class='userComment'><p>".getUserName($commentUserArray[$i]).": ".$commentArray[$i]."</p></div>
                        <br>
                    ";
                }else{
                    echo "
                        <div class='userComment'><p>".getUserName($commentUserArray[$i]).": ".$commentArray[$i]."</p></div>
                    ";
                }
            }else{
                if($i == count($commentUserArray) - 1){
                    echo "
                        <div class='otherComment'><p>".getUserName($commentUserArray[$i]).": ".$commentArray[$i]."</p></div>
                        <br>
                    ";
                }else{
                    echo "
                        <div class='otherComment'><p>".getUserName($commentUserArray[$i]).": ".$commentArray[$i]."</p></div>
                    ";
                }
            }
            $i++;
        }
    }
?>
</div>
<div class="bottom">
<hr>
<form action="index.php?comment=1&post=<?=$image_id?>" method="post" align:"center">
<input type="text" name="comment" placeholder="Comment...">
<input type="submit" name="submit" value="Send">
</form>
</div>
</div>