<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Gallery</title>
</head>
<body>
      <!--Header out here-->
  <div class="topnav" id="myTopnav">
    <a href="index.php">Home</a>
    <a href="gallery.php">Gallery</a>
    <a class="log" href="login.php?user=log">Logout</a>
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
    </div>

    <script>
    //Header 
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
    <h1>Gallery</h1>
</div>
<div class="footer">
          <p>© nmaila</p>
</div>
    </body>
    </html>

<?php

require_once ("config/database.php");
session_start();

if (isset($_GET['no_comment']))
{
  echo "<script>alert('Comment empty. Write something before submitting')</script>";
}
else if (isset($_GET['hacker_vibes']))
{
  echo "<script>alert('You are not authorised to enter this page. Sorry :)')</script>";
}
else if (isset($_GET['comment_sent']))
{
  echo "<script>alert('Comment sent :)')</script>";
}
else if (isset($_GET['server_error']))
{
  $msg = $_GET['msg'];
  echo "<script>alert('Server has gone away. :( ".$msg."')</script>";
}
else if (isset($_GET['not_allowed']))
{
  echo "<script>alert('You can only like a picture once!. :)')</script>";
}

try
{
  $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $conn->setAttribute(PDO::ATTR_ERRMODE, ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT * FROM pictures");
  $stmt->execute();

  $results = $stmt->fetchAll();
  $results_per_page = 5;
  $number_of_results = count($results);

  //number of pages we need
  $number_of_pages = ceil($number_of_results / $results_per_page);

  //Checking which page the user is at
  if (!isset($_GET['page']))
  {
    $page = 1;
  }
  else
  {
    $page = $_GET['page'];
  }
  
  $this_page_first_result = ($page - 1) * $results_per_page;

  //getting the limited data from the database
  $stmt = $conn->prepare("SELECT * FROM pictures ORDER BY id DESC LIMIT ". $this_page_first_result. ',' .$results_per_page);
  $stmt->execute();


  while ($row = array_reverse($stmt->fetch(PDO::FETCH_ASSOC)))
  {
    $pic = $row['name'];
    $user = $row['user'];

    //count the number of likes here!!!!
    try 
    {
      $conn2 = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $conn2->setAttribute(PDO::ATTR_ERRMODE, ERRMODE_EXCEPTION);

      $stmt2 = $conn2->prepare("SELECT * FROM likes WHERE picname = :picname");
      $stmt2->execute(array(':picname' => $pic));

      $likes = $stmt2->fetchAll();
      $likes = count($likes);
    }
    catch (PDOException $e)
    {
      header("Location: gallery.php?server_error");
      exit();
    }

    if (isset($_SESSION['username']))
    {
      $user1 = $_SESSION['username'];
      echo "<form method='post' class='form4'>
      <p>By ".$user."</p>
      <img height='300' width='400' src='".$pic."'>
      <button formaction='config/gallery.back.php?picname=".$pic."&liker=".$user1."' type='submit' name='like' value='1'>Like(".$likes.")</button>
      <input type='text' name='comment'>
      <button formaction='config/gallery.back.php?picname=".$pic."&user=".$user1."' type='submit' name='submit'>Comment</button>
      </form><br/>";
    }
    else
    {
      echo "<form method='post' class='form4'>
      <img height='300' width='400' src='".$pic."'>
      </form><br/>";
    }
  }

  for ($page = 1; $page <= $number_of_pages; $page++)
  {
    echo '<form class="form4">
      <a class="page_number" href="gallery.php?page=' .$page. '">' . $page . '</a>
      </form>'."  ";
  }
  echo "<br/><br/><br/><br/>";
}
catch(PDOException $e)
{
  header("Location: gallery.php?server_error");
  exit();
}
?>