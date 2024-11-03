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
  <link rel="stylesheet" href="resources/css/kontak-creatively.css">
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
      <li><a href="cara-pemesanan.php">
        <i class="ti ti-help"></i>
        <span>Cara Pemesanan</span>
      </a></li>
      <li class="active"><a href="kontak-creatively.php">
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
      <h3>Anda bisa menghubungi kami melalui :</h3>
    </div>

    <a class="box instagram" href="#"><img src="resources/img/instagram.png" alt="instagram"><span>@creatively.id</span></a>
    <a class="box whatsapp" href="#"><img src="resources/img/whatsapp.png" alt="whatsapp"><span>+62 82297697722</span></a>
    <a class="box email" href="#"><img src="resources/img/email.png" alt="email"><span>creativelyid@gmail.com</span></a>
  </main>
</body>

</html>