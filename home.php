<?php

include_once ('config/database.php');

if (isset($_GET['verify']) && $_GET['verify'] == 1 && isset($_GET['email']) && isset($_GET['code']) && isset($_GET['com']))
{
$user = $_GET['email'];
$code = $_GET['code'];

try
{
    //veryfying the user in the database
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(array(':email' => $user));

    //getting the row
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result))
    {
        //Checking if the code matches
        foreach($result as $row)
        {
            if ($row['con_code'] == $code)
            {
                try
                {
                    //Updating user info from active = 0 to active = 1
                    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $conn->prepare('UPDATE users SET active = :active WHERE email = :email');
                    $stmt->execute(array(':active' => '1', ':email' => $user));

                    //Updating the con_code
                    $new_code = hash('whirlpool', rand(0,100000));
                    $stmt = $conn->prepare('UPDATE users SET con_code = :new_code WHERE email = :email');
                    $stmt->execute(array(':new_code' => $new_code, ':email' => $user));
                    echo ("<script>alert('Account is now active!')</script>");
                }
                catch(PDOException $e)
                {
                    header("Location: index.php?con=error");
                    exit();
                }
            }
            else
            {
                //if the code doesn't match
                header("Location: index.php?code=-1");
                exit();
            }
        }
    }
    else
    {
        //if user doesn't exist
        header("Location: index.php?code=-1");
        exit();
    }
}
catch(PDOException $e)
{
    //connection error
    header("Location: index.php?con=error");
    exit();
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="pen-title">
        <h1>home</h1>
    </div>
    
    <div class="module form-module">
        <div class="toggle">
        </div>
        <div class="form">
            <h2>You can now log in to your account :)</h2>
            <form action="" method="POST">
                <button formaction="login.php">Login</button>
            </form>
        </div>
    </div> 
    <div class="footer">
          <p></p>
</div> 
</body>
</html>