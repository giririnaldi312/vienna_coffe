<?php
include "proses/connect.php";
$query = mysqli_query($conn, "SELECT * FROM tb_daftar_menu");
while ($row = mysqli_fetch_array($query)) {
    $result[] = $row;
}

$query_chart = mysqli_query($conn, "SELECT nama_menu, tb_daftar_menu.id, SUM(tb_list_order.jumlah) AS total_jumlah FROM tb_daftar_menu
    LEFT JOIN tb_list_order ON tb_daftar_menu.id = tb_list_order.menu
    GROUP BY tb_daftar_menu.id
    ORDER BY tb_daftar_menu.id ASC
    ");
// $result_chart = array();
while ($record_chart = mysqli_fetch_array($query_chart)) {
    $result_chart[] = $record_chart;
}
// Fungsi pengganti array_column untuk PHP versi <5.5
function array_column_compat($array, $column_name)
{
    $output = array();
    foreach ($array as $row) {
        if (isset($row[$column_name])) {
            $output[] = $row[$column_name];
        }
    }
    return $output;
}

$array_menu = array_column_compat($result_chart, 'nama_menu');
$array_menu_quote = array_map(function ($menu) {
    return "'" . $menu . "'";
}, $array_menu);
$string_menu = implode(',', $array_menu_quote);
// echo $string_menu . "<br>";

$array_jumlah_pesanan = array_column_compat($result_chart, 'total_jumlah');
$string_jumlah_pesanan = implode(',', $array_jumlah_pesanan);
// echo $string_jumlah_pesanan;

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="col-lg-9 mt-2">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php
            $slide = 0;
            $firstSlideButton = true;
            foreach ($result as $dataTombol) {
                ($firstSlideButton) ? $aktif = "active" : $aktif = "";
                $firstSlideButton = false;
            ?>

                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?php echo $slide ?>" class="<?php echo $aktif ?>" aria-current="true" aria-label="Slide <?php echo $slide + 1 ?>"></button>

            <?php
                $slide++;
            } ?>
        </div>
        <div class="carousel-inner rounded">
            <?php
            $firstslide = true;
            foreach ($result as $data) {
                ($firstslide) ? $aktif = "active" : $aktif = "";
                $firstslide = false;
            ?>
                <div class="carousel-item <?php echo $aktif ?>">
                    <img src="assets/img/<?php echo $data['foto'] ?>" class="img-fluid" style="height: 250px; width: 1000px; object-fit: cover;" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?php echo $data['nama_menu'] ?></h5>
                        <p><?php echo $data['keterangan'] ?></p>
                    </div>
                </div>

            <?php } ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="card mt-4 border-0 bg-light">
        <div class="card-body text-center">
            <div class="card mt-4 border-0 bg-light">
                <div class="card-body text-center">
                    <h5 class="card-title">Selamat Datang di Vienna Coffee & Tea – Dumai</h5>
                    <p class="card-text">
                        Vienna Coffee & Tea Shop adalah kedai kopi lokal yang berlokasi di Jl. Nangka No.18 b, Rimba Sekampung, Kota Dumai.
                        Kami menyajikan berbagai pilihan kopi spesial (cappuccino, latte, frappe), teh, serta camilan dan dessert menarik seperti takoyaki mozzarella dan matcha parfait, semuanya dalam suasana nyaman dan instagramable.
                    </p>
                    <p>
                        Tempat yang pas untuk nongkrong santai, meeting informal, atau quality time bersama teman dan keluarga—dilengkapi Wi‑Fi, live music, dan desain cozy.
                    </p>
                    <p>
                        Harga menu berkisar Rp 12.000 – Rp 240.000.
                    </p>
                    <a href="order" class="btn btn-primary">Order Disini</a>
                </div>
            </div>

            <div class="card mt-4 border-0 bg-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Kontak & Jam Buka</h5>
                    <p>📍 Alamat: Jl. Nangka No.18 b, Rimba Sekampung, Dumai Kota, Dumai – Riau</p>
                    <p>📞 WhatsApp / Telp: <a href="https://wa.me/6285271738888" target="_blank">+62-0812-7631-0768</a></p>
                    <p>⏰ Jam Buka: Setiap hari 15:00 – 22:00</p>
                    <p>📷 Instagram: <a href="https://www.instagram.com/viennacoftea/" target="_blank">@viennacoftea</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4 border-0 bg-light">
        <div class="card-body text-center">
            <div>
                <canvas id="myChart"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('myChart');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [<?php echo $string_menu ?>],
                        datasets: [{
                            label: 'Jumlah Porsi Terjual',
                            data: [<?php echo $string_jumlah_pesanan ?>],
                            borderWidth: 1,
                            backgroundColor: [
                                'rgba(240, 63, 63, 0.75)',
                                'rgba(0, 153, 255, 0.75)',
                                'rgba(229, 255, 0, 0.75)',
                                'rgba(0, 255, 0, 0.75)',
                                'rgba(255, 0, 255, 0.75)',
                                'rgba(255, 166, 0, 0.75)'
                            ]
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>