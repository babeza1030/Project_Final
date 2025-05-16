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
    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="../static/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<?php include('../admin/admin_sidebar.php'); ?>

<?php include('../admin/admin_header.php'); ?>

<body style="background: #f6f8fa;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center font-bold" style="color:#00008B;">เพิ่มปีการศึกษา</h2>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">ปีการศึกษา:</label>
                                <input type="text" name="year" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">วันที่เริ่มต้น:</label>
                                <input type="datetime-local" name="data_start" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">วันที่สิ้นสุด:</label>
                                <input type="datetime-local" name="data_end" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">เทอม:</label>
                                <input type="number" name="terms" class="form-control" required>
                            </div>
                            <button type="submit" class="btn w-100" style="background-color: #00008B; color: white;">บันทึก</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>