<?php
//AUTHOR : amatshiy

require_once ('database.php');

//Deleting the database if it exists...
try
{
    $conn = new PDO('mysql:host=127.0.0.1', $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //Preparing the query
    $stmt = $conn->prepare('DROP DATABASE mydata');
    
    //executing the query
    $stmt->execute();
    echo "Database deleted <br/>";
    $found = 1;
}
catch (PDOException $e)
{
    $found = 0;
    echo "Database not found<br/>";
}
$conn = null;

//Re-creating the database
try
{
    $conn = new PDO('mysql:host=127.0.0.1', $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //Preparing the query
    $stmt = $conn->prepare('CREATE DATABASE mydata');
    
    //executing the query
    $stmt->execute();
    if ($found == 1)
    {
        echo "Re-creating database <br/>";
    }
    else
    {
        echo "Creating database <br/>";
    }
    echo "Done! <br/>";

    //Creating table users
    try
    {
        $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //Preparing the query
        echo "Creating table : users <br/>";
        $stmt = $conn->prepare('CREATE TABLE users (
            id int(11) not null PRIMARY KEY AUTO_INCREMENT,
            user_name varchar(255) not null,
            email varchar(255) not null,
            passwd varchar(255) not null,
            active int(1) not null DEFAULT 0,
            con_code varchar(255) not null, 
            noti int(1) not null DEFAULT 1
            );'
        );
        //executing the query
        $stmt->execute();
        echo "Table users created!<br/>";
        try
        {
            $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //Preparing the query
            echo "Creating table : comments <br/>";
            $stmt = $conn->prepare('CREATE TABLE comments (
                id int(11) not null PRIMARY KEY AUTO_INCREMENT,
                name varchar(255) not null,
                user_name varchar(255) not null,
                user_image varchar(255) not null,
                comment varchar(255) not null
                );'
            );
            //executing the query
            $stmt->execute();
            echo "Table comments created! <br/>";
            echo "Creating table : likes <br/>";

            $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $conn->prepare('CREATE TABLE likes (
                id int(11) not null PRIMARY KEY AUTO_INCREMENT,
                picname varchar(255) not null,
                liker varchar(255) not null,
                user_image varchar(255) not null
                );'
            );
            $stmt->execute();
            echo "Table likes created! <br/>";
            try
            {
                $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //Preparing query
                echo "Creating table : pictures <br/>";
                $stmt = $conn->prepare('CREATE TABLE pictures (
                    id int(11) not null PRIMARY KEY AUTO_INCREMENT,
                    name varchar(255) not null,
                    user varchar(255) not null,
                    type varchar(5) not null,
                    ext varchar(5) not null
                    );'
                );
                //executing the query
                $stmt->execute();
                echo "Table pictures created! <br/>";
            }
            catch(PDOException $e)
            {
                echo "Unable to create picture table";
                echo "ERROR: ".$e->getMessage()."<br/>";
            }
        }
        catch (PDOException $e)
        {
            echo "Unable to create table <br/>";
            echo "ERROR: ".$e->getMessage()."<br/>";
        }
    }
    catch (PDOException $e)
    {
        echo "Unable to create table <br/>";
        echo "ERROR: ".$e->getMessage()."<br/>";
    }
}
catch (PDOException $e)
{
    echo "Unable to create database <br/>";
    echo "ERROR: ".$e->getMessage()."<br/>";
}
$conn = null;
?>