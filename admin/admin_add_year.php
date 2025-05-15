<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ถ้ามีการ submit ฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = $_POST['year'];
    $data_start = $_POST['data_start'];
    $data_end = $_POST['data_end'];
    $terms = $_POST['terms'];

    $sql = "INSERT INTO year_table (year, data_start, data_end, terms) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $year, $data_start, $data_end, $terms);

    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มข้อมูลสำเร็จ');window.location='admin_add_year.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มปีการศึกษา</title>
</head>
<body>
    <h2>เพิ่มปีการศึกษา</h2>
    <form method="post">
        <label>ปีการศึกษา:</label>
        <input type="text" name="year" required><br><br>
        <label>วันที่เริ่มต้น:</label>
        <input type="datetime-local" name="data_start" required><br><br>
        <label>วันที่สิ้นสุด:</label>
        <input type="datetime-local" name="data_end" required><br><br>
        <label>เทอม:</label>
        <input type="number" name="terms" required><br><br>
        <button type="submit">บันทึก</button>
    </form>
</body>
</html>