<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Toko Buku Musfiqoh</title>
  <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2>📚 BookAdmin Kelompok 6</h2>
    <ul>
      <li>🏠 Dashboard</li>
      <li><a href="tambah-buku.php">✨ Tambah Buku</a></li>
      <li>📖 Katalog</li>
      <li>📊 Statistik</li>
      <li>⚙️ Pengaturan</li>
    </ul>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Header -->
    <header class="header">
      <h1>Dashboard Admin Toko Buku</h1>
      <div class="profile">
        <span>🔔</span>
        <span>👤 Admin</span>
      </div>
    </header>

    <!-- Info Cards -->
    <section class="cards">
      <div class="card pink">📚 120 Buku Baru</div>
      <div class="card purple">💰 5.320 Penjualan</div>
      <div class="card blue">👥 1.245 Member</div>
      <div class="card gradient">📦 890 Stok Baru</div>
    </section>

    <!-- Grafik Dummy -->
    <section class="charts">
      <div class="chart-box">
        <h3 style="margin-bottom: 50px;">Tren Penjualan Mingguan</h3>
        <canvas id="salesChart"></canvas>
      </div>
      <div class="chart-box">
        <h3>Distribusi Genre</h3>
        <canvas id="genreChart"></canvas>
      </div>
      <div class="chart-box">
        <h3 style="margin-bottom: 50px;">Buku Terlaris</h3>
        <canvas id="topBooksChart"></canvas>
      </div>
    </section>

    <!-- Katalog Buku -->
    <section class="catalog">
      <h2>Katalog Buku</h2>
      <div class="book-grid">
        <div class="book-card">
          <img src="./img/images1.jpeg" alt="Book 1">
          <h3>Cantik Itu Luka</h3>
          <p>Eka Kurniawan</p>
          <h2>Fiksi</h2>
          <span>Rp 75.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images2.jpeg" alt="Book 2">
          <h3>Laut Bercerita</h3>
          <p>Leila S. Chudori</p>
          <h2>Fiksi</h2>
          <span>Rp 85.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images3.jpeg" alt="Book 3">
          <h3>Tentang Kamu</h3>
          <p>Tere Liye</p>
          <h2>Fiksi</h2>
          <span>Rp 95.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images4.jpeg" alt="Book 4">
          <h3>As Long As The Lemon Trees Grow</h3>
          <p>Zoulfa Katouh</p>
          <h2>Romance</h2>
          <span>Rp 105.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images5.jpeg" alt="Book 5">
          <h3>Atomic Habits</h3>
          <p>James Clear</p>
          <h2>Sels Improvement</h2>
          <span>Rp 115.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images6.jpeg" alt="Book 6">
          <h3>Lovers By The Sea</h3>
          <p>Mimi Daisy</p>
          <h2>Romance</h2>
          <span>Rp 125.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images7.jpeg" alt="Book 7">
          <h3>Algoritma & Pemrograman</h3>
          <p>Rinaldi Munir</p>
          <h2>Teknologi</h2>
          <span>Rp 135.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images8.jpeg" alt="Book 8">
          <h3>Algoritma Dan Struktur Data</h3>
          <p>Meidyan Permata Putri, dkk</p>
          <h2>Teknologi</h2>
          <span>Rp 145.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images9.jpeg" alt="Book 9">
          <h3>Kalkulus</h3>
          <p>Varbeg Purcell Rigdon</p>
          <h2>Edukasi</h2>
          <span>Rp 155.000</span>
        </div>
        <div class="book-card">
          <img src="./img/images10.jpeg" alt="Book 10">
          <h3>Belajar Pemrograman Web</h3>
          <p>Kristianto Haryodi</p>
          <h2>Teknologi</h2>
          <span>Rp 165.000</span>
        </div>
      </div>
    </section>


  </div>
  <script src="./js/chart.js" defer></script>
</body>
</html>
