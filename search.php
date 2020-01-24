<?php
    if(isset($_POST['submit'])){
        $istrue = 0;
        $search = $_POST['search'];
        $search = strtolower($search);
            if($search[0] == "#"){
                $result_tag = mysqli_query($conn,"SELECT * FROM uploads");
                while($row_tag = mysqli_fetch_assoc($result_tag)){
                    $tag = $row_tag['tags'];
                    echo $tag;
                    $user = $row_tag['user_id'];
                    $image = $row_tag['image'];
                    if(strpos($search,$tag) == true){
                        $istrue = 1;
                        echo "  <li>
                                    <a href='index.php?profile=".$user."'><img class='images' src='uploads/".$image."'</a>
                                </li>
                                <br>
                        ";
                    }
                }
                if($istrue == 0){
                    echo "<script>alert('No results...')</script>";
                }
            }else{
                $result = mysqli_query($conn,"SELECT * FROM users WHERE firstname='$search' OR lastname='$search' OR  location='$search'");
                $resultcheck = mysqli_num_rows($result);
                if($resultcheck > 0){
                    echo "<ul class='results'>";
                    while($row = mysqli_fetch_assoc($result)){
                        $row_id = $row['id'];
                        $row_firstname = $row['firstname'];
                        $row_firstname = ucfirst($row_firstname);
                        $row_lastname = $row['lastname'];
                        $row_lastname = ucfirst($row_lastname);
                        $row_location = $row['location'];
                        $row_image = $row['image'];
                        
                        echo "
                            <li>
                                <div class='chatusers'><a href='index.php?profile=".$row_id."'>".ucfirst($row_firstname)." ".ucfirst($row_lastname)." ".ucfirst($row_location)."
                                    <img class='chatuserimage' src='profile_pic/".$row_image."'></a>
                            </li>
                            <br>
                        
                        ";
                }
                echo "</ul>";
                }else{
                    echo "<script>alert('No results...')</script>";
                }
            }
    }
?>