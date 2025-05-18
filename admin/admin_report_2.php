<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ดึงปีและเทอมปัจจุบัน
$currentYear = date("Y");
$currentMonth = date("n");
$currentTerm = ($currentMonth >= 1 && $currentMonth <= 6) ? 1 : 2;

// ตั้งค่า yearFilter และ termFilter เป็นค่าปัจจุบัน หากไม่มีการส่งค่าผ่าน $_GET
$yearFilter = isset($_GET['year']) ? $_GET['year'] : $currentYear;
$termFilter = isset($_GET['terms']) ? $_GET['terms'] : $currentTerm; // เปลี่ยน 'term' เป็น 'terms'

// ดึงปีจาก year_table
$yearsQuery = "SELECT DISTINCT year FROM year_table ORDER BY year DESC";
$yearsResult = $conn->query($yearsQuery);

// Query สำหรับดึงข้อมูลกิจกรรม (JOIN กับ year_table)
$query = "
    SELECT a.name AS activity_name, COUNT(nua.activity_id) AS participants
    FROM activities a
    LEFT JOIN new_user_activities nua ON a.id = nua.activity_id
    LEFT JOIN year_table yt ON nua.year_id = yt.year_id
    WHERE 1=1
";

if ($yearFilter) {
    $query .= " AND yt.year = '$yearFilter'";
}

if ($termFilter) {
    $query .= " AND yt.terms = '$termFilter'";
}

$query .= "
    GROUP BY a.name
    ORDER BY participants DESC
";

$result = $conn->query($query);
if (!$result) {
    die("Error in query: " . $conn->error);
}

$activities = [];
$mostPopularActivity = null;
$maxParticipants = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
        if ($row['participants'] > $maxParticipants) {
            $maxParticipants = $row['participants'];
            $mostPopularActivity = $row['activity_name'];
        }
    }
}

// ดึงข้อมูลจากตาราง faculty, department และ student
$query = "
    SELECT 
        f.faculty_name,
        d.department_name,
        SUM(CASE 
            WHEN LEFT(s.student_code, 2) = '64' THEN 1 
            ELSE 0 
        END) AS year_1,
        SUM(CASE 
            WHEN LEFT(s.student_code, 2) = '63' THEN 1 
            ELSE 0 
        END) AS year_2,
        SUM(CASE 
            WHEN LEFT(s.student_code, 2) = '62' THEN 1 
            ELSE 0 
        END) AS year_3,
        SUM(CASE 
            WHEN LEFT(s.student_code, 2) = '61' THEN 1 
            ELSE 0 
        END) AS year_4
    FROM faculty f
    INNER JOIN department d ON f.faculty_id = d.faculty_id
    LEFT JOIN student s ON d.department_id = s.department_id
    GROUP BY f.faculty_name, d.department_name
    ORDER BY f.faculty_name, d.department_name
";
$result = $conn->query($query);
if (!$result) {
    die("Error in query: " . $conn->error);
}

$loanSummary = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $loanSummary[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสรุป</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }


        .box_head {
            background: #F17629;
            color: white;
            padding: 15px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .box {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .box h2 {
            margin-bottom: 20px;
            color: #00008B;
            font-weight: bold;
        }

        .table th {
            background-color: #f9f9f9;
            color: #00008B;
            font-weight: bold;
        }

        .branch-row {
            display: none;
            background-color: #f9f9f9;
        }

        .branch-row td {
            padding-left: 30px;
        }

        .toggle-btn {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }

        .chart-container {
            margin-top: 40px;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        canvas#volunteerChart {
            display: block;
            width: 100% !important;
            height: auto !important;
        }
    </style>
</head>

<?php include('../admin/admin_header.php'); ?>

<body>

    <?php include('../admin/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="box">
            <h2>ตารางรายงานสรุปจิตอาสา</h2>
            <p class="page-desc" style="font-size:1.1rem; color:#6c757d;">รายละเอียดสรุปรายงานกิจกรรมจิตสาธาณะ</p>
            <!-- ฟอร์มเลือกปีและเทอม -->
            <form method="GET" id="filterForm" class="mb-3">
                <div class="row">
                    <div class="col-6">
                        <label for="year" class="form-label">ปีการศึกษา:</label>
                        <select name="year" id="year" class="form-select" onchange="document.getElementById('filterForm').submit();">
                            <?php
                            foreach ($yearsResult as $yearRow) {
                                // แปลงปีค.ศ. เป็นปีพุทธศักราช
                                $thaiYear = $yearRow['year'] + 543;
                                $selected = (isset($_GET['year']) && $_GET['year'] == $yearRow['year']) || (!isset($_GET['year']) && $yearRow['year'] == $currentYear) ? 'selected' : '';
                                echo "<option value='{$yearRow['year']}' $selected>{$thaiYear}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="terms" class="form-label">เทอม:</label>
                        <select name="terms" id="terms" class="form-select" onchange="document.getElementById('filterForm').submit();">
                            <?php
                            $termOptions = [1, 2];
                            foreach ($termOptions as $term) {
                                $selected = (isset($_GET['terms']) && $_GET['terms'] == $term) || (!isset($_GET['terms']) && $term == $currentTerm) ? 'selected' : '';
                                echo "<option value='$term' $selected>$term</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ชื่อกิจกรรม</th>
                        <th>จำนวนผู้เข้าร่วม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($activity['activity_name']); ?></td>
                            <td><?php echo htmlspecialchars($activity['participants']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- สรุปกิจกรรมที่มีผู้เข้าร่วมมากที่สุด -->
            <div id="mostPopularActivity" class="mt-3">
                <h4>กิจกรรมที่มีผู้เข้าร่วมมากที่สุด:
                    <?php echo htmlspecialchars($mostPopularActivity); ?>
                    (<?php echo $maxParticipants; ?> คน)
                </h4>
            </div>
        </div>

        <!-- โหลด Chart.js และ Chart.js Datalabels Plugin -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

        <!-- กราฟสรุปจิตอาสา -->
        <div class="box chart-container">
            <h2>กราฟสรุปจิตอาสา</h2>
            <div style="position: relative; width: 100%;">
                <canvas id="volunteerChart"></canvas>
            </div>
        </div>

        <style>
            .chart-container {
                margin-top: 40px;
                width: 100%;
                max-width: 100%; /* ให้กราฟมีขนาดเต็มความกว้างของตาราง */
                margin: 0 auto; /* จัดให้อยู่ตรงกลาง */
                border: 1px solid #ddd; /* กรอบเหมือนตาราง */
                border-radius: 10px; /* มุมโค้งเหมือนตาราง */
                padding: 20px; /* ระยะห่างภายในกรอบ */
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); /* เงาเหมือนตาราง */
                background: #ffffff; /* พื้นหลังสีขาว */
            }

            canvas#volunteerChart {
                display: block;
                width: 100% !important;
                height: 400px !important; /* ความสูงของกราฟ */
            }
        </style>

        <script>
            // ตรวจสอบข้อมูลใน Console
            console.log(<?php echo json_encode($activities); ?>);

            // กราฟสรุปจิตอาสา
            const volunteerData = <?php echo json_encode($activities); ?>;

            const volunteerCtx = document.getElementById('volunteerChart').getContext('2d');
            const volunteerChart = new Chart(volunteerCtx, {
                type: 'pie', // เปลี่ยนจาก bar เป็น pie
                data: {
                    labels: volunteerData.map(activity => activity.activity_name), // ชื่อกิจกรรม
                    datasets: [{
                        label: 'จำนวนผู้เข้าร่วม',
                        data: volunteerData.map(activity => activity.participants), // จำนวนผู้เข้าร่วม
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ], // สีของแต่ละส่วนในกราฟ
                        hoverOffset: 4
                    }]
                },
                options: {
            responsive: true,
            maintainAspectRatio: false, // ปิดการรักษาสัดส่วน
            plugins: {
                legend: {
                    position: 'top', // ตำแหน่งของคำอธิบาย
                    labels: {
                        font: {
                            size: 14 // ขนาดตัวหนังสือใน Legend
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const label = tooltipItem.label || '';
                            const value = tooltipItem.raw || 0;
                            return `${label}: ${value} คน`;
                        }
                    }
                },
                datalabels: {
                    color: '#000', // สีของตัวเลข
                    font: {
                        size: 14, // ขนาดตัวเลข
                        weight: 'bold'
                    },
                    formatter: function(value, context) {
                        return value + ' คน'; // แสดงจำนวนพร้อมคำว่า "คน"
                    }
                }
            },
            layout: {
                padding: {
                    top: 20,
                    bottom: 20
                }
            }
        },
        plugins: [ChartDataLabels] // เปิดใช้งาน DataLabels Plugin
    });
</script>
                           