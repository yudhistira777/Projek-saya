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

  if (isset($_GET['username'])) {
    $username = mysqli_real_escape_string($conn, $_GET['username']);
    $sql = "SELECT * FROM user WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
    $user = mysqli_fetch_assoc($result);
  } else {
    header("location: daftar-akun.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creatively - Detail Akun</title>
  <link rel="icon" href="resources/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="resources/css/style.css">
  <link rel="stylesheet" href="resources/css/form-tabel.css">
  <link rel="stylesheet" href="resources/tabler-icons/tabler-icons.min.css">
</head>

<body>
  <header>
    <a id="logo" href=""><img src="resources/img/logo-creatively.png" alt="logo-creatively"></a>
    
    <div id="search-box">
      <form method="get" action="./">
        <input type="search" name="cari" placeholder="cari proyek yang anda inginkan...">
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
<?php if (!mysqli_num_rows($result)) { ?>
    <p class="not-available">Maaf, data tidak tersedia!</p>
<?php } else { ?>
    <form method="post">
      <table>
        <tr>
          <th><label for="Username">Username :</label></th>
          <td><input type="text" id="Username" value="<?=htmlspecialchars($user['username'])?>" disabled></td>
        </tr>
        <tr>
          <th><label for="Email">Email :</label></th>
          <td><input type="text" id="Email" value="<?=htmlspecialchars($user['email'])?>" disabled></td>
        </tr>
        <tr>
          <th><label for="Nama">Nama Lengkap :</label></th>
          <td><input type="text" id="Nama" value="<?=htmlspecialchars($user['nama_lengkap'])?>" disabled></td>
        </tr>
        <tr>
          <th><label for="Kota">Asal Kota/Desa :</label></th>
          <td><input type="text" id="Kota" value="<?=htmlspecialchars($user['tempat_lahir'])?>" disabled></td>
        </tr>
        <tr>
          <th><label for="tgl">Tanggal lahir :</label></th>
          <td><input type="text" id="tgl" value="<?=date_format(date_create($user['tanggal_lahir']), "d/m/Y")?>" disabled></td>
        </tr>
        <tr>
          <th><label for="jenis_kelamin">Jenis Kelamin :</label></th>
          <td><input type="text" id="jenis_kelamin" value="<?=htmlspecialchars($user['gender'])?>" disabled></td>
        </tr>
        <tr>
          <th><label for="Kontak">Kontak :</label></th>
          <td><input type="text" id="Kontak" value="<?=htmlspecialchars($user['no_hp'])?>" disabled></td>
        </tr>
        <tr>
          <th><label for="tipe_akun">Tipe Akun :</label></th>
          <td><input type="text" id="tipe_akun" value="<?=htmlspecialchars($user['type'])?>" disabled></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <a class="btn" href="#!" onclick="history.back()">Kembali</a>
          </td>
        </tr>
      </table>
    </form> 
<?php } ?>
  </main>
</body>

</html>
