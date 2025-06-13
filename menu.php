<?php
include "proses/connect.php";
$query = mysqli_query($conn, "SELECT * FROM tb_daftar_menu
  LEFT JOIN tb_kategori_menu ON tb_kategori_menu.id_kat_menu = tb_daftar_menu.kategori");
while ($record = mysqli_fetch_array($query)) {
  $result[] = $record;
}

$select_kat_menu = mysqli_query($conn, "SELECT id_kat_menu,kategori_menu FROM tb_kategori_menu");
$kat_menu_options = array();
while ($row = mysqli_fetch_assoc($select_kat_menu)) {
  $kat_menu_options[] = $row;
}
?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="col-lg-9 mt-2">
  <div class="card">
    <div class="card-header">
      Halaman Menu
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col d-flex justify-content-end">
          <?php if ($_SESSION['level_vienna_coffee'] == 1) { // Hanya Admin yang bisa menambah menu
          ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahUser" title="Tambahkan User Baru">Tambah Menu</button>
          <?php } ?>
          <?php if ($_SESSION['level_vienna_coffee'] == 5) { // Hanya Pelanggan yang bisa melihat tombol "Order Disini"
          ?>
            <a href="order" class="btn btn-success me-1"><i class="bi bi-cart-plus"></i> Order Disini</a>
          <?php } ?>
        </div>
      </div>

      <?php if ($_SESSION['level_vienna_coffee'] == 1) { ?>
        <div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Tambah Menu Makanan dan Minuman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form class="needs-validation" novalidate action="proses/proses_input_menu.php" method="POST" enctype="multipart/form-data" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-lg-6 me-1">
                      <div class="input-group mb-3">
                        <input type="file" class="form-control py-3" id="uploadfoto" placeholder="Masukkan nama anda" name="foto" required>
                        <label class="input-group-text" for="uploadfoto">Upload Foto Menu</label>
                        <div class="invalid-feedback">
                          Masukan File Foto Menu
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="TUusername" placeholder="Nama Menu" name="nama_menu" required>
                        <label for="TUusername">Nama Menu</label>
                        <div class="invalid-feedback">Masukan nama menu.</div>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="TUketerangan" placeholder="Masukkan keterangan" name="keterangan">
                        <label for="TUketerangan">Keterangan</label>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-lg-4">
                      <div class="form-floating">
                        <select class="form-select" id="TUlevel" name="kat_menu" required>
                          <option selected hidden value="">Pilih Kategori Menu</option>
                          <?php
                          foreach ($kat_menu_options as $value) {
                            echo "<option value='" . $value['id_kat_menu'] . "'>" . $value['kategori_menu'] . "</option>";
                          }
                          ?>
                        </select>
                        <label for="TUlevel">Kategori Menu</label>
                        <div class="invalid-feedback">Pilih kategori makanan atau minuman.</div>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-floating">
                        <input type="number" class="form-control" id="TUharga" placeholder="Harga" name="harga" required>
                        <label for="TUharga">Harga</label>
                        <div class="invalid-feedback">Masukan harga menu.</div>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-floating">
                        <input type="number" class="form-control" id="TUstok" placeholder="Stok" name="stok" required>
                        <label for="TUstok">Stok</label>
                        <div class="invalid-feedback">Masukan stok menu.</div>
                      </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="input_menu_validate" value="12345">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php } // Akhir dari if Admin
      ?>
      <?php
      if (empty($result)) {
        echo "Data menu makanan atau minuman tidak ada";
      } else {
        foreach ($result as $row) {
      ?>
          <div class="modal fade" id="ModalView<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Detail menu</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form class="needs-validation" novalidate action="proses/proses_input_menu.php" method="POST" enctype="multipart/form-data" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-lg-12 mb-3">
                        <div class="form-floating">
                          <input disabled type="text" class="form-control" id="TUusername" name="nama_menu" value="<?php echo $row['nama_menu'] ?>">
                          <label for="TUusername">Nama Menu</label>
                          <div class="invalid-feedback">Masukan nama menu.</div>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-12">
                        <div class="form-floating">
                          <input disabled type="text" class="form-control" id="TUketerangan" value="<?php echo $row['keterangan'] ?>">
                          <label for="TUketerangan">Keterangan</label>
                        </div>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-lg-4">
                        <div class="form-floating">
                          <select disabled class="form-select" id="TUlevel" name="kat_menu">
                            <option selected hidden value="">Pilih Kategori Menu</option>
                            <?php
                            foreach ($kat_menu_options as $value) {
                              if ($row['kategori'] == $value['id_kat_menu']) {
                                echo "<option selected value='" . $value['id_kat_menu'] . "'>" . $value['kategori_menu'] . "</option>";
                              } else {
                                echo "<option value='" . $value['id_kat_menu'] . "'>" . $value['kategori_menu'] . "</option>";
                              }
                            }
                            ?>
                          </select>
                          <label for="TUlevel">Kategori Menu</label>
                          <div class="invalid-feedback">Pilih kategori makanan atau minuman.</div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-floating">
                          <input disabled type="number" class="form-control" id="TUharga" value="<?php echo $row['harga'] ?>">
                          <label for="TUharga">Harga</label>
                          <div class="invalid-feedback">Masukan harga menu.</div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-floating">
                          <input disabled type="number" class="form-control" id="TUstok" value="<?php echo $row['stok'] ?>">
                          <label for="TUstok">Stok</label>
                          <div class="invalid-feedback">Masukan stok menu.</div>
                        </div>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php if ($_SESSION['level_vienna_coffee'] == 1) { ?>
            <div class="modal fade" id="ModalEdit<?php echo $row['id'] ?>"" tabindex=" -1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Tambah Menu Makanan dan Minuman</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form class="needs-validation" novalidate action="proses/proses_edit_menu.php" method="POST" enctype="multipart/form-data" enctype="multipart/form-data">
                      <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                      <div class="row">
                        <div class="col-lg-6 me-1">
                          <div class="input-group mb-3">
                            <input type="file" class="form-control py-3" id="uploadfotoo" placeholder="Masukkan nama anda" name="foto">
                            <label class="input-group-text" for="uploadfotoo">Upload Foto Menu</label>
                            <div class="invalid-feedback">
                              Masukan File Foto Menu
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="TUusername" placeholder="Nama Menu" name="nama_menu" required value="<?php echo $row['nama_menu'] ?>">
                            <label>Nama Menu</label>
                            <div class="invalid-feedback">Masukan nama menu.</div>
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-12">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="TUketerangan" placeholder="Masukkan keterangan" name="keterangan" value="<?php echo $row['keterangan'] ?>">
                            <label>Keterangan</label>
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-lg-4">
                          <div class="form-floating">
                            <select class="form-select" id="TUlevel" name="kat_menu">
                              <option selected hidden value="">Pilih Kategori Menu</option>
                              <?php
                              foreach ($kat_menu_options as $value) {
                                if ($row['kategori'] == $value['id_kat_menu']) {
                                  echo "<option selected value='" . $value['id_kat_menu'] . "'>" . $value['kategori_menu'] . "</option>";
                                } else {
                                  echo "<option value='" . $value['id_kat_menu'] . "'>" . $value['kategori_menu'] . "</option>";
                                }
                              }
                              ?>
                            </select>
                            <label>Kategori Menu</label>
                            <div class="invalid-feedback">Pilih kategori makanan atau minuman.</div>
                          </div>
                        </div>

                        <div class="col-lg-4">
                          <div class="form-floating">
                            <input type="number" class="form-control" id="TUharga" placeholder="Harga" name="harga" required value="<?php echo $row['harga'] ?>">
                            <label>Harga</label>
                            <div class="invalid-feedback">Masukan harga menu.</div>
                          </div>
                        </div>

                        <div class="col-lg-4">
                          <div class="form-floating">
                            <input type="number" class="form-control" id="TUstok" placeholder="Stok" name="stok" required value="<?php echo $row['stok'] ?>">
                            <label>Stok</label>
                            <div class="invalid-feedback">Masukan stok menu.</div>
                          </div>
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="input_menu_validate" value="12345">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php } // Akhir dari if Admin
          ?>
          <?php if ($_SESSION['level_vienna_coffee'] == 1) { ?>
            <div class="modal fade" id="ModalDelete<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="#ModalViewLabel" aria-hidden="true">
              <div class="modal-dialog modal-md modal-fullscreen-md-down">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalViewLabel">Hapus Menu</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <form class="needs-validation" novalidate action="proses/proses_delete_menu.php" method="POST">
                    <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                    <input type="hidden" value="<?php echo $row['foto'] ?>" name="foto">
                    <div class="col-lg-12">
                      Apakah anda ingin menghapus menu <b> <?php echo $row['nama_menu'] ?> </b>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button type="submit" class="btn btn-danger" name="input_delete_validate" value="12345">Hapus</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php } // Akhir dari if Admin
          ?>
        <?php
        }
        ?>


        <div class=" table-responsive mt-2">
          <table class="table table-hover" id="example">
            <thead>
              <tr class="text-nowrap">
                <th scope="col">No</th>
                <th scope="col">Foto Menu</th>
                <th scope="col">Nama Menu</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Jenis Menu</th>
                <th scope="col">Kategori</th>
                <th scope="col">Harga</th>
                <th scope="col">Stok</th>
                <?php if ($_SESSION['level_vienna_coffee'] == 1) { // Hanya Admin yang melihat kolom Aksi
                ?>
                  <th scope="col">Aksi</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              foreach ($result as $row) {
              ?>
                <tr>
                  <th scope="row"><?php echo $no++ ?></th>
                  <td>
                    <div style="width: 90px">
                      <img src="assets/img/<?php echo $row['foto'] ?>" class="img-thumbnail" alt="...">
                    </div>
                  </td>
                  <td> <?php echo $row['nama_menu'] ?></td>
                  <td> <?php echo $row['keterangan'] ?></td>
                  <td> <?php echo ($row['jenis_menu'] == 1) ? "Makanan" : "Minuman" ?></td>
                  <td> <?php echo $row['kategori_menu'] ?></td>
                  <td> <?php echo $row['harga'] ?></td>
                  <td> <?php echo $row['stok'] ?></td>
                  <?php if ($_SESSION['level_vienna_coffee'] == 1) { // Hanya Admin yang melihat tombol aksi
                  ?>
                    <td>
                      <div class="d-flex">
                        <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalView<?php echo $row['id'] ?>" title="Detail Data User"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo $row['id'] ?>" title="Edit Data User"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo $row['id'] ?>" title="Hapus Data User"><i class="bi bi-trash"></i></button>
                      </div>
                    </td>
                  <?php } ?>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php } ?>




    </div>
  </div>
</div>