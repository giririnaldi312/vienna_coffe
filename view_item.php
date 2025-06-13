<?php
include "proses/connect.php";

// Query untuk list order
$kode = $_GET['order'];
$meja = $_GET['meja'];
$pelanggan = $_GET['pelanggan'];

$level_user = $_SESSION['level_vienna_coffee'];
$username_pelanggan_session = $_SESSION['username_vienna_coffee'];

$query_sql = "SELECT *, SUM(harga*jumlah) AS harganya FROM tb_list_order
  LEFT JOIN tb_order ON tb_order.id_order = tb_list_order.kode_order
  LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
  LEFT JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
  GROUP BY id_list_order
  HAVING tb_list_order.kode_order = '$kode'";

// Jika level adalah pelanggan, pastikan orderan sesuai dengan pelanggan yang login
if ($level_user == 5) {
  $query_sql .= " AND tb_order.pelanggan = '$username_pelanggan_session'";
}

$query = mysqli_query($conn, $query_sql);

      $result = array();

      while ($record = mysqli_fetch_array($query)) {
  $result[] = $record;
}

// ✅ Tambahkan ini:
$select_menu = mysqli_query($conn, "SELECT * FROM tb_daftar_menu");

$menu_options = array();
if ($select_menu) {
  while ($value = mysqli_fetch_assoc($select_menu)) {
    $menu_options[] = $value;
  }
} else {
  echo "Query menu gagal: " . mysqli_error($conn);
}

?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="col-lg-9 mt-2">
  <div class="card">
    <div class="card-header">
      Halaman Detail Item
    </div>
    <div class="card-body">
      <a href="report" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i></a>
      <div class="row">
        <div class="col-lg-3">
          <div class="form-floating mb-3">
            <input disabled type="text" class="form-control" id="kodeorder" value="<?php echo $kode; ?>">
            <label for="uploadfoto">Kode Order</label>
          </div>
        </div>
        <div class="col-lg-2">
          <div class="form-floating mb-3">
            <input disabled type="text" class="form-control" id="meja" value="<?php echo $meja; ?>">
            <label for="uploadfoto">Meja</label>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="form-floating mb-3">
            <input disabled type="text" class="form-control" id="pelanggan" value="<?php echo $pelanggan; ?>">
            <label for="uploadfoto">Pelanggan</label>
          </div>
        </div>
      </div>

      <?php
      if (empty($result)) {
        echo "Data menu makanan atau minuman tidak ada";
      } else {
        foreach ($result as $row) {
      ?>

        <?php
        }
        ?>

        <?php

        ?>
        <div class=" table-responsive">
          <table class="table table-hover">
            <thead>
              <tr class="text-nowrap">
                <th scope="col">Menu</th>
                <th scope="col">Harga</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Status</th>
                <th scope="col">Catatan</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total = 0;
              foreach ($result as $row) {
              ?>
                <tr>
                  <td> <?php echo $row['nama_menu'] ?></td>
                  <td> <?php echo number_format($row['harga'], 0, ',', '.') ?></td>
                  <td> <?php echo $row['jumlah'] ?> </td>
                  <td> <?php
                        if ($row['status'] == 1) {
                          echo "<span class='badge text-bg-warning'>Masuk ke dapur</span>";
                        } elseif ($row['status'] == 2) {
                          echo "<span class='badge text-bg-primary'>Siap Saji</span>";
                        }
                        ?> </td>
                  <td> <?php echo $row['catatan'] ?> </td>
                  <td>
                    <?php echo number_format($row['harganya'], 0, ',', '.') ?>
                  </td>
                </tr>
              <?php
                $total += $row['harganya'];
              }
              ?>
              <tr>
                <td colspan="5" class="fw-bold">
                  Total harga
                </td>
                <td class="fw-bold">
                  <?php
                  echo number_format($total, 0, ',', '.')
                  ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php } ?>

    </div>
  </div>
</div>