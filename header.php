<?php
include "proses/connect.php";
$query  = mysqli_query($conn, "SELECT * FROM tb_user WHERE username='$_SESSION[username_vienna_coffee]'");
$records = mysqli_fetch_array($query);

?>
<nav class="navbar navbar-expand navbar-dark bg-primary sticky-top">
    <div class="container-lg">
        <a class="navbar-brand" href="."><i class="bi bi-cup-hot"></i> Vienna Coffee</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        echo $hasil['username'];
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalUbahProfile"><i class="bi bi-person-circle"></i> Pofile</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalUbahPassword"><i class="bi bi-key"></i> Ubah Password</a></li>
                        <li><a class="dropdown-item" href="logout" style="color: red;"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="modal fade" id="ModalUbahPassword" tabindex="-1" aria-labelledby="#ModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalViewLabel">Ubah Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="needs-validation" novalidate action="proses/proses_ubah_password.php" method="POST">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input disabled type="email" class="form-control" name="username" placeholder="Username" id="viewUsername" required value="<?php echo $_SESSION['username_vienna_coffee'] ?>">
                                <label for=" viewUsername">Username</label>
                                <div class="invalid-feedback">
                                    Masukan Username
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="passwordlama" id="viewPassword" required>
                                <label for="viewPassword">Password lama</label>
                                <div class="invalid-feedback">
                                    Masukan Password Lama anda
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="passwordbaru" id="viewPasswordBaru" required>
                                <label for=" viewPasswordBaru">Password Baru</label>
                                <div class="invalid-feedback">
                                    Masukan Password baru anda
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="repasswordbaru" id="viewRePasswordBaru" required>
                                <label for="viewRePasswordBaru">Ulangi Password baru</label>
                                <div class="invalid-feedback">
                                    Ulangi Password Baru anda
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="ubah_password_validate" value="12345">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalUbahProfile" tabindex="-1" aria-labelledby="#ModalViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalViewLabel">Ubah Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form class="needs-validation" novalidate action="proses/proses_ubah_profile.php" method="POST">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input disabled type="email" class="form-control" name="username" placeholder="Username" id="viewUsername" required value="<?php echo $_SESSION['username_vienna_coffee'] ?>">
                                <label for=" viewUsername">Username</label>
                                <div class="invalid-feedback">
                                    Masukan Username
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nama" id="viewPassword" required value="<?php echo $records['nama'] ?>">
                                <label for="viewPassword">Nama</label>
                                <div class="invalid-feedback">
                                    Masukan Nama Anda
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="nohp" id="viewPasswordBaru" required value="<?php echo $records['nohp'] ?>">
                                <label for=" viewPasswordBaru">Nomor HP</label>
                                <div class="invalid-feedback">
                                    Masukan Nomor HP Anda
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">


                        <div class="col-lg-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="alamat" style="height: 100px;" id="viewAlamat"><?php echo $records['alamat'] ?></textarea>
                                <label for="viewRePasswordBaru">Alamat</label>
                                <div class="invalid-feedback">
                                    Masukan Alamat Anda
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="ubah_profile_validate" value="12345">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>