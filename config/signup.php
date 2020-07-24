<?php

require_once ('database.php');

if (isset($_POST['submit']))
{
    $username = $_POST['user_name'];
    $email = $_POST['email'];
    $passwd = $_POST['passwd'];

    //Error handling
    //Empty fields
    //password strength checker
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
    if (empty($username) || empty($email) || empty('passwd'))
    {
        header("Location: ../index.php?signup=empty");
        exit();
    }
    else if (!preg_match("/^[a-zA-Z]*$/", $username)) //Checking if the username is characters only
    {
        header("Location: ../index.php?signup=invalid");
        exit();
    }
    else if (passcheck($passwd) == 1)
    {
        header("Location: ../index.php?pas=weak");
        exit();
    }
    else
    {
        try
        {
            //Checking the user exists
            $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Preparing the query
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR user_name = :username");

            //executing the query
            $stmt->execute(array(':email' => $email, ':username' => $username));

            $result = $stmt->fetchAll();
            if (count($result))
            {
                foreach($result as $row)
                {
                    if ($row['email'] == $email)
                    {
                        header("Location: ../index.php?signup=email");
                        exit();
                    }
                    else if ($row['user_name'] == $username)
                    {
                        header("Location: ../index.php?signup=username");
                        exit();
                    }
                }
            }
            else
            {
                //randomising the con_code for user verification
                $con_code = hash('whirlpool', rand(0, 10000));
                $url_salt = hash('whirlpool', rand(0,100000));
                //hashing the password
                $hashedPw = password_hash($passwd, PASSWORD_DEFAULT);

                try
                {
                    //Inserting data to the database
                    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $conn->prepare("INSERT INTO users (user_name, email, passwd, con_code)
                    VALUES(:username, :email, :passwd, :con_code)");

                    $stmt->execute(array(':username' => $username, ':email' => $email, ':passwd' => $hashedPw, ':con_code' => $con_code));

                    //sending the mail
                    $to = $email;
                    $subject = "Verification code from PixelX";
                    $msg = "Welcome to StaGazers your account needs to be activated\nTo activate your account click on the link below\n\nhttp://localhost:8080/camagru/home.php?verify=1&code=".$con_code."&email=".$email."&com=".$url_salt;
                    $headers = 'From: noreply@stargazers.com';
                    mail($to, $subject, $msg, $headers);
                    header("Location: ../index.php?verify=0");
                    exit();
                }
                catch(PDOException $e)
                {
                    //if there is an error
                    $error = $e->getMessage();
                    header("Location: ../index.php?con=error&msg=".$error);
                    exit();
                }
            }
        }
        catch (PDOException $e)
        {
            //if there is an error
            $error = $e->getMessage();
            header("Location: ../index.php?con=error&msg=".$error);
            exit();
        }
    }
}
else
{
    header("Location: ../index.php?submit=0");
    exit();
}
?>