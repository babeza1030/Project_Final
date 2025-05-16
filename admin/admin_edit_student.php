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
$sql = "
    SELECT 
        student.*, 
        father.father_name, father.father_last_name, 
        mother.mother_name, mother.mother_last_name, 
        father.father_id, father.father_address, father.father_occupation, father.father_income, 
        mother.mother_id, mother.mother_address, mother.mother_occupation, mother.mother_income, 
        father.father_phone_number, mother.mother_phone_number, 
        endorsee.full_name AS endorsee_name, endorsee.address AS endorsee_address, endorsee.phone_number AS endorsee_phone_number, 
        department.department_name,
        faculty.faculty_name
    FROM student 
    LEFT JOIN father ON student.father_id = father.father_id 
    LEFT JOIN mother ON student.mother_id = mother.mother_id 
    LEFT JOIN endorsee ON student.endorser_id = endorsee.endorser_id
    LEFT JOIN department ON student.department_id = department.department_id
    LEFT JOIN faculty ON department.faculty_id = faculty.faculty_id
";
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

        /* .box_head {
            background-color: #602800;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        } */

        .container {
            margin-left: 260px;
            /* ชดเชยพื้นที่ของ Sidebar */
            padding: 20px;
            background: #FFFFFF;
            /* สีขาว */
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: calc(100% - 280px);
            /* ปรับความกว้างให้สมดุลกับ Sidebar */
            margin-top: 20px;
            color: #333333;
            /* สีข้อความหลัก */
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            min-height: 100vh;
            box-sizing: border-box;
        }


        /* หัวข้อ */
        h2.text-center {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #602800;
            margin-bottom: 10px;
        }

        p.text-muted {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        /* ปุ่มเพิ่มข้อมูลนักศึกษา */
        .btn-add-student-short {
            background-color: #00008B;
            color: white;
            font-size: 14px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-add-student-short:hover {
            background-color: while;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
            color: #00008B;
        }

        .btn-add-student-short i {
            margin-right: 5px;
            font-size: 16px;
        }

        .text-end {
            padding-right: 15px;
        }

        /* ตาราง */
        .table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            background-color: #fff;
            /* พื้นหลังขาว */
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #fff;
            color: #00008B;
            text-transform: uppercase;
            font-size: 18px;
            /* เพิ่มขนาดหัวตาราง */
            font-weight: bold;
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        .table-striped>tbody>tr {
            background-color: #fff !important;
            /* ไม่มีสลับสี */
        }

        /* ปุ่มแก้ไข */
        .btn-edit {
            background-color: #f1c40f;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
            border: none;
        }

        .btn-edit:hover {
            background-color: #f1c40f;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        /* ปุ่มดูรายละเอียด */
        .btn-view {
            background-color: #2ecc71;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
            border: none;
        }

        .btn-view:hover {
            background-color: #2ecc71;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .btn-edit,
        .btn-view {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            padding: 0;
            font-size: 18px;
        }

        .btn-edit i,
        .btn-view i {
            font-size: 20px;
        }

        .d-flex.gap-2 {
            gap: 10px;
        }
    </style>
</head>

<?php include('../admin/admin_header.php'); ?>

<body>
    <?php include('../admin/admin_sidebar.php'); ?>

    <div class="main-content ">

        <div class="mb-4">
            <h2 class="page-title mb-1" style="font-size:2rem; font-weight:bold; color:#00008B; margin-bottom:0;">ข้อมูลนักศึกษา</h2>
            <p class="page-desc" style="font-size:1.1rem; color:#6c757d;">รายละเอียดข้อมูลนักศึกษา</p>
            <!-- <hr style="border-top:2px solid #FC6600; width:60px; margin:0;"> -->
        </div>

        <!-- ค้นหานักศึกษา -->
        <div class="mb-3" style="max-width:400px;">
            <input type="text" id="searchStudentInput" class="form-control" placeholder="ค้นหานักศึกษา...">
        </div>

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
                                <label for="edit_phone_number" class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control" id="edit_phone_number" name="phone_number">
                            </div>
                            <div class="mb-3">
                                <label for="edit_email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="edit_father_id" class="form-label">เลขบัตรประชาชนบิดา</label>
                                <input type="text" class="form-control" id="edit_father_id" name="father_id">
                            </div>
                            <div class="mb-3">
                                <label for="edit_mother_id" class="form-label">เลขบัตรประชาชนมารดา</label>
                                <input type="text" class="form-control" id="edit_mother_id" name="mother_id">
                            </div>
                            <div class="mb-3">
                                <label for="edit_endorsee_name" class="form-label">ชื่อผู้รับรอง</label>
                                <input type="text" class="form-control" id="edit_endorsee_name" name="endorsee_name">
                            </div>
                            <div class="mb-3">
                                <label for="edit_endorsee_address" class="form-label">ที่อยู่ผู้รับรอง</label>
                                <input type="text" class="form-control" id="edit_endorsee_address" name="endorsee_address">
                            </div>
                            <div class="mb-3">
                                <label for="edit_endorsee_phone" class="form-label">เบอร์โทรศัพท์ผู้รับรอง</label>
                                <input type="text" class="form-control" id="edit_endorsee_phone" name="endorsee_phone">
                            </div>
                            <div class="mb-3">
                                <label for="edit_faculty" class="form-label">คณะ</label>
                                <input type="text" class="form-control" id="edit_faculty" name="faculty">
                            </div>
                            <div class="mb-3">
                                <label for="edit_major" class="form-label">สาขา</label>
                                <input type="text" class="form-control" id="edit_major" name="major">
                            </div>
                            <div class="mb-3">
                                <label for="edit_family_status" class="form-label">สถานภาพครอบครัว</label>
                                <input type="text" class="form-control" id="edit_family_status" name="family_status">
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
                        <div class="card mb-3" style="border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px;">
                            <div class="card-body ">
                                <div class="row mb-2">
                                    <div class="col-md-6 ">
                                        <p><strong>เลขบัตรประชาชน:</strong> <span id="view_student_id"></span></p>
                                        <p><strong>รหัสนักศึกษา:</strong> <span id="view_student_code"></span></p>
                                        <p><strong>ชื่อ:</strong> <span id="view_f_name"></span></p>
                                        <p><strong>นามสกุล:</strong> <span id="view_l_name"></span></p>
                                        <p><strong>ที่อยู่:</strong> <span id="view_address"></span></p>
                                        <p><strong>เบอร์โทรศัพท์:</strong> <span id="view_phone_number"></span></p>
                                        <p><strong>อีเมล:</strong> <span id="view_email"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>ชื่อผู้รับรอง:</strong> <span id="view_endorsee_name"></span></p>
                                        <p><strong>ที่อยู่ผู้รับรอง:</strong> <span id="view_endorsee_address"></span></p>
                                        <p><strong>เบอร์โทรศัพท์ผู้รับรอง:</strong> <span id="view_endorsee_phone"></span></p>
                                        <p><strong>คณะ:</strong> <span id="view_faculty"></span></p>
                                        <p><strong>สาขา:</strong> <span id="view_major"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3" style="border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <div class="card-header bg-primary text-white" style="border-radius: 8px 8px 0 0; font-weight: bold;background: linear-gradient(90deg, #FC6600 60%, #FDA50F 100%);">
                                ข้อมูลบิดา
                            </div>
                            <div class="card-body">
                                <p><strong>ชื่อบิดา:</strong> <span id="view_father_name"></span></p>
                                <p><strong>นามสกุลบิดา:</strong> <span id="view_father_last_name"></span></p>
                                <p><strong>เลขบัตรประชาชนบิดา:</strong> <span id="view_father_id"></span></p>
                                <p><strong>ที่อยู่บิดา:</strong> <span id="view_father_address"></span></p>
                                <p><strong>อาชีพบิดา:</strong> <span id="view_father_occupation"></span></p>
                                <p><strong>เงินเดือนบิดา:</strong> <span id="view_father_income"></span></p>
                                <p><strong>เบอร์โทรศัพท์บิดา:</strong> <span id="view_father_phone"></span></p>
                            </div>
                        </div>
                        
                        <div class="card mb-3" style="border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <div class="card-header bg-primary text-white" style="border-radius: 8px 8px 0 0; font-weight: bold;background: linear-gradient(90deg, #FC6600 60%, #FDA50F 100%);">
                                ข้อมูลมารดา
                            </div>
                            <div class="card-body">
                                <p><strong>ชื่อมารดา:</strong> <span id="view_mother_name"></span></p>
                                <p><strong>นามสกุลมารดา:</strong> <span id="view_mother_last_name"></span></p>
                                <p><strong>เลขบัตรประชาชนมารดา:</strong> <span id="view_mother_id"></span></p>
                                <p><strong>ที่อยู่มารดา:</strong> <span id="view_mother_address"></span></p>
                                <p><strong>อาชีพมารดา:</strong> <span id="view_mother_occupation"></span></p>
                                <p><strong>เงินเดือนมารดา:</strong> <span id="view_mother_income"></span></p>
                                <p><strong>เบอร์โทรศัพท์มารดา:</strong> <span id="view_mother_phone"></span></p>
                                <p><strong>สถานภาพครอบครัว:</strong> <span id="view_family_status"></span></p>
                            </div>
                        </div>

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
            <tbody id="studentTable">
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
                            <button type='button' class='btn btn-edit' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#editStudentModal'
                                    data-student-id='" . htmlspecialchars($row["student_id"]) . "'
                                    data-student-code='" . htmlspecialchars($row["student_code"]) . "'
                                    data-f-name='" . htmlspecialchars($row["f_name"]) . "'
                                    data-l-name='" . htmlspecialchars($row["l_name"]) . "'
                                    data-address='" . htmlspecialchars($row["address"]) . "'
                                    data-phone-number='" . htmlspecialchars($row["phone_number"]) . "'
                                    data-email='" . htmlspecialchars($row["email"]) . "'
                                    data-father-id='" . htmlspecialchars($row["father_id"]) . "'
                                    data-mother-id='" . htmlspecialchars($row["mother_id"]) . "'
                                    data-endorsee-name='" . htmlspecialchars($row["endorsee_name"]) . "'
                                    data-endorsee-address='" . htmlspecialchars($row["endorsee_address"]) . "'
                                    data-endorsee-phone='" . htmlspecialchars($row["endorsee_phone_number"]) . "'
                                    data-faculty='" . htmlspecialchars($row["faculty_name"]) . "'
                                    data-major='" . htmlspecialchars($row["department_name"]) . "'
                                    data-family-status='" . htmlspecialchars($row["family_status"]) . "'
                                    title='แก้ไข'>
                                <i class='bi bi-pencil-square'></i>
                            </button>
                            <button type='button' class='btn btn-view' 
                                    data-bs-toggle='modal' 
                                    data-bs-target='#viewStudentModal'
                                    data-student-id='" . htmlspecialchars($row["student_id"]) . "'
                                    data-student-code='" . htmlspecialchars($row["student_code"]) . "'
                                    data-f-name='" . htmlspecialchars($row["f_name"]) . "'
                                    data-l-name='" . htmlspecialchars($row["l_name"]) . "'
                                    data-address='" . htmlspecialchars($row["address"]) . "'
                                    data-phone-number='" . htmlspecialchars($row["phone_number"]) . "'
                                    data-email='" . htmlspecialchars($row["email"]) . "'
                                    data-father-name='" . htmlspecialchars($row["father_name"]) . "'
                                    data-father-last-name='" . htmlspecialchars($row["father_last_name"]) . "'
                                    data-father-id='" . htmlspecialchars($row["father_id"]) . "'
                                    data-father-address='" . htmlspecialchars($row["father_address"]) . "'
                                    data-father-occupation='" . htmlspecialchars($row["father_occupation"]) . "'
                                    data-father-income='" . htmlspecialchars($row["father_income"]) . "'
                                    data-father-phone='" . htmlspecialchars($row["father_phone_number"]) . "'
                                    data-mother-name='" . htmlspecialchars($row["mother_name"]) . "'
                                    data-mother-last-name='" . htmlspecialchars($row["mother_last_name"]) . "'
                                    data-mother-id='" . htmlspecialchars($row["mother_id"]) . "'
                                    data-mother-address='" . htmlspecialchars($row["mother_address"]) . "'
                                    data-mother-occupation='" . htmlspecialchars($row["mother_occupation"]) . "'
                                    data-mother-income='" . htmlspecialchars($row["mother_income"]) . "'
                                    data-mother-phone='" . htmlspecialchars($row["mother_phone_number"]) . "'
                                    data-family-status='" . htmlspecialchars($row["family_status"]) . "'
                                    data-endorsee-name='" . htmlspecialchars($row["endorsee_name"]) . "'
                                    data-endorsee-address='" . htmlspecialchars($row["endorsee_address"]) . "'
                                    data-endorsee-phone='" . htmlspecialchars($row["endorsee_phone_number"]) . "'
                                    data-faculty='" . htmlspecialchars($row["faculty_name"]) . "'
                                    data-major='" . htmlspecialchars($row["department_name"]) . "'
                                    title='ดูข้อมูล'>
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
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.btn-edit');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    const studentCode = this.getAttribute('data-student-code');
                    const fName = this.getAttribute('data-f-name');
                    const lName = this.getAttribute('data-l-name');
                    const address = this.getAttribute('data-address');
                    const phoneNumber = this.getAttribute('data-phone-number');
                    const email = this.getAttribute('data-email');
                    const fatherId = this.getAttribute('data-father-id');
                    const motherId = this.getAttribute('data-mother-id');
                    const endorseeName = this.getAttribute('data-endorsee-name');
                    const endorseeAddress = this.getAttribute('data-endorsee-address');
                    const endorseePhone = this.getAttribute('data-endorsee-phone');
                    const faculty = this.getAttribute('data-faculty');
                    const major = this.getAttribute('data-major');
                    const familyStatus = this.getAttribute('data-family-status');

                    // เติมข้อมูลในฟอร์ม
                    document.getElementById('edit_student_id').value = studentId;
                    document.getElementById('edit_student_code').value = studentCode;
                    document.getElementById('edit_f_name').value = fName;
                    document.getElementById('edit_l_name').value = lName;
                    document.getElementById('edit_address').value = address;
                    document.getElementById('edit_phone_number').value = phoneNumber;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_father_id').value = fatherId;
                    document.getElementById('edit_mother_id').value = motherId;
                    document.getElementById('edit_endorsee_name').value = endorseeName;
                    document.getElementById('edit_endorsee_address').value = endorseeAddress;
                    document.getElementById('edit_endorsee_phone').value = endorseePhone;
                    document.getElementById('edit_faculty').value = faculty;
                    document.getElementById('edit_major').value = major;
                    document.getElementById('edit_family_status').value = familyStatus;
                });
            });

            const viewButtons = document.querySelectorAll('.btn-view');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const studentId = this.getAttribute('data-student-id');
                    const studentCode = this.getAttribute('data-student-code');
                    const fName = this.getAttribute('data-f-name');
                    const lName = this.getAttribute('data-l-name');
                    const address = this.getAttribute('data-address');
                    const phoneNumber = this.getAttribute('data-phone-number');
                    const email = this.getAttribute('data-email');
                    const fatherName = this.getAttribute('data-father-name');
                    const fatherLastName = this.getAttribute('data-father-last-name');
                    const fatherId = this.getAttribute('data-father-id');
                    const fatherAddress = this.getAttribute('data-father-address');
                    const fatherOccupation = this.getAttribute('data-father-occupation');
                    const fatherIncome = this.getAttribute('data-father-income');
                    const fatherPhone = this.getAttribute('data-father-phone');
                    const motherName = this.getAttribute('data-mother-name');
                    const motherLastName = this.getAttribute('data-mother-last-name');
                    const motherId = this.getAttribute('data-mother-id');
                    const motherAddress = this.getAttribute('data-mother-address');
                    const motherOccupation = this.getAttribute('data-mother-occupation');
                    const motherIncome = this.getAttribute('data-mother-income');
                    const motherPhone = this.getAttribute('data-mother-phone');
                    const familyStatus = this.getAttribute('data-family-status');
                    const endorseeName = this.getAttribute('data-endorsee-name');
                    const endorseeAddress = this.getAttribute('data-endorsee-address');
                    const endorseePhone = this.getAttribute('data-endorsee-phone');
                    const faculty = this.getAttribute('data-faculty');
                    const major = this.getAttribute('data-major');

                    // เติมข้อมูลใน Modal
                    document.getElementById('view_student_id').textContent = studentId;
                    document.getElementById('view_student_code').textContent = studentCode;
                    document.getElementById('view_f_name').textContent = fName;
                    document.getElementById('view_l_name').textContent = lName;
                    document.getElementById('view_address').textContent = address;
                    document.getElementById('view_phone_number').textContent = phoneNumber;
                    document.getElementById('view_email').textContent = email;
                    document.getElementById('view_father_name').textContent = fatherName;
                    document.getElementById('view_father_last_name').textContent = fatherLastName;
                    document.getElementById('view_father_id').textContent = fatherId;
                    document.getElementById('view_father_address').textContent = fatherAddress;
                    document.getElementById('view_father_occupation').textContent = fatherOccupation;
                    document.getElementById('view_father_income').textContent = fatherIncome;
                    document.getElementById('view_father_phone').textContent = fatherPhone;
                    document.getElementById('view_mother_name').textContent = motherName;
                    document.getElementById('view_mother_last_name').textContent = motherLastName;
                    document.getElementById('view_mother_id').textContent = motherId;
                    document.getElementById('view_mother_address').textContent = motherAddress;
                    document.getElementById('view_mother_occupation').textContent = motherOccupation;
                    document.getElementById('view_mother_income').textContent = motherIncome;
                    document.getElementById('view_mother_phone').textContent = motherPhone;
                    document.getElementById('view_family_status').textContent = familyStatus;
                    document.getElementById('view_faculty').textContent = faculty;
                    document.getElementById('view_major').textContent = major;
                    document.getElementById('view_endorsee_name').textContent = endorseeName;
                    document.getElementById('view_endorsee_address').textContent = endorseeAddress;
                    document.getElementById('view_endorsee_phone').textContent = endorseePhone;

                });
            });
        });

        // ฟังก์ชันค้นหานักศึกษา (เหมือน admin_dashboard.php)
        document.getElementById("searchStudentInput").addEventListener("keyup", function() {
            let searchValue = this.value.toLowerCase();
            let tableRows = document.getElementById("studentTable").getElementsByTagName("tr");

            for (let row of tableRows) {
                row.style.display = row.innerText.toLowerCase().includes(searchValue) ? "" : "none";
            }
        });
    </script>
</body>

</html>