<?php
    //gets current profile you looking at
    $user = $_GET['profile'];
    
    //shows users profile pic
    if(isset($_GET['picture'])){
        $sql = "SELECT * FROM users WHERE id=".$user;
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            $image = $row['image'];
            echo "  <button class='backbutton'>Back</button>
                    <p class='showpic'>
                    <img src='profile_pic/$image'>
                    </p>
            ";
        }
    }else{
        //if it is the own profile it switches to profile editor
        if($user == $_SESSION['user_id']){
            header("Refresh:0,url=index.php?showprofile=1");
        }else{
            $sql = "SELECT * FROM users WHERE id=".$user;
            $result = mysqli_query($conn,$sql);
            //gets user information
            while($row = mysqli_fetch_assoc($result)){
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $birthday_day = $row['birthday_day'];
                $birthday_month = $row['birthday_month'];
                $birthday_year = $row['birthday_year'];
                $gender = $row['gender'];
                if(isset($row['location'])){
                  $location = $row['location'];
                }else{
                  $location = "";
                }
            }
            //showprofile is content div for this page
            echo "
                    <div class='showprofile'>
                        <div class='gender'>
                            ";
                            //shows users gender icon
                            if($gender == "Male"){
                                echo "<i class='fas fa-mars'></i>";
                            }else{
                                echo "<i class='fas fa-venus'></i>";
                            }
                            //shows users name and profile pic
            echo "
                        </div>
                        <h1 style='text-align:center' class='profilename'>".ucfirst($firstname)." ".ucfirst($lastname)."</h1>
                        <a href='index.php?profile=".$user."&picture=1'><span class='circle_profile showprofile_circle'></span></a>
                        ";
                        //checks if already friend
                        $friendResult = mysqli_query($conn,"SELECT * FROM friends WHERE to_user_id=".$user." AND user_id=".$user_id." OR to_user_id=".$user_id." AND user_id=".$user);
                        $friendResultCheck = mysqli_num_rows($friendResult);
                        //shows normal info about user
                        if(!(isset($_GET['showpost'])) && !(isset($_GET['chat'])) && !(isset($_GET['friendadd'])) && !(isset($_GET['showfriends']))){
                          echo "<ul>
                              <li>Birthday: ".$birthday_month.", ".$birthday_day." ".$birthday_year."</li>
                              <br>
                              <li>Location: ".$location."</li>
                              <br>
                              <button><a href='index.php?profile=".$user."&showpost=1'>Show posts</a></button>
                              <button><a href='index.php?profile=".$user."&chat=1'>Chat with user</a></button>
                              <button><a href='index.php?profile=".$user."&showfriends=1'>Show friends</a></button>
                              ";
                              //friend add/remove button
                              if($friendResultCheck == 1){
                                echo 
                                  "
                                  <form class='float-left friend-remove' action='index.php?profile=".$user."&friendadd=1' method='post'>
                                  <input type='submit' name='remove' value='Remove friend'>
                                  </form>
                                  ";
                              }else{
                                echo 
                                  "
                                  <form class='float-left friend-add' action='index.php?profile=".$user."&friendadd=1' method='post'>
                                  <input type='submit' name='add' value='Add as a friend'>
                                  </form>
                                  ";
                              }
                              echo "
                          </ul>
                      </div>";
                      //shows all post user has posted
                    }elseif(isset($_GET['showpost'])){
                          $sql_post = "SELECT * FROM uploads WHERE user_id=".$user;
                          $result_post = mysqli_query($conn,$sql_post);
                          echo "<div class='userpost-div'>";
                          while($row_post = mysqli_fetch_assoc($result_post)){
                              $posts = $row_post['image'];
                              echo "<img class='userposts' src='uploads/".$posts."'>";
                          }
                          echo "</div>";
                          //opens chat with user
                        }elseif(isset($_GET['chat'])){
                            mysqli_query($conn,"UPDATE chat set to_user_seen = 1 WHERE to_user =".$user_id." AND user_id".$user);
                            //if form has detected new message post it
                            if(isset($_POST['submit'])){
                              $message = $_POST['message'];
                              mysqli_query($conn,"INSERT INTO chat (user_id,to_user,messages) VALUES (".$user_id.",".$user.",'".$message."')");
                              mysqli_query($conn,"UPDATE chat set from_user_seen=1 WHERE user_id=".$user_id." AND to_user=".$user);
                            }
                            //if delete button has clicked delete image with fitting id
                            if(isset($_GET['delete'])){
                              $delete = $_GET['delete'];
                              mysqli_query($conn,"DELETE FROM chat WHERE id=".$delete);
                              header("Refresh:0,url=index.php?profile=".$user."&chat=1");
                            }
                            //creates chat window
                            echo "<div class='chatwindow'><div class='chat-content'>";
                            $result_chat = mysqli_query($conn,"SELECT * FROM chat WHERE user_id=".$user_id." AND to_user=".$user." OR to_user=".$user_id." OR user_id=".$user);
                            //checks if users have even chated before
                            $result_chat_check = mysqli_num_rows($result_chat);
                            if($result_chat_check < 0){
                              
                            }else{
                              while($row_chat = mysqli_fetch_assoc($result_chat)){
                                if(isset($row_chat['messages'])){
                                  $message_row = $row_chat['messages'];
                                }else{
                                  $message_row = "";
                                }
                                if(isset($row_chat['user_id'])){
                                  $user_id_row = $row_chat['user_id'];
                                }else{
                                  $user_id_row = "";
                                }
                                if(isset($row_chat['id'])){
                                  $chat_id = $row_chat['id'];
                                }else{
                                  $chat_id = "";
                                }
                                //if user_id_row(message send user) == own id align right and insert own profile pic
                                if($user_id_row == $user_id){
                                  $result_chat_own = mysqli_query($conn,"SELECT * FROM users WHERE id=".$user_id);
                                  while($row_chat_own = mysqli_fetch_assoc($result_chat_own)){
                                    $own_image = $row_chat_own['image'];
                                  }
                                  echo "<div class='chat-own-post'><a href='index.php?profile=".$user_id_row."'><img src='profile_pic/".$own_image."' class='chat-image-own'></a><a class='chat-delete' href='index.php?profile=".$user."&chat=1&delete=".$chat_id."'><i class='far fa-trash-alt'></i></a><p>".$message_row."</p></div>";
                                  echo "<br><br><br><br>";
                                }
                                if($user_id_row == $user){
                                  $result_chat_user = mysqli_query($conn,"SELECT * FROM users WHERE id=".$user);
                                  while($row_chat_user = mysqli_fetch_assoc($result_chat_user)){
                                    $usre_image = $row_chat_user['image'];
                                  }
                                  echo "<div class='chat-user-post'><a href='index.php?profile=".$user_id_row."'><img src='profile_pic/".$usre_image."' class='chat-image-user'></a><p>".$message_row."</p></div>";
                                  echo "<br><br><br><br>";
                                }
                              }
                              //enter message form window
                              echo "</div>
                                      <form class='chat-form' action='index.php?profile=".$user."&chat=1' method='post'>
                                      <hr>
                                        <input type='text' name='message' placeholder='message...'>
                                        <input type='submit' name='submit' value='Send'>
                                      </form></div>
                              ";
                            }
                        }//add user as friend
                          elseif(isset($_GET['friendadd'])){
                            if(isset($_POST['add'])){
                              mysqli_query($conn,"INSERT INTO friends (user_id,to_user_id) VALUES (".$user_id.",".$user.")");
                              header("Refresh:0,url=index.php?profile=".$user);
                            }
                            if(isset($_POST['remove'])){
                              $test = mysqli_query($conn,"DELETE FROM friends WHERE user_id=".$user_id." AND to_user_id=".$user." OR user_id=".$user." AND to_user_id=".$user_id);
                              header("Refresh:0,url=index.php?profile=".$user);
                            }
                          }elseif(isset($_GET['showfriends'])){
                            //users friends image array
                            $imagearray = array();
                            //users friends id
                            $userarray = array();
                            
                            //selects * where id of user is in friend table
                            $result_userId = mysqli_query($conn,"SELECT * FROM friends WHERE user_id=".$user." OR to_user_id=".$user);
                            echo "<div class='userpost-div'>";
                            while($row_userId = mysqli_fetch_assoc($result_userId)){
                                
                                $userId = $row_userId['user_id'];
                                $toUserId = $row_userId['to_user_id'];
                              
                                //if user_id table is = users id uses other id
                                if($userId == $user){
                                    //move to userarray
                                    array_push($userarray,$toUserId);
                                }
                                //same as above
                                if($toUserId == $user){
                                    array_push($userarray,$userId);
                                }
                                
                            }
                            //foreach item in userarray (ids of friends) it loops threw code
                            foreach($userarray as $value){
                                //selects img from users where id=item in array
                                $result = mysqli_query($conn,"SELECT * FROM users WHERE id=".$value);
                                while($row = mysqli_fetch_assoc($result)){
                                    $image = $row['image'];
                                    //moves image from friend id in imagearray
                                    array_push($imagearray,$image);
                                }
                            }
                            $i = 0;
                            //foreach item in imagearray (profile pics of friends) it loops
                            foreach($imagearray as $imagevalue){
                              echo "<a href='index.php?profile=".$userarray[$i]."'><img class='userposts' src='profile_pic/".$imagevalue."'></a>";
                              $i++;
                            }
                
                            echo "</div>";
                          }
        }
    }



?>
