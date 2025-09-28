<?php
require_once 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengguna Terdaftar</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Data Pengguna Terdaftar</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal Registrasi</th>
                <th>Photo Path</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, nama, email, photo, tanggal_registrasi FROM users ORDER BY id ASC";
            $result = $koneksi->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . $row["tanggal_registrasi"] . "</td>";
                    echo "<td>" . $row["photo"] . "</td>";
                    echo "<td> <a class='btn btn-warning' href='update.php?id=". $row['id'] ."'>Update</a> </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada data yang terdaftar</td></tr>";
            }
            $koneksi->close();
            ?>
        </tbody>
    </table>
    <br>
    <a href="index.html">Kembali ke Form Registrasi</a>

</body>
</html>