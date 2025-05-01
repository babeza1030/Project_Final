<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

function getFacultyName($facultyId) {
    $faculties = [
        1 => "คณะวิศวกรรมศาสตร์",
        2 => "คณะบริหารธุรกิจ",
        3 => "คณะบัญชี"
    ];
    return $faculties[$facultyId] ?? "ไม่ทราบคณะ";
}

function getDepartmentName($departmentId) {
    $departments = [
        1 => "วิศวกรรมคอมพิวเตอร์",
        2 => "วิศวกรรมเครื่องกล",
        3 => "การจัดการธุรกิจ",
        4 => "การบัญชี"
    ];
    return $departments[$departmentId] ?? "ไม่ทราบสาขา";
}

$student_id = $_SESSION['username']; // Assuming username is actually student_id
$sql = "SELECT 
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
        WHERE student.student_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Execute failed: " . htmlspecialchars($stmt->error));
}

$student = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลส่วนตัว</title>

    <link rel="stylesheet" href="../static/css/user_profile.css">
    <link rel="stylesheet" href="../static/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/css/bootstrap.css">


    <style>
        .info-body {
            display: grid;
            grid-template-columns: 1fr 2fr;
            /* คอลัมน์ซ้ายเล็ก ขวาใหญ่ */
            row-gap: 10px;
            /* ระยะห่างระหว่างแถว */
        }

        .info-row {
            display: contents;
        }

        .info-row strong {
            font-weight: 700;
            text-align: left;
            padding-right: 15px;
        }

        .info-row span {
            text-align: left;
        }
    </style>
</head>

<body>
    <?php include('../user/header.php'); ?>

    <div class="container" id="profile-container">
        <h2 class="title">ข้อมูลส่วนตัว</h2>
        <div class="info-row"><strong>รูปภาพ</strong> <span><img src="<?php echo htmlspecialchars($student["profile_image"]); ?>" alt="Profile Image" style="width: 100px; height: auto;"></span></div>

        <!-- <p class="subtitle">ข้อมูลส่วนตัวของนักศึกษา</p> -->

        <!-- กล่องข้อมูลส่วนตัว -->
        <div class="info-card">
            <div class="info-header">ข้อมูลส่วนตัว</div>
            <div class="gradient-line"></div>
            <div class="info-body">
                <div class="info-row"><strong>เลขบัตรประจำตัวประชาชน</strong> <span><?php echo htmlspecialchars($student["student_id"]); ?></span></div>
                <div class="info-row"><strong>เลขรหัสนักศึกษา</strong> <span><?php echo htmlspecialchars($student["student_code"]); ?></span></div>
                <div class="info-row"><strong>ชื่อ</strong> <span><?php echo htmlspecialchars($student["f_name"]); ?></span></div>
                <div class="info-row"><strong>นามสกุล</strong> <span><?php echo htmlspecialchars($student["l_name"]); ?></span></div>
                <div class="info-row"><strong>ที่อยู่</strong> <span><?php echo htmlspecialchars($student["address"]); ?></span></div>
                <div class="info-row"><strong>เบอร์โทรศัพท์</strong> <span><?php echo htmlspecialchars($student["phone_number"]); ?></span></div>
                <div class="info-row"><strong>อีเมล</strong> <span><?php echo htmlspecialchars($student["email"]); ?></span></div>
                <!-- <div class="info-row"><strong>Spouse ID</strong> <span><?php echo htmlspecialchars($student["spouse_id"]); ?></span></div> -->
                <div class="info-row"><strong>เลขบัตรประจำตัวประชาชนบิดา</strong> <span><?php echo htmlspecialchars($student["father_id"]); ?></span></div>
                <div class="info-row"><strong>เลขบัตรประจำตัวประชาชนมารดา</strong> <span><?php echo htmlspecialchars($student["mother_id"]); ?></span></div>
                <!-- <div class="info-row"><strong>Guardian_ID</strong> <span><?php echo htmlspecialchars($student["guardian_id"]); ?></span></div> -->
                <!-- <div class="info-row"><strong>Endorser_ID</strong> <span><?php echo htmlspecialchars($student["endorser_id"]); ?></span></div> -->
                <!-- <div class="info-row"><strong>Department_ID</strong> <span><?php echo htmlspecialchars($student["department_id"]); ?></span></div> -->
                <!-- <div class="info-row"><strong>Family_status_ID</strong> <span><?php echo htmlspecialchars($student["family_status"]); ?></span></div> -->
                <div class="info-row"><strong>ชื่อผู้รับรอง</strong> <span><?php echo htmlspecialchars($student["endorsee_name"]); ?></span></div>
                <div class="info-row"><strong>ที่อยู่ผู้รับรอง</strong> <span><?php echo htmlspecialchars($student["endorsee_address"]); ?></span></div>
                <div class="info-row"><strong>เบอร์โทรศัพท์ผู้รับรอง</strong> <span><?php echo htmlspecialchars($student["endorsee_phone_number"]); ?></span></div>
                <div class="info-row"><strong>คณะ</strong> <span><?php echo htmlspecialchars(getFacultyName($student["s_faculty"])); ?></span></div>
                <div class="info-row"><strong>สาขา</strong> <span><?php echo htmlspecialchars(getDepartmentName($student["s_department"])); ?></span></div>
                <div class="info-row"><strong>สถานภาพครอบครัว</strong> <span><?php echo htmlspecialchars($student["family_status"]); ?></span></div>
                <!-- <div class="info-row"><strong>รหัสผ่าน</strong> <span><?php echo htmlspecialchars($student["password"]); ?></span></div> -->
                
            </div>
            <a href="user_profile_process_1.php?student_id=<?php echo htmlspecialchars($student['student_id']); ?>" class="edit-btn">
                <i class="bi bi-pencil-square"></i> แก้ไขข้อมูล
            </a>
        </div>

        <!-- กล่องข้อมูลติดต่อ -->
        <div class="info-card">
            <div class="info-header">ข้อมูลติดต่อ</div>
            <div class="gradient-line"></div>
            <div class="info-body">
                <div class="info-row"><strong>เบอร์โทรศัพท์มือถือ</strong> <span><?php echo htmlspecialchars($student["phone_number"]); ?></span></div>
                <div class="info-row"><strong>เบอร์โทรศัพท์บ้าน</strong> <span><?php echo htmlspecialchars($student["phone_number_home"]); ?></span></div>
                <div class="info-row"><strong>อีเมล</strong> <span><?php echo htmlspecialchars($student["email"]); ?></span></div>
                <div class="info-row"><strong>ที่อยู่</strong> <span><?php echo htmlspecialchars($student["address"]); ?></span></div>
            </div>
            <a href="user_profile_process_2.php?student_id=<?php echo htmlspecialchars($student['student_id']); ?>" class="edit-btn">
                <i class="bi bi-pencil-square"></i> แก้ไขข้อมูล
            </a>
        </div>

        <!-- กล่องข้อมูลสถานภาพครอบครัว -->
        <div class="info-card">
            <div class="info-header">ข้อมูลสถานภาพครอบครัว</div>
            <div class="gradient-line"></div>
            <div class="info-body">
                <div class="info-row"><strong>ชื่อบิดา</strong> <span><?php echo htmlspecialchars($student["father_name"]); ?></span></div>
                <div class="info-row"><strong>นามสกุลบิดา</strong> <span><?php echo htmlspecialchars($student["father_last_name"]); ?></span></div>
                <div class="info-row"><strong>เลขบัตรประจำตัวประชาชน</strong> <span><?php echo htmlspecialchars($student["father_id"]); ?></span></div>
                <div class="info-row"><strong>ที่อยู่</strong> <span><?php echo htmlspecialchars($student["father_address"]); ?></span></div>
                <div class="info-row"><strong>อาชีพ</strong> <span><?php echo htmlspecialchars($student["father_occupation"]); ?></span></div>
                <div class="info-row"><strong>เงินเดือนบิดา</strong> <span><?php echo htmlspecialchars($student["father_income"]); ?></span></div>
                <div class="info-row"><strong>เบอร์โทรศัพท์บิดา</strong> <span><?php echo htmlspecialchars($student["father_phone_number"]); ?></span></div>
                <div class="info-row"><strong>ชื่อมารดา</strong> <span><?php echo htmlspecialchars($student["mother_name"]); ?></span></div>
                <div class="info-row"><strong>นามสกุลมารดา</strong> <span><?php echo htmlspecialchars($student["mother_last_name"]); ?></span></div>
                <div class="info-row"><strong>เลขบัตรประจำตัวประชาชน</strong> <span><?php echo htmlspecialchars($student["mother_id"]); ?></span></div>
                <div class="info-row"><strong>ที่อยู่</strong> <span><?php echo htmlspecialchars($student["mother_address"]); ?></span></div>
                <div class="info-row"><strong>อาชีพ</strong> <span><?php echo htmlspecialchars($student["mother_occupation"]); ?></span></div>
                <div class="info-row"><strong>เงินเดือนมารดา</strong> <span><?php echo htmlspecialchars($student["mother_income"]); ?></span></div>
                <div class="info-row"><strong>เบอร์โทรศัพท์มารดา</strong> <span><?php echo htmlspecialchars($student["mother_phone_number"]); ?></span></div>
                <div class="info-row"><strong>สถานภาพครอบครัว</strong> <span><?php echo htmlspecialchars($student["family_status"]); ?></span></div>
                <a href="user_profile_process_3.php?student_id=<?php echo htmlspecialchars($student['student_id']); ?>" class="edit-btn">
                    <i class="bi bi-pencil-square"></i> แก้ไขข้อมูล
                </a>
            </div>
                
        </div>  
        
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>