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

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '/path/to/upload/directory/';
    $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
        echo "File is valid and was successfully uploaded.\n";
    } else {
        echo "Possible file upload attack!\n";
    }
} else {
    echo "No file uploaded or there was an error uploading the file.";
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
        <form action="user_profile_process_save1.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id"
                    value="<?php echo htmlspecialchars($row['student_id']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="student_code" class="form-label">Student Code</label>
                <input type="text" class="form-control" id="student_code" name="student_code"
                    value="<?php echo htmlspecialchars($row['student_code']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="f_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="f_name" name="f_name"
                    value="<?php echo htmlspecialchars($row['f_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="l_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="l_name" name="l_name"
                    value="<?php echo htmlspecialchars($row['l_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address"
                    value="<?php echo htmlspecialchars($row['address']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number"
                    value="<?php echo htmlspecialchars($row['phone_number']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Image</label>
                <?php if (!empty($row['profile_image'])): ?>
                    <div>
                        <img src="<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Profile Image" style="width: 100px; height: auto;">
                    </div>
                    <p>มีรูปภาพโปรไฟล์แล้ว</p>
                <?php endif; ?>
                <input type="file" class="form-control" id="profile_image" name="profile_image">
            </div>
            <div class="mb-3">
                <label for="s_faculty" class="form-label">คณะ</label>
                <select class="form-control" id="s_faculty" name="s_faculty" required>
                    <option value="">-- เลือกคณะ --</option>
                    <option value="1" <?php echo ($row['s_faculty'] == 1) ? 'selected' : ''; ?>>คณะวิศวกรรมศาสตร์</option>
                    <option value="2" <?php echo ($row['s_faculty'] == 2) ? 'selected' : ''; ?>>คณะบริหารธุรกิจ</option>
                    <option value="3" <?php echo ($row['s_faculty'] == 3) ? 'selected' : ''; ?>>คณะบัญชี</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="s_department" class="form-label">สาขา</label>
                <select class="form-control" id="s_department" name="s_department" required>
                    <option value="">-- เลือกสาขา --</option>
                    <option value="1" <?php echo ($row['s_department'] == 1) ? 'selected' : ''; ?>>วิศวกรรมคอมพิวเตอร์</option>
                    <option value="2" <?php echo ($row['s_department'] == 2) ? 'selected' : ''; ?>>วิศวกรรมเครื่องกล</option>
                    <option value="3" <?php echo ($row['s_department'] == 3) ? 'selected' : ''; ?>>การจัดการธุรกิจ</option>
                    <option value="4" <?php echo ($row['s_department'] == 4) ? 'selected' : ''; ?>>การบัญชี</option>
                </select>
            </div>
            <!-- <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div> -->
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
            <a href="user_profile.php" class="btn btn-secondary mt-2">ยกเลิก</a>
        </form>
    </div>

</body>

</html>