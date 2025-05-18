<?php
session_start();

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']);
unset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../static/style.css">
    <style>
        body {
            background-image: url('../static/img/bg.jpg'); /* ใช้รูปภาพ bg.jpg */
            background-size: cover; /* ปรับขนาดรูปภาพให้เต็มหน้าจอ */
            background-position: center; /* จัดตำแหน่งรูปภาพให้อยู่ตรงกลาง */
            background-repeat: no-repeat; /* ไม่ให้รูปภาพซ้ำ */
            font-family: 'Prompt', sans-serif; /* ใช้ฟอนต์ Prompt */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            backdrop-filter: blur(10px); /* เพิ่มเอฟเฟกต์เบลอ */
            -webkit-backdrop-filter: blur(10px); /* รองรับเบราว์เซอร์ที่ใช้ Webkit */
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.5); /* พื้นหลังสีขาวโปร่งแสง */
            backdrop-filter: blur(15px); /* เพิ่มเอฟเฟกต์เบลอ */
            -webkit-backdrop-filter: blur(15px); /* รองรับเบราว์เซอร์ที่ใช้ Webkit */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(252, 176, 69, 0.5); /* เงาสีส้มแบบฟุ้ง */
            border: 2px solid transparent; /* ขอบโปร่งใส */
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
            background: #fff; /* พื้นหลังสีขาว */
            border: 2px solid transparent; /* ขอบโปร่งใส */
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            background-image: linear-gradient(white, white), linear-gradient(90deg, rgba(252, 176, 69, 1) 0%, rgba(253, 94, 0, 1) 100%);
            background-origin: border-box;
            background-clip: padding-box, border-box; /* ขอบไล่สีส้ม */
        }

        .form-control:focus {
            border-color: #f39c12; /* ขอบสีส้มเมื่อโฟกัส */
            box-shadow: 0px 0px 5px rgba(243, 156, 18, 0.5); /* เงาสีส้ม */
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
            transition: background 1s ease; /* เพิ่ม transition เพื่อให้เปลี่ยนสีอย่างนุ่มนวล */
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, rgba(253, 94, 0, 1) 0%, rgba(252, 176, 69, 1) 100%);
            color: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ hover */
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            color: #2980b9;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>เข้าสู่ระบบ (เจ้าหน้าที่)</h2>
        <form action="adminlogin_process.php" method="post">
            <div class="form-group">
                <label for="username" class="form-label">รหัสประจำตัวเจ้าหน้าที่</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
            <div class="register-link mt-3">
                <p>ไม่มีบัญชีเข้าใช้งาน? <a href="../admin/adminregister.php">ลงทะเบียนขอสิทธิ์เข้าใช้งาน</a></p>
            </div>
        </form>
        <?php if ($login_error): ?>
            <p class="error-message"><?php echo htmlspecialchars($login_error); ?></p>
        <?php endif; ?>
    </div>
</body>

</html>