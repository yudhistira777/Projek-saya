<?php
	session_start();

	include("connect.php");

	if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit();
  }

  $nama_akun = $_SESSION['nama'];
  if ($_SESSION['type'] == "admin") $nama_akun .= " (admin)";

  if (isset($_POST['pesan']) && $_SESSION['type'] == "customer") {
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    $id_proyek = mysqli_real_escape_string($conn, trim($_POST['id_proyek']));
    $waktu_pembelian = date("Y-m-d H:i:s");
    $permintaan = mysqli_real_escape_string($conn, trim($_POST['permintaan']));
    $status = "belum dikonfirmasi";
    $bukti_pembayaran = $_FILES['bukti_pembayaran']['tmp_name'];

    if (empty($id_proyek) || empty($permintaan) || empty($bukti_pembayaran)) {
      echo "<script>alert('maaf, masih ada input yang kosong!')</script>";
    } else {
      $size = $_FILES['bukti_pembayaran']['size'];
      $mimetype = explode("/", $_FILES['bukti_pembayaran']['type']);
      $ext = $mimetype[1];

      if ($mimetype[0] != "image") {
        echo "<script>alert('maaf, file harus bertipe gambar!')</script>";
      } else if ($size > 5000000) {
        echo "<script>alert('maaf, ukuran file gambar terlalu besar!')</script>";
      } else {
        $nama_gambar = md5(time()."-".rand()).".".$ext;
        move_uploaded_file($bukti_pembayaran, $_SERVER['DOCUMENT_ROOT']."/resources/img/bukti-pembayaran/$nama_gambar");

        $sql = "INSERT INTO pemesanan VALUES (NULL, '$username', '$id_proyek', '$waktu_pembelian', '$permintaan', '$status', '$nama_gambar', NULL)";
        mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
        $id_transaksi = mysqli_insert_id($conn);

        $sql = "INSERT INTO history VALUES (NULL, '$status', NULL, '$waktu_pembelian', '$id_transaksi')";
        mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

        if (isset($_SESSION['form_init'])) unset($_SESSION['form_init']);
        header("location: pesanan-anda.php");
        exit();
      }
    }

    $_SESSION['form_init'] = $_POST;
    echo "<script>location.href = ''</script>";
    exit();
  }

  if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, trim($_GET['id']));
    $sql = "SELECT * FROM project WHERE id_project='$id' AND deleted=0 LIMIT 1";
    $result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));
    $proyek = mysqli_fetch_assoc($result);
  } else {
    header("location: ./");
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
  <title>Creatively - <?=($proyek) ? htmlspecialchars($proyek['nama_project']) : "Detail Proyek" ?></title>
  <link rel="icon" href="resources/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="resources/css/style.css">
  <link rel="stylesheet" href="resources/css/detail-proyek.css">
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
      <li class="active"><a href="./">
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
    <div class="container">
<?php if (!mysqli_num_rows($result)) { ?>
      <p class="not-available">Maaf, data tidak tersedia!</p>
<?php } else { ?>
      <a id="gambar-proyek" href="resources/img/gambar-proyek/<?=htmlspecialchars($proyek['gambar'])?>" target="_blank">
        <img src="resources/img/gambar-proyek/<?=htmlspecialchars($proyek['gambar'])?>" alt="<?=htmlspecialchars($proyek['nama_project'])?>">
      </a>
     
      <h1 id="nama-proyek"><?=htmlspecialchars($proyek['nama_project'])?></h1>
      <p id="id-proyek">ID Proyek: <?=htmlspecialchars($proyek['id_project'])?></p>
      <p id="deskripsi"><?=htmlspecialchars($proyek['deskripsi_project'])?></p>

      <hr>

      <p class="catatan">
        *) Silahkan tuliskan deskripsi dari desain yang 
        anda buat berdasarkan gambar di atas.
      </p>

      <p class="catatan">
        *) Permintaan yang anda tuliskan, tidak dapat 
        dirubah kembali ketika sudah melakukan pemesanan, 
        jadi jika ingin melakukan tambahan permintaan anda 
        bisa menghubungi admin melalui WhatsApp.
      </p>

<?php if ($_SESSION['type'] == "customer") { ?>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_proyek" value="<?=htmlspecialchars($proyek['id_project'])?>">
        <textarea id="permintaan" name="permintaan" placeholder="Tuliskan permintaan anda disini...."><?=LoadFormInit('permintaan')?></textarea>
        <p id="harga">Pembayaran Sebesar Rp<?=number_format($proyek['harga_project'], 0, ',', '.')?></p>
        <p class="catatan">
          *) Silahkan Bayar ke Rekening <b>40078************ (Bank ***)</b> 
          kemudian upload bukti transaksi dibawah ini.
        </p>
        <input type="file" name="bukti_pembayaran" accept="image/*" title="upload bukti transaksi">
        <a class="btn" href="#!" onclick="history.back()">Kembali</a>
        <button class="btn" type="submit" name="pesan">Pesan Sekarang</button>
      </form>
<?php } else { ?>
      <form>
        <p id="harga">Pembayaran Sebesar Rp50.000</p>
        <a class="btn" href="./" onclick="history.back()">Kembali</a>
      </form>
<?php } ?>

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
  </main>
</body>

</html>

<?php if (isset($_SESSION['form_init'])) unset($_SESSION['form_init']); ?>
