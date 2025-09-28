<?php
    require_once "koneksi.php";
    $id = $_GET["id"];
    if(empty($id)){
        exit("Illegal!");
    }
    $user = null;
    $sql = "SELECT nama, email FROM users WHERE id=$id";
    $result = $koneksi->query($sql);
    if($result->num_rows != 1) {
        exit("ID salah!");
    }else{
        while($row = $result->fetch_assoc()) {
            $user = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=h1, initial-scale=1.0">
    <title>Update Registrasi</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Update Data User</h1>
    <form action="update_user.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?= $user['nama'] ?>">
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" id="email" value="<?= $user['email'] ?>">
        </div>
        <div class="form-group">
            <label for="">Password (Isi jika ingin merubah password)</label>
            <input type="password" name="password" id="password">
        </div>
        <div class="form-group">
            <label for="">Foto Profil</label>
            <input type="file" name="gambar" id="gambar">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="tampil_data.php" class="btn btn-danger">Batal</a>
    </form>
</body>
</html>