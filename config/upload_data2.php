<?php

require_once ('database.php');

$user = "sticker";
if (!file_exists("upload"))
{
    mkdir("upload");
}

$upload_dir = "upload/";
$img = $_POST['hidden_data_2'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = $upload_dir .$user. ".png";

$success = file_put_contents($file, $data);
print $success ? $file : "<script>alert('Unable to save file')</script>";