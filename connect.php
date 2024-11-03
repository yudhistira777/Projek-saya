<?php
  $host = "db1.creatively.id";
  $username = "root";
  $password = "123456";
  $dbname = "db_creatively";

  $conn = mysqli_connect($host, $username, $password, $dbname)
  or die("connect to database error: " . mysqli_connect_error($conn));
?>