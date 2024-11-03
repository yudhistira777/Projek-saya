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

  if (isset($_POST['buat_proyek'])) {
    $nama_proyek = mysqli_real_escape_string($conn, trim($_POST['nama_proyek']));
    $gambar = $_FILES['gambar']['tmp_name'];
    $deskripsi = mysqli_real_escape_string($conn, trim($_POST['deskripsi']));
    $harga = mysqli_real_escape_string($conn, trim($_POST['harga']));

    if (empty($nama_proyek) || empty($gambar) || empty($deskripsi) || empty($harga) ) {
      echo "<script>alert('maaf, masih ada input yang kosong!')</script>";
    } else if (!is_numeric($_POST['harga'])) {
      echo "<script>alert('maaf, input harga harus berupa bilangan!')</script>";
    } else {
      $size = $_FILES['gambar']['size'];
      $mimetype = explode("/", $_FILES['gambar']['type']);
      $ext = $mimetype[1];

      if ($mimetype[0] != "image") {
        echo "<script>alert('maaf, file harus bertipe gambar!')</script>";
      } else if ($size > 5000000) {
        echo "<script>alert('maaf, ukuran file gambar terlalu besar!')</script>";
      } else {
        $nama_gambar = md5(time()."-".rand()).".".$ext;
        move_uploaded_file($gambar, $_SERVER['DOCUMENT_ROOT']."/resources/img/gambar-proyek/$nama_gambar");

        $sql = "INSERT INTO project VALUES (NULL, '$nama_proyek', '$deskripsi', '$harga', '$nama_gambar', 0)";
        mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

        if (isset($_SESSION['form_init'])) unset($_SESSION['form_init']);
        header("location: daftar-proyek.php");
        exit();
      }
    }
    
    $_SESSION['form_init'] = $_POST;
    echo "<script>location.href = ''</script>";
    exit();
  }

  function LoadFormInit($keyname) {
		if (isset($_SESSION['form_init'])) 
      return htmlspecialchars($_SESSION['form_init'][$keyname]);
		else
			return "";
	}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creatively - Buat Proyek</title>
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
      <li class="active"><a href="buat-proyek.php">
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
    <form method="post" enctype="multipart/form-data">
      <table>
        <tr>
          <th><label for="nama_proyek">Nama Proyek :</label></th>
          <td><input type="text" id="nama_proyek" name="nama_proyek" placeholder="masukan nama proyek" value="<?=LoadFormInit("nama_proyek")?>"></td>
        </tr>
        <tr>
          <th><label for="gambar">Gambar :</label></th>
          <td><input type="file" id="gambar" name="gambar" accept="image/*" title="upload gambar proyek"></td>
        </tr>
        <tr>
          <th><label for="deskripsi">Deskripsi :</label></th>
          <td><textarea id="deskripsi" name="deskripsi" placeholder="masukan deskripsi"><?=LoadFormInit("deskripsi")?></textarea></td>
        </tr>
        <tr>
          <th><label for="harga">Harga (Rp) :</label></th>
          <td><input type="text" id="harga" name="harga" placeholder="masukan harga" value="<?=LoadFormInit("harga")?>"></td>
        </tr>
        <tr>
          <th></th>
          <td>
            <button class="btn" name="buat_proyek" type="submit">Buat Proyek</button>
          </td>
        </tr>
      </table>
    </form> 
  </main>
</body>

</html>

<?php if (isset($_SESSION['form_init'])) unset($_SESSION['form_init']); ?>
