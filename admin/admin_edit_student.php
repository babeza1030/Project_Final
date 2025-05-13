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
$sql = "SELECT * FROM student";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $student_code = $_POST['student_code'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $faculty = $_POST['faculty'];
    $major = $_POST['major'];
    $address = $_POST['address'];

    // ตรวจสอบและอัปโหลดไฟล์รูปภาพ
    $profile_image = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $uploads_dir = '/xampp/htdocs/Project_Final/uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        $profile_image = $uploads_dir . '/' . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $profile_image);
    }

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO student (student_id, student_code, password, f_name, l_name, faculty, major, address, profile_image) 
            VALUES ('$student_id', '$student_code', '$password', '$f_name', '$l_name', '$faculty', '$major', '$address', '$profile_image')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('เพิ่มข้อมูลนักศึกษาเรียบร้อยแล้ว');</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลนักศึกษา</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>

        /* ตั้งค่าเนื้อหาหลัก */
        .main-content {
            margin-left: 270px; /* เว้นที่สำหรับ Sidebar */
            padding: 20px;
            width: calc(100% - 270px);
            background-color: #f9f9f9; /* เพิ่มพื้นหลังสีอ่อน */
            min-height: 100vh; /* ให้เนื้อหาครอบคลุมเต็มหน้าจอ */
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05); /* เพิ่มเงาแบบด้านใน */
            border-radius: 8px; /* เพิ่มมุมโค้ง */
        }

        .box_head {
            display: flex; /* ใช้ Flexbox */
            justify-content: right; /* จัดข้อความให้อยู่ซ้าย-ขวา */
            align-items: center; /* จัดให้อยู่กึ่งกลางแนวตั้ง */
            background: linear-gradient(90deg, #f17629, #ff8c42); /* ไล่สี */
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .box_head span {
            font-size: 20px; /* ขนาดตัวอักษรใหญ่ขึ้น */
            font-weight: bold;
        }

        .box_head p {
            margin: 0;
            font-size: 14px;
            font-weight: normal;
            color: #f0f0f0; /* สีข้อความอ่อนลง */
        }

        /* ตั้งค่าตาราง */
        .table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        .table th {
            background-color: #f17629; /* สีส้มหลัก */
            color: white; /* สีตัวอักษร */
            text-transform: uppercase;
            font-size: 14px;
            font-weight: bold;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fff7eb; /* สีส้มอ่อน */
        }

        .table-striped tbody tr:hover {
            background-color: #ffe4d1; /* สีส้มอ่อนเมื่อ Hover */
            cursor: pointer;
        }

        /* ปุ่มแก้ไข */
        .btn-edit {
            background-color: #f17629; /* สีส้มหลัก */
            color: white; /* สีตัวอักษร */
            padding: 8px 15px; /* เพิ่มขนาดปุ่ม */
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
            border: none;
        }

        .btn-edit:hover {
            background-color: #e65c00; /* สีส้มเข้มเมื่อ Hover */
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
        }

        /* ปุ่มกลับไปที่แดชบอร์ด */
        .btn-secondary {
            background-color: #6c757d; /* สีเทา */
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268; /* สีเทาเข้มเมื่อ Hover */
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
        }

        /* หัวข้อ */
        .page-title {
            font-size: 32px; /* ขนาดตัวอักษรใหญ่ขึ้น */
            font-weight: bold; /* ตัวอักษรหนา */
            text-align: center; /* จัดให้อยู่ตรงกลาง */
            color: #f17629; /* ใช้สีส้มหลัก */
            margin-bottom: 30px; /* เพิ่มระยะห่างด้านล่าง */
            text-transform: uppercase; /* ตัวอักษรเป็นตัวพิมพ์ใหญ่ */
        }

        .box_head {
            background: #F17629; /* พื้นหลัง */
            color: white; /* สีตัวอักษร */
            padding: 15px; /* ระยะห่างภายใน */
            text-align: right; /* จัดข้อความให้อยู่ด้านขวา */
            font-size: 18px; /* ขนาดตัวอักษร */
            font-weight: bold; /* ตัวอักษรหนา */
            border-radius: 5px; /* มุมโค้ง */
            margin-bottom: 20px; /* เพิ่มระยะห่างด้านล่าง */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
        }



        h2 {
            text-align: center;
            color: #f17629; /* สีส้มหลัก */
            font-weight: bold;
            margin-bottom: 30px;
            text-transform: uppercase; /* ตัวอักษรพิมพ์ใหญ่ */
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
            border-color: #f17629; /* สีส้มหลัก */
            box-shadow: 0px 0px 5px rgba(241, 118, 41, 0.5); /* เพิ่มเงาเมื่อ Focus */
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
            background: #f17629; /* สีส้มหลัก */
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: #d65c1e; /* สีส้มเข้มเมื่อ Hover */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
        }

        .btn-secondary {
            background: #6c757d; /* สีเทา */
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62; /* สีเทาเข้มเมื่อ Hover */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
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

        .btn-add-student {
            background-color: #28a745; /* สีเขียว */
            color: white; /* สีตัวอักษร */
            font-size: 16px; /* ขนาดตัวอักษร */
            font-weight: bold; /* ตัวอักษรหนา */
            padding: 10px 20px; /* ระยะห่างภายใน */
            border-radius: 5px; /* มุมโค้ง */
            border: none; /* ไม่มีเส้นขอบ */
            transition: all 0.3s ease; /* เพิ่มเอฟเฟกต์ */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
        }

        .btn-add-student:hover {
            background-color: #218838; /* สีเขียวเข้มเมื่อ Hover */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
            transform: translateY(-2px); /* ยกปุ่มขึ้นเล็กน้อย */
        }

        .btn-add-student i {
            margin-right: 8px; /* เพิ่มระยะห่างระหว่างไอคอนกับข้อความ */
            font-size: 18px; /* ขนาดไอคอน */
        }

        .btn-add-student-short {
            background-color: #28a745; /* สีเขียว */
            color: white; /* สีตัวอักษร */
            font-size: 16px; /* ขนาดตัวอักษร */
            font-weight: bold; /* ตัวอักษรหนา */
            padding: 10px 20px; /* ระยะห่างภายใน */
            border-radius: 5px; /* มุมโค้ง */
            border: none; /* ไม่มีเส้นขอบ */
            transition: all 0.3s ease; /* เพิ่มเอฟเฟกต์ */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
            display: inline-block; /* จัดปุ่มให้อยู่ในบรรทัดเดียว */
        }

        .btn-add-student-short:hover {
            background-color: #218838; /* สีเขียวเข้มเมื่อ Hover */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
            transform: translateY(-2px); /* ยกปุ่มขึ้นเล็กน้อย */
        }

        .btn-add-student-short i {
            margin-right: 8px; /* เพิ่มระยะห่างระหว่างไอคอนกับข้อความ */
            font-size: 18px; /* ขนาดไอคอน */
        }

        .text-end {
            padding-right: 15px; /* เว้นขอบด้านขวา */
        }

        .btn-add-student-short {
            background-color: #28a745; /* สีเขียว */
            color: white; /* สีตัวอักษร */
            font-size: 14px; /* ขนาดตัวอักษร */
            font-weight: bold; /* ตัวอักษรหนา */
            padding: 8px 15px; /* ระยะห่างภายใน */
            border-radius: 5px; /* มุมโค้ง */
            border: none; /* ไม่มีเส้นขอบ */
            transition: all 0.3s ease; /* เพิ่มเอฟเฟกต์ */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
            display: inline-flex; /* จัดปุ่มให้เป็นแนวนอน */
            align-items: center; /* จัดไอคอนให้อยู่กึ่งกลาง */
            justify-content: center; /* จัดข้อความให้อยู่ตรงกลาง */
        }

        .btn-add-student-short:hover {
            background-color: #218838; /* สีเขียวเข้มเมื่อ Hover */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
            transform: translateY(-1px); /* ยกปุ่มขึ้นเล็กน้อย */
        }

        .btn-add-student-short i {
            margin-right: 6px; /* เพิ่มระยะห่างระหว่างไอคอนกับข้อความ */
            font-size: 16px; /* ขนาดไอคอน */
        }

        .text-end {
            padding-right: 15px; /* เว้นขอบด้านขวา */
        }

        .btn-edit, .btn-view {
            width: 100%; /* ปรับปุ่มให้กว้างเต็ม */
        }

        .btn-edit i, .btn-view i {
            margin-right: 5px; /* เพิ่มระยะห่างระหว่างไอคอนกับข้อความ */
        }

        .d-flex.flex-column {
            gap: 10px; /* เพิ่มช่องว่างระหว่างปุ่ม */
        }

        .d-flex.gap-2 {
            gap: 10px; /* เพิ่มช่องว่างระหว่างปุ่ม */
        }

        .btn-edit, .btn-view {
            display: inline-flex; /* จัดปุ่มให้เป็นแนวนอน */
            align-items: center; /* จัดไอคอนให้อยู่กึ่งกลาง */
            justify-content: center; /* จัดข้อความให้อยู่ตรงกลาง */
        }

        .btn-edit i, .btn-view i {
            margin-right: 5px; /* เพิ่มระยะห่างระหว่างไอคอนกับข้อความ */
        }

        .btn-edit, .btn-view {
            width: 40px; /* กำหนดความกว้าง */
            height: 40px; /* กำหนดความสูง */
            display: inline-flex; /* จัดปุ่มให้เป็นแนวนอน */
            align-items: center; /* จัดไอคอนให้อยู่กึ่งกลาง */
            justify-content: center; /* จัดข้อความให้อยู่ตรงกลาง */
            border-radius: 5px; /* มุมโค้งเล็กน้อย */
            padding: 0; /* ลบ Padding */
            font-size: 18px; /* ขนาดไอคอน */
        }

        .btn-edit {
            background-color: #f17629; /* สีส้ม */
            color: white; /* สีไอคอน */
            border: none; /* ไม่มีเส้นขอบ */
            transition: all 0.3s ease; /* เพิ่มเอฟเฟกต์ */
        }

        .btn-edit:hover {
            background-color: #e65c00; /* สีส้มเข้มเมื่อ Hover */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
        }

        .btn-view {
            background-color: #17a2b8; /* สีฟ้า */
            color: white; /* สีไอคอน */
            border: none; /* ไม่มีเส้นขอบ */
            transition: all 0.3s ease; /* เพิ่มเอฟเฟกต์ */
        }

        .btn-view:hover {
            background-color: #138496; /* สีฟ้าเข้มเมื่อ Hover */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* เพิ่มเงาเมื่อ Hover */
        }

        .btn-edit i, .btn-view i {
            font-size: 20px; /* ขนาดไอคอน */
        }

        .d-flex.gap-2 {
            gap: 10px; /* เพิ่มช่องว่างระหว่างปุ่ม */
        }
    </style>
</head>

<?php include('../admin/admin_header.php'); ?>
        
<body>
    
        <?php include('../admin/admin_sidebar.php'); ?>
    

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="page-title">แก้ไขข้อมูลนักศึกษา</h2>
        
        <!-- ปุ่มเพิ่มข้อมูลนักศึกษา -->
        <div class="text-end mb-3">
            <button type="button" class="btn btn-add-student-short" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-person-plus"></i> เพิ่มนักศึกษา
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStudentModalLabel">เพิ่มข้อมูลนักศึกษา</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data">
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
                                <label for="faculty" class="form-label">คณะ</label>
                                <input type="text" class="form-control" id="faculty" name="faculty" required>
                            </div>
                            <div class="form-group">
                                <label for="major" class="form-label">สาขา</label>
                                <input type="text" class="form-control" id="major" name="major" required>
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">ที่อยู่</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="form-group">
                                <label for="file" class="form-label">รูปภาพ</label>
                                <input type="file" class="form-control" id="file" name="file" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">บันทึก</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal สำหรับแก้ไขข้อมูลนักศึกษา -->
        <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStudentModalLabel">แก้ไขข้อมูลนักศึกษา</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="admin_edit_student_process_save.php" method="POST">
                            <input type="hidden" id="edit_student_id" name="student_id">
                            <div class="mb-3">
                                <label for="edit_student_code" class="form-label">รหัสนักศึกษา</label>
                                <input type="text" class="form-control" id="edit_student_code" name="student_code" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_f_name" class="form-label">ชื่อ</label>
                                <input type="text" class="form-control" id="edit_f_name" name="f_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_l_name" class="form-label">นามสกุล</label>
                                <input type="text" class="form-control" id="edit_l_name" name="l_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_address" class="form-label">ที่อยู่</label>
                                <input type="text" class="form-control" id="edit_address" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_password" class="form-label">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="edit_password" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal สำหรับดูรายละเอียด -->
        <div class="modal fade" id="viewStudentModal" tabindex="-1" aria-labelledby="viewStudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewStudentModalLabel">รายละเอียดนักศึกษา</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>เลขบัตรประชาชน:</strong> <span id="view_student_id"></span></p>
                        <p><strong>รหัสนักศึกษา:</strong> <span id="view_student_code"></span></p>
                        <p><strong>ชื่อ:</strong> <span id="view_f_name"></span></p>
                        <p><strong>นามสกุล:</strong> <span id="view_l_name"></span></p>
                        <p><strong>ที่อยู่:</strong> <span id="view_address"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>เลขบัตรประชาชน</th>
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>ที่อยู่</th>
                    <th>ตัวเลือก</th>
                </tr>
            </thead>
            <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["student_id"]) . "</td>
                    <td>" . htmlspecialchars($row["student_code"]) . "</td>
                    <td>" . htmlspecialchars($row["f_name"] . " " . $row["l_name"]) . "</td>
                    <td>" . htmlspecialchars($row["address"]) . "</td>
                    <td>
                        <div class='d-flex justify-content-center align-items-center gap-2'>
                            <!-- ปุ่มแก้ไข -->
                            <button type='button' class='btn btn-edit' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#editStudentModal'
                                    data-student-id='" . htmlspecialchars($row["student_id"]) . "'
                                    data-student-code='" . htmlspecialchars($row["student_code"]) . "'
                                    data-f-name='" . htmlspecialchars($row["f_name"]) . "'
                                    data-l-name='" . htmlspecialchars($row["l_name"]) . "'
                                    data-address='" . htmlspecialchars($row["address"]) . "'
                                    title='แก้ไข'>
                                <i class='bi bi-pencil-square'></i>
                            </button>

                            <!-- ปุ่มดูรายละเอียด -->
                            <button type='button' class='btn btn-view' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#viewStudentModal'
                                    data-student-id='" . htmlspecialchars($row["student_id"]) . "'
                                    data-student-code='" . htmlspecialchars($row["student_code"]) . "'
                                    data-f-name='" . htmlspecialchars($row["f_name"]) . "'
                                    data-l-name='" . htmlspecialchars($row["l_name"]) . "'
                                    data-address='" . htmlspecialchars($row["address"]) . "'
                                    title='ดูรายละเอียด'>
                                <i class='bi bi-eye'></i>
                            </button>
                        </div>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5' class='text-center'>ไม่พบข้อมูล</td></tr>";
    }
    ?>
</tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const studentId = this.getAttribute('data-student-id');
                const studentCode = this.getAttribute('data-student-code');
                const fName = this.getAttribute('data-f-name');
                const lName = this.getAttribute('data-l-name');
                const address = this.getAttribute('data-address');

                // เติมข้อมูลในฟอร์ม
                document.getElementById('edit_student_id').value = studentId;
                document.getElementById('edit_student_code').value = studentCode;
                document.getElementById('edit_f_name').value = fName;
                document.getElementById('edit_l_name').value = lName;
                document.getElementById('edit_address').value = address;
            });
        });

        const viewButtons = document.querySelectorAll('.btn-view');
        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const studentId = this.getAttribute('data-student-id');
                const studentCode = this.getAttribute('data-student-code');
                const fName = this.getAttribute('data-f-name');
                const lName = this.getAttribute('data-l-name');
                const address = this.getAttribute('data-address');

                // เติมข้อมูลในฟอร์ม
                document.getElementById('view_student_id').textContent = studentId;
                document.getElementById('view_student_code').textContent = studentCode;
                document.getElementById('view_f_name').textContent = fName;
                document.getElementById('view_l_name').textContent = lName;
                document.getElementById('view_address').textContent = address;
            });
        });
    });
</script>
</body>
</html>

