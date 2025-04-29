<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ดึงข้อมูลทั้งหมดจากฐานข้อมูล student
$sql = "SELECT student_id, student_code, CONCAT(f_name, ' ', l_name) AS full_name, address, phone_number, email FROM student";
$result = $conn->query($sql);

// ดึงข้อมูลคณะและสาขา
$faculty_sql = "
    SELECT 
        f.faculty_name, 
        d.department_name 
    FROM 
        faculty f
    LEFT JOIN 
        department d 
    ON 
        f.faculty_id = d.faculty_id
    ORDER BY f.faculty_name, d.department_name
";
$faculty_result = $conn->query($faculty_sql);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="../static/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ตั้งค่า Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #ffffff;
            color: #333;
            padding-top: 20px;
            border-right: 2px solid #ddd;
        }

        .sidebar img {
            display: block;
            width: 80%;
            margin: 0 auto 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 12px 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .sidebar ul li a {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 16px;
            transition: 0.3s;
        }

        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 18px;
            color: #F17629;
        }

        .sidebar ul li a:hover {
            background: #f5f5f5;
            padding-left: 10px;
            border-radius: 5px;
        }

        /* ตั้งค่าเนื้อหาหลัก */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
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

        .status-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .status-card {
            width: 300px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            color: white;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .pending { background-color: #f8c471; }
        .in-review { background-color: #5d6d7e; }
        .approved { background-color: #7dcea0; }

        /* ตาราง */
        .table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f17629;
            color: white;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../static/img/logo.png" alt="Kasem Bundit University">
        <ul>
            <li><a href="admin_dashboard.php"><i class="bi bi-house"></i> หน้าหลัก (Dashboard)</a></li>
            <!-- <li><a href="adminCheck_Borrower_Status.php"><i class="bi bi-search"></i> ตรวจสอบสถานะผู้กู้</a></li> -->
            <!-- <li><a href="adminCheck_form_Status.php"><i class="bi bi-file-text"></i> ตรวจสอบสถานะเอกสาร</a></li> -->
            <li><a href="admin_edit_student.php"><i class="bi bi-person"></i> แก้ไขข้อมูลนักศึกษา</a></li>
            <li><a href="adminadd_user.php"><i class="bi bi-person-plus"></i> เพิ่มนักศึกษา</a></li>
            <li><a href="admin_edit_teacher.php"><i class="bi bi-briefcase"></i> แก้ไขข้อมูลอาจารย์</a></li>
            <li><a href="adminadd_teacher.php"><i class="bi bi-person-plus"></i> เพิ่มอาจารย์</a></li>
            <!-- <li><a href="admin_edit_admin.php"><i class="bi bi-gear"></i> จัดการแอดมิน</a></li> -->
            <li><a href="admin_Check_document_status.php"><i class="bi bi-file-text"></i> ตรวจสอบเอกสารจิตอาสา</a></li>




            <li><a href="adminlogout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header class="box_head">
            <?php if (isset($_SESSION['username'])): ?>
                <span>ยินดีต้อนรับ , <?php echo $_SESSION['username']; ?></span>
            <?php endif; ?>
            
            <p class="text-right">  วันที่: <?php echo date("d/m/Y"); ?></p>
            <br>

        </header>
        <div class="container mt-4">
            <h2 class="text-center">ตรวจสอบสถานะผู้กู้</h2>
            <p class="text-center">ข้อมูลนักศึกษาที่ลงทะเบียนในระบบ</p>
        <div class="dashboard text-center">

            <!-- <h1>Admin Dashboard</h1> -->

                <!-- การ์ดสถานะ
                <div class="status-container">
                    <div class="status-card pending">
                        <h3>รอดำเนินการ</h3>
                        <p>กำลังเตรียมเอกสารและส่งข้อมูล</p>
                    </div>

                    <div class="status-card in-review">
                        <h3>กำลังตรวจสอบ</h3>
                        <p>เจ้าหน้าที่กำลังพิจารณาเอกสาร</p>
                    </div>

                    <div class="status-card approved">
                        <h3>อนุมัติแล้ว</h3>
                        <p>ได้รับอนุมัติเรียบร้อย สามารถดำเนินการต่อ</p>
                    </div>
                </div> -->

            <!-- ค้นหา -->
            <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="ค้นหานักศึกษา...">
            </div>

            <!-- ตารางนักศึกษา -->
            <table class="table">
                <thead>
                    <tr>
                        <th>เลขบัตรประชาชน</th>
                        <th>รหัสนักศึกษา</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>ที่อยู่</th>
                        <th>เบอร์โทร</th>
                        <th>อีเมล</th>
                    </tr>
                </thead>
                <tbody id="studentTable">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["student_id"]) ?></td>
                            <td><?= htmlspecialchars($row["student_code"]) ?></td>
                            <td><?= htmlspecialchars($row["full_name"]) ?></td>
                            <td><?= htmlspecialchars($row["address"]) ?></td>
                            <td><?= htmlspecialchars($row["phone_number"]) ?></td>
                            <td><?= htmlspecialchars($row["email"]) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="text-center">ข้อมูลคณะและสาขา</h2>
        <p class="text-center">รายชื่อคณะและสาขาที่มีในระบบ</p>

        <table class="table">
            <thead>
                <tr>
                    <th>คณะ</th>
                    <th>สาขา</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $faculty_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["faculty_name"]) ?></td>
                        <td><?= htmlspecialchars($row["department_name"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="container mt-4">
        <h2 class="text-center">สถิติข้อมูลนักศึกษา</h2>
        <canvas id="studentChart" width="400" height="200"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const studentData = {
            labels: ['คณะวิทยาศาสตร์', 'คณะวิศวกรรมศาสตร์', 'คณะบริหารธุรกิจ', 'คณะมนุษยศาสตร์'],
            datasets: [{
                label: 'จำนวนนักศึกษา',
                data: [120, 150, 100, 80], // ตัวอย่างข้อมูล
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
        };

        const config = {
            type: 'bar',
            data: studentData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const studentChart = new Chart(
            document.getElementById('studentChart'),
            config
        );
    </script>

    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let searchValue = this.value.toLowerCase();
            let tableRows = document.getElementById("studentTable").getElementsByTagName("tr");

            for (let row of tableRows) {
                row.style.display = row.innerText.toLowerCase().includes(searchValue) ? "" : "none";
            }
        });
    </script>

</body>
</html>
