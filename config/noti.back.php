<?php

require_once('database.php');
session_start();

if (isset($_SESSION['username']))
{
    if (isset($_GET['status']))
    {
        $status = $_GET['status'];
        if ($status == "on")
        {
            $n = 1;
        }
        else
        {
            $n = 0;
        }
        $username = $_SESSION['username'];

        //updating notification settings
        try
        {
            $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("UPDATE users SET noti = :n WHERE user_name = :username");
            $stmt->execute(array(':n' => $n, ':username' => $username));
            header("Location: ../noti.php?updated");
        }
        catch(PDOException $e)
        {
            header("Location: ../noti.php?server_error");
            exit();
        }
    }
    else
    {
        header("Location: ../noti.php?error");
        exit();
    }
}
else
{
    header("Location: ../index.php?user=log");
    exit();
}
?>