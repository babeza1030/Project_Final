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

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบค่าที่ส่งมาจากฟอร์ม
    var_dump($_POST);
    var_dump($_FILES);

    $officer_id = $_POST['officer_id'];
    $officer_password = password_hash($_POST['officer_password'], PASSWORD_BCRYPT);
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $campus = $_POST['campus'];
    $room_number = $_POST['room_number'];
    $position = $_POST['position'];

    // ตรวจสอบและอัปโหลดไฟล์รูปภาพ
    $profile_image = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $uploads_dir = '/xampp/htdocs/Project_Final/uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        $profile_image = $uploads_dir . '/' . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $profile_image);
    } else {
        echo "File upload failed!";
    }

    // ตรวจสอบค่าก่อนบันทึก
    echo "Officer ID: $officer_id, First Name: $f_name, Last Name: $l_name";

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO teacher (officer_id, password, f_name, l_name, campus, room_number, position, profile_image) 
            VALUES ('$officer_id', '$officer_password', '$f_name', '$l_name', '$campus', '$room_number', '$position', '$profile_image')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('เพิ่มข้อมูลสำเร็จ');</script>";
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
    <title>แก้ไขข้อมูลอาจารย์</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        color: #333333; /* สีข้อความหลัก */
    }

    .box_head {
        background: #e67d57; /* สีส้มแดง */
        color: white;
        padding: 15px;
        text-align: right;
        font-size: 18px;
        font-weight: bold;
        border-radius: 5px;
    }

    .container {
        margin-left: 260px; /* ชดเชยพื้นที่ของ Sidebar */
        padding: 20px;
        background: #FFFFFF; /* สีขาว */
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        max-width: calc(100% - 280px); /* ปรับความกว้างให้สมดุลกับ Sidebar */
        margin-top: 20px;
        color: #333333; /* สีข้อความหลัก */
    }

    h2 {
        text-align: center;
        color: #333333; /* สีข้อความหลัก */
        font-weight: bold;
        margin-bottom: 10px;
    }

    p.text-muted {
        text-align: center;
        color: #808080; /* สีเทาปานกลาง */
        font-size: 16px;
        margin-bottom: 20px;
    }

    table th {
        background: #e67d57; /* สีส้มแดง */
        color: #495057;
        font-weight: bold;
        text-align: center;
    }

    table td {
        color: #333333; /* สีข้อความหลัก */
        text-align: center;
        vertical-align: middle;
    }

    .btn-edit {
        background-color: #32CD32; /* สีเขียวเข้ม */
        color: white;
        border: none;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 18px;
    }

    .btn-edit:hover {
        background-color: #228B22; /* สีเขียวเข้มกว่าเมื่อ Hover */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .btn-view {
        background-color: #FFD700; /* สีเหลืองเข้ม */
        color: white;
        border: none;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 18px;
    }

    .btn-view:hover {
        background-color: #FFA500; /* สีส้มเข้มเมื่อ Hover */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .btn-delete {
        background-color: #9370DB; /* สีม่วงเข้ม */
        color: white;
        border: none;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 18px;
    }

    .btn-delete:hover {
        background-color: #6A5ACD; /* สีม่วงเข้มกว่าเมื่อ Hover */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .btn-success {
        background-color: #00008B; /* สีส้มอ่อน */
        color: #ddd; /* สีฟ้าเข้ม */
        border: none;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #e49264; /* สีส้ม */
        color: white;
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
    
<?php include('../admin/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center">แก้ไขข้อมูลอาจารย์</h2>
        <p class="text-center text-muted">รายละเอียดข้อมูลอาจารย์</p>
        
        <!-- ปุ่มเปิด Modal -->
        <div class="mb-3 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                เพิ่มรายชื่ออาจารย์
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTeacherModalLabel">เพิ่มรายชื่ออาจารย์</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="officer_id">เลขบัตรประชาชน</label>
                                <input type="text" class="form-control" id="officer_id" name="officer_id" required>
                            </div>
                            <div class="form-group">
                                <label for="officer_password">รหัสผ่าน</label>
                                <input type="password" class="form-control" id="officer_password" name="officer_password" required>
                            </div>
                            <div class="form-group">
                                <label for="f_name">ชื่อ</label>
                                <input type="text" class="form-control" id="f_name" name="f_name" required>
                            </div>
                            <div class="form-group">
                                <label for="l_name">นามสกุล</label>
                                <input type="text" class="form-control" id="l_name" name="l_name" required>
                            </div>
                            <div class="form-group">
                                <label for="campus">วิทยาเขต</label>
                                <input type="text" class="form-control" id="campus" name="campus" required>
                            </div>
                            <div class="form-group">
                                <label for="room_number">หมายเลขห้อง</label>
                                <input type="text" class="form-control" id="room_number" name="room_number" required>
                            </div>
                            <div class="form-group">
                                <label for="position">ตำแหน่ง</label>
                                <input type="text" class="form-control" id="position" name="position" required>
                            </div>
                            <div class="form-group">
                                <label for="file">รูปภาพ</label>
                                <input type="file" class="form-control" id="file" name="file" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">เพิ่มอาจารย์</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>เลขบัตรประชาชน</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>วิทยาเขต</th>
                    <th>หมายเลขห้อง</th>
                    <th>ตำแหน่ง</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["officer_id"]) . "</td>
                    <td>" . htmlspecialchars($row["f_name"]) . "</td>
                    <td>" . htmlspecialchars($row["l_name"]) . "</td>
                    <td>" . htmlspecialchars($row["campus"]) . "</td>
                    <td>" . htmlspecialchars($row["room_number"]) . "</td>
                    <td>" . htmlspecialchars($row["position"]) . "</td>
                    <td>
    <div class='d-flex justify-content-center align-items-center gap-2'>
        <!-- ปุ่มแก้ไข -->
        <button type='button' class='btn btn-edit' 
                data-bs-toggle='modal' 
                data-bs-target='#editTeacherModal'
                data-officer-id='" . htmlspecialchars($row['officer_id']) . "'
                data-f-name='" . htmlspecialchars($row['f_name']) . "'
                data-l-name='" . htmlspecialchars($row['l_name']) . "'
                data-campus='" . htmlspecialchars($row['campus']) . "'
                data-room-number='" . htmlspecialchars($row['room_number']) . "'
                data-position='" . htmlspecialchars($row['position']) . "'
                title='แก้ไข'>
            <i class='bi bi-pencil-square'></i>
        </button>

        <!-- ปุ่มดูรายละเอียด -->
        <button type='button' class='btn btn-view' 
                data-bs-toggle='modal' 
                data-bs-target='#viewTeacherModal'
                data-officer-id='" . htmlspecialchars($row['officer_id']) . "'
                data-f-name='" . htmlspecialchars($row['f_name']) . "'
                data-l-name='" . htmlspecialchars($row['l_name']) . "'
                data-campus='" . htmlspecialchars($row['campus']) . "'
                data-room-number='" . htmlspecialchars($row['room_number']) . "'
                data-position='" . htmlspecialchars($row['position']) . "'
                title='ดูรายละเอียด'>
            <i class='bi bi-eye'></i>
        </button>

        <!-- ปุ่มลบ -->
        <button type='button' class='btn btn-delete' 
                data-officer-id='" . htmlspecialchars($row['officer_id']) . "'
                title='ลบ'>
            <i class='bi bi-trash'></i>
        </button>
    </div>
</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='text-center'>ไม่พบข้อมูล</td></tr>";
    }
    ?>
</tbody>

        </table>
        <!-- <a href="admin_dashboard.php" class="btn btn-secondary">กลับไปที่แดชบอร์ด</a> -->
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            $('#addTeacherModal').modal('hide');
            this.reset();
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // เติมข้อมูลใน Modal แก้ไข
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const studentId = this.getAttribute('data-student-id');
                const studentCode = this.getAttribute('data-student-code');
                const fName = this.getAttribute('data-f-name');
                const lName = this.getAttribute('data-l-name');
                const address = this.getAttribute('data-address');

                document.getElementById('edit_student_id').value = studentId;
                document.getElementById('edit_student_code').value = studentCode;
                document.getElementById('edit_f_name').value = fName;
                document.getElementById('edit_l_name').value = lName;
                document.getElementById('edit_address').value = address;
            });
        });

        // เติมข้อมูลใน Modal ดูรายละเอียด
        const viewButtons = document.querySelectorAll('.btn-view');
        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const officerId = this.getAttribute('data-officer-id');
                const fName = this.getAttribute('data-f-name');
                const lName = this.getAttribute('data-l-name');
                const campus = this.getAttribute('data-campus');
                const roomNumber = this.getAttribute('data-room-number');
                const position = this.getAttribute('data-position');

                document.getElementById('view_officer_id').textContent = officerId;
                document.getElementById('view_f_name').textContent = fName;
                document.getElementById('view_l_name').textContent = lName;
                document.getElementById('view_campus').textContent = campus;
                document.getElementById('view_room_number').textContent = roomNumber;
                document.getElementById('view_position').textContent = position;
            });
        });
    });
</script>

<!-- Modal สำหรับดูรายละเอียด -->
<div class="modal fade" id="viewTeacherModal" tabindex="-1" aria-labelledby="viewTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTeacherModalLabel">รายละเอียดอาจารย์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>เลขบัตรประชาชน:</strong> <span id="view_officer_id"></span></p>
                <p><strong>ชื่อ:</strong> <span id="view_f_name"></span></p>
                <p><strong>นามสกุล:</strong> <span id="view_l_name"></span></p>
                <p><strong>วิทยาเขต:</strong> <span id="view_campus"></span></p>
                <p><strong>หมายเลขห้อง:</strong> <span id="view_room_number"></span></p>
                <p><strong>ตำแหน่ง:</strong> <span id="view_position"></span></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
