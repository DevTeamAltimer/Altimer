<?php
    if(isset($_POST['submit'])){
        $text = $_POST['text'];
        $email = $_POST['mail'];
        $type = $_POST['type'];
        if(empty($text) || empty($email)){
            echo "<script>alert('Missing fields Error!')</script>";
            header("Refresh:0,url=index.php?support=1");
        }else{
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                echo "<script>alert('Invalid email aderess!')</script>";
                header("Refresh:0,url=index.php?support=1");
            }else{
                $info = "TEXT: ".$text." EMAIL: ".$email." TYPE: ".$type;
                mail("maxime@dunckert.de","Support",$info);
                echo "<h1>Thanks for using the support,</h1><p>We will take care of your problem :)</p>";
                echo "<button><a href='index.php?support=1'>Back</a></button>";
            }
        }
    }else{
        echo "<h1>Altimer Support</h1>
        <form action='index.php?support=1' method='POST'>
            <p>Tell us your problem</p>
            <textarea name='text' id='uploadtext' rows='4' cols='40'></textarea>
            <br>
            <p>Give us an email where we can reach you</p>
            <input type='text' name='mail' placeholder='Email'>
            <br>
            <br>
            <p>Tell us what kind of issue you have</p>
            <select name='type'>
                <option name='bug'>Bug report</option>
                <option name='register'>Register error</option>
                <option name='chat'>Chat error</option>
                <option name='post'>Post error</option>
                <option name='missing'>Missing things we could add</option>
            </select>
            <br>
            <br>
            <input type='submit' name='submit' value='Submit'>
        </form>
        <a style='color:black' href='index.php?legal=1'>Legal</a>
        ";
    }
?>