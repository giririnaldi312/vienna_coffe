<?php
include "proses/connect.php";

// Query untuk list order
$query = mysqli_query($conn, "SELECT * FROM tb_list_order
  LEFT JOIN tb_order ON tb_order.id_order = tb_list_order.kode_order
  LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
  LEFT JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
  ORDER BY waktu_order ASC
");


while ($record = mysqli_fetch_array($query)) {
  $result[] = $record;
}

// Tambahkan ini:
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
      Halaman Dapur
    </div>
    <div class="card-body">

      <?php
      if (empty($result)) {
        echo "Data menu makanan atau minuman tidak ada";
      } else {
        foreach ($result as $row) {
      ?>


          <div class="modal fade" id="terima<?php echo $row['id_list_order'] ?>"" tabindex=" -1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Terima Order</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form class="needs-validation" novalidate action="proses/proses_terima_orderitem.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id_list_order'] ?>">
                    <div class="row">
                      <div class="col-lg-8">
                        <div class="form-floating mb-3">
                          <select disabled class="form-select" name="menu" id="">
                            <option selected hidden value="">Pilih Menu</option>
                            <?php
                            foreach ($menu_options as $value) {
                              if ($row['menu'] == $value['id']) {
                                echo "<option selected value='" . $value['id'] . "'>" . $value['nama_menu'] . "</option>";
                              } else {
                                echo "<option value='" . $value['id'] . "'>" . $value['nama_menu'] . "</option>";
                              }
                            }
                            ?>
                          </select>
                          <label for=menu">Menu Makanan/Minuman</label>
                          <div class="invalid-feedback">
                            Pilih Menu
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-floating">
                          <input disabled type="Number" class="form-control" id="TUusername" placeholder="Jumlah Porsi" name="jumlah" required value="<?php echo $row['jumlah'] ?>">
                          <label for="TUusername">Jumlah Porsi</label>
                          <div class="invalid-feedback">Masukan Jumlah Porsi.</div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-12">
                        <div class="form-floating">
                          <input type="text" class="form-control" id="TUketerangan" placeholder="Masukkan catatan" name="catatan" value="<?php echo $row['catatan'] ?>">
                          <label for="TUketerangan">catatan</label>
                        </div>
                      </div>
                    </div>


                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button type="submit" class="btn btn-primary" name="terima_orderitem_validate" value="12345">Terima</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="siapsaji<?php echo $row['id_list_order'] ?>"" tabindex=" -1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Siap saji</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form class="needs-validation" novalidate action="proses/proses_siapsaji_orderitem.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id_list_order'] ?>">
                    <div class="row">
                      <div class="col-lg-8">
                        <div class="form-floating mb-3">
                          <select disabled class="form-select" name="menu" id="">
                            <option selected hidden value="">Pilih Menu</option>
                            <?php
                            foreach ($menu_options as $value) {
                              if ($row['menu'] == $value['id']) {
                                echo "<option selected value='" . $value['id'] . "'>" . $value['nama_menu'] . "</option>";
                              } else {
                                echo "<option value='" . $value['id'] . "'>" . $value['nama_menu'] . "</option>";
                              }
                            }
                            ?>
                          </select>
                          <label for=menu">Menu Makanan/Minuman</label>
                          <div class="invalid-feedback">
                            Pilih Menu
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-floating">
                          <input disabled type="Number" class="form-control" id="TUusername" placeholder="Jumlah Porsi" name="jumlah" required value="<?php echo $row['jumlah'] ?>">
                          <label for="TUusername">Jumlah Porsi</label>
                          <div class="invalid-feedback">Masukan Jumlah Porsi.</div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-12">
                        <div class="form-floating">
                          <input type="text" class="form-control" id="TUketerangan" placeholder="Masukkan catatan" name="catatan" value="<?php echo $row['catatan'] ?>" readonly>
                          <label for="TUketerangan">catatan</label>
                        </div>
                      </div>
                    </div>


                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button type="submit" class="btn btn-primary" name="siapsaji_orderitem_validate" value="12345">Siap Saji</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>

        <?php

        ?>
        <div class=" table-responsive mt-2">
          <table class="table table-hover" id="example">
            <thead>
              <tr class="text-nowrap">
                <th scope="col">No</th>
                <th scope="col">Kode Order</th>
                <th scope="col">Waktu Order</th>
                <th scope="col">Menu</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Catatan</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($result as $row) {
                // Di dapur, hanya tampilkan item yang belum siap saji
                if ($row['status'] != 2) {
              ?>
                  <tr>
                    <td> <?php echo $no++ ?></td>
                    <td> <?php echo $row['kode_order'] ?></td>
                    <td> <?php echo $row['waktu_order'] ?></td>
                    <td> <?php echo $row['nama_menu'] ?></td>
                    <td> <?php echo $row['jumlah'] ?> </td>
                    <td> <?php echo $row['catatan'] ?> </td>
                    <td> <?php
                          if ($row['status'] == 0) { // Menampilkan status 0 (Belum Diterima)
                            echo "<span class='badge text-bg-secondary'>Belum Diterima</span>";
                          } elseif ($row['status'] == 1) {
                            echo "<span class='badge text-bg-warning'>Masuk ke dapur</span>";
                          }
                          // Status 2 (Siap Saji) tidak ditampilkan di sini karena filter if ($row['status'] != 2) di atas
                          ?> </td>
                    <td>
                      <div class="d-flex">
                        <button class="<?php echo ($row['status'] == 0) ? "btn btn-primary btn-sm me-1" : "btn btn-secondary btn-sm me-1 disabled"; ?>" data-bs-toggle="modal" data-bs-target="#terima<?php echo $row['id_list_order'] ?>" title="Terima Order">Terima</button>
                        <button class="<?php echo ($row['status'] == 1) ? "btn btn-success btn-sm me-1 text-nowrap" : "btn btn-secondary btn-sm me-1 disabled text-nowrap"; ?>" data-bs-toggle="modal" data-bs-target="#siapsaji<?php echo $row['id_list_order'] ?>" title="Siap Di sajikan">Siap Saji</button>
                      </div>
                    </td>
                  </tr>
              <?php
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      <?php } ?>

    </div>
  </div>
</div>