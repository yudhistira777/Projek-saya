<?php
	session_start();

	include("connect.php");

	if (isset($_SESSION['username'])) {
		header("location: ./");
		exit();
	}

	if (isset($_POST['login'])) {
		$username = mysqli_real_escape_string($conn, trim($_POST['username']));
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		if (empty($username) || empty($password)) {
			echo "<script>alert('maaf, username atau password tidak boleh kosong')</script>";
			echo "<script>location.href = ''</script>";
		} else {
			$sql = "SELECT username, nama_lengkap, type FROM user WHERE username='$username' AND password='$password' LIMIT 1";
			$result = mysqli_query($conn, $sql) or die("query error: " . mysqli_error($conn));

			if (mysqli_num_rows($result)) {
				$user = mysqli_fetch_assoc($result);
				$_SESSION['username'] = $user['username'];
				$_SESSION['type'] = $user['type'];
				
				$nama = $_SESSION['nama'] = $user['nama_lengkap'];
				if ($_SESSION['type'] == "admin") $nama .= " (admin)";

				header("location: ./");
			} else {
				echo "<script>alert('maaf, username atau password salah!')</script>";
				echo "<script>location.href = ''</script>";
			}
		}

		exit();
	}
?>

<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Creatively - Login</title>
	<link rel="icon" href="resources/img/favicon.png" type="image/png">
	<link rel="stylesheet" href="resources/css/login-registrasi.css">
</head>

<body>
	<div class="container">
		<img class="logo" src="resources/img/logo-creatively.png">

		<form method="post">
			<input name="username" type="text" placeholder="Username">
			<input name="password" type="password" placeholder="Password">
			<button name="login" type="submit">Login</button>
		</form>

		<p>Belum punya akun? <a href="registrasi.php">Klik untuk daftar!</a></p>
	</div>
</body>

</html>