<?php
    if(isset($_SESSION['user_id'])){
        header("Refresh:0,url=index.php?home=1");
    }else{
?>
<form action="index.php?home=1&loginsuccess=1" method="post">
    <h1>Login</h1>
    <p>For full access login please.</p>
    <p>Make sure not to miss any fields.</p>    
    <input type="text" name="email" placeholder="Email">
    <br>
    <br>
    <input type="password" name="password" placeholder="Password">
    <br>
    <br>
    <input class="floating" type="submit" name="submit" value="Login">
    <button class="floatinga"><a href="index.php?signup=1">Sign Up</a></button>
</form>
<?php       
    }
?>