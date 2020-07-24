<?php

require_once('database.php');

if (isset($_POST['email']))
{
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        header("Location: ../forgot.php?invalid=1");
    }
    else if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        //Checking if the email exists in the database
        try
        {
            $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(array(':email' => $email));

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result))
            {
                foreach($result as $row)
                {
                    if ($row['active'] == 1)
                    {
                        $con_code = $row['con_code'];
                        $url_salt = hash('whirlpool', rand(0, 100000));
                        $to = $email;
                        $subject = "Reset code from PixelX";
                        $msg = "To reset your account password\nClick the link below\n\nhttp://localhost:8080/camagru/reset.php?reset=1&code=".$con_code."&email=".$email."&com=".$url_salt;
                        $headers = 'From: noreply@pixelx.com';
                        mail($to, $subject, $msg, $headers);
                        header("Location: ../forgot.php?reset=1");
                    }
                    else
                    {
                        header("Location: ../forgot.php?verify=-1");
                        exit();
                    }
                }
            }
            else
            {
                header("Location: ../forgot.php?email_not_found");
                exit();
            }
        }
        catch(PDOException $e)
        {
            //if there's an error
            header("Location: ../forgot.php?con=error");
            exit();
        }
    }
}
else
{
    header("Location: ../forgot.php?email");
    exit();
}
?>