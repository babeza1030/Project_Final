<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ดึงข้อมูลจากตาราง activities และ new_user_activities พร้อมกรองปีและเทอม
$yearFilter = isset($_GET['year']) ? intval($_GET['year']) : null;
$termFilter = isset($_GET['term']) ? intval($_GET['term']) : null;

$query = "
    SELECT a.name AS activity_name, COUNT(nua.activity_id) AS participants
    FROM activities a
    LEFT JOIN new_user_activities nua ON a.id = nua.activity_id
    WHERE 1=1
";

if ($yearFilter) { // ตรวจสอบว่ามีการเลือกปีหรือไม่
    $query .= " AND YEAR(nua.start_date) = $yearFilter";
}

if ($termFilter) {
    $query .= " AND nua.terms = $termFilter";
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
            WHEN s.start_year IS NOT NULL AND (YEAR(CURDATE()) - s.start_year) = 1 THEN 1 
            ELSE 0 
        END) AS year_1,
        SUM(CASE 
            WHEN s.start_year IS NOT NULL AND (YEAR(CURDATE()) - s.start_year) = 2 THEN 1 
            ELSE 0 
        END) AS year_2,
        SUM(CASE 
            WHEN s.start_year IS NOT NULL AND (YEAR(CURDATE()) - s.start_year) = 3 THEN 1 
            ELSE 0 
        END) AS year_3,
        SUM(CASE 
            WHEN s.start_year IS NOT NULL AND (YEAR(CURDATE()) - s.start_year) = 4 THEN 1 
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

// var_dump($loanSummary);
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
            color: #333;
        }

        .table th {
            background-color: #f17629;
            color: white;
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
            max-width: 600px;
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
    <div class="main-content">

        

        <!-- Box: ตารางสรุปผู้กู้ กยศ. -->
        <div class="box">
            <h2>ตารางสรุปผู้กู้ กยศ.</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>คณะ</th>
                        <th>สาขา</th>
                        <th>ปี 1</th>
                        <th>ปี 2</th>
                        <th>ปี 3</th>
                        <th>ปี 4</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loanSummary as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['year_1']); ?></td>
                            <td><?php echo htmlspecialchars($row['year_2']); ?></td>
                            <td><?php echo htmlspecialchars($row['year_3']); ?></td>
                            <td><?php echo htmlspecialchars($row['year_4']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        

        
    </div>



</body>

</html>
