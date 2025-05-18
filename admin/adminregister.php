<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: adminlogin.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../static/style.css">
    <style>
        body {
            background-image: url('../static/img/bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Prompt', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(252, 176, 69, 0.5);
            border: 2px solid transparent;
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
            background: #fff;
            border: 2px solid transparent;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            background-image: linear-gradient(white, white), linear-gradient(90deg, rgba(252, 176, 69, 1) 0%, rgba(253, 94, 0, 1) 100%);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .form-control:focus {
            border-color: #f39c12;
            box-shadow: 0px 0px 5px rgba(243, 156, 18, 0.5);
        }

        .btn-primary {
            background: linear-gradient(90deg, rgba(252, 176, 69, 1) 0%, rgba(253, 94, 0, 1) 100%);
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            color: #fff;
            transition: background 0.5s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, rgba(253, 94, 0, 1) 0%, rgba(252, 176, 69, 1) 100%);
            color: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            color: #2980b9 ;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>ลงทะเบียน (เจ้าหน้าที่)</h2>
        <form action="../admin/adminregister.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">รหัสประจำตัวเจ้าหน้าที่</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" name="register" class="btn btn-primary">ลงทะเบียน</button>
        </form>

        <div class="register-link mt-3">
            <p>มีบัญชีแล้ว? <a href="adminlogin.php">เข้าสู่ระบบ</a></p>
        </div>
    </div>
</body>

</html>