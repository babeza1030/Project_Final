<?php
session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']);
unset($_SESSION['username']);
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: #f4f7f6;
        font-family: 'Arial', sans-serif;
    }

    .container {
        max-width: 600px;
        background: white;
        padding: 20px;
        margin: 50px auto;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: bold;
        color: #555;
    }

    .form-control {
        border: 2px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        font-size: 16px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.3);
    }

    button {
        width: 100%;
        padding: 12px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 5px;
        transition: 0.3s;
    }

    .btn-primary {
        background: #F17629;
        border: none;
    }

    .btn-primary:hover {
        background: #d65c1e;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    @media (max-width: 768px) {
        .container {
            max-width: 90%;
        }
    }
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>

<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form action="adminlogin_process.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <div class="register-link">
                <p>ไม่มีบัญชีเข้าใช้งาน
                    <a href="../admin/adminregister.php">สมัคร</a>
                </p>
            </div>
        </form>
        <?php
        if ($login_error) {
            echo "<p style='color:red;'>$login_error</p>";
        }
        ?>
    </div>
</body>

</html>