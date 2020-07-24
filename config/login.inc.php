<?php

require_once('database.php');
session_start();

if (isset($_POST['submit']))
{
    $login = $_POST['login'];
    $passwd = $_POST['passwd'];

    if (empty($login) || empty($passwd))
    {
        header("Location: ../login.php?login=empty");
        exit();
    }
    else
    {
        try
        {
            //connecting and checking the user in the database
            $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare('SELECT * FROM users WHERE email = :email OR user_name = :username');
            $stmt->execute(array(':email' => $login, ':username' => $login));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($result))
            {
                foreach($result as $row)
                {
                    //Checking if details are correct
                    if ($row['active'] == 1 && password_verify($passwd ,$row['passwd']))
                    {
                        $_SESSION['username'] = $row['user_name'];
                        $_SESSION['email'] = $row['email'];
                        $folder_name = $_SESSION['username'];
                        if (file_exists("upload/".$folder_name))
                        {
                            mkdir("upload/".$folder_name);
                        }
                        header("Location: ../login.php?verify=1");
                        exit();
                    }
                    else if ($row['active'] == 0)
                    {
                        header("Location: ../login.php?verify=0");
                        exit();
                    }
                    else
                    {
                        header("Location: ../login.php?user=invalid");
                        exit();
                    }
                }
            }
            else
            {
                //if the user is not found
                header("Location: ../login.php?user=not_found");
                exit();
            }
        }
        catch(PDOException $e)
        {
            header("location: ../login.php?server=error");
            exit();
        }
    }
}
else
{
    header("Location: login.php");
    exit();
}
?>