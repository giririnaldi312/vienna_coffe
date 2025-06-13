<div class="col-lg-3">
    <nav class="navbar navbar-expand-lg bg-light rounded border mt-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" style="width: 250px;">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Vienna Coffe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav nav-pills flex-column justify-content-end flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link ps-2 <?php echo ((isset($_GET['x']) && ($_GET['x'] == 'home') || $_GET['x'] == '')) ? 'active link-light' : 'link-dark'; ?>" aria-current="page" href="home"><i class="bi bi-house-fill"></i> Dashboard</a>
                        </li>
                        <?php
                        // Admin (1) dan Pelanggan (5) bisa melihat Daftar Menu
                        if ($hasil['level'] == 1 || $hasil['level'] == 5) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x'] == 'menu') ? 'active link-light' : 'link-dark'; ?>" href="menu"><i class="bi bi-menu-up"></i> Daftar Menu</a>
                            </li>
                        <?php } ?>

                        <?php
                        // Hanya Admin (1) yang bisa melihat Kategori Menu
                        if ($hasil['level'] == 1) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x'] == 'katmenu') ? 'active link-light' : 'link-dark'; ?>" href="katmenu"><i class="bi bi-tags"></i> Kategori Menu</a>
                            </li>
                        <?php } ?>

                        <?php
                        // Admin (1) dan Pelanggan (5) bisa melihat Order
                        if ($hasil['level'] == 1 || $hasil['level'] == 5) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x'] == 'order') ? 'active link-light' : 'link-dark'; ?>" href="order"><i class="bi bi-cart4"></i> Order</a>
                            </li>
                        <?php } ?>

                        <?php
                        // Hanya Admin (1) yang bisa melihat Dapur
                        if ($hasil['level'] == 1) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x'] == 'dapur') ? 'active link-light' : 'link-dark'; ?>" href="dapur"><i class="bi bi-fire"></i> Dapur</a>
                            </li>
                        <?php } ?>

                        <?php
                        // Hanya Admin (1) yang bisa melihat Pengguna
                        if ($hasil['level'] == 1) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x'] == 'user') ? 'active link-light' : 'link-dark'; ?>" href="user"><i class="bi bi-card-checklist"></i> Pengguna</a>
                            </li>
                        <?php } ?>

                        <?php
                        // Admin (1) dan Pelanggan (5) bisa melihat Laporan
                        if ($hasil['level'] == 1 || $hasil['level'] == 5) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x'] == 'report') ? 'active link-light' : 'link-dark'; ?>" href="report"><i class="bi bi-clipboard2-check-fill"></i> Laporan</a>
                            </li>
                        <?php } ?>

                    </ul>

                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>