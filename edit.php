<?php
include 'db.php';

$id = $_GET['id'];
$q = mysqli_query($koneksi, "SELECT * FROM manga WHERE id='$id'");
$m = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Manga</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<h1>Edit Manga</h1>

<div class="card form-card">
<form method="post">
    <label>Judul Manga</label>
    <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($m['judul']) ?>" required>

    <label>Volume Dimiliki</label>
    <input type="number" name="volume" min="1" value="<?= $m['volume'] ?>" required>

    <label>Status</label>
    <select name="status" required>
        <option <?= $m['status']=="On Going"?"selected":"" ?>>On Going</option>
        <option <?= $m['status']=="Completed"?"selected":"" ?>>Completed</option>
        <option <?= $m['status']=="Dropped"?"selected":"" ?>>Dropped</option>
    </select>

    <label>Genre</label>
    <select name="genre" required>
        <option <?= $m['genre']=="Action"?"selected":"" ?>>Action</option>
        <option <?= $m['genre']=="Romance"?"selected":"" ?>>Romance</option>
        <option <?= $m['genre']=="Comedy"?"selected":"" ?>>Comedy</option>
        <option <?= $m['genre']=="Drama"?"selected":"" ?>>Drama</option>
        <option <?= $m['genre']=="Fantasy"?"selected":"" ?>>Fantasy</option>
        <option <?= $m['genre']=="Shounen"?"selected":"" ?>>Shounen</option>
        <option <?= $m['genre']=="Seinen"?"selected":"" ?>>Seinen</option>
        <option <?= $m['genre']=="Slice of Life"?"selected":"" ?>>Slice of Life</option>
        <option <?= $m['genre']=="Horror"?"selected":"" ?>>Horror</option>
        <option <?= $m['genre']=="Isekai"?"selected":"" ?>>Isekai</option>
        <option <?= $m['genre']=="Sport"?"selected":"" ?>>Sport</option>
        <option <?= $m['genre']=="Mystery"?"selected":"" ?>>Mystery</option>
        <option <?= $m['genre']=="Lainnya"?"selected":"" ?>>Lainnya</option>
    </select>

    <label>Harga per Volume (Rp)</label>
    <input type="number" name="harga" min="0" value="<?= $m['harga'] ?>" required>

    <label>URL Cover Manga</label>
    <input type="url" id="cover" name="cover" value="<?= htmlspecialchars($m['cover']) ?>">

    <img id="preview"
        src="<?= $m['cover'] ?>"
        style="width:130px; margin-top:12px; border-radius:6px; <?= empty($m['cover'])?'display:none;':'' ?>">

    <div class="form-btn-group" style="margin-top:18px;">
        <a href="index.php" class="btn btn-light">Batal</a>
        <button name="update" class="btn btn-primary">Update</button>
    </div>
</form>
</div>

<script>
document.getElementById("cover").addEventListener("input", function () {
    let url = this.value.trim();
    let prev = document.getElementById("coverPreview");

    if (url === "") {
        prev.style.display = "none";
        return;
    }

    prev.src = url;
    prev.style.display = "block";
});
</script>
</body>
</html>

<?php
if(isset($_POST['update'])){
    mysqli_query($koneksi, "
        UPDATE manga SET
            cover='".$_POST['cover']."',
            judul='".$_POST['judul']."',
            volume='".$_POST['volume']."',
            status='".$_POST['status']."',
            genre='".$_POST['genre']."',
            harga='".$_POST['harga']."'
        WHERE id='$id'
    ");

    header("Location: index.php");
}
?>