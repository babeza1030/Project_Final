<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ดึงข้อมูลปีและเทอมจาก year_table
$year_sql = "SELECT DISTINCT year, terms FROM year_table ORDER BY year DESC, terms ASC";
$year_result = $conn->query($year_sql);

// ตรวจสอบข้อผิดพลาด
if (!$year_result) {
    die("SQL Error: " . $conn->error);
}

// สร้าง array สำหรับปีและเทอม
$years = [];
$terms = [];
if ($year_result->num_rows > 0) {
    while ($row = $year_result->fetch_assoc()) {
        $years[] = $row['year'];
        $terms[] = $row['terms'];
    }
    $years = array_unique($years);
    $terms = array_unique($terms);
    rsort($years);
    sort($terms);
}

// รับค่าปีและเทอมจาก URL (GET)
$selected_year = isset($_GET['year']) ? $_GET['year'] : '';
$selected_term = isset($_GET['terms']) ? $_GET['terms'] : ''; // เปลี่ยน term เป็น terms

// ดึงปีและเทอมปัจจุบัน
$current_year = date("Y");
$current_term = "1"; // กำหนดเทอมปัจจุบัน (ปรับตามเงื่อนไขของระบบคุณ)

// หากไม่มีการเลือกปีหรือเทอม ให้ใช้ค่าปัจจุบัน
if (empty($selected_year)) {
    $selected_year = $current_year;
}
if (empty($selected_term)) {
    $selected_term = $current_term;
}

// ปรับ SQL Query เพื่อกรองข้อมูลตามปีและเทอมที่เลือก
$sql = "
    SELECT 
        nau.id, 
        nau.student_code AS username,  
        stu.f_name, 
        stu.l_name,
        nau.activity_name, 
        act.max_hours,
        nau.hours AS hours_completed, 
        nau.location, 
        nau.details, 
        nau.image_path, 
        nau.created_at,
        yt.year,
        yt.terms
    FROM 
        new_user_activities nau
    LEFT JOIN 
        student stu ON nau.student_code = stu.student_code 
    LEFT JOIN 
        activities act ON nau.activity_id = act.id
    LEFT JOIN 
        year_table yt ON nau.year_id = yt.year_id
    WHERE 1=1
";

// เพิ่มเงื่อนไขกรองปี
$params = [];
$types = "";

if (!empty($selected_year)) {
    $sql .= " AND yt.year = ?";
    $params[] = $selected_year;
    $types .= "s";
}

// เพิ่มเงื่อนไขกรองเทอม
if (!empty($selected_term)) {
    $sql .= " AND yt.terms = ?";
    $params[] = $selected_term;
    $types .= "s";
}

// ตรวจสอบว่ามีการกรอกชื่อผู้ใช้หรือไม่
$is_searching = !empty($_GET['username']);

// ปรับ SQL Query ตามเงื่อนไข
if ($is_searching) {
    // กรณีค้นหาชื่อผู้ใช้
    $sql .= " AND (stu.student_code LIKE ? OR stu.f_name LIKE ? OR stu.l_name LIKE ?)";
    $params[] = '%' . $_GET['username'] . '%';
    $params[] = '%' . $_GET['username'] . '%';
    $params[] = '%' . $_GET['username'] . '%';
    $types .= "sss";
} else {
    // กรณีเลือกปีและเทอม (หรือไม่มีการกรอกอะไรเลย)
    if (!empty($selected_year)) {
        $sql .= " AND yt.year = ?";
        $params[] = $selected_year;
        $types .= "s";
    }
    if (!empty($selected_term)) {
        $sql .= " AND yt.terms = ?";
        $params[] = $selected_term;
        $types .= "s";
    }
}

// รับค่าจาก URL
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// เพิ่มตัวกรองสถานะใน SQL Query
if ($status_filter === 'checked' || $status_filter === 'unchecked') {
    $sql .= " AND nau.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$sql .= " ORDER BY nau.created_at DESC";

// เตรียมคำสั่ง SQL
$stmt = $conn->prepare($sql);

// ตรวจสอบว่าคำสั่ง SQL ถูกเตรียมไว้สำเร็จหรือไม่
if (!$stmt) {
    die("Error preparing SQL: " . $conn->error);
}

// ผูกพารามิเตอร์
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// รันคำสั่ง SQL
$stmt->execute();

// ดึงผลลัพธ์
$result = $stmt->get_result();

// Query สำหรับนับเอกสารที่ตรวจแล้วและยังไม่ตรวจ (กรองตามปีและเทอม)
$count_params = [];
$count_types = "";
$count_where = "";

if (!empty($selected_year)) {
    $count_where .= " AND yt.year = ?";
    $count_params[] = $selected_year;
    $count_types .= "s";
}
if (!empty($selected_term)) {
    $count_where .= " AND yt.terms = ?";
    $count_params[] = $selected_term;
    $count_types .= "s";
}

$checked_count_sql = "
    SELECT COUNT(*) AS checked_count
    FROM new_user_activities nau
    LEFT JOIN year_table yt ON nau.year_id = yt.year_id
    WHERE nau.status = 'checked' $count_where
";
$unchecked_count_sql = "
    SELECT COUNT(*) AS unchecked_count
    FROM new_user_activities nau
    LEFT JOIN year_table yt ON nau.year_id = yt.year_id
    WHERE nau.status = 'unchecked' $count_where
";

$checked_count_stmt = $conn->prepare($checked_count_sql);
$unchecked_count_stmt = $conn->prepare($unchecked_count_sql);

if (!empty($count_params)) {
    $checked_count_stmt->bind_param($count_types, ...$count_params);
    $unchecked_count_stmt->bind_param($count_types, ...$count_params);
}

$checked_count_stmt->execute();
$checked_count_result = $checked_count_stmt->get_result();
$checked_count = $checked_count_result->fetch_assoc()['checked_count'] ?? 0;

$unchecked_count_stmt->execute();
$unchecked_count_result = $unchecked_count_stmt->get_result();
$unchecked_count = $unchecked_count_result->fetch_assoc()['unchecked_count'] ?? 0;

$checked_count_stmt->close();
$unchecked_count_stmt->close();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบสถานะเอกสาร</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="../static/css/bootstrap.css">
    <style>
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
            margin-left: 270px;
            /* เว้นที่สำหรับ Sidebar */
            max-width: 1200px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            font-size: 1rem;
            color: #6c757d;
        }

        .filters {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .filters .form-label {
            font-weight: bold;
            color: #495057;
        }

        .filters .form-control {
            border-radius: 4px;
            font-size: 1rem;
        }

        .filters .btn-primary {
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .table th {
            background-color: #f1f1f1;
            font-weight: bold;
            color: #495057;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            font-size: 0.9rem;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #00008B;
            color: #ffffff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-primary:active {
            background-color: #00008B;
            transform: scale(0.95);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #ffffff;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-check {
            background-color: #5d6d7e;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-check:hover {
            background-color: #34495e;
        }

        .btn-score {
            background-color: #28a745;
            /* สีเขียว */
            color: #ffffff;
            /* สีตัวอักษร */
            border: none;
            padding: 8px 16px;
            /* เพิ่ม padding ให้ปุ่มดูใหญ่ขึ้น */
            border-radius: 5px;
            /* มุมโค้งมน */
            font-size: 1rem;
            /* ขนาดตัวอักษร */
            font-weight: bold;
            /* ตัวอักษรหนา */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            /* เพิ่มเอฟเฟกต์ */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* เพิ่มเงา */
        }

        .btn-score:hover {
            background-color: #218838;
            /* สีเขียวเข้มขึ้นเมื่อ hover */
            transform: scale(1.05);
            /* ขยายเล็กน้อยเมื่อ hover */
        }

        .btn-score:active {
            background-color: #1e7e34;
            /* สีเข้มขึ้นเมื่อคลิก */
            transform: scale(0.95);
            /* ย่อเล็กน้อยเมื่อคลิก */
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        #detailsModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            overflow: auto;
            /* เพิ่ม overflow เพื่อให้สามารถเลื่อนดูได้ */
            z-index: 1000;
            /* ทำให้ modal อยู่ด้านบนสุด */
        }

        #detailsModal .modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 20px;
            width: 50%;
            border-radius: 8px;
            position: relative;
            max-height: 90%;
            /* จำกัดความสูงของ modal */
            overflow-y: auto;
            /* เพิ่ม scroll bar สำหรับเนื้อหาใน modal */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #detailsModal img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }

        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        /* ปรับ Card */
        .card {
            border: none;
            /* ลบขอบของ Card */
            border-radius: 8px;
            /* มุมโค้งมน */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* เงาเพื่อเพิ่มความลึก */
        }

        .card-body {
            padding: 20px;
            /* ระยะห่างภายใน Card */
        }

        .card-title {
            font-size: 1.5rem;
            /* ขนาดตัวอักษรใหญ่ */
            font-weight: bold;
            /* ตัวอักษรหนา */
        }

        .card-text {
            font-size: 2.5rem;
            /* ขนาดตัวอักษรใหญ่สำหรับจำนวนเอกสาร */
            margin-bottom: 10px;
            /* ระยะห่างระหว่างจำนวนเอกสารกับคำอธิบาย */
        }

        .text-muted {
            font-size: 0.9rem;
            /* ขนาดตัวอักษรเล็กสำหรับคำอธิบาย */
            color: rgba(255, 255, 255, 0.7);
            /* สีอ่อนๆ สำหรับคำอธิบาย */
        }

        .btn-details {
            background-color: #00008B;
            /* สีฟ้า */
            color: #ffffff;
            /* สีตัวอักษร */
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-details:hover {
            background-color: #138496;
            /* สีฟ้าเข้มขึ้นเมื่อ hover */
            transform: scale(1.05);
            /* ขยายเล็กน้อยเมื่อ hover */
            color: #ffffff;
        }

        .btn-details:active {
            background-color: #117a8b;
            /* สีเข้มขึ้นเมื่อคลิก */
            transform: scale(0.95);
            /* ย่อเล็กน้อยเมื่อคลิก */
        }

        .card-selected {
            transform: scale(1.05);
            /* ขยาย Card ขึ้น 5% */
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
            /* เพิ่มเงา */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            /* เพิ่มเอฟเฟกต์การเปลี่ยนแปลง */
        }

        /* ปรับ select ให้มี caret สามเหลี่ยม */
        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='gray' height='18' viewBox='0 0 20 20' width='18' xmlns='http://www.w3.org/2000/svg'><path d='M5.516 7.548a.625.625 0 0 1 .884-.032l3.6 3.375 3.6-3.375a.625.625 0 1 1 .852.916l-4.025 3.775a.625.625 0 0 1-.852 0l-4.025-3.775a.625.625 0 0 1-.032-.884z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.2em;
            padding-right: 2.5em;
        }

        .page-title {
            color:#00008B ;
        }
        
    </style>
</head>

<?php include('../admin/admin_header.php'); ?>

<body>

    <?php include('../admin/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <h2 class="page-title">ตรวจสอบเอกสารจิตอาสา</h2>
        <p class="page-desc">ข้อมูลเอกสารที่ส่งเข้ามาในระบบ</p>

        <!-- Tabs -->
        <ul class="nav nav-tabs justify-content-start ps-2 mb-4" id="statusTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link <?php echo (!isset($_GET['status']) || $_GET['status'] === 'unchecked') ? 'active' : ''; ?>"
                   href="?status=unchecked<?php
                        if (!empty($selected_year)) echo '&year=' . urlencode($selected_year);
                        if (!empty($selected_term)) echo '&terms=' . urlencode($selected_term);
                        if (!empty($_GET['username'])) echo '&username=' . urlencode($_GET['username']);
                   ?>">
                    ยังไม่ตรวจ
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link <?php echo (isset($_GET['status']) && $_GET['status'] === 'checked') ? 'active' : ''; ?>"
                   href="?status=checked<?php
                        if (!empty($selected_year)) echo '&year=' . urlencode($selected_year);
                        if (!empty($selected_term)) echo '&terms=' . urlencode($selected_term);
                        if (!empty($_GET['username'])) echo '&username=' . urlencode($_GET['username']);
                   ?>">
                    ตรวจแล้ว
                </a>
            </li>
        </ul>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form method="GET" action="" class="row g-3">
                    <!-- เลือกปีและเทอมในบรรทัดเดียวกัน -->
                    <div class="col-md-6">
                        <label for="year" class="form-label">ปีการศึกษา:</label>
                        <select name="year" id="year" class="form-control" onchange="this.form.submit();">
                            <option value="">-- เลือกปี --</option>
                            <?php foreach ($years as $year): ?>
                                <?php $thaiYear = $year + 543; // แปลงปีค.ศ. เป็นปีพุทธศักราช ?>
                                <option value="<?php echo $year; ?>" <?php echo ($selected_year == $year) ? 'selected' : ''; ?>>
                                    <?php echo $thaiYear; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="terms" class="form-label">เทอม:</label>
                        <select name="terms" id="terms" class="form-control" onchange="this.form.submit();">
                            <option value="">-- เลือกเทอม --</option>
                            <?php foreach ($terms as $term): ?>
                                <option value="<?php echo htmlspecialchars($term); ?>" <?php echo ($selected_term == $term) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($term); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- ค้นหาชื่อผู้ใช้และปุ่มค้นหาในบรรทัดเดียวกัน -->
                    <div class="col-md-9">
                        <label for="username" class="form-label">ชื่อผู้ใช้:</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="ค้นหาชื่อผู้ใช้" value="<?php echo htmlspecialchars($_GET['username'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
                    </div>

                    <!-- เพิ่ม hidden input สำหรับ status -->
                    <input type="hidden" name="status" value="<?php echo isset($_GET['status']) ? htmlspecialchars($_GET['status']) : 'unchecked'; ?>">
                </form>
            </div>
        </div>

        <?php if (!isset($_GET['status']) || $_GET['status'] === 'unchecked'): ?>
            <!-- การ์ดสรุปจำนวน: ยังไม่ตรวจ -->
            <div class="mb-3">
                <div class="summary-card  text-dark d-flex flex-column align-items-center justify-content-center py-3 rounded shadow-sm" style="background:  #f1c40f;">
                    <div class="fs-4 fw-bold">ยังไม่ตรวจ</div>
                    <div class="fs-2 fw-bold"><?php echo $unchecked_count; ?></div>
                    <div class="small">รายการที่ยังไม่ได้รับการตรวจสอบ</div>
                </div>
            </div>
        <?php elseif ($_GET['status'] === 'checked'): ?>
            <!-- การ์ดสรุปจำนวน: ตรวจแล้ว -->
            <div class="mb-3">
                <div class="summary-card bg-success text-white d-flex flex-column align-items-center justify-content-center py-3 rounded shadow-sm" style="background: rgb(2, 179, 40);">
                    <div class="fs-4 fw-bold">ตรวจแล้ว</div>
                    <div class="fs-2 fw-bold"><?php echo $checked_count; ?></div>
                    <div class="small">รายการที่ตรวจสอบแล้ว</div>
                </div>
            </div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>ชื่อจิตกรรม</th>
                    <th>ชั่วโมงสูงสุด</th> <!-- เพิ่มคอลัมน์นี้ -->
                    <th>ชั่วโมงที่ทำได้</th>
                    <th>ดูรายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $count = 1; // ตัวนับลำดับเริ่มต้นที่ 1
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($count) . "</td>"; // ใช้ตัวนับลำดับแทน ID
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['f_name'] . " " . $row['l_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['activity_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['max_hours']) . "</td>"; // เพิ่มชั่วโมงสูงสุด
                        echo "<td>" . htmlspecialchars($row['hours_completed']) . "</td>";
                        echo "<td><button class='btn btn-details' onclick='viewDetails(" . json_encode($row) . ")'>ดูรายละเอียด</button></td>";
                        echo "</tr>";
                        $count++; // เพิ่มค่าตัวนับลำดับ
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>ไม่มีข้อมูลกิจกรรม</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="detailsModal">
        <div class="modal-content">
            <h3>รายละเอียดกิจกรรม</h3>
            <p><strong>ชื่อผู้ใช้:</strong> <span id="modal-username"></span></p>
            <p><strong>ชื่อ-นามสกุล:</strong> <span id="modal-fullname"></span></p>
            <p><strong>ชื่อจิตกรรม:</strong> <span id="modal-activity"></span></p>
            <p><strong>ชั่วโมงสูงสุด:</strong> <span id="modal-max-hours"></span></p>
            <p><strong>ชั่วโมงที่ทำได้:</strong> <span id="modal-hours"></span></p>
            <p><strong>สถานที่:</strong> <span id="modal-location"></span></p>
            <p><strong>คำอธิบาย:</strong> <span id="modal-details"></span></p>
            <p><strong>วันที่สร้าง:</strong> <span id="modal-created-at"></span></p>
            <p><strong>รูปภาพ:</strong></p>
            <img id="modal-image" src="" alt="ชื่อกิจกรรม" style="display: none;">

            <!-- ปุ่มเพิ่มคะแนน -->
            <div class="mt-3">
                <button onclick="giveScoreInModal()" class="btn btn-score">เพิ่มคะแนน</button>
            </div>

            <button onclick="closeModal()" class="btn btn-secondary mt-3">ปิด</button>
        </div>
    </div>

    <!-- Modal สำหรับกรอกคะแนน -->
    <div id="scoreModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000;">
        <div class="modal-content" style="background: #fff; margin: 10% auto; padding: 20px; width: 40%; border-radius: 8px; position: relative;">
            <h3>เพิ่มคะแนน</h3>
            <p><strong>ชื่อกิจกรรม:</strong> <span id="score-activity-name"></span></p>
            <p><strong>ชั่วโมงสูงสุด:</strong> <span id="score-max-hours"></span></p>
            <form id="scoreForm" method="GET" action="give_score.php">
                <input type="hidden" name="username" id="score-username">
                <label for="score-input">กรุณากรอกคะแนน:</label>
                <input type="number" id="score-input" name="h_hours" class="form-control" min="0" max="" required>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                    <button type="button" class="btn btn-secondary" onclick="closeScoreModal()">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkDocument(username, activityName) {
            if (confirm(`คุณต้องการตรวจเอกสารของกิจกรรม "${activityName}" หรือไม่?`)) {
                window.location.href = `check_document.php?username=${encodeURIComponent(username)}`;
            }
        }

        function giveScoreInModal() {
            const username = document.getElementById('modal-username').innerText;
            const activityName = document.getElementById('modal-activity').innerText;
            const maxHours = parseInt(document.getElementById('modal-max-hours').innerText);

            // ตั้งค่าข้อมูลใน Modal
            document.getElementById('score-username').value = username;
            document.getElementById('score-activity-name').innerText = activityName;
            document.getElementById('score-max-hours').innerText = maxHours;
            document.getElementById('score-input').max = maxHours;

            // แสดง Modal
            document.getElementById('scoreModal').style.display = 'block';
        }

        function closeScoreModal() {
            document.getElementById('scoreModal').style.display = 'none';
        }

        // ใน modal
        function viewDetails(row) {
            document.getElementById('modal-username').innerText = row.username;
            document.getElementById('modal-fullname').innerText = row.f_name + " " + row.l_name;
            document.getElementById('modal-activity').innerText = row.activity_name;
            document.getElementById('modal-max-hours').innerText = row.max_hours; // กำหนดค่าชั่วโมงสูงสุด
            document.getElementById('modal-hours').innerText = row.hours_completed;
            document.getElementById('modal-location').innerText = row.location;
            document.getElementById('modal-details').innerText = row.details;
            document.getElementById('modal-created-at').innerText = row.created_at;

            // แสดงรูปภาพ
            if (row.image_path) {
                document.getElementById('modal-image').src = row.image_path;
                document.getElementById('modal-image').alt = row.activity_name; // ใช้ชื่อกิจกรรมใน alt
                document.getElementById('modal-image').style.display = 'block';
            } else {
                document.getElementById('modal-image').style.display = 'none';
            }

            document.getElementById('detailsModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }
    </script>
    <script>
        document.getElementById('year').addEventListener('change', function() {
            updateFilters();
        });

        document.getElementById('terms').addEventListener('change', function() { // เปลี่ยน id เป็น terms
            const selectedTerm = this.value;
            const url = new URL(window.location.href);
            if (selectedTerm) {
                url.searchParams.set('terms', selectedTerm); // เปลี่ยน term เป็น terms
            } else {
                url.searchParams.delete('terms'); // เปลี่ยน term เป็น terms
            }
            window.location.href = url.toString();
        });

        function updateFilters() {
            const selectedYear = document.getElementById('year').value;
            const selectedTerm = document.getElementById('terms').value;
            const url = new URL(window.location.href);

            if (selectedYear) {
                url.searchParams.set('year', selectedYear);
            } else {
                url.searchParams.delete('year');
            }

            if (selectedTerm) {
                url.searchParams.set('terms', selectedTerm);
            } else {
                url.searchParams.delete('terms');
            }

            window.location.href = url.toString();
        }
    </script>
    <!-- <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div> -->
</body>

</html>
<!-- เพิ่ม CSS สำหรับ tab -->
<style>
    .nav-tabs .nav-link {
        font-size: 1.1rem;
        font-weight: bold;
        color: #495057;
        border: 1px solid #dee2e6;
        border-bottom: none;
        background: #f8f9fa;
        margin-right: 2px;
        border-radius: 8px 8px 0 0;
        transition: background 0.2s, color 0.2s;
    }
    .nav-tabs .nav-link.active {
        background: #fff;
        color: #FC6600;
        border-bottom: 2px solid #fff;
        border-top: 3px solid #FC6600;
        border-right: 1px solid #dee2e6;
        border-left: 1px solid #dee2e6;
        font-weight: bold;
    }
    .nav-tabs .badge {
        margin-left: 6px;
        font-size: 1rem;
        vertical-align: middle;
    }
</style>