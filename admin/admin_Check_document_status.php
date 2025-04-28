<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ดึงข้อมูลปีจาก start_date และ end_date
$year_sql = "SELECT DISTINCT YEAR(start_date) AS year_start, YEAR(end_date) AS year_end FROM new_user_activities ORDER BY year_start DESC";
$year_result = $conn->query($year_sql);

// ตรวจสอบข้อผิดพลาด
if (!$year_result) {
    die("SQL Error: " . $conn->error);
}

// ตรวจสอบผลลัพธ์
$years = [];
if ($year_result->num_rows > 0) {
    while ($year_row = $year_result->fetch_assoc()) {
        $years[] = $year_row['year_start'];
        if ($year_row['year_end'] !== $year_row['year_start']) {
            $years[] = $year_row['year_end'];
        }
    }
    $years = array_unique($years); // ลบค่าที่ซ้ำกัน
    rsort($years); // เรียงลำดับจากมากไปน้อย
}

// รับค่าปีและเทอมจาก URL (GET)
$selected_year = isset($_GET['year']) ? $_GET['year'] : '';
$selected_term = isset($_GET['term']) ? $_GET['term'] : '';

// ปรับ SQL Query เพื่อกรองข้อมูลตามปีและเทอมที่เลือก
$sql = "
    SELECT 
        nau.id, 
        nau.activity_name, 
        nau.hours AS hours_completed, 
        nau.location, 
        nau.details, 
        nau.image_path, 
        nau.username, 
        stu.f_name, 
        stu.l_name,
        act.max_hours,
        nau.created_at
    FROM 
        new_user_activities nau
    LEFT JOIN 
        student stu      
    ON 
        nau.username = stu.student_id
    LEFT JOIN 
        activities act
    ON 
        nau.activity_id = act.id
    WHERE 1=1
";

// เพิ่มเงื่อนไขกรองปี
$params = [];
$types = "";

if (!empty($selected_year)) {
    $sql .= " AND YEAR(nau.start_date) = ?";
    $params[] = $selected_year;
    $types .= "s";
}

// เพิ่มเงื่อนไขกรองเทอม
if (!empty($selected_term)) {
    $sql .= " AND nau.term = ?";
    $params[] = $selected_term;
    $types .= "s";
}

// เพิ่มเงื่อนไขกรอง username
if (!empty($_GET['username'])) {
    $sql .= " AND nau.username LIKE ?";
    $params[] = '%' . $_GET['username'] . '%';
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
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .filters {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .filters form {
            margin-left: auto; /* ดันฟอร์มไปด้านขวา */
            display: flex;
            align-items: center;
        }

        .filters input[type="text"] {
            padding: 5px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }

        .filters button {
            margin-left: 10px;
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
            background-color: #007bff;
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
            background-color: #004085;
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
            background-color: #28a745; /* สีเขียว */
            color: #ffffff; /* สีตัวอักษร */
            border: none;
            padding: 8px 16px; /* เพิ่ม padding ให้ปุ่มดูใหญ่ขึ้น */
            border-radius: 5px; /* มุมโค้งมน */
            font-size: 1rem; /* ขนาดตัวอักษร */
            font-weight: bold; /* ตัวอักษรหนา */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease; /* เพิ่มเอฟเฟกต์ */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
        }

        .btn-score:hover {
            background-color: #218838; /* สีเขียวเข้มขึ้นเมื่อ hover */
            transform: scale(1.05); /* ขยายเล็กน้อยเมื่อ hover */
        }

        .btn-score:active {
            background-color: #1e7e34; /* สีเข้มขึ้นเมื่อคลิก */
            transform: scale(0.95); /* ย่อเล็กน้อยเมื่อคลิก */
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
            overflow: auto; /* เพิ่ม overflow เพื่อให้สามารถเลื่อนดูได้ */
            z-index: 1000; /* ทำให้ modal อยู่ด้านบนสุด */
        }

        #detailsModal .modal-content {
            background: #fff;
            margin: 5% auto;
            padding: 20px;
            width: 50%;
            border-radius: 8px;
            position: relative;
            max-height: 90%; /* จำกัดความสูงของ modal */
            overflow-y: auto; /* เพิ่ม scroll bar สำหรับเนื้อหาใน modal */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #detailsModal img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">ตรวจสอบเอกสารจิตอาสา</h2>
        <p class="text-center">ข้อมูลเอกสารที่ส่งเข้ามาในระบบ</p>

        <div class="filters mb-3">
            <label for="year">ปี:</label>
            <select id="year" name="year">
                <option value="">ทั้งหมด</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?php echo htmlspecialchars($year); ?>" <?php echo ($selected_year == $year) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($year); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="term">เทอม:</label>
            <select id="term" name="term">
                <option value="">ทั้งหมด</option>
                <option value="1" <?php echo ($selected_term == '1') ? 'selected' : ''; ?>>เทอม 1</option>
                <option value="2" <?php echo ($selected_term == '2') ? 'selected' : ''; ?>>เทอม 2</option>
            </select>

            <!-- ฟอร์มค้นหา -->
            <form method="GET" action="" style="margin-left: auto; display: flex; align-items: center;">
                <input type="text" name="username" id="username" placeholder="ค้นหา username" 
                       value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>" 
                       style="padding: 5px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1rem;">
                <button type="submit" class="btn btn-primary" style="margin-left: 10px;">ค้นหา</button>
            </form>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>ชื่อจิตกรรม</th>
                    <th>ชั่วโมงที่ทำได้</th>
                    <th>ดูรายละเอียด</th>
                    <th>เพิ่มคะแนน</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $count = 1; // ตัวนับลำดับ
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($count) . "</td>";
                        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["f_name"] . " " . $row["l_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["activity_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["hours_completed"]) . "</td>";
                        echo "<td>
                                <button class='btn btn-primary' onclick='viewDetails(" . json_encode($row) . ")'>ดูรายละเอียด</button>
                              </td>";
                        echo "<td>
                                <button class='btn btn-score' onclick='giveScore(\"" . htmlspecialchars($row["username"]) . "\")'>เพิ่มคะแนน</button>
                              </td>";
                        echo "</tr>";
                        $count++;
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
        <img id="modal-image" src="" alt="Activity Image" style="display: none;">
        <button onclick="closeModal()" class="btn btn-secondary">ปิด</button>
    </div>
</div>

    <script>
        function checkDocument(username) {
            if (confirm("คุณต้องการตรวจเอกสารนี้หรือไม่?")) {
                window.location.href = `check_document.php?username=${encodeURIComponent(username)}`;
            }
        }

        function giveScore(username) {
            const h_hours = prompt("กรุณากรอกคะแนน (0-100):");
            if (h_hours !== null && !isNaN(h_hours) && h_hours >= 0 && h_hours <= 100) {
                window.location.href = `give_score.php?username=${encodeURIComponent(username)}&h_hours=${h_hours}`;
            } else {
                alert("กรุณากรอกคะแนนที่ถูกต้อง!");
            }
        }

        // ใน modal
        function viewDetails(row) {
            document.getElementById('modal-username').innerText = row.username;
            document.getElementById('modal-fullname').innerText = row.f_name + " " + row.l_name;
            document.getElementById('modal-activity').innerText = row.activity_name;
            document.getElementById('modal-max-hours').innerText = row.max_hours;
            document.getElementById('modal-hours').innerText = row.hours_completed;
            document.getElementById('modal-location').innerText = row.location;
            document.getElementById('modal-details').innerText = row.details;
            document.getElementById('modal-created-at').innerText = row.created_at;

            // แสดงรูปภาพ
            if (row.image_path) {
                document.getElementById('modal-image').src = row.image_path;
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
        document.getElementById('year').addEventListener('change', function () {
            updateFilters();
        });

        document.getElementById('term').addEventListener('change', function () {
            updateFilters();
        });

        function updateFilters() {
            const selectedYear = document.getElementById('year').value;
            const selectedTerm = document.getElementById('term').value;
            const url = new URL(window.location.href);

            if (selectedYear) {
                url.searchParams.set('year', selectedYear);
            } else {
                url.searchParams.delete('year');
            }

            if (selectedTerm) {
                url.searchParams.set('term', selectedTerm);
            } else {
                url.searchParams.delete('term');
            }

            window.location.href = url.toString();
        }
    </script>
    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
</body>

</html>