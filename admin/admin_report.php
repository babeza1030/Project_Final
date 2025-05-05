<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ดึงข้อมูลสำหรับตารางและกราฟ
$volunteer_sql = "SELECT activity_name, COUNT(*) AS total_participants, SUM(hours_completed) AS total_hours FROM new_user_activities GROUP BY activity_name";
$volunteer_result = $conn->query($volunteer_sql);

if (!$volunteer_result) {
    die("Error in volunteer SQL: " . $conn->error);
}

$loan_sql = "SELECT loan_status, COUNT(*) AS total_students FROM student_loans GROUP BY loan_status";
$loan_result = $conn->query($loan_sql);

if (!$loan_result) {
    die("Error in loan SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานสรุป</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="../static/css/bootstrap.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .table-container {
            margin-top: 20px;
        }

        .chart-container {
            margin-top: 40px;
            width: 100%;
            max-width: 600px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../static/img/logo.png" alt="Kasem Bundit University">
        <ul>
            <li><a href="admin_dashboard.php"><i class="bi bi-house"></i> หน้าหลัก (Dashboard)</a></li>
            <li><a href="admin_edit_student.php"><i class="bi bi-person"></i> แก้ไขข้อมูลนักศึกษา</a></li>
            <li><a href="adminadd_user.php"><i class="bi bi-person-plus"></i> เพิ่มนักศึกษา</a></li>
            <li><a href="admin_edit_teacher.php"><i class="bi bi-briefcase"></i> แก้ไขข้อมูลอาจารย์</a></li>
            <li><a href="adminadd_teacher.php"><i class="bi bi-person-plus"></i> เพิ่มอาจารย์</a></li>
            <li><a href="admin_Check_document_status.php"><i class="bi bi-file-text"></i> ตรวจสอบเอกสารจิตอาสา</a></li>
            <li><a href="admin_report.php"><i class="bi bi-file-text"></i> รายงานสรุป</a></li>
            <li><a href="adminlogout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>รายงานสรุป</h1>

        <!-- ตารางรายงานสรุปจิตอาสา -->
        <div class="table-container">
            <h2>ตารางรายงานสรุปจิตอาสา</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ชื่อกิจกรรม</th>
                        <th>จำนวนผู้เข้าร่วม</th>
                        <th>จำนวนชั่วโมงรวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $volunteer_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['activity_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_participants']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_hours']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- ตารางสรุปผู้กู้ กยศ. -->
        <div class="table-container">
            <h2>ตารางสรุปผู้กู้ กยศ.</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>สถานะการกู้</th>
                        <th>จำนวนผู้กู้</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $loan_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['loan_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_students']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- กราฟ -->
        <div class="chart-container">
            <h2>กราฟสรุปจิตอาสา</h2>
            <canvas id="volunteerChart"></canvas>
        </div>

        <div class="chart-container">
            <h2>กราฟสรุปผู้กู้ กยศ.</h2>
            <canvas id="loanChart"></canvas>
        </div>
    </div>

    <script>
        // กราฟสรุปจิตอาสา
        const volunteerCtx = document.getElementById('volunteerChart').getContext('2d');
        const volunteerChart = new Chart(volunteerCtx, {
            type: 'bar',
            data: {
                labels: [
                    <?php
                    $volunteer_result->data_seek(0); // รีเซ็ต pointer
                    while ($row = $volunteer_result->fetch_assoc()) {
                        echo '"' . $row['activity_name'] . '",';
                    }
                    ?>
                ],
                datasets: [{
                    label: 'จำนวนชั่วโมงรวม',
                    data: [
                        <?php
                        $volunteer_result->data_seek(0);
                        while ($row = $volunteer_result->fetch_assoc()) {
                            echo $row['total_hours'] . ',';
                        }
                        ?>
                    ],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // กราฟสรุปผู้กู้ กยศ.
        const loanCtx = document.getElementById('loanChart').getContext('2d');
        const loanChart = new Chart(loanCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php
                    $loan_result->data_seek(0);
                    while ($row = $loan_result->fetch_assoc()) {
                        echo '"' . $row['loan_status'] . '",';
                    }
                    ?>
                ],
                datasets: [{
                    label: 'จำนวนผู้กู้',
                    data: [
                        <?php
                        $loan_result->data_seek(0);
                        while ($row = $loan_result->fetch_assoc()) {
                            echo $row['total_students'] . ',';
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

</body>
</html>
