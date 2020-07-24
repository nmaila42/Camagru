<?php

require_once('config/database.php');

if (isset($_GET['reset']) && $_GET['reset'] == 1)
{
    echo ("<script>alert('A reset link has been sent your email')</script>");
}
else if (isset($_GET['email_not_found']))
{
  echo ("<script>alert('This is not a registered email. Please register first');</script>");
}
else if (isset($_GET['con']) && $_GET['con'] == "error")
{
  echo ("<script>alert('Connection to the server failed');</script>");
}
else if (isset($_GET['verify']) && $_GET['verify'] == -1)
{
  echo ("<script>alert('This account is not yet varified');</script>");
}
else if (isset($_GET['email']))
{
  echo ("<script>alert('Enter your email to get a reset link');</script>");
}
else if (isset($_GET['email_not_found']))
{
  echo ("<script>alert('This is not a registered email. Please register to get an account');</script>");
}
else if (isset($_GET['con']) && $_GET['con'] == "error")
{
  echo ("<script>alert('Connection to the server failed');</script>");
}
else if (isset($_GET['code']) && $_GET['code'] == -1)
{
  echo ("<script>alert('Invalid code entered. To reset your account enter your email and submit');</script>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!--Header out here-->
  <div class="topnav" id="myTopnav">
    <a href="index.php">Home</a>
    <a href="gallery.php">Gallery</a>
    <a href="index.php?user=log">Logout</a>
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
  </div>

    <!-- <script>
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
</script> -->
<div class="pen-title">
    <h1>Forgot</h1>
</div>

<div class="module form-module">
    <div class="toggle">
</div>
<div class="form">
    <h2>Enter your email</h2>
    <form action="config/forgot.inc.php" method="POST">
        <input type="email" name="email" placeholder="E-mail"/>
        <button type="submit" name="submit">Submit</button>
        <button formaction="login.php">Login</button>
    </form>
</div>
</div>
<div class="footer">
          <p></p>
</div>
</body>
</html>