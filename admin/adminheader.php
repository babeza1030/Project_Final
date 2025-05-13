<header class="box_head">
    <?php if (isset($_SESSION['c_name']) && isset($_SESSION['c_sername'])): ?>

        <select id="navigationDropdown">
            <option value="index.php">
                <span>ยินดีต้อนรับ, <?php echo $_SESSION['c_name'] . " " . $_SESSION['c_sername']; ?></span>
            </option>
            <option value="profile.php">แก้ไขโปรไฟล์</option>
            <option value="#">กยศ.</option>
            <option value="#">จิตอาสา</option>
            <option value="logout.php">ออกจากระบบ</option>
        </select>

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
    <img src="all-imh/logo (1).png" alt="">
    <nav>
        <a href="#">หน้าแรก</a>
        <a href="page-1.html">หลักเกณฑ์การกู้ยืม</a>
        <a href="#">ข่าวสาร</a>
        <a href="#">ติดต่อ</a>
    </nav>
</div>