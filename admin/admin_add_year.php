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
    // แปลงปี พ.ศ. เป็น ค.ศ.
    $year = $_POST['year'] - 543; // แปลง พ.ศ. เป็น ค.ศ.
    $data_start = str_replace('-', '-', $_POST['data_start']);
    $data_end = str_replace('-', '-', $_POST['data_end']);
    $terms = $_POST['terms'];

    // แปลงวันที่เริ่มต้นและสิ้นสุดจาก พ.ศ. เป็น ค.ศ.
    $data_start = date("Y-m-d\TH:i", strtotime($_POST['data_start'] . " -543 years"));
    $data_end = date("Y-m-d\TH:i", strtotime($_POST['data_end'] . " -543 years"));

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
    <script>
        // แปลงปีใน input[type="datetime-local"] เป็น พ.ศ. ก่อนแสดง
        document.addEventListener("DOMContentLoaded", function () {
            const startInput = document.querySelector('input[name="data_start"]');
            const endInput = document.querySelector('input[name="data_end"]');

            if (startInput && startInput.value) {
                const startDate = new Date(startInput.value);
                startDate.setFullYear(startDate.getFullYear() + 543); // แปลง ค.ศ. เป็น พ.ศ.
                startInput.value = startDate.toISOString().slice(0, 16);
            }

            if (endInput && endInput.value) {
                const endDate = new Date(endInput.value);
                endDate.setFullYear(endDate.getFullYear() + 543); // แปลง ค.ศ. เป็น พ.ศ.
                endInput.value = endDate.toISOString().slice(0, 16);
            }
        });
    </script>
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
                                <label class="form-label">ปีการศึกษา (พ.ศ.):</label>
                                <input type="number" name="year" class="form-control" required placeholder="เช่น 2568">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">เทอม:</label>
                                <input type="number" name="terms" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">วันที่เริ่มต้น:</label>
                                <input type="datetime-local" name="data_start" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">วันที่สิ้นสุด:</label>
                                <input type="datetime-local" name="data_end" class="form-control" required>
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