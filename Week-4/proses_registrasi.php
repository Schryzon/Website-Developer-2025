<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

    if (empty($nama) || empty($email) || empty($_POST['password'])) {
        echo "Error: Semua field harus diisi! <a href='index.html'>Kembali</a>";
    }  else {
        $query = "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("sss", $nama, $email, $password);

        if ($stmt->execute()) {
            echo "<h1>Registrasi Berhasil!</h1>";
            echo "<p>Selamat, " . $nama . "! Data Anda telah disimpan.</p>";
            echo '<a href="index.html">Kembali ke Form</a> | <a href="tampil_data.php">Lihat Data</a>';
        }else {
            if ($koneksi->errno == 1062) {
                echo "Error: Email " . $email . " sudah terdaftar. <a href='index.html'>Kembali</a>";
            }
            else {
                echo "Error saat registrasi: " . $stmt->error . " <a href='index.html'>Kembali</a>";
            }
        }$stmt->close();
    }
} else {
    header('Location: index.html');
    exit();
}
$koneksi->close();
?>