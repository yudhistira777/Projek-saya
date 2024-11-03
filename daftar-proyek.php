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
  <title>Creatively - Daftar Proyek</title>
  <link rel="icon" href="resources/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="resources/css/style.css">
  <link rel="stylesheet" href="resources/css/tabel.css">
  <link rel="stylesheet" href="resources/tabler-icons/tabler-icons.min.css">
</head>

<body>
  <header>
    <a id="logo" href=""><img src="resources/img/logo-creatively.png" alt="logo-creatively"></a>
    
    <div id="search-box">
      <form method="get">
        <input type="search" name="cari" value="<?=htmlspecialchars($cari)?>" placeholder="cari proyek dengan kata kunci...">
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
      <li class="active"><a href="daftar-proyek.php">
        <i class="ti ti-files"></i>
        <span>Daftar Proyek</span>
      </a></li>
      <li><a href="daftar-pesanan.php">
        <i class="ti ti-stack-2"></i>
        <span>Daftar Pesanan</span>
      </a></li>
      <li><a href="daftar-akun.php">
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
    <table class="daftar-proyek">
      <tr>
        <th>No</th>
        <th>ID</th>
        <th>Nama Proyek</th>
        <th>Harga</th>
        <th>Aksi</th>
      </tr>
<?php
  $cari = mysqli_real_escape_string($conn, $cari);

  $sql = "SELECT id_project, nama_project, harga_project FROM project WHERE deleted=0";
  if (!empty($cari)) $sql .= " AND (id_project LIKE '%$cari%' OR nama_project LIKE '%$cari%')";

  $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

  if (!mysqli_num_rows($result)) {
    echo "<tr><td class='kosong' colspan=5>Data tidak tersedia!</td></tr>";
  }

  $no = 0;

  while ($proyek = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?=++$no?></td>
        <td><?=htmlspecialchars($proyek['id_project'])?></td>
        <td><?=htmlspecialchars($proyek['nama_project'])?></td>
        <td>Rp<?=number_format($proyek['harga_project'], 0, ',', '.')?></td>
        <td><a href="edit-proyek.php?id=<?=htmlspecialchars($proyek['id_project'])?>" title="Lihat atau Edit"><i class="ti ti-file-pencil"></i></a></td>
      </tr>
<?php } ?>
    </table>
  </main>
</body>

</html>
