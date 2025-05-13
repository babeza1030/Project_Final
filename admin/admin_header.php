<header class="box_head">
    <?php if (isset($_SESSION['username'])): ?>
        <span>ยินดีต้อนรับ , <?php echo $_SESSION['username']; ?></span>
    <?php endif; ?>

    <p class="text-right"> วันที่: <?php echo date("d/m/Y"); ?></p>
    <br>

</header>