<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ตั้งค่า Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #ffffff;
            color: #333;
            padding-top: 20px;
            border-right: 2px solid #ddd;
        }

        .sidebar img {
            display: block;
            width: 80%;
            margin: 0 auto 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 12px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .sidebar ul li a {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 16px;
            transition: 0.3s;
        }

        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 18px;
            color: #F17629;
        }

        .sidebar ul li a:hover {
            background: #f5f5f5;
            padding-left: 10px;
            border-radius: 5px;
        }

        .box_head {
            background: #F17629;
            /* สีพื้นหลัง */
            color: white;
            /* สีตัวอักษร */
            padding: 15px;
            /* ระยะห่างภายใน */
            text-align: right;
            /* จัดข้อความให้อยู่ด้านขวา */
            font-size: 18px;
            /* ขนาดตัวอักษร */
            font-weight: bold;
            /* ตัวอักษรหนา */
            border-radius: 5px;
            /* มุมโค้ง */
            margin-bottom: 20px;
            /* เพิ่มระยะห่างด้านล่าง */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            /* เพิ่มเงา */
        }

        /* ตั้งค่าเนื้อหาหลัก */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            /* เพิ่มพื้นหลังสีอ่อน */
        }

        .container {
            margin-top: 80px;
            /* เว้นระยะด้านบนสำหรับ box_head */
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            /* เพิ่มเงา */
        }

        h2 {
            text-align: center;
            color: #f17629;
            /* ใช้สีส้มหลัก */
            font-weight: bold;
            margin-bottom: 30px;
            text-transform: uppercase;
            /* ตัวอักษรพิมพ์ใหญ่ */
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
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #f17629;
            /* ใช้สีส้มหลัก */
            box-shadow: 0px 0px 5px rgba(241, 118, 41, 0.5);
            /* เพิ่มเงาเมื่อ Focus */
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
            background: #f17629;
            /* สีส้มหลัก */
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: #d65c1e;
            /* สีส้มเข้มเมื่อ Hover */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            /* เพิ่มเงาเมื่อ Hover */
        }

        .btn-secondary {
            background: #6c757d;
            /* สีเทา */
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
            /* สีเทาเข้มเมื่อ Hover */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            /* เพิ่มเงาเมื่อ Hover */
        }

        .alert {
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                max-width: 90%;
            }
        }
    </style>
</head>

<?php include('../admin/admin_header.php'); ?>

<body>

    <?php include('../admin/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center">เพิ่มรายชื่อนักศึกษา</h2>
        <form action="adminadd_user.php" method="post">
            <div class="form-group">
                <label for="student_id" class="form-label">เลขบัตรประชาชน</label>
                <input type="text" class="form-control" id="student_id" name="student_id" required>
            </div>
            <div class="form-group">
                <label for="student_code" class="form-label">รหัสนักศึกษา</label>
                <input type="text" class="form-control" id="student_code" name="student_code" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="f_name" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="f_name" name="f_name" required>
            </div>
            <div class="form-group">
                <label for="l_name" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="l_name" name="l_name" required>
            </div>
            <div class="form-group">
                <label for="address" class="form-label">ที่อยู่</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="admin_dashboard.php" class="btn btn-secondary mt-3">กลับ</a>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $student_id = $_POST['student_id'];
        $student_code = $_POST['student_code'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $f_name = $_POST['f_name'];
        $l_name = $_POST['l_name'];
        $address = $_POST['address'];

        // Database connection
        session_start();
        include '/xampp/htdocs/Project_Final/server.php';
        if (!isset($_SESSION['username'])) {
            header("Location: admin_login.php");
            exit();
        }

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO student (student_id, student_code, password ,f_name , l_name , address) VALUES ('$student_id', '$student_code', '$password', '$f_name', '$l_name', '$address')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>New user added successfully</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }

        $conn->close();
    }
    ?>
</body>

</html>