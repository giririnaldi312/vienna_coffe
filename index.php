<?php
session_start();

if (isset($_GET['x']) && $_GET['x'] == 'home') {
  $page = "home.php";
  include "main.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'order') {
  // Admin (1) dan Pelanggan (5) bisa akses halaman order
  if ($_SESSION['level_vienna_coffee'] == 1 ||  $_SESSION['level_vienna_coffee'] == 5) {
    $page = "order.php";
    include "main.php";
  } else {
    $page = "home.php";
    include "main.php";
  }
} elseif (isset($_GET['x']) && $_GET['x'] == 'user') {
  // Hanya Admin (1) yang bisa mengelola pengguna
  if ($_SESSION['level_vienna_coffee'] == 1) {
    $page = "user.php";
    include "main.php";
  } else {
    $page = "home.php";
    include "main.php";
  }
} elseif (isset($_GET['x']) && $_GET['x'] == 'dapur') {
  // Hanya Admin (1) yang bisa mengakses dapur
  if ($_SESSION['level_vienna_coffee'] == 1) {
    $page = "dapur.php";
    include "main.php";
  } else {
    $page = "home.php";
    include "main.php";
  }
} elseif (isset($_GET['x']) && $_GET['x'] == 'report') {
  // Admin (1) dan Pelanggan (5) bisa akses laporan
  if ($_SESSION['level_vienna_coffee'] == 1 || $_SESSION['level_vienna_coffee'] == 5) {
    $page = "report.php";
    include "main.php";
  } else {
    $page = "home.php";
    include "main.php";
  }
} elseif (isset($_GET['x']) && $_GET['x'] == 'menu') {
  // Admin (1) dan Pelanggan (5) bisa melihat menu
  if ($_SESSION['level_vienna_coffee'] == 1 || $_SESSION['level_vienna_coffee'] == 5) {
    $page = "menu.php";
    include "main.php";
  } else {
    $page = "home.php";
    include "main.php";
  }
} elseif (isset($_GET['x']) && $_GET['x'] == 'login') {
  include "login.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'logout') {
  include "proses/proses_logout.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'katmenu') {
  // Hanya Admin (1) yang bisa mengelola kategori menu
  if ($_SESSION['level_vienna_coffee'] == 1) {
    $page = "katmenu.php";
    include "main.php";
  } else {
    $page = "home.php";
    include "main.php";
  }
} elseif (isset($_GET['x']) && $_GET['x'] == 'orderitem') {
  // Admin (1) dan Pelanggan (5) bisa akses detail order item
  if ($_SESSION['level_vienna_coffee'] == 1 ||  $_SESSION['level_vienna_coffee'] == 5) {
    $page = "order_item.php";
    include "main.php";
  } else {
    $page = "home.php";
    include "main.php";
  }
} elseif (isset($_GET['x']) && $_GET['x'] == 'viewitem') {
  // Admin (1) dan Pelanggan (5) bisa melihat detail item
  if ($_SESSION['level_vienna_coffee'] == 1 || $_SESSION['level_vienna_coffee'] == 5) {
    $page = "view_item.php";
    include "main.php";
  } else {
    $page = "home.php";
    include "main.php";
  }
} else {
  $page = "home.php";
  include "main.php";
}

