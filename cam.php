<?php

session_start();

if (isset($_GET['no_image']))
{
    echo "<script>alert('Please select a photo to upload first!')</script>";
}
else if (isset($_GET['file_error']))
{
    echo "<script>alert('Error: Image is invalid.')</script>";
}
else if (isset($_GET['format_not_supported']))
{
    echo "<script>alert('Only jpeg, jpg and png are allowed!')</script>";
}
else if (isset($_GET['file_too_large']))
{
    echo "<script>alert('File too large!!!. Try a file less than 10mb')</script>";
}
else if (isset($_GET['file_exists']))
{
    echo "<script>alert('File already exists. Try a different photo')</script>";
}
else if (isset($_GET['file_uploaded']))
{
    echo "<script>alert('File uploaded!')</script>";
}
else if (isset($_GET['file_not_found']))
{
    echo "<script>alert('Error: File not found!')</script>";
}
else if (isset($_SESSION['username']) && isset($_SESSION['email']) && isset($_GET['login']) && $_GET['login'] == 1)
{
    echo ("<script>alert('Logged in successfully');</script>");
}
else if ($_SESSION['username'] == "" || $_SESSION['email'] == "")
{
    header("Location: login.php?user=res");
    exit();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Camera</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        
          <!--Header out here-->
  <div class="topnav" id="myTopnav">
    <a href="index.php">Home</a>
    <a href="gallery.php">Gallery</a>
    <a href="settings.php">Settings</a>
    <a href="photos.php">Photos</a>
    <a href="index.php?user=log">Logout</a>
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
    </div>
    <!--Stickers-->
    <div class="grid">
            <div class="row">
                <div class="column">
                    <button onclick="showImage(1)">
                        <img id="inlove" src="stickers/inlove.png">
                    </button>
                    <button onclick="showImage(2)">
                        <img id="joy" src="stickers/joy.png">
                    </button>
                </div>
                <div class="column">
                    <button onclick="showImage(3)" type="button">
                        <img id="weak_smile" src="stickers/weak_smile.png">
                    </button>
                    <button onclick="showImage(4)">
                        <img id="smile" src="stickers/smile.png">
                    </button>
                </div>
                </div>
            </div>
        </div>
        <div class="pen-title">
            <h1>Camara</h1><span> <i class='fa fa-code'></i> </span>
        </div>
        <!--Camera here DUDE!!!-->
            <form class="booth" action="config/upload.php" method="POST" enctype="multipart/form-data">
                <video class="video" id="video" width="400" height="300"></video>
                <canvas class="canvas" id="over_video" width="400" height="300"></canvas>
                <canvas class="canvas" id="over_video2" width="400" height="300"></canvas>
                <a href="#" id="capture" class="take">Take Photo!</a>
                <input type="file" name="image" id="fileToUpload">
                <input id="long" type="submit" value="Upload Image" name="submit">
            </form>
            <div class="boot1">
                <canvas id="canvas" width="400" height="300"></canvas>
            </div>
            <div class="container">
                    <?php
                    session_start();

                    $user = $_SESSION['username'];
                    $dir = "config/upload/".$user."/*.*";
                    $files_uo = glob($dir);
                    $files = array_reverse($files_uo);

                    for ($i = 0; $i < count($files); $i++)
                    {
                        $num = $files[$i];
                        echo '<img id="display" src="'.$num.'" alt="image file" />'."<br/><br/>";
                    }
                    ?>
                    </div>
    <!--js code!!!-->
    <script>

    //SUPER IMPOSING BEGINS. DAN DAN DAAAAAAN!!!!
    var canvas1 = document.getElementById('over_video'),
    context1 = canvas1.getContext('2d');
    var img1;

    function showImage(img_num)
    {
        var button = document.getElementById('capture');
        button.style.visibility='visible';

        if (img_num == 1)
        {
            img1 = document.getElementById('inlove');
        }
        else if (img_num == 2)
        {
            img1 = document.getElementById('joy');
        }
        else if (img_num == 3)
        {
            img1 = document.getElementById('weak_smile');
        }
        else
        {
            img1 = document.getElementById('smile');
        }

        //clearing the canvas everytime a sticker is clicked
        context1.clearRect(0, 0, canvas1.width, canvas1.height);
        context1.drawImage(img1, 0, 0, 100, 100);
    }
    
    //CAMERA STUFF BEGIN HERE
    (function()
    {
        var video = document.getElementById('video'),
        canvas = document.getElementById('over_video'),
        context = canvas.getContext('2d');
        vendorUrl = window.URL || window.webkitURL;
        
        navigator.getMedia = navigator.getUserMedia ||
        navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia ||
        navigator.msGetUserMedia;
        navigator.getMedia({
            video: true,
            audio: false
        }, function (stream) {
            video.src = vendorUrl.createObjectURL(stream);
            video.play();
        }, function (error) {
            alert('Error tyring to use camera');
            console.log("Camera not found!!!");
        });
        
        var clickBtn = document.getElementById('capture').addEventListener('click', function() {
            var button = document.getElementById('capture');
            button.style.visibility = 'hidden';

            context1.clearRect(0, 0, 100, 100);
            context.drawImage(video, 0, 0, 400, 300);

            //Sticker image things
            canvas2 = document.getElementById('over_video2');
            context2 = canvas2.getContext('2d');

            context2.drawImage(img1, 0, 0, 100, 100);
            //getting image information
            var raw = canvas2.toDataURL("image/png");
            document.getElementById('hidden_data_2').value = raw;
            var fd = new FormData(document.forms["save_canvas"]);
            //saving the image
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'config/upload_data2.php', true);
            xhr.send(fd)
            context2.clearRect(0, 0, 100, 100);

            //Saving picture taken from the live stream
            //getting image information
            var raw = canvas.toDataURL("image/png");
            document.getElementById('hidden_data').value = raw;
            var fd = new FormData(document.forms["form1"]);
            //saving the image
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'config/upload_data.php', true);
            xhr.send(fd);
            context.clearRect(0, 0, 400, 300);

            new_canvas = document.getElementById('canvas');
            new_context = new_canvas.getContext('2d');

            new_context.drawImage(video, 0, 0, 400, 300);
            new_context.drawImage(img1, 0, 0, 100, 100);
        });
    })();
    //THIS IS THE END OF THE FIRST JS LINES

    </script>
    <script>
        
        //getting image path via url params
        var url  = window.location.href;
        var params = url.split("=");

        //This is for the upload part.
        if (params[1] != null)
        {
            var file_path = "config/" + params[1];

            //displaying the image on the canvas
            var canvas = document.getElementById('canvas')

            if (canvas != null)
            {
                var context = canvas.getContext('2d');
                console.log("Getting image");
                display_image();
                function display_image()
                {
                    display_image = new Image();
                    display_image.src = file_path;
                    console.log("Url found");

                    if (file_path.search("upload"))
                    {
                        window.location.href = "cam.php";
                    }
                        display_image.onload = function(){
                            context.drawImage(display_image, 0, 0, 400, 300);
                            console.log("Displaying image");
                        }
                }
            }
            else
            {
                console.log("Error: Unable to display image");
            }
        }
        else
        {
            console.log("No params found");
        }
        //Header
        function myFunction()
        {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") 
            {
                x.className += " responsive";
            } 
            else 
            {
                x.className = "topnav";
            }
        }
        //THE END OF THE SECOND JS LINES
        </script>
        
            <form method="POST" accept-charset="utf-8" name="form1">
                <input name="hidden_data" id="hidden_data" type="hidden"/>
                </form>
            
            <form method="POST" accept-charset="utf-8" name="save_canvas">
                <input name="hidden_data_2" id="hidden_data_2" type="hidden"/>
            </form>
        </br>
        </br>
        </br>
                <div class="footer">
                    <p>© 2019 nmaila</p>
                </div>
            </body>
            </html>