document.addEventListener('DOMContentLoaded', function () {
  // ================== Grafik ==================
  const ctx = document.getElementById('salesChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [{
          label: 'Jumlah Penjualan',
          data: [120, 190, 150, 220, 300, 250, 400],
          borderColor: '#F4A460',
          backgroundColor: 'rgba(184, 178, 166, 0.2)',
          fill: true,
          tension: 0.3,
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
      }
    });
  }

  const ctxGenre = document.getElementById('genreChart');
  if (ctxGenre) {
    new Chart(ctxGenre, {
      type: 'pie',
      data: {
        labels: [
          'Sastra','Romance','Teknologi','Self Improvement','Mystery',
          'Fantasi','Sejarah','Biografi','Horror','Travel',
          'Psikologi','Komedi','Fiksi Ilmiah','Agama','Motivasi'
        ],
        datasets: [{
          label: 'Jumlah Buku',
          data: [2500,2200,3000,2100,2300,2600,2400,2000,2200,2100,2500,2000,2300,2200,2600],
          backgroundColor: [
            '#A0522D','#CD853F','#D2B48C','#F4A460','#DEB887',
            '#BC8F8F','#8B4513','#FFA07A','#FF7F50','#E9967A',
            '#C76114','#F5DEB3','#8B0000','#CD5C5C','#FFE4B5'
          ],
          borderWidth: 1
        }]
      },
      options: { 
        responsive: true, 
        plugins: { legend: { position: 'bottom' } } 
      }
    });
  }

  const ctxTopBooks = document.getElementById('topBooksChart');
  if (ctxTopBooks) {
    new Chart(ctxTopBooks, {
      type: 'bar',
      data: {
        labels: ['Laut Bercerita','Salvation of a Saint','Secret of Divine Love','Laskar Pelangi','Everybody Lies'],
        datasets: [{
          label: 'Jumlah Penjualan',
          data: [420, 380, 350, 320, 300],
          backgroundColor: [
            '#A0522D', '#CD853F', '#D2B48C', '#F4A460', '#DEB887'
          ] // Warna berbeda untuk setiap bar
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
      }
    });
  }

  // ================== Form Tambah Buku ==================
  const bookForm = document.getElementById('bookForm');
  const bookTableBody = document.querySelector('#bookTable tbody');

  // Event delegation untuk tombol hapus (baik yang sudah ada maupun yang baru)
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-btn')) {
      if (confirm("Apakah kamu yakin ingin menghapus buku ini?")) {
        e.target.closest('tr').remove();
      }
    }
  });

  if (bookForm) {
    bookForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const judul = document.getElementById('judul').value.trim();
      const penulis = document.getElementById('penulis').value.trim();
      const genre = document.getElementById('genre').value.trim();
      const harga = document.getElementById('harga').value.trim();
      const cover = document.getElementById('cover').value.trim();

      // Validasi input
      if (!judul || !penulis || !genre || !harga) {
        alert("Semua field wajib diisi kecuali cover!");
        return;
      }

      if (harga === "" || isNaN(harga) || parseInt(harga) <= 0) {
        alert("Harga harus berupa angka positif!");
        return;
      }

      // Tambahkan row baru
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${judul}</td>
        <td>${penulis}</td>
        <td>${genre}</td>
        <td>Rp ${parseInt(harga).toLocaleString('id-ID')}</td>
        <td>${cover ? `<img src="${cover}" alt="cover" width="40">` : "-"}</td>
        <td><button class="delete-btn">Hapus</button></td>
      `;

      bookTableBody.appendChild(row);
      bookForm.reset();
    });
  }
});