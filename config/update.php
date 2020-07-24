<?php

session_start();
require_once('database.php');

if (isset($_POST['newpd']) && isset($_POST['conpd']))
{
    $email = $_SESSION['email'];
    $newpd = $_POST['newpd'];
    $conpd = $_POST['conpd'];

    function passcheck($str)
    {
        if (strlen($str) <= 7)
        {
            //checking if the password is 8 more characters long 
            return (1);
        }
        if (!preg_match("#[0-9]+#", $str))
        {
            //checking if the password consists of a number
            return (1);
        }
        if (!preg_match("#[a-z]+#", $str))
        {
            //checking if the password consists of a lowercase letter
            return (1);
        }
        if (!preg_match("#[A-Z]+#", $str))
        {
            //checking if the password consists of a uppercase letter
            return (1);
        }
        else
        {
            return (0);
        }
    }
    if (empty($newpd) || empty($conpd))
    {
        header("Location: ../reset.php?empty");
        exit();
    }
    else if (passcheck($newpd) == 1)
    {
        header("Location: ../reset.php?pas=weak");
        exit();
    }
    else
    {
        if ($newpd == $conpd)
        {
            $passwd = password_hash($newpd, PASSWORD_DEFAULT);
            try
            {
                $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("UPDATE users SET passwd = :passwd WHERE email = :email");
                $stmt->execute(array(':passwd' => $passwd, 'email' => $email));
                session_destroy();
                header("Location: ../login.php?reset=suc");
            }
            catch (PDOException $e)
            {
                header("Location: ../login.php?con=error");
                exit();
            }
        }
        else
        {
            header("Location: ../reset.php?match");
            exit();
        }
    }
}
else
{
    header("Location: ../reset.php?empty");
    exit();
}
?>