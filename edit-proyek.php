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

  if (isset($_GET['id'])) {
    $id_proyek = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM project WHERE id_project='$id_proyek' AND deleted=0 LIMIT 1";
    $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
    $proyek = mysqli_fetch_assoc($result);
  } else if (isset($_GET['hapusproyek'])) {
    $id_proyek = mysqli_real_escape_string($conn, $_GET['hapusproyek']);
    $sql = "UPDATE project SET deleted=1 WHERE id_project='$id_proyek'";
    mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
    header("location: daftar-proyek.php");
    exit();
  } else {
    header("location: daftar-proyek.php");
    exit();
  }

  if (isset($_POST['perbarui'])) {
    $nama_proyek = mysqli_real_escape_string($conn, trim($_POST['nama_proyek']));
    $ubah_gambar = $_FILES['ubah_gambar']['tmp_name'];
    $deskripsi = mysqli_real_escape_string($conn, trim($_POST['deskripsi']));
    $harga = mysqli_real_escape_string($conn, trim($_POST['harga']));

    if (empty($nama_proyek) || empty($deskripsi) || empty($harga) ) {
      echo "<script>alert('maaf, masih ada input yang kosong!')</script>";
    } else if (!is_numeric($_POST['harga'])) {
      echo "<script>alert('maaf, input harga harus berupa bilangan!')</script>";
    } else if (!empty($ubah_gambar)) {
      $size = $_FILES['ubah_gambar']['size'];
      $mimetype = explode("/", $_FILES['ubah_gambar']['type']);
      $ext = $mimetype[1];

      if ($mimetype[0] != "image") {
        echo "<script>alert('maaf, file harus bertipe gambar!')</script>";
      } else if ($size > 5000000) {
        echo "<script>alert('maaf, ukuran file gambar terlalu besar!')</script>";
      } else {
        unlink($_SERVER['DOCUMENT_ROOT']."/resources/img/gambar-proyek/".$proyek['gambar']);

        $nama_gambar = md5(time()."-".rand()).".".$ext;
        move_uploaded_file($ubah_gambar, $_SERVER['DOCUMENT_ROOT']."/resources/img/gambar-proyek/$nama_gambar");

        $sql = "UPDATE project SET nama_project='$nama_proyek', 
          gambar='$nama_gambar', deskripsi_project='$deskripsi', 
          harga_project='$harga' WHERE id_project='$id_proyek' AND deleted=0 LIMIT 1";

        mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
      }
    } else {
      $sql = "UPDATE project SET nama_project='$nama_proyek', 
        deskripsi_project='$deskripsi', harga_project='$harga' 
        WHERE id_project='$id_proyek' AND deleted=0 LIMIT 1";

      mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
    }

    echo "<script>location.href = ''</script>";
    exit();
  }
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creatively - Edit Proyek</title>
  <link rel="icon" href="resources/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="resources/css/style.css">
  <link rel="stylesheet" href="resources/css/form-tabel.css">
  <link rel="stylesheet" href="resources/tabler-icons/tabler-icons.min.css">
  <script src="resources/js/script.js" type="text/javascript"></script>
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
<?php if (!mysqli_num_rows($result)) { ?>
    <p class="not-available">Maaf, data tidak tersedia!</p>
<?php } else { ?>
    <form method="post" enctype="multipart/form-data">
      <table>
        <tr>
          <th><label for="id_proyek">ID Proyek :</label></th>
          <td><input type="text" id="id_proyek" value="<?=htmlspecialchars($proyek['id_project'])?>" disabled></td>
        </tr>
        <tr>
          <th><label for="nama_proyek">Nama Proyek :</label></th>
          <td><input type="text" id="nama_proyek" name="nama_proyek" placeholder="masukan nama proyek" value="<?=htmlspecialchars($proyek['nama_project'])?>"></td>
        </tr>
        <tr>
          <th><label for="gambar">Gambar :</label></th>
          <td><a class="img-wrapper" href="resources/img/gambar-proyek/<?=htmlspecialchars($proyek['gambar'])?>" target="_blank">
            <img src="resources/img/gambar-proyek/<?=htmlspecialchars($proyek['gambar'])?>" alt="<?=htmlspecialchars($proyek['nama_project'])?>">
          </a></td>
        </tr>
        <tr>
          <th><label for="ubah_gambar">Ubah Gambar :</label></th>
          <td><input type="file" id="ubah_gambar" name="ubah_gambar" accept="image/*" title="upload gambar proyek"></td>
        </tr>
        <tr>
          <th><label for="deskripsi">Deskripsi :</label></th>
          <td><textarea id="deskripsi" name="deskripsi" placeholder="masukan deskripsi"><?=htmlspecialchars($proyek['deskripsi_project'])?></textarea></td>
        </tr>
        <tr>
          <th><label for="harga">Harga (Rp) :</label></th>
          <td><input type="text" id="harga" name="harga" placeholder="masukan harga" value="<?=htmlspecialchars($proyek['harga_project'])?>"></td>
        </tr>
        <tr>
          <th></th>
          <td>
            <a class="btn" href="daftar-proyek.php">Kembali</a>
            <button class="btn" name="perbarui" type="submit">Perbarui</button>
            <button 
              class="btn red" name="hapus_proyek" type="button" 
              onclick="actionConfirm('anda yakin ingin menghapus proyek ini?', '?hapusproyek=<?=htmlspecialchars($proyek['id_project'])?>')"
            >Hapus Proyek</button>
          </td>
        </tr>
      </table>
    </form> 
<?php } ?>
  </main>
</body>

</html>
