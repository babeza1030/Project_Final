<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = trim($_POST['student_id']);
    $f_name = trim($_POST['f_name']);
    $l_name = trim($_POST['l_name']);
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่าฟิลด์ student_id ไม่ว่าง
    if (empty($student_id)) {
        echo "<script>alert('กรุณากรอกเลขบัตรประจำตัวประชาชน');</script>";
        exit();
    }

    // Validate passwords
    if ($password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน');</script>";
    } else {
        // ตรวจสอบว่ามี student_id ซ้ำหรือไม่
        $check_sql = "SELECT * FROM student WHERE student_id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('เลขบัตรประจำตัวประชาชนนี้มีการลงทะเบียนแล้ว');</script>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert data into the database
            $sql = "INSERT INTO student (student_id, f_name, l_name, phone_number, email, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $student_id, $f_name, $l_name, $phone_number, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>alert('ลงทะเบียนสำเร็จ'); window.location.href='user_login.php';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');</script>";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กองทุน</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../static/css/register.css">
    <link rel="stylesheet" href="../static/css/bootstrap-reboot.min.css">
</head>

<body>
    <div class="container">
        <div class="box-black"></div>
        <nav class="header">
            <img src="../static/img/logo.png" alt>
            <h1>ลงทะเบียน</h1>
            <form action="register.php" method="POST">

                <div class="input-box">
                    <input type="text" id="student_id" name="student_id" required placeholder="เลขบัตรประจำตัวประชาชน">
                </div>

                <div class="input-box">
                    <input type="text" id="f_name" name="f_name" required placeholder="ชื่อ">
                </div>

                <div class="input-box">
                    <input type="text" id="l_name" name="l_name" required placeholder="นามสกุล">
                </div>

                <div class="input-box">
                    <input type="text" id="phone_number" name="phone_number" required placeholder="เบอร์โทรศัพท์">
                </div>

                <div class="input-box">
                    <input type="text" id="email" name="email" required placeholder="Email">
                </div>

                <div class="input-box">
                    <input type="password" id="password" name="password" class="form-control" required placeholder="รหัสผ่าน">
                </div>

                <div class="input-box">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="ยืนยันรหัสผ่าน">
                    <div id="passwordError" class="text-danger mt-1 d-none">รหัสผ่านไม่ตรงกัน</div>
                </div>

                <button type="submit" class="btn btn-primary">ลงทะเบียน</button>
            </form>

            <script>
                document.getElementById("registerForm").addEventListener("submit", function (event) {
                    const password = document.getElementById("password").value;
                    const confirmPassword = document.getElementById("confirm_password").value;
                    const errorText = document.getElementById("passwordError");

                    if (password !== confirmPassword) {
                        event.preventDefault();
                        errorText.classList.remove("d-none");
                    } else {
                        errorText.classList.add("d-none");
                    }
                });
            </script>

            <div class="register-link">
                <p>มีบัญชีเข้าใช้งานแล้ว
                    <a href="user_login.php">เข้าใช้งาน</a>
                </p>
            </div>
        </nav>
    </div>
</body>

</html>