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

$selected_activity = isset($_SESSION['selected_activity']) ? $_SESSION['selected_activity'] : ['activity_name' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $activity_name = $_POST['activity_name'];
    $location = $_POST['location'];
    $details = $_POST['details'];
    $username = $_SESSION['username'];
    $activity_id = isset($_SESSION['selected_activity']['id']) ? $_SESSION['selected_activity']['id'] : null;

    // คำนวณ start_date และ end_date
    $start_date = date('Y-m-d'); // วันที่ปัจจุบัน
    $end_date = date('Y-m-d', strtotime('+14 days', strtotime($start_date))); // +14 วันจาก start_date

    // คำนวณ terms
    $current_month = date('n'); // เดือนปัจจุบัน (1-12)
    $terms = ($current_month <= 6) ? 1 : 2; // ครึ่งปีแรกเป็นเทอม 1, ครึ่งปีหลังเป็นเทอม 2

    // Handle file upload
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["activity_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check file size
    if ($_FILES["activity_image"]["size"] > 2097152) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
        echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["activity_image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO new_user_activities (username, activity_id, activity_name, location, details, image_path, start_date, end_date, terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissssssi", $username, $activity_id, $activity_name, $location, $details, $target_file, $start_date, $end_date, $terms);
            if ($stmt->execute()) {
                echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location.href='user_studentloan1.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Loan Kasem Bundit</title>

    <link rel="stylesheet" href="../static/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #F17629;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .header img {
            height: 50px;
        }

        .header .title {
            flex: 1;
            text-align: center;
            color: white;
            font-size: 24px;
        }

        .nav {
            background-color: #F17629;
            overflow: hidden;
        }

        .nav a {
            float: right;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 18px;
        }

        .nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .content {
            padding: 20px;
        }

        .nav .active {
            background-color: white;
            color: #F17629;
        }

        .nav .active:hover {
            background-color: white;
            color: #F17629;
        }

        .menu-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #ffffff;
            border-bottom: 2px solid #F17629;
        }

        .menu-container img.logo {
            height: 60px;
            /* ปรับขนาดภาพเป็น 60px */
        }

        .menu {
            display: flex;
            justify-content: flex-end;
        }

        .menu a {
            margin: 0 10px;
            color: #F17629;
            text-decoration: none;
            font-size: 18px;
        }

        .menu a:hover {
            text-decoration: underline;
        }

        .h11 {
            font-size: 25px;
            color: #17008a;
        }

        footer {
            background-color: #F17629;
            color: white;
            text-align: center;
            padding: 5px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>

<body>
    <?php include('../user/header.php'); ?>

    <div class="menu-container">
        <div class="menu">
            <a href="user_studentloan1.php" class="btn btn-primary">หน้าแรก</a>
            <a href="user_studentloan2.php" class="btn btn-secondary">กิจกรรม</a>
            <a href="user_studentloan4.php" class="btn btn-secondary">เพิ่มเติม</a>
        </div>
    </div>

    <main class="container my-4">
        <h2 class="text-center mb-4">กิจกรรมจิตอาสา กยศ.</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="activity-name" class="form-label">ชื่อกิจกรรม</label>
                <input type="text" class="form-control" id="activity-name" name="activity_name" placeholder="ชื่อกิจกรรมที่เลือก..."
                    value="<?php echo htmlspecialchars($selected_activity['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">สถานที่</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="เพิ่มสถานที่...">
            </div>
            <div class="mb-3">
                <label for="details" class="form-label">รายละเอียดกิจกรรม</label>
                <textarea class="form-control" id="details" name="details" rows="4" placeholder="การมีส่วนร่วมของนักศึกษา..."></textarea>
            </div>
            <div class="mb-3">
                <label for="activity-image" class="form-label">ภาพกิจกรรม</label>
                <input type="file" class="form-control" id="activity-image" name="activity_image" accept=".jpg, .jpeg, .png">
                <div class="form-text">** Maximum 2MB. Type .jpg, .jpeg, .png</div>
            </div>
            <button type="submit" class="btn btn-success">เสร็จสิ้น</button>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>