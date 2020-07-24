<?php

require_once('database.php');
session_start();
$user = $_SESSION['username'];

$dir_upload = "upload/";
$userdir = $dir_upload.$user."/";

if (!file_exists($dir_upload))
{
    mkdir($dir_upload);
}
if (!file_exists($userdir))
{
    mkdir($userdir);
}

if (isset($_FILES['image']))
{
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $exploded = explode('.', $file_name);
    $file_ext = strtolower(end($exploded));

    $extensions = array("jpeg", "jpg", "png");

    if ($file_size == false)
    {
        header("Location: ../cam.php?no_image");
        exit();
    }
    else if (in_array($file_ext, $extensions) === false)
    {
        header("Location: ../cam.php?format_not_supported");
        exit();
    }
    else if ($file_size > 100000000)
    {
        header("Location: ../cam.php?file_too_large");
        exit();
    }
    else
    {
        $file_path = $dir_upload.$file_name;
        $file_path_2 = $userdir.$file_name;

        if (file_exists($file_path))
        {
            header("Location: ../cam.php?file_exists");
            exit();
        }
        else
        {
            move_uploaded_file($file_tmp, $file_path);
            
            //renaming file
            $new_name = $user.mktime().".".$file_ext;
            $new_path = $dir_upload.$new_name;
            $new_path2 = $userdir.$new_name;

            rename($file_path, $new_path);
            copy($new_path, $new_path2);
            try
            {
                $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $conn->prepare("INSERT INTO pictures (name, user, type, ext)
                VALUES(:name, :user, :type, :ext)");
                $stmt->execute(array(':name' => "config/".$new_path, ':user' => $user, 'type' => "image", ':ext' => $file_ext));
            }
            catch(PDOException $e)
            {
                header("Location: ../cam.php?server_error");
                exit();
            }
            header("Location: ../cam.php?file_uploaded&file_path=".$file_path);
            exit();
        }
    }
}
else
{
    header("Location: ../cam.php?file_not_found");
    exit();
}

?>