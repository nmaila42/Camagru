<?php

session_start();
require_once ('database.php');

$user = $_SESSION['username'];
$upload_dir = "upload/";
$userdir = $upload_dir.$user."/";

if (!file_exists($upload_dir))
{
    mkdir($upload_dir);
}
if (!file_exists($userdir))
{
    mkdir($userdir);
}

$img = $_POST['hidden_data'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$image_name = $user.mktime(). ".png";
$file = $upload_dir .$image_name;
$file_2 = $userdir.$image_name;

$saved_file = file_put_contents($file, $data);

$image = $file;
$image_sticker = "upload/sticker.png";

function super_impose($src, $dest, $sticker)
{
    $image_1 = imagecreatefrompng($src);
    $stamp = imagecreatefrompng($sticker);
    
    list($width, $height) = getimagesize($src);
    list($width_small, $height_small) = getimagesize($sticker);
    $marge_right = ($width / 2) - ($width_small / 2);
    $marge_bottom = ($height / 2) - ($height_small / 2);

    $sx = imagesx($stamp);
    $sy = imagesy($stamp);
    imagealphablending($image_1, true);
    imagesavealpha($image_1, true);
    imagecopy($image_1, $stamp, imagesx($image_1) - $sx - $marge_right, imagesy($image_1) - $sy - $marge_bottom, 0, 0, $width_small, $height_small);
    return imagepng($image_1, $dest);
}
$new_image = super_impose($image, $file, $image_sticker);
copy($file, $file_2);

//Sending image path to database
try
{
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO pictures (name, user, type, ext)
    VALUES(:name, :user, :type, :ext)");
    $stmt->execute(array(':name' => "config/".$file, ':user' => $user, ':type' => "image", ':ext' => "png"));
}
catch(PDOException $e)
{
    header("Location: ../cam.php?server_error");
    exit();
}
//print $success ? $file : "<script>alert('Unable to save file')</script>";
?>