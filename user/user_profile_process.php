<?php
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลนักเรียน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">แก้ไขข้อมูลนักเรียน</h2>
    <form action="user_profile_process_save.php" method="POST" onsubmit="return validateForm()">
        <div class="mb-3">
            <label for="student_id" class="form-label">Student ID</label>
            <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($row['student_id']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="student_code" class="form-label">Student Code</label>
            <input type="text" class="form-control" id="student_code" name="student_code" value="<?php echo htmlspecialchars($row['student_code']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="f_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="f_name" name="f_name" value="<?php echo htmlspecialchars($row['f_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="l_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="l_name" name="l_name" value="<?php echo htmlspecialchars($row['l_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
</body>
</html>
