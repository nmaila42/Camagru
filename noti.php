<?php

session_start();

if (!isset($_SESSION['username']))
{
  header("Location: index.php?user=log");
  exit();
}
if (isset($_GET['updated']))
{
    echo "<script>alert('User notifications updated')</script>";
}
else if (isset($_GET['server_error']))
{
    echo "<script>alert('Server error. Unable to update')</script>";
}
else if (isset($_GET['error']))
{
    echo "<script>alert('Error trying to validate status')</script>";
}
?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Notifications</title>
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
    <h1>Camagru</h1><span> <i class='fa fa-code'></i> </span>
</div>

<div class="module form-module">
  <div class="toggle"><i class="fa fa-times fa-pencil"></i>
  </div>
  <div class="form">
    <h2>Notification settings</h2>
    <form action="#" method="POST">
      <p>Turn on/off notifications:</p>
      <button type="submit" formaction="config/noti.back.php?status=on" name="status" value="on">On</button>
      <button type="submit" formaction="config/noti.back.php?status=off" name="status" value="off">Off</button>
      <br><br>
      <button formaction="settings.php">Settings</button>
      <button formaction="index.php">Home</button>
    </form>
  </div>
</div>

<div class="footer">
          <p></p>
</div>
</body>
</html>