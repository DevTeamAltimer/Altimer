<?php
    $sql = "SELECT * FROM chat WHERE user_id=".$user_id." OR to_user=".$user_id;
    $query = mysqli_query($conn,$sql);
    $result = mysqli_num_rows($query);
    if($result != 0){
        echo "
                <div class='chatrow'>";
                $users = array();
                $other_users = array();
                $notseen = 0;
                while($row = mysqli_fetch_assoc($query)){
                    $user = $row['to_user'];
                    if($user == $user_id){
                        $other_user = $row['user_id'];
                        if(!in_array($other_user,$other_users) && !in_array($other_user,$users)){
                            $search = "SELECT * FROM users WHERE id=".$other_user;
                            $query_search = mysqli_query($conn,$search);
                            while($row_search = mysqli_fetch_assoc($query_search)){
                                $firstname = $row_search['firstname'];
                                $lastname = $row_search['lastname'];
                                $image = $row_search['image'];
                                echo "<br><div class='chatusers'><a href='index.php?profile=".$other_user."&chat=1'>".ucfirst($firstname)." ".ucfirst($lastname)."
                                <img class='chatuserimage' src='profile_pic/".$image."'></a>";
                                if($notseen > 0){
                                    echo "<span class='badge'>".$notseen."</span></div><br>";
                                }else{
                                    echo "</div><br>";
                                }
                        }
                            array_push($other_users,$other_user);
                        }
                    }else{
                        if(!in_array($user,$users) && !in_array($user,$other_users)){
                            $search = "SELECT * FROM users WHERE id=".$user;
                            $query_search = mysqli_query($conn,$search);
                            while($row_search = mysqli_fetch_assoc($query_search)){
                                $firstname = $row_search['firstname'];
                                $lastname = $row_search['lastname'];
                                $image = $row_search['image'];
                                echo "<br><div class='chatusers'><a href='index.php?profile=".$user."&chat=1'>".ucfirst($firstname)." ".ucfirst($lastname)."
                                <img class='chatuserimage' src='profile_pic/".$image."'></a>";
                                if($notseen > 0){
                                    echo "<span class='badge'>".$notseen."</span></div><br>";
                                }else{
                                    echo "</div><br>";
                                }
                        }
                            array_push($users,$user);
                        }
                    }
                }
        echo "
                </div>
        ";
    }else{
        
    }
?>