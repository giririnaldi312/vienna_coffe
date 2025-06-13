  <?php
  include "proses/connect.php";
  $query = mysqli_query($conn, "SELECT * FROM tb_user");
  while ($record = mysqli_fetch_array($query)) {
    $result[] = $record;
  }
  ?>

  <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <div class="col-lg-9 mt-2">
    <div class="card">
      <div class="card-header">
        Halaman Pengguna
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col d-flex justify-content-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahUser" title="Tambahkan User Baru">Tambah User</button>
          </div>
        </div>



        <!-- Modal Tambah User Baru -->
        <div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="ModalTambahUserLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-fullscreen-md-down">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalTambahUserLabel">Tambah User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form class="needs-validation" novalidate action="proses/proses_input_user.php" method="POST">
                  <div class="row">
                    <div class="col-lg-6 me-1">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="TUnama" placeholder="Masukkan nama anda" name="nama" required>
                        <label for="TUnama">Nama</label>
                        <div class="invalid-feedback">
                          Masukan Nama.
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="TUusername" placeholder="name@example.com" name="username" required>
                        <label for="TUusername">Username</label>
                        <div class="invalid-feedback">
                          Masukan Username.
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-floating mb-3">
                        <select class="form-select" id="TUlevel" name="level" required>
                          <option selected hidden value="">Pilih level User</option>
                          <option value="1">Owner/Admin</option>
                          <option value="5">Pelanggan</option>
                        </select>
                        <label for="TUlevel">Level User</label>
                        <div class="invalid-feedback">
                          Pilih Level User.
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-8">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="TUnohp" placeholder="08xxxxxxxx" name="nohp">
                        <label for="TUnohp">No HP</label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-floating mb-3">
                      <input type="password" class="form-control" id="TUpassword" placeholder="Masukkan password" name="password" disabled value="12345">
                      <label for="TUpassword">Password</label>
                    </div>
                  </div>

                  <div class="form-floating mb-3">
                    <textarea class="form-control" id="TUalamat" style="height: 100px;" name="alamat"></textarea>
                    <label for="TUalamat">Alamat</label>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="input_user_validate" value="12345">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Akhir Modal Tambah User Baru -->

        <?php
        foreach ($result as $row) {
        ?>
          <!-- Modal View -->
          <div class="modal fade" id="ModalView<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="#ModalViewLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ModalViewLabel">Detail Data User</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="needs-validation" novalidate action="proses/proses_input_user.php" method="POST">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="viewNama" name="nama" placeholder="Your Name" value="<?php echo $row['nama'] ?>" disabled>
                          <label for="viewNama">Nama</label>

                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="username" placeholder="Username" id="viewUsername" value="<?php echo $row['username'] ?>" disabled>
                          <label for=" viewUsername">Username</label>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-5">
                        <div class="form-floating mb-3">
                          <select class="form-select" aria-label="Default select example" disabled name="level" id="">
                            <?php
                            $data = array("Owner/Admin", "Kasir", "Pelayan", "Dapur");
                            foreach ($data as $key => $value) {
                              if ($row['level'] == $key + 1) {
                                echo "<option selected value='$key' >$value</option>";
                              } else {
                                echo "<option value='$key' >$value</option>";
                              }
                            }
                            ?>
                          </select>
                          <label for="viewLevelUser">Level User</label>
                        </div>
                      </div>
                      <div class="col-lg-7">
                        <div class="form-floating mb-3">
                          <input disabled type="text" class="form-control" name="nohp" placeholder="08xxxxxxxxxx" id="viewNohp" value="<?php echo $row['nohp'] ?>">
                          <label for="viewNohp">No HP</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-floating">
                      <textarea disabled class="form-control" name="alamat" style="height: 100px;" id="viewAlamat"><?php echo $row['alamat'] ?></textarea>
                      <label for="viewAlamat">Alamat</label>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Akhir Modal View -->

          <!-- Modal Edit -->
          <div class="modal fade" id="ModalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="#ModalViewLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ModalViewLabel">Edit Data User</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="needs-validation" novalidate action="proses/proses_edit_user.php" method="POST">
                  <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="viewNama" name="nama" placeholder="Your Name" value="<?php echo $row['nama'] ?>" required>
                          <label for="viewNama">Nama</label>

                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-floating mb-3">
                          <input <?php echo ($row['username'] == $_SESSION['username_vienna_coffee']) ? 'readonly' : ''; ?> type="text" class="form-control" name="username" placeholder="Username" id="viewUsername" value="<?php echo $row['username'] ?>" required>
                          <label for=" viewUsername">Username</label>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-5">
                        <div class="form-floating mb-3">
                          <select class="form-select" aria-label="Default select example" required name="level" id="">
                            <?php
                            $levels = array(
                              1 => "Owner/Admin",
                              5 => "Pelanggan"
                            );
                            foreach ($levels as $key => $value) {
                              if ($row['level'] == $key) {
                                echo "<option selected value='" . $key . "' >" . $value . "</option>";
                              } else {
                                echo "<option value='" . $key . "' >" . $value . "</option>";
                              }
                            }
                            ?>
                          </select>
                          <label for="viewLevelUser">Level User</label>
                        </div>
                      </div>
                      <div class="col-lg-7">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="nohp" placeholder="08xxxxxxxxxx" id="viewNohp" value="<?php echo $row['nohp'] ?>">
                          <label for="viewNohp">No HP</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-floating">
                      <textarea class="form-control" name="alamat" style="height: 100px;" id="viewAlamat"><?php echo $row['alamat'] ?></textarea>
                      <label for="viewAlamat">Alamat</label>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="input_user_validate" value="12345">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Akhir Modal Edit -->

          <!-- Modal Delete -->
          <div class="modal fade" id="ModalDelete<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="#ModalViewLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ModalViewLabel">Hapus Data User</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="needs-validation" novalidate action="proses/proses_delete_user.php" method="POST">
                  <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                  <div class="col-lg-12">
                    <?php
                    if ($row['username'] == $_SESSION['username_vienna_coffee']) {
                      echo "<div class='alert alert-danger'>Anda tidak dapat menghapus diri anda sendiri.</div>";
                    } else {
                      echo "Apakah anda yakin ingin menghapus user <b>$row[username]</b>";
                    }
                    ?>


                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger" name="input_user_validate" value="12345" <?php echo ($row['username'] == $_SESSION['username_vienna_coffee']) ? 'hidden' : ''; ?>>Hapus</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Akhir Modal Delete -->

          <!-- Modal Reset Password -->
          <div class="modal fade" id="ModalResetPassword<?php echo $row['id'] ?>" tabindex="-1" aria-labelledby="#ModalViewLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-fullscreen-md-down">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="ModalViewLabel">Reset Password User</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="needs-validation" novalidate action="proses/proses_reset_password.php" method="POST">
                  <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                  <div class="col-lg-12">
                    <?php
                    if ($row['username'] == $_SESSION['username_vienna_coffee']) {
                      echo "<div class='alert alert-danger'>Anda tidak dapat mereset Password diri anda sendiri.</div>";
                    } else {
                      echo "Apakah anda yakin ingin mereset password user <b> $row[username]</b> Menjadi password bawaan sistem yaitu <b>Password</b>";
                    }
                    ?>


                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger" name="input_user_validate" value="12345" <?php echo ($row['username'] == $_SESSION['username_vienna_coffee']) ? 'hidden' : ''; ?>>Reset Password</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Akhir Modal Reset Password -->

        <?php
        }
        ?>




        <?php
        if (empty($result)) {
          echo "Data user tidak ada";
        } else {
        ?>
          <div class=" table-responsive mt-2">
            <table class="table table-hover" id="example">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama</th>
                  <th scope="col">Username</th>
                  <th scope="col">Level</th>
                  <th scope="col">No Handphone</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($result as $row) {
                ?>
                  <tr class="<?php echo ($row['id'] == 1) ? 'bg-superadmin' : ''; ?>">
                    <th scope="row"><?php echo $no++ ?></th>
                    <td><?php echo $row['nama'] ?></td>
                    <td><?php echo $row['username'] ?></td>
                    <td>
                      <?php
                      if ($row['level'] == 1) {
                        echo "Admin";
                      } elseif ($row['level'] == 5) { // Mengasumsikan '5' adalah level baru untuk Pelanggan
                        echo "Pelanggan";
                      }
                      ?>
                    </td>
                    <td><?php echo $row['nohp'] ?></td>
                    <td class="d-flex">
                      <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalView<?php echo $row['id'] ?>" title="Detail Data User"><i class="bi bi-eye"></i></button>
                      <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo $row['id'] ?>" title="Edit Data User"><i class="bi bi-pencil-square"></i></button>
                      <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo $row['id'] ?>" title="Hapus Data User"><i class="bi bi-trash"></i></button>
                      <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalResetPassword<?php echo $row['id'] ?>" title="Reset Password User"><i class="bi bi-key"></i></button>
                    </td>
                  </tr>
                <?php } ?>
                <style>
                  .bg-superadmin td,
                  .bg-superadmin th {
                    background-color: rgb(204, 206, 255) !important;
                    /* kuning muda */
                    color: rgb(0, 0, 0) !important;

                  }
                </style>

              </tbody>
            </table>
          </div>
        <?php } ?>




      </div>
    </div>
  </div>