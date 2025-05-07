<?php
// เชื่อมต่อฐานข้อมูล
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

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM teacher";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลอาจารย์</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ปรับพื้นหลังและฟอร์ม */
        body {
            background: #f4f7f6;
            font-family: 'Arial', sans-serif;
        }

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
            color: white;
            padding: 15px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
        }

        .container {
            margin-left: 270px; /* เว้นที่สำหรับ Sidebar */
            max-width: 900px;
            background: white;
            padding: 20px;
            margin-top: 50px;
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
</head>

<header class="box_head">
            <?php if (isset($_SESSION['username'])): ?>
                <span>ยินดีต้อนรับ , <?php echo $_SESSION['username']; ?></span>
            <?php endif; ?>
            
            <p class="text-right">  วันที่: <?php echo date("d/m/Y"); ?></p>
            <br>

        </header>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../static/img/logo.png" alt="Kasem Bundit University">
        <ul>
            <li><a href="admin_dashboard.php"><i class="bi bi-house"></i> หน้าหลัก (Dashboard)</a></li>
            <!-- <li><a href="adminCheck_Borrower_Status.php"><i class="bi bi-search"></i> ตรวจสอบสถานะผู้กู้</a></li> -->
            <!-- <li><a href="adminCheck_form_Status.php"><i class="bi bi-file-text"></i> ตรวจสอบสถานะเอกสาร</a></li> -->
            <li><a href="admin_edit_student.php"><i class="bi bi-person"></i> แก้ไขข้อมูลนักศึกษา</a></li>
            <li><a href="adminadd_user.php"><i class="bi bi-person-plus"></i> เพิ่มนักศึกษา</a></li>
            <li><a href="admin_edit_teacher.php"><i class="bi bi-briefcase"></i> แก้ไขข้อมูลอาจารย์</a></li>
            <li><a href="adminadd_teacher.php"><i class="bi bi-person-plus"></i> เพิ่มอาจารย์</a></li>
            <!-- <li><a href="admin_edit_admin.php"><i class="bi bi-gear"></i> จัดการแอดมิน</a></li> -->
            <li><a href="admin_Check_document_status.php"><i class="bi bi-file-text"></i> ตรวจสอบเอกสารจิตอาสา</a></li>
            <li><a href="admin_report_1.php"><i class="bi bi-file-text"></i> ตารางสรุปผู้กู้ กยศ.</a></li>
            <li><a href="admin_report_2.php"><i class="bi bi-file-text"></i> ตารางรายงานสรุป จิตอาสา</a></li>




            <li><a href="adminlogout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center rounded-pill mb-4 col px-md-5 btn-edit">แก้ไขข้อมูลอาจารย์</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Officer ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Campus</th>
                    <th>Room Number</th>
                    <th>Position</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row["officer_id"]) . "</td>
                                <td>" . htmlspecialchars($row["f_name"]) . "</td>
                                <td>" . htmlspecialchars($row["l_name"]) . "</td>
                                <td>" . htmlspecialchars($row["campus"]) . "</td>
                                <td>" . htmlspecialchars($row["room_number"]) . "</td>
                                <td>" . htmlspecialchars($row["position"]) . "</td>
                                <td><a href='admin_edit_teacher_process.php?officer_id=" . htmlspecialchars($row["officer_id"]) . "' class='btn btn-primary btn-edit'>แก้ไข</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>ไม่พบข้อมูล</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-secondary">กลับไปที่แดชบอร์ด</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
