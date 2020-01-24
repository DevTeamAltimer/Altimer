<?php
    if(isset($_SESSION['user_id'])){
        header("Refresh:0,url=index.php?home=1");
    }else{
    if(isset($_GET['success'])){
        $firstname = $_POST['firstname'];
        $firstname = strtolower($firstname);
        $lastname = $_POST['lastname'];
        $lastname = strtolower($lastname);
        $email = $_POST['email'];
        $email = strtolower($email);
        $password = $_POST['password'];
        $birthday_month = $_POST['month'];
        $birthday_day = $_POST['day'];
        $birthday_year = $_POST['year'];
        $gender = $_POST['gender'];
        
        if(empty($firstname) || empty($lastname) || empty($email) || empty($password)){
            echo "<script>alert('Missing fields Error!')</script>";
        }else{
            if(!preg_match("/^[a-zA-Z]*$/",$firstname) || !preg_match("/^[a-zA-Z]*$/",$lastname)){
                echo "<script>alert('Invalid characters!')</script>";
            }else{
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    echo "<script>alert('Invalid email adress!')</script>";
                }else{
                    $result = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
                    $resultckeck = mysqli_num_rows($result);

                    if($resultckeck > 0){
                        echo "<script>alert('Email alreay taken...')</script>";
                    }else{
                        $sql = "INSERT INTO users (firstname,lastname,email,password,birthday_day,birthday_month,birthday_year,gender) VALUES ('$firstname','$lastname','$email','$password','$birthday_day','$birthday_month','$birthday_year','$gender')";
                        mysqli_query($conn,$sql);
                        header("Refresh:0,url=index.php?login=1");
                    }
                }
            }
        }
    }
?>
<div id="signup">
    <form action="index.php?signup=1&success=1" method="post">
        <h1>Sign Up</h1>
        <p>It's quick and easy.</p>
        <input type="text" name="firstname" placeholder="First name">
        <br>
        <br>
        <input type="text" name="lastname" placeholder="Last Name">
        <br>
        <br>
        <input type="text" name="email" placeholder="Email">
        <br>
        <br>
        <input type="password" name="password" placeholder="New password">
        
        <h2>Birthday</h2>
        <select name="month">
            <option name='jan'>Jan</option>
            <option name="feb">Feb</option>
            <option name="mar">Mar</option>
            <option name="apr">Apr</option>
            <option name="may">May</option>
            <option name="jun">Jun</option>
            <option name="jul">Jul</option>
            <option name="aug">Aug</option>
            <option name="sep">Sep</option>
            <option name="oct">Oct</option>
            <option name="nov">Nov</option>
            <option name="dec">Dec</option>
        </select>
        <select name="day">
            <?php
            for($i = 1;$i<=31;$i++){
                echo "<option name='number'>".$i."</option>";
            }
            ?>
        </select>
        <select name="year">
            <?php
            for($i = date("Y");$i>=date("Y")-100;$i--){
                echo "<option name='number'>".$i."</option>";
            }
            ?>
        </select>

        <h2>Gender</h2>
        <select name="gender">
            <option name='male'>Male</option>
            <option name="female">Female</option>
        </select>
        <br>
        <br>
        <input class="floating" type="submit" name="submit" value="Sign Up">
    </form>
    <button class="floatinga"><a href="index.php?login=1">Login</a></button>
</div>
<?php
    }
?>