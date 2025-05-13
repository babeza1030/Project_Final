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

        .box_head {
            background-color: #602800;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
            background-color: #f9f9f9;
            min-height: 100vh;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
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
            background-color: #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
            color:#00008B;
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
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #f9f9f9;
            color: #495057;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: bold;
        }

        .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        

        /* ปุ่มแก้ไข */
        .btn-edit {
            background-color: #f17629;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
            border: none;
        }

        .btn-edit:hover {
            background-color: #e65c00;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* ปุ่มดูรายละเอียด */
        .btn-view {
            background-color: #17a2b8;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
            border: none;
        }

        .btn-view:hover {
            background-color: #138496;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .btn-edit, .btn-view {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            padding: 0;
            font-size: 18px;
        }

        .btn-edit i, .btn-view i {
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
    

    <!-- Main Content -->
    <div class="main-content">

        <h2 class="text-center">ข้อมูลนักศึกษา</h2>
        <p class="text-center text-muted">รายละเอียดข้อมูลนักศึกษา</p>

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

