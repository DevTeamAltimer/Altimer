<h1>Upload your photo</h1>
<form action="index.php?home=1&uploaded=1" method="post" enctype="multipart/form-data">
    <p>Select your photo</p>
    <input type="file" name="image" required>
    <br>
    <br>
    <p>Add a description for your photo</p>
    <textarea name="description" id="uploadtext" rows="4" cols="40"></textarea>
    <br>
    <br>
    <p>Add some tags seperated by commas to your image</p>
    <input type="text" name="tag" placeholder="funny,cats,..." required>
    <br>
    <br>
    <input type="submit" name="submit" value="Post">
</form>