<?php
    // Logout
    if(isset($_GET['logout'])){
        session_unset();
        session_destroy();
        header("Refresh:0,url=index.php?home=1");
    }

    //edit post info
    if(isset($_GET['editpost'])){
        if(isset($_POST['submit'])){
            $descriptionEdit = "";
            $tagEdit = "";
            $imageEdit = $_GET['post'];
            if(isset($_POST['description'])){
                $descriptionEdit = $_POST['description'];
            }
            if(isset($_POST['tags'])){
                $tagEdit = $_POST['tags'];
            }
            if($tagEdit != "" && $descriptionEdit != ""){
                if($tagEdit != ""){
                    if($descriptionEdit != ""){
                        mysqli_query($conn,"UPDATE uploads SET description='$descriptionEdit',tags='$tagEdit' WHERE image='$imageEdit'");
                    }else{
                        mysqli_query($conn,"UPDATE uploads SET description='$descriptionEdit' WHERE image='$imageEdit'");
                    }
                }else{
                if($descriptionEdit != ""){
                    if($tagEdit != ""){
                        mysqli_query($conn,"UPDATE uploads SET description='$descriptionEdit',tags='$tagEdit' WHERE image='$imageEdit'");
                    }else{
                        mysqli_query($conn,"UPDATE uploads SET tags='$tagEdit' WHERE image='$imageEdit'");
                    }
                }
            }
            }else{
                echo "<script>alert('Select at least one field')</script>";
            }
        }
    }

    //uploads post
    if(isset($_GET['uploaded'])){
        if(isset($_POST['submit'])){
            $image = $_FILES['image'];
            $tags = array();
            $tag = $_POST['tag'];
            $tag = strtolower($tag);
            foreach($tag as $value){
                array_push($tags,$value);
            }
            if(!(empty($image))){
                $imagename = $_FILES['image']['name'];
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageSize = $_FILES['image']['size'];
                $imageError = $_FILES['image']['error'];
                $imageType = $_FILES['image']['type'];

                $imageExt = explode(".",$imagename);
                $imageActExt = strtolower(end($imageExt));

                $description = $_POST['description'];

                $allowed = array ('jpg', 'jpeg', 'png');
                //checks if file is a image
                if(in_array($imageActExt,$allowed)){
                    //checks if image has an error
                    if($imageError === 0){
                        //checks if image is less that 500mb
                        if($imageSize < 500000){
                            //creates a unique id by milliseconds
                            $imageNameNew = uniqid('',true).".".$imageActExt;
                            //sets storing folder
                            $imagelocation = 'uploads/'.$imageNameNew;
                            //moves image to storing folder
                            move_uploaded_file($imageTmpName,$imagelocation);
                            //uploads filename to database
                            if(empty($description)){
                                $sql_upload = "INSERT INTO uploads (user_id,image,tags) VALUES ('$user_id','$imageNameNew','$tag')";
                            }else{
                                $sql_upload = "INSERT INTO uploads (user_id,image,description,tags) VALUES ('$user_id','$imageNameNew','$description','$tag')";
                            }
                            mysqli_query($conn,$sql_upload) or die(mysqli_error($conn));
                            header("Refresh:0,url=index.php?home=1");
                        }else{
                            echo "<script>alert('This file is to big!')</script>";
                        }
                    }else{
                        echo "<script>alert('Upload Error!')</script>";
                    }

                }
            }
        }
    }
    //deletes post
    if(isset($_GET['delete'])){
        $post = $_GET['post'];
        mysqli_query($conn,"DELETE FROM uploads WHERE image='$post'");
        unlink("uploads/".$post);
        header("Refresh:0,url=index.php?home=1");
    }

    //set image to liked in database
    if(isset($_GET['like'])){
        $imageId = $_GET['like'];

        $result_like = mysqli_query($conn,"SELECT * FROM usersxuploads WHERE user_id=".$user_id." AND image_id=".$imageId);
        $result_like_check = mysqli_num_rows($result_like);
        if($result_like_check == 0){
            mysqli_query($conn,"INSERT INTO usersxuploads (user_id,image_id) VALUES (".$user_id.",".$imageId.")");
            header("Refresh:0,url=index.php?home=1");
        }else{
            mysqli_query($conn,"DELETE FROM usersxuploads WHERE user_id=".$user_id." AND image_id=".$imageId);
            header("Refresh:0,url=index.php?home=1");
        }
    }

?>
<!--
    shows posted images
-->
<div class="feed">
    <?php
        $result = mysqli_query($conn, "SELECT * FROM uploads ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($result)){
            $image = $row['image'];
            $image_id = $row['id'];
            $user = $row['user_id'];
            $description = $row['description'];
            $firstname = "";
            $lastname = "";
            
            //gets first & lastname of user with post id
            $results = mysqli_query($conn,"SELECT * FROM users WHERE id=".$user);
            while($roww = mysqli_fetch_assoc($results)){
                $firstname = $roww['firstname'];
                $lastname = $roww['lastname'];
            }
            echo "
                <section>
                <div class='posts'>
                    <p style='text-align:center'><img id=".$image." class='images rounded mx-auto d-block' src='uploads/".$image."'></p>
                    <p>".$description."</p>";
                    //checks if current user has posted this post
                    if($user == $user_id){
                        echo "  <div class='delete post'>
                                    <a href='index.php?editpost=1&post=".$image."'><i class='fas fa-edit'></i></a>
                                    <a href='index.php?comment=1&post=".$image_id."'><i class='far fa-comments'></i></a>
                                    <a href='index.php?home=1&delete=1&post=".$image."'><i class='far fa-trash-alt'></i></a>
                                </div>
                        ";
                    }else{
                        //shows a different heart if like is true in database
                        $result_like_row = mysqli_query($conn,"SELECT * FROM usersxuploads WHERE user_id=".$user_id." AND image_id=".$image_id);
                        $result_like_row_check = mysqli_num_rows($result_like_row);
                        $result_likes = mysqli_query($conn,"SELECT * FROM usersxuploads WHERE image_id=".$image_id);
                        $result_likes_number = mysqli_num_rows($result_likes);
                        //checks if likes are == 0
                        if($result_like_row_check == 0){
                            if($result_likes_number > 0){      
                                echo "  <div class='delete post'>
                                            <a href='index.php?comment=1&post=".$image_id."'><i class='far fa-comments'></i></a>
                                            <a href='index.php?home=1&like=".$image_id."'>".$result_likes_number."  <i class='far fa-heart'></i></a>
                                        </div>
                                ";
                            }else{
                                echo "  <div class='delete post'>
                                            <a href='index.php?comment=1&post=".$image_id."'><i class='far fa-comments'></i></a>
                                            <a href='index.php?home=1&like=".$image_id."'><i class='far fa-heart'></i></a>
                                        </div>
                                ";
                            }
                        }else{
                            if($result_likes_number > 0){      
                                echo "  <div class='delete post'>
                                            <a href='index.php?comment=1&post=".$image_id."'><i class='far fa-comments'></i></a>
                                            <a href='index.php?home=1&like=".$image_id."'>".$result_likes_number."  <i class='fas fa-heart'></i></a>
                                        </div>
                                ";
                            }else{
                                echo "  <div class='delete post'>
                                            <a href='index.php?comment=1&post=".$image_id."'><i class='far fa-comments'></i></a>
                                            <a href='index.php?home=1&like=".$image_id."'><i class='fas fa-heart'></i></a>
                                        </div>
                                ";
                            }
                        }
                    }
                    //shows username and description
                    echo "
            <p>Uploaded by <a class='usernames' href='index.php?profile=".$user."'>".ucfirst($firstname)." ".ucfirst($lastname)."</a></p>
                </div>
                <hr>
                </section>
            ";
                }

            
    ?>
</div>