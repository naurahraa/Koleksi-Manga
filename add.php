<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Manga</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<h1>Tambah Manga</h1>

<div class="card form-card">
<form method="post">
    <label>Judul Manga</label>
    <input type="text" id="judul" name="judul" placeholder="Contoh: Jujutsu Kaisen" required>

    <label>Volume Dimiliki</label>
    <input type="number" name="volume" min="1" placeholder="Contoh: 12" required>

    <label>Status</label>
    <select name="status" required>
        <option value="">-- Pilih Status --</option>
        <option value="On Going">On Going</option>
        <option value="Completed">Completed</option>
        <option value="Dropped">Dropped</option>
    </select>

    <label>Genre</label>
    <select name="genre" required>
        <option value="">-- Pilih Genre --</option>
        <option value="Action">Action</option>
        <option value="Romance">Romance</option>
        <option value="Comedy">Comedy</option>
        <option value="Drama">Drama</option>
        <option value="Fantasy">Fantasy</option>
        <option value="Shounen">Shounen</option>
        <option value="Seinen">Seinen</option>
        <option value="Slice of Life">Slice of Life</option>
        <option value="Horror">Horror</option>
        <option value="Isekai">Isekai</option>
        <option value="Sport">Sport</option>
        <option value="Mystery">Mystery</option>
        <option value="Lainnya">Lainnya</option>
    </select>

    <label>Harga per Volume (Rp)</label>
    <input type="number" name="harga" min="0" placeholder="Contoh: 60000" required>

    <label>URL Cover Manga (opsional)</label>
    <input type="url" id="cover" name="cover" placeholder="https://contoh.com/cover.jpg">

    <img id="coverPreview" src="" style="width:130px; margin-top:10px; border-radius:6px; display:none;">

    <div class="form-btn-group">
        <a href="index.php" class="btn btn-light">Batal</a>
        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
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
if(isset($_POST['submit'])){
    mysqli_query($koneksi, "
        INSERT INTO manga (cover, judul, volume, status, genre, harga)
        VALUES(
            '".$_POST['cover']."',
            '".$_POST['judul']."',
            '".$_POST['volume']."',
            '".$_POST['status']."',
            '".$_POST['genre']."',
            '".$_POST['harga']."'
        )
    ");

    header("Location: index.php");
}
?>