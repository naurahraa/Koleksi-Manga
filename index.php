<?php
include 'db.php';

$result = mysqli_query($koneksi, "SELECT * FROM manga ORDER BY id ASC");

$mangas = [];
$totalNilai = 0;
$totalVolume = 0;
$totalJudul = 0;

while($row = mysqli_fetch_assoc($result)){
    $row['total'] = $row['volume'] * $row['harga'];
    $mangas[] = $row;

    $totalJudul++;
    $totalVolume += $row['volume'];
    $totalNilai += $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Koleksi Manga</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="floating-btn-container">
        <a href="add.php">Tambah Manga</a>
    </div>

    <h1>Koleksi Manga Mu</h1>

    <div class="card inventory-card">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-value"><?= $totalJudul ?></div>
                <div class="stat-label">Total Judul</div>
            </div>

            <div class="stat-card">
                <div class="stat-value"><?= $totalVolume ?></div>
                <div class="stat-label">Total Volume</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">Rp <?= number_format($totalNilai, 0, ',', '.') ?></div>
                <div class="stat-label">Total Nilai Koleksi</div>
            </div>
        </div>

        <div class="filter-row">
            <input type="text" id="searchInput" class="search-box"
                placeholder="Cari manga... (judul / genre / status)">
            
            <select id="genreFilter" class="filter-select">
                <option value="">Semua Genre</option>
                <option value="Shounen">Shounen</option>
                <option value="Shoujo">Shoujo</option>
                <option value="Seinen">Seinen</option>
                <option value="Isekai">Isekai</option>
                <option value="Comedy">Comedy</option>
                <option value="Romance">Romance</option>
                <option value="Action">Action</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Sport">Sport</option>
                <option value="Mystery">Mystery</option>
            </select>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th class="sortable" data-col="0">ID</th>
                    <th>Cover</th>
                    <th class="sortable" data-col="2">Judul</th>
                    <th class="sortable" data-col="3">Volume</th>
                    <th class="sortable" data-col="4">Status</th>
                    <th class="sortable" data-col="5">Genre</th>
                    <th class="sortable" data-col="6">Harga</th>
                    <th class="sortable" data-col="7">Total</th>
                    <th>Aksi</th>
                </tr>
                </thead>

                <tbody id="mangaTableBody">
                <?php if(count($mangas) === 0){ ?>
                    <tr>
                        <td colspan="9">Belum ada data manga.</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach($mangas as $m){ ?>
                    <tr>
                        <td><?= $m['id'] ?></td>

                        <td>
                            <?php if(!empty($m['cover'])){ ?>
                                <img src="<?= htmlspecialchars($m['cover']) ?>" class="cover-thumb">
                            <?php } else { ?>
                            <?php } ?>
                        </td>

                        <td class="title-cell"><?= htmlspecialchars($m['judul']) ?></td>
                        <td><?= $m['volume'] ?></td>
                        <td><?= htmlspecialchars($m['status']) ?></td>
                        <td><?= htmlspecialchars($m['genre']) ?></td>
                        <td>Rp <?= number_format($m['harga'],0,',','.') ?></td>
                        <td>Rp <?= number_format($m['total'],0,',','.') ?></td>

                        <td class="action-cell">
                            <a class="action-btn action-edit" href="edit.php?id=<?= $m['id'] ?>">Edit</a>
                            <button class="action-btn action-delete"
                                onclick="openDeleteModal(<?= $m['id'] ?>)">Hapus</button>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <div id="pagination" class="pagination"></div>
    </div>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3>Hapus Manga?</h3>
        <p>Apakah Anda yakin ingin menghapus manga ini?</p>

        <div class="modal-actions">
            <button class="btn btn-light" onclick="closeModal()">Batal</button>
            <a id="deleteConfirmBtn" class="btn btn-danger">Hapus</a>
        </div>
    </div>
</div>

<script>
let allRows = [];
let currentPage = 1;
const rowsPerPage = 5;
let currentSort = { index: null, asc: true };

document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('mangaTableBody');
    allRows = Array.from(tbody.querySelectorAll('tr'));

    document.getElementById('searchInput').addEventListener('keyup', () => {
        currentPage = 1;
        renderTable();
    });

    document.getElementById('genreFilter').addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });

    document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            if (currentSort.index === col) {
                currentSort.asc = !currentSort.asc;
            } else {
                currentSort.index = col;
                currentSort.asc = true;
            }
            renderTable();
        });
    });

    renderTable();
});

function getCellValue(row, index) {
    const text = row.children[index].textContent.trim();
    const numeric = text.replace(/[^\d]/g, '');
    if (numeric !== '' && !isNaN(numeric)) return parseInt(numeric, 10);
    return text.toLowerCase();
}

function renderTable() {
    const tbody = document.getElementById('mangaTableBody');
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const genreFilter = document.getElementById('genreFilter').value.toLowerCase();

    let filtered = allRows.filter(row => {
        const cells = row.children;
        const genre = cells[5].textContent.toLowerCase();
        const rowText = row.textContent.toLowerCase();

        if (genreFilter && genre !== genreFilter) return false;
        if (searchText && !rowText.includes(searchText)) return false;
        return true;
    });

    if (currentSort.index !== null) {
        filtered.sort((a, b) => {
            const va = getCellValue(a, currentSort.index);
            const vb = getCellValue(b, currentSort.index);
            if (va < vb) return currentSort.asc ? -1 : 1;
            if (va > vb) return currentSort.asc ? 1 : -1;
            return 0;
        });
    }

    const totalPages = Math.max(1, Math.ceil(filtered.length / rowsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * rowsPerPage;
    const pageRows = filtered.slice(start, start + rowsPerPage);

    tbody.innerHTML = '';

    if (filtered.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9">Belum ada data manga.</td>
            </tr>
        `;
        renderPagination(1);
        return;
    }

    pageRows.forEach(r => tbody.appendChild(r));
    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const container = document.getElementById('pagination');
    container.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'page-btn' + (i === currentPage ? ' active' : '');
        btn.addEventListener('click', () => {
            currentPage = i;
            renderTable();
        });
        container.appendChild(btn);
    }
}

function openDeleteModal(id) {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'flex';
    modal.classList.add('show');
    document.getElementById('deleteConfirmBtn').href = 'delete.php?id=' + id;
}

function closeModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 150);
}
</script>

</body>
</html>