<?php
include "proses/connect.php";
date_default_timezone_set('Asia/Jakarta');

$level_user = $_SESSION['level_vienna_coffee'];
$username_pelanggan = $_SESSION['username_vienna_coffee'];

$query_sql = "SELECT tb_order.*,tb_bayar.*,nama, SUM(harga*jumlah) AS harganya FROM tb_order
  LEFT JOIN tb_user ON tb_user.id = tb_order.pelayan
  LEFT JOIN tb_list_order ON tb_list_order.kode_order = tb_order.id_order
  LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
  LEFT JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order";

if ($level_user == 5) {
  $query_sql .= " WHERE tb_order.pelanggan = '$username_pelanggan'";
}

$query_sql .= " GROUP BY id_order ORDER BY waktu_order DESC";
$query = mysqli_query($conn, $query_sql);

while ($record = mysqli_fetch_array($query)) {
  $result[] = $record;
}
?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="col-lg-9 mt-2">
  <div class="card">
    <div class="card-header">Halaman Order</div>
    <div class="card-body">
      <div class="row">
        <div class="col d-flex justify-content-end">
          <?php if ($level_user == 5) { ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahUser">Buat Order</button>
          <?php } ?>
        </div>
      </div>

      <?php if ($level_user == 5) { ?>
        <div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Tambah Orderan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form class="needs-validation" novalidate action="proses/proses_input_order.php" method="POST">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="kode_order" value="<?php echo date('ymdHi') . rand(100, 999) ?>" readonly>
                        <label>Kode Order</label>
                      </div>
                    </div>
                    <div class="col-lg-2">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="meja" required>
                        <label>Meja</label>
                      </div>
                    </div>
                    <div class="col-lg-7">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="pelanggan" required value="<?php echo $username_pelanggan ?>" readonly>
                        <label>Nama Pelanggan</label>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="input_order_validate" value="12345">Buat Order</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php if (empty($result)) {
        echo "Data order tidak ada";
      } else {
        foreach ($result as $row) {
          if ($level_user == 1 || ($level_user == 5 && $row['pelanggan'] == $username_pelanggan)) {
      ?>
            <div class="modal fade" id="ModalEdit<?php echo $row['id_order'] ?>" tabindex="-1">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Data Orderan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <form class="needs-validation" novalidate action="proses/proses_edit_order.php" method="POST">
                      <div class="row">
                        <div class="col-lg-3">
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="kode_order" value="<?php echo $row['id_order'] ?>" readonly>
                            <label>Kode Order</label>
                          </div>
                        </div>
                        <div class="col-lg-2">
                          <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="meja" value="<?php echo $row['meja'] ?>" required>
                            <label>Meja</label>
                          </div>
                        </div>
                        <div class="col-lg-7">
                          <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="pelanggan" value="<?php echo $row['pelanggan'] ?>" required>
                            <label>Nama Pelanggan</label>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="edit_order_validate" value="1">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="ModalDelete<?php echo $row['id_order'] ?>" tabindex="-1">
              <div class="modal-dialog modal-md modal-fullscreen-md-down">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus Orderan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form class="needs-validation" novalidate action="proses/proses_delete_order.php" method="POST">
                    <input type="hidden" value="<?php echo $row['id_order'] ?>" name="kode_order">
                    <div class="col-lg-12 p-3">
                      Apakah anda ingin menghapus order atas nama <b><?php echo $row['pelanggan'] ?></b> dengan kode order <b><?php echo $row['id_order'] ?></b>?
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button type="submit" class="btn btn-danger" name="delete_order_validate" value="12345">Hapus</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
        <?php
          }
        }
        ?>
        <div class="table-responsive mt-2">
          <table class="table table-hover" id="example">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Order</th>
                <th>Username</th>
                <th>Meja</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Waktu Order</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              foreach ($result as $row) { ?>
                <tr>
                  <th><?php echo $no++ ?></th>
                  <td><?php echo $row['id_order'] ?></td>
                  <td><?php echo $row['nama'] ?></td>
                  <td><?php echo $row['meja'] ?></td>
                  <td><?php echo number_format((int)$row['harganya'], 0, ',', '.') ?></td>
                  <td>
                    <?php if (!empty($row['id_bayar'])) {
                      echo "<span class='badge text-bg-success'>Sudah Dibayar</span>";
                    } else {
                      echo "<span class='badge text-bg-warning'>Belum Dibayar</span>";
                    } ?>
                  </td>
                  <td><?php echo $row['waktu_order'] ?></td>
                  <td>
                    <div class="d-flex">
                      <a class="btn btn-primary btn-sm me-1" href="./?x=orderitem&order=<?php echo $row['id_order'] ?>&meja=<?php echo $row['meja'] ?>&pelanggan=<?php echo $row['pelanggan'] ?>"><i class="bi bi-eye"></i></a>
                      <?php if ($level_user == 1 || ($level_user == 5 && $row['pelanggan'] == $username_pelanggan)) { ?>
                        <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-warning btn-sm me-1"; ?>" data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo $row['id_order'] ?>"><i class="bi bi-pencil-square"></i></button>
                        <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-danger btn-sm me-1"; ?>" data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo $row['id_order'] ?>"><i class="bi bi-trash"></i></button>
                      <?php } ?>
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