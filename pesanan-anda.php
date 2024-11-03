<?php
	session_start();

	include("connect.php");

  if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
  }

  if ($_SESSION['type'] != "customer") {
    header("location: ./");
    exit();
  }

  $nama_akun = $_SESSION['nama'];

  if (isset($_GET['status'])) {
    $status = trim($_GET['status']);
  } else {
    $status = "semua";
  }

  if (isset($_GET['cari'])) {
    $cari = trim($_GET['cari']);
  } else {
    $cari = "";
  }

  if (isset($_GET['urutan'])) {
    $urutan = trim($_GET['urutan']);
  } else {
    $urutan = "asc";
  }
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creatively - Pesanan Anda</title>
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
        <input type="hidden" name="status" value="<?=htmlspecialchars($status)?>">
        <input type="hidden" name="urutan" value="<?=htmlspecialchars($urutan)?>">
        <input type="search" name="cari" value="<?=htmlspecialchars($cari)?>" placeholder="cari pesanan anda dengan kata kunci...">
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
      <li class="active"><a href="pesanan-anda.php">
        <i class="ti ti-stack-2"></i>
        <span>Pesanan Anda</span>
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
    <select name="filter_tipe_akun" title="Filter Status" onchange="location.href='?status='+this.value+'&urutan=<?=htmlspecialchars($urutan)?>'">
      <option disabled>Filter Status</option>
      <option value="semua" <?php if ($status == "semua") echo "selected" ?> >Semua</option>
      <option value="belum dikonfirmasi" <?php if ($status == "belum dikonfirmasi") echo "selected" ?> >Belum Dikonfirmasi</option>
      <option value="sedang diproses" <?php if ($status == "sedang diproses") echo "selected" ?> >Sedang Diproses</option>
      <option value="sedang direvisi" <?php if ($status == "sedang direvisi") echo "selected" ?> >Sedang Direvisi</option>
      <option value="selesai" <?php if ($status == "selesai") echo "selected" ?> >Selesai</option>
      <option value="dibatalkan" <?php if ($status == "dibatalkan") echo "selected" ?> >Dibatalkan</option>
    </select>

    <select name="filter_tipe_akun" title="Filter Urutan" onchange="location.href='?status=<?=htmlspecialchars($status)?>'+'&urutan='+this.value">
      <option disabled>Filter Urutan</option>
      <option value="asc" <?php if ($urutan == "asc") echo "selected" ?> >Terlama - Terbaru</option>
      <option value="desc" <?php if ($urutan == "desc") echo "selected" ?> >Terbaru - Terlama</option>
    </select>

    <table class="pesanan-anda">
      <tr>
        <th>No</th>
        <th>ID</th>
        <th>Nama Proyek Pesanan Anda</th>
        <th>Waktu Pemesanan</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
<?php
  $status = mysqli_real_escape_string($conn, $status);
  $cari = mysqli_real_escape_string($conn, $cari);
  $username = mysqli_real_escape_string($conn, $_SESSION['username']);

  $sql = "SELECT id_transaksi, nama_project, tanggal_pembelian, status FROM pemesanan
    INNER JOIN project ON pemesanan.id_project=project.id_project WHERE username='$username'";

  if ($status != "semua") $sql .= " AND status='$status'";
  if (!empty($cari)) $sql .= " AND (id_transaksi LIKE '%$cari%' OR nama_project LIKE '%$cari%')";

  if ($urutan == "asc") {
    $sql .= " ORDER BY tanggal_pembelian asc";
  } else {
    $sql .= " ORDER BY tanggal_pembelian desc";
  }

  $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

  if (!mysqli_num_rows($result)) {
    echo "<tr><td class='kosong' colspan=5>Data tidak ada!</td></tr>";
  }

  $no = 0;

  while ($pesanan = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?=++$no?></td>
        <td><?=htmlspecialchars($pesanan['id_transaksi'])?></td>
        <td><?=htmlspecialchars($pesanan['nama_project'])?></td>
        <td><?=date_format(date_create($pesanan['tanggal_pembelian']), "d/m/Y H:i")?></td>
        <td><?=htmlspecialchars($pesanan['status'])?></td>
        <td><a href="detail-pesanan.php?id=<?=htmlspecialchars($pesanan['id_transaksi'])?>" title="Lihat atau Edit"><i class="ti ti-file-pencil"></i></a></td>
      </tr>
<?php } ?>
    </table>
  </main>
</body>

</html>
