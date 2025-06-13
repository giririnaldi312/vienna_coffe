<?php
include "proses/connect.php";

// Pastikan session sudah dimulai untuk mengakses level_vienna_coffee
if (session_id() == '') {
  session_start();
}


// Mengamankan dan mengambil nilai dari $_GET
// Gunakan isset() untuk memeriksa keberadaan variabel sebelum mengaksesnya
$kode = isset($_GET['order']) ? mysqli_real_escape_string($conn, $_GET['order']) : '';
$meja = isset($_GET['meja']) ? mysqli_real_escape_string($conn, $_GET['meja']) : '';
$pelanggan = isset($_GET['pelanggan']) ? mysqli_real_escape_string($conn, $_GET['pelanggan']) : '';

// Mengambil level dan username dari session dengan kompatibilitas PHP lama
$level_user = isset($_SESSION['level_vienna_coffee']) ? $_SESSION['level_vienna_coffee'] : null;
$username_pelanggan_session = isset($_SESSION['username_vienna_coffee']) ? $_SESSION['username_vienna_coffee'] : null;

// Membangun kueri SQL secara bertahap untuk kejelasan dan keamanan
$query_sql = "SELECT *, SUM(harga*jumlah) AS harganya, tb_order.waktu_order FROM tb_list_order
  LEFT JOIN tb_order ON tb_order.id_order = tb_list_order.kode_order
  LEFT JOIN tb_daftar_menu ON tb_daftar_menu.id = tb_list_order.menu
  LEFT JOIN tb_bayar ON tb_bayar.id_bayar = tb_order.id_order
  GROUP BY id_list_order
  HAVING tb_list_order.kode_order = '$kode'"; // Variabel $kode sudah di-escape dan diapit tanda kutip tunggal

// Menambahkan filter berdasarkan pelanggan jika user adalah pelanggan
if ($level_user == 5 && $username_pelanggan_session) {
  $query_sql .= " AND tb_order.pelanggan = '$username_pelanggan_session'";
}

// Menjalankan kueri
$query = mysqli_query($conn, $query_sql);
$result = array();
if ($query) {
  while ($record = mysqli_fetch_array($query)) {
    $result[] = $record;
  }
} else {
  // Tampilkan pesan error MySQL jika kueri gagal
  echo "Query order gagal: " . mysqli_error($conn);
}

// Query untuk daftar menu
$select_menu = mysqli_query($conn, "SELECT * FROM tb_daftar_menu");
$menu_options = array();
if ($select_menu) {
  while ($value = mysqli_fetch_assoc($select_menu)) {
    $menu_options[] = $value;
  }
} else {
  echo "Query menu gagal: " . mysqli_error($conn);
}

// Mengambil status pembayaran untuk order ini
$status_bayar_query = mysqli_query($conn, "SELECT id_bayar FROM tb_bayar WHERE id_bayar = '$kode'");
$status_bayar = mysqli_fetch_assoc($status_bayar_query);
$is_paid = !empty($status_bayar);

// Cek apakah semua item sudah siap saji
$all_items_ready = true;
if (!empty($result)) {
  foreach ($result as $item) {
    if ($item['status'] != 2) { // Jika ada item yang statusnya belum 'Siap Saji'
      $all_items_ready = false;
      break;
    }
  }
} else {
  $all_items_ready = false; // Jika tidak ada item sama sekali, tidak bisa dibayar
}

?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="col-lg-9 mt-2">
  <div class="card">
    <div class="card-header">
      Halaman Order Item
    </div>
    <div class="card-body">
      <a href="order" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i></a>
      <div class="row">
        <div class="col-lg-3">
          <div class="form-floating mb-3">
            <input disabled type="text" class="form-control" id="kodeorder" value="<?php echo htmlspecialchars($kode); ?>">
            <label for="uploadfoto">Kode Order</label>
          </div>
        </div>
        <div class="col-lg-2">
          <div class="form-floating mb-3">
            <input disabled type="text" class="form-control" id="meja" value="<?php echo htmlspecialchars($meja); ?>">
            <label for="uploadfoto">Meja</label>
          </div>
        </div>
        <div class="col-lg-3">
          <div class="form-floating mb-3">
            <input disabled type="text" class="form-control" id="pelanggan" value="<?php echo htmlspecialchars($pelanggan); ?>">
            <label for="uploadfoto">Pelanggan</label>
          </div>
        </div>
      </div>

      <?php if (!$is_paid && ($level_user == 1 || $level_user == 5)) { ?>
        <div class="modal fade" id="tambahitem" tabindex="-1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Tambah Menu Makanan dan Minuman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form class="needs-validation" novalidate action="proses/proses_input_orderitem.php" method="POST">
                  <input type="hidden" name="kode_order" value="<?php echo htmlspecialchars($kode) ?>">
                  <input type="hidden" name="meja" value="<?php echo htmlspecialchars($meja) ?>">
                  <input type="hidden" name="pelanggan" value="<?php echo htmlspecialchars($pelanggan) ?>">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="form-floating mb-3">
                        <select class="form-select" name="menu" id="">
                          <option selected hidden value="">Pilih Menu</option>
                          <?php
                          foreach ($menu_options as $value) {
                            echo "<option value='" . htmlspecialchars($value['id']) . "'>" . htmlspecialchars($value['nama_menu']) . "</option>";
                          }
                          ?>
                        </select>
                        <label for="menu">Menu Makanan/Minuman</label>
                        <div class="invalid-feedback">
                          Pilih Menu
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-floating">
                        <input type="Number" class="form-control" id="TUusername" placeholder="Jumlah Porsi" name="jumlah" required>
                        <label for="TUusername">Jumlah Porsi</label>
                        <div class="invalid-feedback">Masukan Jumlah Porsi.</div>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="TUketerangan" placeholder="Masukkan catatan" name="catatan">
                        <label for="TUketerangan">catatan</label>
                      </div>
                    </div>
                  </div>


                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="input_orderitem_validate" value="12345">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php } // Akhir dari if Add Item
      ?>
      <?php
      if (empty($result)) {
        echo "Data menu makanan atau minuman tidak ada";
      } else {
        foreach ($result as $row) {
      ?>


          <?php if (!$is_paid && ($level_user == 1 || $level_user == 5)) { ?>
            <div class="modal fade" id="ModalEdit<?php echo htmlspecialchars($row['id_list_order']) ?>" tabindex="-1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-fullscreen-md-down">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Edit Menu Makanan dan Minuman</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form class="needs-validation" novalidate action="proses/proses_edit_orderitem.php" method="POST">
                      <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_list_order']) ?>">
                      <input type="hidden" name="kode_order" value="<?php echo htmlspecialchars($kode) ?>">
                      <input type="hidden" name="meja" value="<?php echo htmlspecialchars($meja) ?>">
                      <input type="hidden" name="pelanggan" value="<?php echo htmlspecialchars($pelanggan) ?>">
                      <div class="row">
                        <div class="col-lg-8">
                          <div class="form-floating mb-3">
                            <select class="form-select" name="menu" id="">
                              <option selected hidden value="">Pilih Menu</option>
                              <?php
                              foreach ($menu_options as $value) {
                                if ($row['menu'] == $value['id']) {
                                  echo "<option selected value='" . htmlspecialchars($value['id']) . "'>" . htmlspecialchars($value['nama_menu']) . "</option>";
                                } else {
                                  echo "<option value='" . htmlspecialchars($value['id']) . "'>" . htmlspecialchars($value['nama_menu']) . "</option>";
                                }
                              }
                              ?>
                            </select>
                            <label for="menu">Menu Makanan/Minuman</label>
                            <div class="invalid-feedback">
                              Pilih Menu
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-floating">
                            <input type="Number" class="form-control" id="TUusername" placeholder="Jumlah Porsi" name="jumlah" required value="<?php echo htmlspecialchars($row['jumlah']) ?>">
                            <label for="TUusername">Jumlah Porsi</label>
                            <div class="invalid-feedback">Masukan Jumlah Porsi.</div>
                          </div>
                        </div>
                      </div>

                      <div class="row mb-3">
                        <div class="col-12">
                          <div class="form-floating">
                            <input type="text" class="form-control" id="TUketerangan" placeholder="Masukkan catatan" name="catatan" value="<?php echo htmlspecialchars($row['catatan']) ?>">
                            <label for="TUketerangan">catatan</label>
                          </div>
                        </div>
                      </div>


                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="edit_orderitem_validate" value="12345">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php } // Akhir dari if Edit
          ?>
          <?php if (!$is_paid && ($level_user == 1 || $level_user == 5)) { ?>
            <div class="modal fade" id="ModalDelete<?php echo htmlspecialchars($row['id_list_order']) ?>" tabindex="-1" aria-labelledby="#ModalViewLabel" aria-hidden="true">
              <div class="modal-dialog modal-md modal-fullscreen-md-down">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalViewLabel">Hapus Menu</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <form class="needs-validation" novalidate action="proses/proses_delete_orderitem.php" method="POST">
                    <input type="hidden" value="<?php echo htmlspecialchars($row['id_list_order']) ?>" name="id">
                    <input type="hidden" name="kode_order" value="<?php echo htmlspecialchars($kode) ?>">
                    <input type="hidden" name="meja" value="<?php echo htmlspecialchars($meja) ?>">
                    <input type="hidden" name="pelanggan" value="<?php echo htmlspecialchars($pelanggan) ?>">
                    <div class="col-lg-12">Apakah anda ingin menghapus menu <b> <?php echo htmlspecialchars($row['nama_menu']) ?> </b>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      <button type="submit" class="btn btn-danger" name="delete_orderitem_validate" value="12345">Hapus</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php } // Akhir dari if Delete
          ?>
        <?php
        }
        ?>
        <?php if (!$is_paid && ($level_user == 1 || $level_user == 5)) { ?>
          <div class="modal fade" id="bayar" tabindex="-1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Pembayaran</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                            <td> <?php echo htmlspecialchars($row['nama_menu']) ?></td>
                            <td> <?php echo number_format($row['harga'], 0, ',', '.') ?></td>
                            <td> <?php echo htmlspecialchars($row['jumlah']) ?> </td>
                            <td> <?php
                                  if ($row['status'] == 1) {
                                    echo "<span class='badge text-bg-warning'>Masuk ke dapur</span>";
                                  } elseif ($row['status'] == 2) {
                                    echo "<span class='badge text-bg-primary'>Siap Saji</span>";
                                  }
                                  ?> </td>
                            <td> <?php echo htmlspecialchars($row['catatan']) ?> </td>
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
                  <span class="text-danger fs-5 fw-semibold">Apakah anda yakin ingin melakukan Pembayaran?*</span>
                  <form class="needs-validation" novalidate action="proses/proses_bayar.php" method="POST">
                    <input type="hidden" name="kode_order" value="<?php echo htmlspecialchars($kode) ?>">
                    <input type="hidden" name="meja" value="<?php echo htmlspecialchars($meja) ?>">
                    <input type="hidden" name="pelanggan" value="<?php echo htmlspecialchars($pelanggan) ?>">
                    <input type="hidden" name="total" value="<?php echo htmlspecialchars($total) ?>">
                    <div class="row">

                      <div class="col-lg-12">
                        <div class="form-floating">
                          <input type="number" class="form-control" id="TUusername" placeholder="Nominal Uang" name="uang" required>
                          <label for="TUusername">Nominal Uang</label>
                          <div class="invalid-feedback">Masukan Nominal Uang</div>
                        </div>
                      </div>
                    </div>


                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-success" name="bayar_validate" value="12345">Bayar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php } // Akhir dari if Bayar
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
                <?php if (!$is_paid && ($level_user == 1 || $level_user == 5)) { // Hanya tampil jika belum dibayar dan user adalah Admin atau Pelanggan
                ?>
                  <th scope="col">Aksi</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $total = 0;
              foreach ($result as $row) {
              ?>
                <tr>
                  <td> <?php echo htmlspecialchars($row['nama_menu']) ?></td>
                  <td> <?php echo number_format($row['harga'], 0, ',', '.') ?></td>
                  <td> <?php echo htmlspecialchars($row['jumlah']) ?> </td>
                  <td> <?php
                        if ($row['status'] == 1) {
                          echo "<span class='badge text-bg-warning'>Masuk ke dapur</span>";
                        } elseif ($row['status'] == 2) {
                          echo "<span class='badge text-bg-primary'>Siap Saji</span>";
                        }
                        ?> </td>
                  <td> <?php echo htmlspecialchars($row['catatan']) ?> </td>
                  <td>
                    <?php echo number_format($row['harganya'], 0, ',', '.') ?>
                  </td>
                  <?php if (!$is_paid && ($level_user == 1 || $level_user == 5)) { // Hanya tampil jika belum dibayar dan user adalah Admin atau Pelanggan
                  ?>
                    <td>
                      <div class="d-flex">
                        <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-warning btn-sm me-1"; ?>" data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo htmlspecialchars($row['id_list_order']) ?>" title="Edit Data"><i class="bi bi-pencil-square"></i></button>
                        <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary btn-sm me-1 disabled" : "btn btn-danger btn-sm me-1"; ?>" data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo htmlspecialchars($row['id_list_order']) ?>" title="Hapus Data"><i class="bi bi-trash"></i></button>
                      </div>
                    </td>
                  <?php } ?>
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
      <div>
        <?php if (!$is_paid && ($level_user == 1 || $level_user == 5)) { // Hanya tampil jika belum dibayar dan user adalah Admin atau Pelanggan
        ?>
          <button class="<?php echo (!empty($row['id_bayar'])) ? "btn btn-secondary disabled" : "btn btn-success"; ?>" data-bs-toggle="modal" data-bs-target="#tambahitem"><i class="bi bi-plus-circle"></i> Item</button>
          <button class="<?php echo (!$is_paid && ($level_user == 1 || $level_user == 5) && $all_items_ready) ? "btn btn-secondary disabled" : " btn btn-primary"; ?>" data-bs-toggle="modal" data-bs-target="#bayar"><i class="bi bi-cash-coin"></i> Bayar</button>
        <?php } ?>
        <?php if ($level_user == 1) { // Tombol Cetak Struk hanya untuk Admin
        ?>
          <button onclick="printStruk()" class=" btn btn-info"><i class="bi bi-printer"></i> Cetak Struk</button>
        <?php } ?>
      </div>


    </div>
  </div>
</div>


<div id="strukContent" class="d-none">
  <style>
    #struk {
      font-family: "Arial", sans-serif;
      font-size: 12px;
      max-width: 300px;
      border: 1px solid #ccc;
      padding: 10px;
      width: 80mm;
    }

    #struk h2 {
      text-align: center;
      color: #333;
    }

    #struk p {
      margin: 5px 0;
    }

    #struk table {
      font-size: 12px;
      border-collapse: collapse;
      margin-top: 10px;
      width: 100%;
    }

    #struk th,
    #struk td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    #struk .total {
      font-weight: bold;
    }
  </style>
  <div id="struk">
    <h2>Struk Pembayaran Vienna Coffee</h2>
    <p>Kode Order: <?php echo htmlspecialchars($kode) ?></p>
    <p>Meja: <?php echo htmlspecialchars($meja) ?></p>
    <p>Pelanggan: <?php echo htmlspecialchars($pelanggan) ?></p>
    <p>Waktu Order <?php echo isset($result[0]['waktu_order']) ? date('d/m/Y H:i:s', strtotime($result[0]['waktu_order'])) : ''; ?></p>
    <table>
  </div>
  <th>
    <tr class="total">
      <th>Menu</th>
      <th>Harga</th>
      <th>Jumlah</th>
      <th>Total</th>
    </tr>
  </th>
  <tbody>
    <?php
    $total = 0;
    if (!empty($result)) {
      foreach ($result as $row) { ?>
        <tr>
          <td><?php echo htmlspecialchars($row['nama_menu']) ?></td>
          <td><?php echo number_format($row['harga'], 0, ',', '.') ?></td>
          <td><?php echo htmlspecialchars($row['jumlah']) ?></td>
          <td><?php echo number_format($row['harganya'], 0, ',', '.') ?></td>
        </tr>
    <?php
        $total += $row['harganya'];
      }
    }
    ?>
    <tr>
      <td colspan="3">Total Harga</td>
      <td><?php echo number_format($total, 0, ',', '.') ?></td>
    </tr>
  </tbody>
  </table>
</div>

<script>
  function printStruk() {
    var strukContent = document.getElementById("strukContent").innerHTML;

    var printFrame = document.createElement('iframe');
    printFrame.style.display = 'none';
    document.body.appendChild(printFrame);
    printFrame.contentDocument.write(strukContent);
    printFrame.contentWindow.print();
    document.body.removeChild(printFrame);
  }
</script>