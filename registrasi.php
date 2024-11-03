<?php
	session_start();

	include("connect.php");

	if (isset($_SESSION['username'])) {
		header("location: ./");
		exit();
	}

	if (isset($_POST['daftar'])) {
		$username = mysqli_real_escape_string($conn, trim($_POST['username']));
		$email = mysqli_real_escape_string($conn, trim($_POST['email']));
		$nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
		$asal_kota = mysqli_real_escape_string($conn, trim($_POST['asal_kota']));
		$tanggal_lahir = mysqli_real_escape_string($conn, trim($_POST['tanggal_lahir']));
		$jenis_kelamin = mysqli_real_escape_string($conn, trim($_POST['jenis_kelamin']));
		$kontak = mysqli_real_escape_string($conn, trim($_POST['kontak']));
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		$ulangi_password = mysqli_real_escape_string($conn, $_POST['ulangi_password']);

		$sql = "SELECT username FROM user WHERE username='$username' LIMIT 1";
		$result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

		if (
			empty($username) || empty($email) || empty($nama) || 
			empty($asal_kota) || empty($tanggal_lahir) || empty($jenis_kelamin) || 
			empty($kontak) || empty($password) || empty($ulangi_password)
		) {
			echo "<script>alert('maaf, masih ada input yang kosong!')</script>";
		} else if (mysqli_num_rows($result)) {
			echo "<script>alert('maaf, username sudah ada yang menggunakan!')</script>";
		} else if ($password != $ulangi_password) {
			echo "<script>alert('maaf, password harus sama dengan ulangi password!')</script>";
		} else {
			$sql = "INSERT INTO user VALUES (
				'$username', '$password', 'customer', '$nama', '$jenis_kelamin', 
				'$asal_kota', '$tanggal_lahir', '$kontak', '$email'
			)";

			$result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

			if (isset($_SESSION['form_init'])) unset($_SESSION['form_init']);
			echo "<script>alert('akun anda berhasil dibuat, silahkan melakukan login!')</script>";
			echo "<script>location.href = 'login.php'</script>";
			exit();
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
	<title>Creatively - Registrasi</title>
	<link rel="icon" href="resources/img/favicon.png" type="image/png">
	<link rel="stylesheet" href="resources/css/login-registrasi.css">
</head>

<body>
	<div class="container">
		<img class="logo" src="resources/img/logo-creatively.png">

		<form method="post">
			<input type="text" name="username" placeholder="Username" value="<?=LoadFormInit('username')?>">
			<input type="text" name="email" placeholder="Email" value="<?=LoadFormInit('email')?>">
			<input type="text" name="nama" placeholder="Nama Lengkap" value="<?=LoadFormInit('nama')?>">
			<input type="text" name="asal_kota" placeholder="Asal Kota / Desa" value="<?=LoadFormInit('asal_kota')?>">
			<input type="date" name="tanggal_lahir" title="Tanggal Lahir" placeholder="Tanggal Lahir" value="<?=LoadFormInit('tanggal_lahir')?>">
			<select name="jenis_kelamin">
				<option value="" disabled <?php if(!LoadFormInit('jenis_kelamin')) echo "selected" ?>>Jenis Kelamin</option>
				<option value="laki-laki" <?php if(LoadFormInit('jenis_kelamin') == "laki-laki") echo "selected" ?>>laki-laki</option>
				<option value="perempuan" <?php if(LoadFormInit('jenis_kelamin') == "perempuan") echo "selected" ?>>perempuan</option>
			</select>
			<input type="text" name="kontak" placeholder="Kontak" value="<?=LoadFormInit('kontak')?>">
			<input type="password" name="password" placeholder="Password">
			<input type="password" name="ulangi_password" placeholder="Ulangi Password">
			<button type="submit" name="daftar">Daftar</button>
		</form>

		<p>Sudah punya akun? <a href="login.php">Klik untuk login!</a></p>
	</div>
</body>

</html>

<?php if (isset($_SESSION['form_init'])) unset($_SESSION['form_init']); ?>