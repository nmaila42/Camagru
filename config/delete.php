<?php

session_start();
require_once('database.php');

if (isset($_SESSION['username']))
{
    if (isset($_POST['delete']))
    {
        $user = $_SESSION['username'];
        $master_dir = $_GET['picname'];
        $filename = $_GET['filename'];

        try
        {
            $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("DELETE FROM pictures WHERE name = :name");
            $stmt->execute(array(':name' => $master_dir));

            unlink("upload/".$filename);
            unlink("upload/".$user."/".$filename);
            if (file_exists("upload/sticker.png"))
            {
                unlink("upload/sticker.png");
            }
            header("Location: ../photos.php?deleted");
        }
        catch(PDOException $e)
        {
            header("Location: ../photos.php?server_error");
            exit();
        }
    }
    else
    {
        header("Location: ../photos.php?unable_to_delete");
        exit();
    }
}
else
{
    header("Location: ../photos.php?auth");
    exit();
}
?>