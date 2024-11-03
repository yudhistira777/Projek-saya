<?php
	session_start();

	include("connect.php");

  if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
  }

  $nama_akun = $_SESSION['nama'];
  if ($_SESSION['type'] == "admin") $nama_akun .= " (admin)";

  if (isset($_GET['id'])) {
    $id_transaksi = mysqli_real_escape_string($conn, trim($_GET['id']));
    $username = mysqli_real_escape_string($conn, trim($_SESSION['username']));

    if ($_SESSION['type'] == "admin") {
      $sql = "SELECT * FROM pemesanan 
        INNER JOIN project ON pemesanan.id_project=project.id_project 
        WHERE id_transaksi='$id_transaksi' LIMIT 1";
    } else {
      $sql = "SELECT * FROM pemesanan 
        INNER JOIN project ON pemesanan.id_project=project.id_project 
        WHERE id_transaksi='$id_transaksi' AND username='$username' LIMIT 1";
    }

    $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
    $pesanan = mysqli_fetch_assoc($result);
  } else {
    if ($_SESSION['type'] == "admin") {
      header("location: daftar-pesanan.php");
    } else {
      header("location: pesanan-anda.php");
    }
    exit();
  }

  if (isset($_POST['perbarui']) && $_SESSION['type'] == "admin") {
    $status = mysqli_real_escape_string($conn, trim($_POST['status']));
    $catatan = mysqli_real_escape_string($conn, trim($_POST['catatan']));
    $file_hasil = $_FILES['upload_hasil']['tmp_name'];
    $waktu_pembaruan = date("Y-m-d H:i:s");

    if ($status == "selesai" && empty($file_hasil)) {
      echo "<script>alert('maaf, anda perlu mengupload file hasil untuk mengatur status menjadi selesai!')</script>";
      echo "<script>history.back()</script>";
      exit();
    } else if ($status != "selesai" && !empty($file_hasil)) {
      echo "<script>alert('maaf, anda perlu merubah status menjadi selesai untuk bisa mengupload hasil!')</script>";
      echo "<script>history.back()</script>";
      exit();
    } else if ($status == "selesai" && !empty($file_hasil)) {
      $size = $_FILES['upload_hasil']['size'];
      $mimetype = explode("/", $_FILES['upload_hasil']['type']);
      $ext = $mimetype[1];

      if ($size > 5000000) {
        echo "<script>alert('maaf, ukuran file gambar terlalu besar!')</script>";
        echo "<script>history.back()</script>";
        exit();
      }

      if (!is_null($pesanan['file_hasil'])) {
        unlink($_SERVER['DOCUMENT_ROOT']."/resources/file-hasil/".$pesanan['file_hasil']);
      } 

      $nama_file = "proyek-" . $pesanan['id_transaksi'] . "." . $ext;
      move_uploaded_file($file_hasil, $_SERVER['DOCUMENT_ROOT']."/resources/file-hasil/$nama_file");

      $sql = "UPDATE pemesanan SET status='$status', file_hasil='$nama_file' WHERE id_transaksi='$id_transaksi' LIMIT 1";
      mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
    } else {
      $sql = "UPDATE pemesanan SET status='$status' WHERE id_transaksi='$id_transaksi' LIMIT 1";
      mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
    }

    if ($status != $pesanan['status'] || !empty($catatan)) {
      if (empty($catatan)) {
        $sql = "INSERT INTO history VALUES (NULL, '$status', NULL, '$waktu_pembaruan', '$id_transaksi')";
      } else {
        $sql = "INSERT INTO history VALUES (NULL, '$status', '$catatan', '$waktu_pembaruan', '$id_transaksi')";
      }

      mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
      echo "<script>alert('Data pesanan berhasil diperbarui!')</script>";
    } else {
      echo "<script>alert('Anda tidak melakukan perubahan!')</script>";
    }

    echo "<script>history.back()</script>";
    exit();
  }
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creatively - Detail Pesanan</title>
  <link rel="icon" href="resources/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="resources/css/style.css">
  <link rel="stylesheet" href="resources/css/form-tabel.css">
  <link rel="stylesheet" href="resources/css/detail-proyek.css">
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
<?php if ($_SESSION['type'] == "admin") { ?>
      <li><a href="buat-proyek.php">
        <i class="ti ti-file-plus"></i>
        <span>Buat Proyek</span>
      </a></li>
      <li><a href="daftar-proyek.php">
        <i class="ti ti-files"></i>
        <span>Daftar Proyek</span>
      </a></li>
      <li class="active"><a href="daftar-pesanan.php">
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

  <main class="detail-pemesanan">
<?php if (!mysqli_num_rows($result)) { ?>
    <p class="not-available">Maaf, data tidak tersedia!</p>
<?php } else { ?>
    <form method="post" enctype="multipart/form-data">
      <table>
        <tr>
          <th><label>ID Pesanan :</label></th>
          <td><div class="field"><?=htmlspecialchars($pesanan['id_transaksi'])?></div></td>
        </tr>
<?php if ($_SESSION['type'] == "admin") { ?>
        <tr>
          <th><label>Username :</label></th>
          <td><div class="field"><a href="detail-akun.php?username=<?=htmlspecialchars($pesanan['username'])?>"><?=htmlspecialchars($pesanan['username'])?></a></div></td>
        </tr>
<?php } ?>
        <tr>
          <th><label>ID Proyek :</label></th>
          <td><div class="field"><a href="detail-proyek.php?id=<?=htmlspecialchars($pesanan['id_project'])?>"><?=htmlspecialchars($pesanan['id_project'])?></a></div></td>
        </tr>
        <tr>
          <th><label>Waktu Pesan :</label></th>
          <td><div class="field"><?=date_format(date_create($pesanan['tanggal_pembelian']), "d/m/Y H:i")?></div></td>
        </tr>
        <tr>
          <th><label>Nama Proyek :</label></th>
          <td><div class="field"><?=htmlspecialchars($pesanan['nama_project'])?></div></td>
        </tr>
        <tr>
          <th><label>Harga (Rp) :</label></th>
          <td><div class="field">Rp<?=number_format($pesanan['harga_project'], 0, ',', '.')?></div></td>
        </tr>
        <tr>
          <th><label>Gambar :</label></th>
          <td><a class="img-wrapper" href="resources/img/gambar-proyek/<?=htmlspecialchars($pesanan['gambar'])?>" target="_blank">
            <img src="resources/img/gambar-proyek/<?=htmlspecialchars($pesanan['gambar'])?>" alt="<?=htmlspecialchars($pesanan['nama_project'])?>">
          </a></td>
        </tr>
        <tr>
          <th><label>Deskripsi :</label></th>
          <td><div class="field-area"><?=htmlspecialchars($pesanan['deskripsi_project'])?></div></td>
        </tr>
        <tr>
          <th><label>Permintaan :</label></th>
          <td><div class="field-area"><?=htmlspecialchars($pesanan['keterangan'])?></div></td>
        </tr>
<?php if ($_SESSION['type'] == "admin") { ?>        
        <tr>
          <th><label>Bukti Pembayaran :</label></th>
          <td><a class="icon-btn upload-gambar" href="resources/img/bukti-pembayaran/<?=htmlspecialchars($pesanan['bukti_pembayaran'])?>" title="unduh bukti pembayaran" download><i class="ti ti-photo-down"></i><span>Unduh Gambar</span></a></td>
        </tr>
        <tr>
          <th><label for="gambar">Upload Hasil :</label></th>
          <td><input type="file" id="upload_hasil" name="upload_hasil" title="upload hasil proyek"></td>
        </tr>
        <tr>
          <th><label for="status">Status :</label></th>
          <td>
            <select id="status" name="status">
              <option value="belum dikonfirmasi" <?php if ($pesanan['status'] == "semua") echo "selected" ?> >Belum Dikonfirmasi</option>
              <option value="sedang diproses" <?php if ($pesanan['status'] == "sedang diproses") echo "selected" ?> >Sedang Diproses</option>
              <option value="sedang direvisi" <?php if ($pesanan['status'] == "sedang direvisi") echo "selected" ?>>Sedang Direvisi</option>
              <option value="selesai" <?php if ($pesanan['status'] == "selesai") echo "selected" ?>>Selesai</option>
              <option value="dibatalkan" <?php if ($pesanan['status'] == "dibatalkan") echo "selected" ?>>Dibatalkan</option>
            </select>
          </td>
        </tr>
        <tr>
          <th><label for="catatan">Catatan :</label></th>
          <td><input type="text" id="catatan" name="catatan" placeholder="tambah catatan (opsional)"></td>
        </tr>
<?php } else { ?>
        <tr>
          <th><label>Status :</label></th>
          <td><div class="field"><?=htmlspecialchars($pesanan['status'])?></div></td>
        </tr>
<?php } ?>
        <tr>
          <th></th>
          <td>
<?php if ($_SESSION['type'] == "admin") { ?>
            <a class="btn" href="daftar-pesanan.php">Kembali</a>
<?php } else { ?>
            <a class="btn" href="pesanan-anda.php">Kembali</a>
<?php } ?>
<?php if ($pesanan['status'] == "selesai" && $pesanan['file_hasil']) { ?>
            <a class="icon-btn unduh-file" href="resources/file-hasil/<?=htmlspecialchars($pesanan['file_hasil'])?>" title="unduh file hasil" download><i class="ti ti-file-download"></i><span>Unduh File</span></a>
<?php } ?>
<?php if ($_SESSION['type'] == "admin") { ?>
            <button class="btn" name="perbarui" type="submit">Perbarui</button>
<?php } ?>
          </td>
        </tr>
      </table>

      <div class="info">
        <hr>

        <p class="history-title">Riwayat Status Pemesanan: </p>
        <ul>
<?php 
  $sql = "SELECT * FROM history WHERE id_transaksi='$id_transaksi'";
  $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

  while ($history = mysqli_fetch_assoc($result)) { ?>
          <li><?=htmlspecialchars($history['status'])?> (<?=date_format(date_create($history['waktu']), "d/m/Y H:i")?>) <?=(!empty($history["catatan"])) ? (": ".htmlspecialchars($history['catatan'])) : "" ?></li>
<?php } ?>
        </ul>
<?php if ($_SESSION['type'] == "customer") { ?>
        <hr>
          
        <p class="catatan">
          *) Silahkan hubungi via WhatsApp dibawah untuk menkonsultasikan 
          dan memantau perkembangan proyek pesanan anda. Kemudian anda juga 
          dapat melakukan pengajuan revisi ataupun pembatalan pesanan.
        </p>

        <p class="catatan">
          *) Kami menvalidasi identitas WhatsApp anda menggunakan nomor 
          kontak yang tersimpan di akun anda. Pastikan gunakan nomor 
          tersebut untuk menghubungi Kami. Anda juga dapat merubah nomor 
          kontak anda pada menu Akun.
        </p>

        <a class="icon-btn wa-btn" href="#"><i class="ti ti-brand-whatsapp"></i><span>Chat via WhatApp</span></a>
<?php } ?>
      </div>
    </form>
<?php } ?>
  </main>
</body>

</html>
