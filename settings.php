<?php

//Messages
if (isset($_GET['success']))
{
    echo "<script>alert('Account updated successfully')</script>";
}
else if (isset($_GET['server_error']))
{
    echo "<script>alert('Error trying to run a query or server error')</script>";
}
else if (isset($_GET['pass']))
{
    echo "<script>alert('Password empty or incorrect')</script>";
}
else if (isset($_GET['pass_incorrect']))
{
    echo "<script>alert('Password is incorrect.')</script>";
}
else if (isset($_GET['unable_to_connect']))
{
    echo "<script>alert('Unable to connect to the database.')</script>";
}
else if (isset($_GET['empty']))
{
    echo "<script>alert('All fields are empty. Nothing to be updated')</script>";
}
else if (isset($_GET['user_not_found']))
{
    echo "<script>alert('Incorrect Username/Password')</script>";
}
else if (isset($_GET['username']))
{
    echo "<script>alert('That username is not available. :(')</script>";
}
else if (isset($_GET['email']))
{
    echo "<script>alert('That email is not available')</script>";
}
?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Settings</title>
      <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <!--Header out here-->
  <div class="topnav" id="myTopnav">
    <a href="index.php">Home</a>
    <a href="gallery.php">Gallery</a>
    <a class="log" href="index.php?user=log">Logout</a>
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
    </div>

    <script>
    function myFunction()
    {
      var x = document.getElementById("myTopnav");
      if (x.className === "topnav") 
      {
        x.className += " responsive";
        } 
        else 
        {
          x.className = "topnav";
          }
          }
</script>
<div class="pen-title">
    <h1>Settings</h1><span> <i class='fa fa-code'></i> </span>
</div>

<div class="module form-module">
  <div class="toggle"><i class="fa fa-times fa-pencil"></i>
  </div>
  <div class="form">
    <h2>Update account</h2>
    <form action="config/settings.back.php" method="POST">
      <input type="text" name="user_name" placeholder="Username"/>
      <input type="email" name="email" placeholder="Email Address"/>
      <p>Enter password to confirm it's you</p>
      <input type="password" name="passwd" placeholder="Password to confirm">
      <button type="submit" name="submit">Update</button><br/>
      <button formaction="forgot.php">Password Settings</button>
      <button formaction="noti.php">Comments Notifications</button>
    </form>
  </div>
</div>

<div class="footer">
          <p></p>
</div>
</body>
</html>