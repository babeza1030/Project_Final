<header class="box_head">
    <?php if (isset($_SESSION['username'])): ?>
                <select id="navigationDropdown">
                    <option value="index.php">
                        <span>Welcome, <?php echo $_SESSION['username']; ?></span>
                    </option>
                    <option value="index.php">หน้าแรก</option>
                    <option value="user_profile.php">แก้ไขข้อมูล</option>
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
        <img src="../static/img/logo.png" alt="">
        <nav>
            <a href="#">หน้าแรก</a>
            <a href="page-1.html">หลักเกณฑ์การกู้ยืม</a>
            <a href="#">ข่าวสาร</a>
            <a href="#">ติดต่อ</a>
        </nav>
    </div>