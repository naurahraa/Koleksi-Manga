<?php
include 'db.php';

$id = $_GET['id'];

mysqli_query($koneksi, "DELETE FROM manga WHERE id='$id'");

header("location:index.php");
?>