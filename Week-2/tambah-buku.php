<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Buku - Toko Buku Musfiqoh</title>
  <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2>📚 BookAdmin Kelompok 6</h2>
    <ul>
      <li><a href="dashboard.php">🏠 Dashboard</a></li>
      <li><a href="tambah-buku.php">✨ Tambah Buku</a></li>
      <li><a href="dashboard.php#catalog">📖 Katalog</a></li>
      <li><a href="dashboard.php#charts">📊 Statistik</a></li>
      <li><a href="#">⚙️ Pengaturan</a></li>
    </ul>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <header class="header">
      <h1>Tambah Buku Baru</h1>
      <div class="profile">
        <span>🔔</span>
        <span>👤 Admin</span>
      </div>
    </header>

    <!-- Form Tambah Buku -->
    <section class="tambah-buku">
      <h2>Tambah Buku ke Daftar</h2>
      <form id="bookForm">
        <label>Judul*</label>
        <input type="text" id="judul" required>

        <label>Penulis*</label>
        <input type="text" id="penulis" required>

        <label>Genre*</label>
        <input type="text" id="genre" required>

        <label>Harga*</label>
        <input type="number" id="harga" required min="1">

        <label>Cover (URL Gambar)</label>
        <input type="url" id="cover">

        <button type="submit">Tambah Buku</button>
      </form>

      <table id="bookTable">
        <thead>
          <tr>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Genre</th>
            <th>Harga</th>
            <th>Cover</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data buku ditambahkan lewat JS -->
        </tbody>
      </table>
    </section>
  </div>

  <script src="./js/chart.js" defer></script>
</body>
</html>
