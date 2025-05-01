<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['student_id'])) {
    $student_id = intval($_GET['student_id']);

    // ดึงข้อมูลนักเรียนจากฐานข้อมูล
    $sql = "SELECT * FROM student WHERE student_id = $student_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูล";
        exit();
    }
} else {
    echo "ไม่มีการระบุ ID นักเรียน";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลนักเรียน</title>

    <link rel="stylesheet" href="../static/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ปรับพื้นหลังและฟอร์ม */
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

    <!-- <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script> -->
</head>
<body>

<?php include('../user/header.php'); ?>

<div class="container mt-5">
    <h2 class="mb-4">แก้ไขข้อมูล</h2>
    <form action="user_profile_process_save2.php" method="POST" onsubmit="return validateForm()">
    <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['student_id']); ?>">
    <div class="mb-3">
        <label for="phone_number" class="form-label">เบอร์โทรศัพท์มือถือ</label>
        <input type="text" class="form-control" id="phone_number" name="phone_number" 
               value="<?php echo htmlspecialchars($row['phone_number']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="phone_number_home" class="form-label">เบอร์โทรศัพท์บ้าน</label>
        <input type="text" class="form-control" id="phone_number_home" name="phone_number_home" 
               value="<?php echo htmlspecialchars($row['phone_number_home']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">อีเมล</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?php echo htmlspecialchars($row['email']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">ที่อยู่</label>
        <input type="text" class="form-control" id="address" name="address"
               value="<?php echo htmlspecialchars($row['address']); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
    <a href="user_profile.php" class="btn btn-secondary mt-2">ยกเลิก</a>
</form>
</div>

</body>
</html>
