<style>
.box_head {
    margin-left: 250px;
    width: calc(100% - 250px);
    border-radius: 0 0 18px 18px;
    background: linear-gradient(90deg, #FC6600 60%, #FDA50F 100%);
    box-shadow: 0 2px 8px rgba(252,102,0,0.10);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 32px;
}
</style>

<header class="box_head py-3 px-4 mb-4" style="background: linear-gradient(90deg, #FC6600 60%, #FDA50F 100%); border-radius: 0 0 18px 18px; box-shadow: 0 2px 8px rgba(252,102,0,0.10); display: flex; justify-content: space-between; align-items: center;">
    <div class="d-flex flex-column">
        <?php if (isset($_SESSION['username'])): ?>
            <span class="fw-bold text-white" style="font-size: 1.15rem;">
                <i class="bi bi-person-circle me-2"></i>ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
        <?php endif; ?>
    </div>
    <div>
        <span class="text-white" style="font-size: 1.05rem;">
            <i class="bi bi-calendar-event me-1"></i>
            วันที่: 
            <?php
            $th_month = [
                1 => "มกราคม", 2 => "กุมภาพันธ์", 3 => "มีนาคม", 4 => "เมษายน",
                5 => "พฤษภาคม", 6 => "มิถุนายน", 7 => "กรกฎาคม", 8 => "สิงหาคม",
                9 => "กันยายน", 10 => "ตุลาคม", 11 => "พฤศจิกายน", 12 => "ธันวาคม"
            ];
            $d = date("j"); // วันที่
            $m = date("n"); // เดือน (ตัวเลข)
            $y = date("Y") + 543; // ปีพุทธศักราช
            echo $d . " " . $th_month[$m] . " " . $y;
            ?>
        </span>
    </div>
</header>