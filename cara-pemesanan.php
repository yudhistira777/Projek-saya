<?php
	session_start();

	include("connect.php");

	if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
  }

  $nama_akun = $_SESSION['nama'];
  if ($_SESSION['type'] == "admin") $nama_akun .= " (admin)";

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
  <title>Creatively - Beranda</title>
  <link rel="icon" href="resources/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="resources/css/style.css">
  <link rel="stylesheet" href="resources/css/cara-pemesanan.css">
  <link rel="stylesheet" href="resources/tabler-icons/tabler-icons.min.css">
</head>

<body>
  <header>
    <a id="logo" href=""><img src="resources/img/logo-creatively.png" alt="logo-creatively"></a>
    
    <div id="search-box">
      <form>
        <input type="search" name="cari" value="<?=htmlspecialchars($cari)?>" placeholder="cari proyek yang anda inginkan...">
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
<?php if ($_SESSION['type'] == "admin") { ?>
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
      <li><a href="daftar-akun.php">
        <i class="ti ti-users"></i>
        <span>Daftar Akun</span>
      </a></li>
<?php } else { ?>
      <li><a href="pesanan-anda.php">
        <i class="ti ti-stack-2"></i>
        <span>Pesanan Anda</span>
      </a></li>
<?php } ?>
      <li><a href="akun-anda.php">
        <i class="ti ti-user-circle"></i>
        <span>Akun Anda</span>
      </a></li>
      <li><a href="logout.php">
        <i class="ti ti-logout"></i>
        <span>Logout</span>
      </a></li>
      <hr>
      <li class="active"><a href="cara-pemesanan.php">
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
    <div class="pemesanan">
      <img src="resources/img/logo-creatively.png" alt="logo" width="250px">
      <h3>Cara Melakukan pemesanan di Creatively :</h3>
      <ol>
          <li><p>Pilih menu <strong>Beranda</strong> pada sidebar</p></li>
          <li><p>Pada menu <Strong>Beranda</Strong> anda bisa melihat-lihat project yang tim <br> creatively tawarkan. Anda juga bisa memanfaatkan fitur pencarian <br> untuk mencari project yang anda inginkan lebih cepat.</p></li>
          <li>Kemudian anda tinggal pilih saja project mana yang anda inginkan</li>
          <li><p>Selanjutnya anda tinggal mengisikan deskripsi terkait detail <br> pesanan yang anda inginkan.</p></li>
          <li><p>Sebelum melakukan pemesanan, anda perlu melakukan <br> pembayaran melalui transfer bank ke nomor rekening yang sudah <br> tersedia.</p></li>
          <li><p>Foto atau scan bukti transaksi yang sudah dilakukan, kemudian upload <br> gambar </p></li>
          <li><p>Setelah itu klik tombol <strong>Pesan Sekarang</strong> untuk melakukan <br> pemesanan.</p></li>
          <li>Selanjutnya anda akan diarahkan ke detail pemesanan.</li>
          <li><p>Pada detail pemesanan anda bisa melihat status, untuk <br> mengetahui perkembangan pemesanan anda.</p></li>
          <li><p>Jika anda perlu bantuan, revisi, atau pembatalan anda bisa <br> menghubungi kami melalui <strong>Kontak Creatively</strong></p></li>         
      </ol>
      </div>
  </main>
</body>

</html>