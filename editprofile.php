<?php
    $countries = array("Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua &amp; Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia &amp; Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central Arfrican Republic","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauro","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre &amp; Miquelon","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St Kitts &amp; Nevis","St Lucia","St Vincent","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad &amp; Tobago","Tunisia","Turkey","Turkmenistan","Turks &amp; Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe");
    if(isset($_POST['submit'])){
        $image = $_FILES['image'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $location = $_POST['country'];
        $password = $_POST['password'];


        if(empty($firstname) || empty($lastname) || empty($location) || empty($password) || empty($image)){
            echo "<script>alert('Missing fields Error!')</script>";
        }else{
            if(!(in_array($location,$countries))){
                echo "<script>alert('No valid country')</script>";
            }else{
            $sql_upload2 = "UPDATE users SET location='$location' WHERE id=".$user_id;
            mysqli_query($conn,$sql_upload2) or die(mysqli_error($conn));
            if(!(empty($image))){
                $imagename = $_FILES['image']['name'];
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageSize = $_FILES['image']['size'];
                $imageError = $_FILES['image']['error'];
                $imageType = $_FILES['image']['type'];

                $imageExt = explode(".",$imagename);
                $imageActExt = strtolower(end($imageExt));

                $allowed = array ('jpg', 'jpeg', 'png');
                if(in_array($imageActExt,$allowed)){
                    if($imageError === 0){
                        if($imageSize < 500000){
                            $imageNameNew = uniqid('',true).".".$imageActExt;
                            $imagelocation = 'profile_pic/'.$imageNameNew;
                            move_uploaded_file($imageTmpName,$imagelocation);
                            $sql_upload = "UPDATE users SET image='$imageNameNew' WHERE id=".$user_id;
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
    }
?>
<?php
    $sql_form = "SELECT * FROM users WHERE id=".$user_id;
    $result_form = mysqli_query($conn,$sql_form);
    while($row = mysqli_fetch_assoc($result_form)){
        $firstname_row = $row['firstname'];
        $lastname_row = $row['lastname'];
        if(isset($row['location'])){
          $location_row = $row['location'];
        }else{
          $location_row = "";
        }
        $password_row = $row['password'];
    }
?>
<h1>Edit your profile</h1>
<form action="index.php?editprofile=1&edit=1" method="POST" enctype="multipart/form-data">
    <input type="text" name="firstname" value="<?=ucfirst($firstname_row)?>" placeholder="Firstname">
    <br>
    <br>
    <input type="text" name="lastname" value="<?=ucfirst($lastname_row)?>" placeholder="Lastname">
    <br>
    <br>
    <p>Edit your country</p>
    <input type="text" name="country" id="countryinput" value="<?=$location_row?>" placeholder="Country">
    <br>
    <br>
    <input type="password" name="password" value="<?=$password_row?>" placeholder="Password">
    <br>
    <br>
    <p>Upload profile picture</p>
    <input type="file" name="image">
    <br>
    <br>
    <input type="submit" name="submit" value="Edit">
</form>
