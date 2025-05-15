<header class="box_head py-3 px-4 mb-4" style="background: linear-gradient(90deg, #FC6600 60%, #FDA50F 100%); border-radius: 0 0 18px 18px; box-shadow: 0 2px 8px rgba(252,102,0,0.10); display: flex; justify-content: space-between; align-items: center;">
    <?php if (isset($_SESSION['username'])): ?>
        <?php if (isset($_SESSION['username'])): ?>
            <span class="fw-bold text-white" style="font-size: 1.15rem;">
                <i class="bi bi-person-circle me-2"></i>ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
        <?php endif; ?>
        <div>
            <span class="text-white" style="font-size: 1.05rem;">
                <i class="bi bi-calendar-event me-1"></i>
                วันที่:
                <?php
                $th_month = [
                    1 => "มกราคม",
                    2 => "กุมภาพันธ์",
                    3 => "มีนาคม",
                    4 => "เมษายน",
                    5 => "พฤษภาคม",
                    6 => "มิถุนายน",
                    7 => "กรกฎาคม",
                    8 => "สิงหาคม",
                    9 => "กันยายน",
                    10 => "ตุลาคม",
                    11 => "พฤศจิกายน",
                    12 => "ธันวาคม"
                ];
                $d = date("j");
                $m = date("n");
                $y = date("Y") + 543;
                echo $d . " " . $th_month[$m] . " " . $y;
                ?>
            </span>

            <style>

            </style>

            <script>
                document.getElementById("navigationDropdown").onchange = function() {
                    var selectedLink = this.value;
                    if (selectedLink) {
                        window.location.href = selectedLink;
                    }
                };
            </script>

        <?php else: ?>

            <a href="register.php">ลงทะเบียน</a>
            <a href="login.php">เข้าสู่ระบบ</a>
        <?php endif; ?>
</header>

<div class="box_logo">
    <a href="index.php"><img src="../static/img/logo.png" alt=""></a>


    <nav>
        <a href="index.php">หน้าแรก</a>
        <a href="user_criteria.php">หลักเกณฑ์การกู้ยืม</a>
        <a href="#">ข่าวสาร</a>
        <a href="user_profile.php">แก้ไขข้อมูล</ฟ>
        <a href="user_studentloan1.php">จิตอาสา</a>
        <a href="user_reset_password.php?student_id=<?php echo htmlspecialchars($_SESSION['username']); ?>">เปลี่ยนรหัสผ่าน</a>
        <a href="user_contact.php">ติดต่อ</a>
        <a href="logout.php">ออกจากระบบ</a>
            
    </nav>
</div>