<?php
	session_start();

	include("connect.php");

  if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
  }

  $username = $_SESSION['username'];

  $nama_akun = $_SESSION['nama'];
  if ($_SESSION['type'] == "admin") $nama_akun .= " (admin)";

  if (isset($_POST['perbarui'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $asal_kota = mysqli_real_escape_string($conn, trim($_POST['asal_kota']));
    $tanggal_lahir = mysqli_real_escape_string($conn, trim($_POST['tanggal_lahir']));
    $jenis_kelamin = mysqli_real_escape_string($conn, trim($_POST['jenis_kelamin']));
    $kontak = mysqli_real_escape_string($conn, trim($_POST['kontak']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $ulangi_password = mysqli_real_escape_string($conn, $_POST['ulangi_password']);

    if (
      empty($email) || empty($nama) || 
      empty($asal_kota) || empty($tanggal_lahir) ||
      empty($jenis_kelamin) || empty($kontak) ||
      empty($password) || empty($ulangi_password)
    ) {
      echo "<script>alert('maaf, masih ada input yang kosong!')</script>";
    } else if ($password != $ulangi_password) {
      echo "<script>alert('maaf, password harus sama dengan ulangi password!')</script>";
    } else {
      $sql = "UPDATE user SET email='$email', nama_lengkap='$nama', 
        tempat_lahir='$asal_kota', tanggal_lahir='$tanggal_lahir', 
        gender='$jenis_kelamin', no_hp='$kontak', password='$password'
        WHERE username='$username'";

      mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
      $_SESSION['nama'] = $nama;

      echo "<script>alert('data akun anda berhasil diperbarui!')</script>";
    }

    echo "<script>location.href = ''</script>";
    exit();
  }

  $sql = "SELECT * FROM user WHERE username='$username'";
  $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
  $user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creatively - Akun Anda</title>
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
      <li class="active"><a href="akun-anda.php">
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
    <form method="post">
      <table>
        <tr>
          <th><label for="Username">Username :</label></th>
          <td><input type="text" id="Username" placeholder="masukan username" value="<?=htmlspecialchars($user['username'])?>" disabled></td>
        </tr>
        <tr>
          <th><label for="Email">Email :</label></th>
          <td><input type="text" id="Email" name="email" placeholder="masukan email" value="<?=htmlspecialchars($user['email'])?>"></td>
        </tr>
        <tr>
          <th><label for="Nama">Nama Lengkap :</label></th>
          <td><input type="text" id="Nama" name="nama" placeholder="masukan nama" value="<?=htmlspecialchars($user['nama_lengkap'])?>"></td>
        </tr>
        <tr>
          <th><label for="Kota">Asal Kota/Desa :</label></th>
          <td><input type="text" id="Kota" name="asal_kota" placeholder="masukan asal kota" value="<?=htmlspecialchars($user['tempat_lahir'])?>"></td>
        </tr>
        <tr>
          <th><label for="tgl">Tanggal lahir :</label></th>
          <td><input type="date" id="tgl" name="tanggal_lahir" value="<?=htmlspecialchars($user['tanggal_lahir'])?>"></td>
        </tr>
        <tr>
          <th><label>Jenis Kelamin :</label></th>
          <td>
            <input type="radio" id="laki-laki" name="jenis_kelamin" value="laki-laki" <?php if ($user['gender'] == "laki-laki") { ?> checked <?php } ?>>
            <label for="laki-laki">laki-laki</label>
            <input type="radio" id="perempuan" name="jenis_kelamin" value="perempuan" <?php if ($user['gender'] == "perempuan") { ?> checked <?php } ?>>
            <label for="perempuan">perempuan</label>
          </td>
        </tr>
        <tr>
          <th><label for="Kontak">Kontak :</label></th>
          <td><input type="text" id="Kontak" name="kontak" value="<?=htmlspecialchars($user['no_hp'])?>"></td>
        </tr>
        <tr>
          <th><label for="Password">Password :</label></th>
          <td><input type="Password" id="Password" name="password" value="<?=htmlspecialchars($user['password'])?>"></td>
        </tr>
        <tr>
          <th><label for="Ulangi_password">Ulangi Password :</label></th>
          <td><input type="Password" id="Ulangi_password" name="ulangi_password" value="<?=htmlspecialchars($user['password'])?>"></td>
        </tr>
        <tr>
          <th></th>
          <td>
            <button class="btn" name="perbarui" type="submit">Perbarui</button>
            <a class="btn" href="">Reset</a>
          </td>
        </tr>
      </table>
    </form> 
  </main>
</body>

</html>
