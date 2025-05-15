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

// เพิ่ม Debug: แสดงค่า $_SESSION['username']
echo "Session username: " . htmlspecialchars($_SESSION['username']);

// รับค่าปีจาก URL (GET)
$selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// กำหนดเทอมปัจจุบัน
$current_month = date('n');
$current_term = ($current_month >= 1 && $current_month <= 6) ? '1' : '2';

// รับค่า terms จาก URL (GET) หรือใช้เทอมปัจจุบันเป็นค่าเริ่มต้น
$selected_terms = isset($_GET['terms']) ? $_GET['terms'] : $current_term;

// ปรับ SQL Query เพื่อกรองข้อมูลตามปีและเทอมที่เลือก (JOIN กับ year_table)
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
        act.max_hours
    FROM 
        new_user_activities nau
    LEFT JOIN 
        student stu ON nau.username = stu.student_id
    LEFT JOIN 
        activities act ON nau.activity_id = act.id
    LEFT JOIN 
        year_table yt ON nau.year_id = yt.year_id
    WHERE 
        nau.username = ?
";

// เพิ่มเงื่อนไขกรองปี
if (!empty($selected_year)) {
    $sql .= " AND yt.year = ?";
}

if (!empty($selected_terms)) {
    $sql .= " AND yt.terms = ?";
}

$sql .= " ORDER BY nau.created_at DESC";

// เตรียมคำสั่ง SQL
$stmt = $conn->prepare($sql);

// ตรวจสอบว่าคำสั่ง SQL ถูกเตรียมไว้สำเร็จหรือไม่
if (!$stmt) {
    die("Error preparing SQL: " . $conn->error);
}

// ผูกพารามิเตอร์
if (!empty($selected_year) && !empty($selected_terms)) {
    $stmt->bind_param("sss", $_SESSION['username'], $selected_year, $selected_terms);
} elseif (!empty($selected_year)) {
    $stmt->bind_param("ss", $_SESSION['username'], $selected_year);
} elseif (!empty($selected_terms)) {
    $stmt->bind_param("ss", $_SESSION['username'], $selected_terms);
} else {
    $stmt->bind_param("s", $_SESSION['username']);
}

// รันคำสั่ง SQL
$stmt->execute();

// ดึงผลลัพธ์
$result = $stmt->get_result();

// ดึงข้อมูลปีจาก year_table
$year_sql = "SELECT DISTINCT year FROM year_table ORDER BY year DESC";
$year_result = $conn->query($year_sql);

// ตรวจสอบผลลัพธ์
$years = [];
if ($year_result->num_rows > 0) {
    while ($year_row = $year_result->fetch_assoc()) {
        $years[] = $year_row['year'];
    }
}
$current_year = date('Y');
if (!in_array($current_year, $years)) {
    $years[] = $current_year;
}
rsort($years);

// ดึงค่า terms จาก year_table
$terms_sql = "SELECT DISTINCT terms FROM year_table ORDER BY terms ASC";
$terms_result = $conn->query($terms_sql);

$terms = [];
if ($terms_result->num_rows > 0) {
    while ($row = $terms_result->fetch_assoc()) {
        $terms[] = $row['terms'];
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กิจกรรมจิตสาธารณะ</title>

    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="../static/css/bootstrap.css">

    <!-- Internal CSS -->
    <style>
        /* Reset */
       

        /* Header */
        header {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            font-size: 24px;
            color: #333;
        }

        /* Main Content */
        main {
            padding: 20px;
        }

        /* Filter Section */
        .filter-section {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-end; /* จัดให้อยู่ชิดขวา */
            gap: 10px; /* ระยะห่างระหว่างองค์ประกอบ */
            margin-left: auto; /* บังคับให้ชิดขวา */
            padding-right: 20px; /* เว้นระยะห่างจากขอบขวา */
        }

        .filter-section label {
            margin-right: 5px;
        }

        .filter-section select {
            margin-right: 10px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .filter-section button {
            padding: 5px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filter-section button:hover {
            background-color: #0056b3;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        /* Adjust table text alignment to left */
        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            /* ค่า 기본: ชิดซ้าย */
        }

        /* กำหนด alignment สำหรับคอลัมน์ "ชั่วโมง" และ "ชั่วโมงที่ได้" */
        table th:nth-child(3),
        table th:nth-child(4),
        table td:nth-child(3),
        table td:nth-child(4) {
            text-align: center;
            /* ตรงกลาง */
        }

        /* Header of the table */
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        /* Footer of the table (Total Hours) */
        table tfoot tr {
            background-color: #e9ecef;
            /* Light gray background for total row */
        }

        /* กำหนด alignment สำหรับ td ใน tfoot */
        table tfoot td {
            text-align: center;
            /* ตรงกลาง */
            font-weight: bold;
            /* Bold text for total hours */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }
        }

        /* Custom Header Style */
        h1 {
            text-align: right;
            /* จัดข้อความให้ชิดขวา */
            margin-right: 20px;
            /* เพิ่มระยะห่างจากขอบขวา */
            margin-top: 20px;
            /* เพิ่มระยะห่างจากขอบบน */
            margin-bottom: 20px;
            /* เพิ่มระยะห่างจากขอบล่าง */
            font-size: 32px;
            /* ปรับขนาดตัวอักษรให้ใหญ่ขึ้น */
            color: #333;
            /* สีของข้อความ */
        }

        /* กำหนด alignment สำหรับ td ใน tfoot */
        table tfoot td {
            text-align: left;
            /* ชิดซ้าย */
            font-weight: bold;
            /* ทำให้ข้อความเป็นตัวหนา */
            padding-left: 10px;
            /* เพิ่มระยะห่างจากขอบซ้าย */
        }

        /* กำหนด alignment สำหรับ td ใน tfoot */
        table tfoot td:first-child {
            text-align: left;
            /* ชิดซ้ายสำหรับข้อความ "รวมชั่วโมงทั้งหมด" */
            font-weight: bold;
            /* ทำให้ข้อความเป็นตัวหนา */
            padding-left: 10px;
            /* เพิ่มระยะห่างจากขอบซ้าย */
        }

        table tfoot td:last-child {
            text-align: center;
            /* จัดตัวเลขให้อยู่ตรงกลาง */
            font-weight: bold;
            /* ทำให้ตัวเลขเป็นตัวหนา */
        }

        /* จัดข้อความในคอลัมน์ "ลำดับ" และ "ชื่อกิจกรรม" ให้อยู่ตรงกลาง */
        table th:nth-child(1),
        table td:nth-child(1),
        table th:nth-child(2),
        table td:nth-child(2) {
            text-align: center;
            /* จัดให้อยู่ตรงกลาง */
        }

        .volunteer-button-container {
            text-align: right;
            margin-top: 20px;
            padding-right: 20px;
            /* เว้นขอบด้านขวา */
        }

        .volunteer-button {
            padding: 5px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .volunteer-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <?php include('../user/header.php'); ?>


    <h1>กิจกรรมจิตสาธารณะ กยศ</h1>

    <!-- Filter Section -->
    <div class="filter-section">
        <form>
            <label for="year">เลือกปี:</label>
            <select id="year" name="year">
                <option value="">ทั้งหมด</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?php echo htmlspecialchars($year); ?>" <?php echo ($selected_year == $year) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($year); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- แสดงตัวเลือก terms -->
            <label for="terms">เลือกเทอม:</label>
            <select id="terms" name="terms">
                <option value="">ทั้งหมด</option>
                <?php foreach ($terms as $term): ?>
                    <option value="<?php echo htmlspecialchars($term); ?>" <?php echo ($selected_terms == $term) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($term); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">กรองข้อมูล</button>
        </form>
    </div>

    <!-- ปุ่มส่งแบบฟอร์มจิตอาสา -->
    <div class="volunteer-button-container">
        <button type="button" onclick="location.href='user_studentloan2.php'" class="volunteer-button">
            ส่งแบบฟอร์มจิตอาสา
        </button>
    </div>

    <!-- Main Content -->
    <main>
        <table id="activity-table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อกิจกรรม</th>
                    <th>ชั่วโมงสูงสุด</th>
                    <th>ชั่วโมงที่ทำได้</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_hours = 0; // กำหนดค่าเริ่มต้นให้ตัวแปรรวมชั่วโมงทั้งหมด

                if ($result->num_rows > 0) {
                    $count = 1; // ตัวนับลำดับ
                    while ($row = $result->fetch_assoc()) {
                        echo "<td>" . htmlspecialchars($count) . "</td>";
                        echo "<td>" . htmlspecialchars($row["activity_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["max_hours"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["hours_completed"]) . "</td>";
                        echo "</tr>";
                        $total_hours += $row["hours_completed"];
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่มีข้อมูลกิจกรรม</td></tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">รวมชั่วโมงทั้งหมด:</td>
                    <td><strong><?php echo htmlspecialchars($total_hours); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </main>

    <script>
        // Event Listener สำหรับ year
        document.getElementById('year').addEventListener('change', function () {
            const selectedYear = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('year', selectedYear);
            window.location.href = url.toString();
        });

        // Event Listener สำหรับ terms
        document.getElementById('terms').addEventListener('change', function () {
            const selectedTerms = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('terms', selectedTerms);
            window.location.href = url.toString();
        });
    </script>

</body>

</html>