<?php
	session_start();

	include("connect.php");

  if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
  }

  if ($_SESSION['type'] != "admin") {
    header("location: ./");
    exit();
  }

  $nama_akun = $_SESSION['nama'] . " (admin)";

  if (isset($_GET['tipe'])) {
    $tipe = trim($_GET['tipe']);
  } else {
    $tipe = "semua";
  }

  if (isset($_GET['cari'])) {
    $cari = trim($_GET['cari']);
  } else {
    $cari = "";
  }
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creatively - Daftar Akun</title>
  <link rel="icon" href="resources/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="resources/css/style.css">
  <link rel="stylesheet" href="resources/css/tabel.css">
  <link rel="stylesheet" href="resources/tabler-icons/tabler-icons.min.css">
  <script type="text/javascript" src="resources/js/script.js"></script>
</head>

<body>
  <header>
    <a id="logo" href=""><img src="resources/img/logo-creatively.png" alt="logo-creatively"></a>
    
    <div id="search-box">
      <form method="get">
        <input type="hidden" name="tipe" value="<?=htmlspecialchars($tipe)?>">
        <input type="search" name="cari" value="<?=htmlspecialchars($cari)?>" placeholder="cari akun dengan kata kunci...">
        <button type="submit"><i class="ti ti-search"></i></button>
      </form>
    </div>
  </header>

  <nav>
    <div id="nama-akun">
      <p>Anda Login Sebagai</p>
      <p><?=htmlspecialchars($nama_akun)?></p>
    </div>

    <ul id="menu">
      <li><a href="./">
        <i class="ti ti-home"></i>
        <span>Beranda</span>
      </a></li>
      <li><a href="buat-proyek.php">
        <i class="ti ti-file-plus"></i>
        <span>Buat Proyek</span>
      </a></li>
      <li><a href="daftar-proyek.php">
        <i class="ti ti-files"></i>
        <span>Daftar Proyek</span>
      </a></li>
      <li><a href="daftar-pesanan.php">
        <i class="ti ti-stack-2"></i>
        <span>Daftar Pesanan</span>
      </a></li>
      <li class="active"><a href="daftar-akun.php">
        <i class="ti ti-users"></i>
        <span>Daftar Akun</span>
      </a></li>
      <li><a href="akun-anda.php">
        <i class="ti ti-user-circle"></i>
        <span>Akun Anda</span>
      </a></li>
      <li><a href="logout.php">
        <i class="ti ti-logout"></i>
        <span>Logout</span>
      </a></li>
      <hr>
      <li><a href="cara-pemesanan.php">
        <i class="ti ti-help"></i>
        <span>Cara Pemesanan</span>
      </a></li>
      <li><a href="kontak-creatively.php">
        <i class="ti ti-message"></i>
        <span>Kontak Creatively</span>
      </a></li>
      <li><a href="tentang-creatively.php">
        <i class="ti ti-info-circle"></i>
        <span>Tentang Creatively</span>
      </a></li>
    </ul>
  </nav>

  <main>
    <select name="filter_tipe_akun" title="Filter Tipe Akun" onchange="location.href='?tipe='+this.value">
      <option disabled>Filter Tipe Akun</option>
      <option value="semua" <?php if ($tipe == "semua") echo "selected" ?> >Semua</option>
      <option value="customer" <?php if ($tipe == "customer") echo "selected" ?> >Customer</option>
      <option value="admin" <?php if ($tipe == "admin") echo "selected" ?> >Admin</option>
    </select>

    <table class="daftar-akun">
      <tr>
        <th>No</th>
        <th>Username</th>
        <th>Nama</th>
        <th>Tipe Akun</th>
        <th>Aksi</th>
      </tr>
<?php
  $tipe = mysqli_real_escape_string($conn, $tipe);
  $cari = mysqli_real_escape_string($conn, $cari);

  $sql = "SELECT * FROM user";
  
  if ($tipe != "semua") {
    $sql .= " WHERE type='$tipe'";
    if (!empty($cari)) $sql .= " AND (username LIKE '%$cari%' OR nama_lengkap LIKE '%$cari%')";
  } else {
    if (!empty($cari)) $sql .= " WHERE username LIKE '%$cari%' OR nama_lengkap LIKE '%$cari%'";
  }

  $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

  if (!mysqli_num_rows($result)) {
    echo "<tr><td class='kosong' colspan=5>Data tidak tersedia!</td></tr>";
  }

  $no = 0;

  while ($user = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?=++$no?></td>
        <td><?=htmlspecialchars($user['username'])?></td>
        <td><?=htmlspecialchars($user['nama_lengkap'])?></td>
        <td><?=htmlspecialchars($user['type'])?></td>
        <td><a href="detail-akun.php?username=<?=htmlspecialchars($user['username'])?>" title="Lihat Detail"><i class="ti ti-file-text"></i></a></td>
      </tr>
<?php } ?>
    </table>
  </main>
</body>

</html>
