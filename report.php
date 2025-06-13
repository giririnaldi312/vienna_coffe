<?php
include "proses/connect.php";
date_default_timezone_set('Asia/Jakarta'); // Menggunakan set() untuk mengatur zona waktu

// Dapatkan username pelanggan dari session
$username_pelanggan = $_SESSION['username_vienna_coffee'];
$level_user = $_SESSION['level_vienna_coffee'];

// Kueri untuk laporan
$query_sql = "SELECT
                o.id_order,
                o.waktu_order,
                o.meja,
                u.nama,
                b.waktu_bayar,
                SUM(dm.harga * lo.jumlah) AS harganya,
                -- Menentukan status proses dapur secara keseluruhan untuk order
                CASE
                    WHEN SUM(CASE WHEN lo.status = 0 THEN 1 ELSE 0 END) > 0 THEN 'Menunggu Diterima'
                    WHEN SUM(CASE WHEN lo.status = 1 THEN 1 ELSE 0 END) > 0 THEN 'Diproses di Dapur'
                    WHEN SUM(CASE WHEN lo.status = 2 THEN 1 ELSE 0 END) = COUNT(lo.id_list_order) THEN 'Siap Saji'
                    ELSE 'Status Tidak Diketahui' -- Fallback jika ada status yang tidak terdefinisi
                END AS kitchen_status_summary
              FROM
                tb_order AS o
              LEFT JOIN
                tb_user AS u ON u.id = o.pelayan
              LEFT JOIN
                tb_list_order AS lo ON lo.kode_order = o.id_order
              LEFT JOIN
                tb_daftar_menu AS dm ON dm.id = lo.menu
              JOIN
                tb_bayar AS b ON b.id_bayar = o.id_order";

// Tambahkan kondisi WHERE jika user adalah pelanggan
if ($level_user == 5) { // Jika level adalah pelanggan
  $query_sql .= " WHERE o.pelanggan = '$username_pelanggan'";
}

$query_sql .= " GROUP BY o.id_order, o.waktu_order, o.meja, u.nama, b.waktu_bayar ORDER BY o.waktu_order DESC";

$query = mysqli_query($conn, $query_sql);

$result = array();
if ($query) {
  while ($record = mysqli_fetch_array($query)) {
    $result[] = $record;
  }
} else {
  // Tampilkan pesan error MySQL jika kueri gagal
  echo "Query laporan gagal: " . mysqli_error($conn);
}

?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="col-lg-9 mt-2">
  <div class="card">
    <div class="card-header">
      Halaman Laporan
    </div>

    <div class="card-body">
      <?php
      if (empty($result)) {
        echo "Data laporan tidak ada";
      } else {
      ?>
        <div class="table-responsive mt-2">
          <table class="table table-hover" id="example">
            <thead>
              <tr class="text-nowrap">
                <th scope="col">No</th>
                <th scope="col">Kode Order</th>
                <th scope="col">Waktu Order</th>
                <th scope="col">Waktu Bayar</th>
                <th scope="col">Pelanggan</th>
                <th scope="col">Meja</th>
                <th scope="col">Total Harga</th>
                <th scope="col">Status Proses Dapur</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($result as $row) {
              ?>
                <tr>
                  <th scope="row"><?php echo $no++ ?></th>
                  <td> <?php echo $row['id_order'] ?></td>
                  <td> <?php echo $row['waktu_order'] ?></td>
                  <td> <?php echo $row['waktu_bayar'] ?></td>
                  <td> <?php echo $row['nama'] ?></td>
                  <td> <?php echo $row['meja'] ?> </td>
                  <td> <?php echo number_format((int)$row['harganya'], 0, ',', '.')  ?> </td>
                  <td>
                    <?php
                    // Menampilkan badge berdasarkan status proses dapur
                    $status_badge_class = '';
                    switch ($row['kitchen_status_summary']) {
                      case 'Menunggu Diterima':
                        $status_badge_class = 'text-bg-secondary';
                        break;
                      case 'Diproses di Dapur':
                        $status_badge_class = 'text-bg-warning';
                        break;
                      case 'Siap Saji':
                        $status_badge_class = 'text-bg-primary';
                        break;
                      default:
                        $status_badge_class = 'text-bg-info'; // Untuk status tidak diketahui
                        break;
                    }
                    echo "<span class='badge " . $status_badge_class . "'>" . $row['kitchen_status_summary'] . "</span>";
                    ?>
                  </td>
                  <td>
                    <div class="d-flex">
                      <a class="btn btn-primary btn-sm me-1" href="./?x=viewitem&order=<?php echo $row['id_order'] . "&meja=" . $row['meja'] . "&pelanggan=" . $row['nama'] ?>"><i class="bi bi-eye"></i></a>
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php } ?>
    </div>
  </div>
</div>