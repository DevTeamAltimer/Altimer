<?php
    $info = "";
    $tags = "";
    $image = $_GET['post'];
    $result = mysqli_query($conn,"SELECT * FROM uploads WHERE image='$image'");
    while($row = mysqli_fetch_assoc($result)){
        $info = $row['description'];
        $tags = $row['tags'];
    }

?>

<form action="index.php?home=1&editpost=1&post=<?=$image?>" method="post">
<h1>Edit post</h1>
<p>Description: </p>
<textarea name="description" id="uploadtext" placeholder="description"><?=$info?></textarea>
<br>
<p>Tags: </p>
<input type="text" name="tags" palceholder="tags" value="<?=$tags?>">
<br>
<br>
<input type="submit" value="submit" name="submit">
</form>