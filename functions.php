<?php

function conn(){
    $conn = new mysqli("localhost","root","","altimer");
    return $conn;
}klmlk
magic_quotes_runtimelkmölmk-

function getUserName($id){
    $result = mysqli_query(conn(),"SELECT * FROM users WHERE id=".$id);
    while($row = mysqli_fetch_assoc($result)){
        $firstName = $row['firstname'];
        $lastName = $row['lastname'];
    }
    $string = "";
    $string .= strval(ucfirst($firstName));
    $string .= " ";
    $string .= strval(ucfirst($lastName));
    return $string;
}

function currentURL(){
    $url = $_SERVER[REQUEST_URI];
    return $url;
}