<?php
session_start();

if(isset($_SESSION['logged_in']))
{
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nivis Labs</title>

    <style>

        body{
            margin:0;
            padding:0;
            font-family:Arial;
            background:#111;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .login-box{
            width:350px;
            background:#fff;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.5);
        }

        .login-box h2{
            text-align:center;
            margin-bottom:20px;
        }

        .login-box input{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:5px;
        }

        .login-box button{
            width:100%;
            padding:12px;
            border:none;
            background:#000;
            color:#fff;
            font-size:16px;
            border-radius:5px;
            cursor:pointer;
        }

        .error{
            color:red;
            text-align:center;
            margin-bottom:10px;
        }

    </style>

</head>
<body>

<div class="login-box">

    <h2>Client Login</h2>

    <?php
    if(isset($_GET['error']))
    {
        echo "<div class='error'>Invalid Username or Password</div>";
    }
    ?>

    <form action="check_login.php" method="POST">

        <input type="text" name="username" placeholder="Enter Username" required>

        <input type="password" name="password" placeholder="Enter Password" required>

        <button type="submit">Login</button>

    </form>

</div>

</body>
</html>