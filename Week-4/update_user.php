<?php
    require_once "koneksi.php";
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        echo"<h1>Tidak diizinkan untuk mengakses halaman selain HTTP POST!</h1>";
    }

    $id = $_POST["id"];
    $nama = $_POST["nama"] ?? null;
    $email = $_POST["email"] ?? null;
    $password = $_POST["password"] ?? null;

    if(!empty($password)){
        $password = password_hash($password, PASSWORD_ARGON2ID);
    }

    if(empty($nama) || empty($email)){
        echo "Nama dan Email tidak boleh kosong";
        exit;
    }

    $nama = htmlspecialchars($nama);
    $email = htmlspecialchars($email);

    $query = "UPDATE users SET nama=?, email=?";

    if(!empty($password)){
        $query .= ", password=?";
    }

    $query .= " WHERE id=?";

    $stmt = $koneksi->prepare($query);

    $type = "ss";
    if(!empty($password)){
        $stmt->bind_param("sssi", $nama, $email, $password, $id);
    }else{
        $stmt->bind_param("ssi", $nama, $email, $id);
    }

    if($stmt->execute()){
        $stmt->close();

        if(isset($_FILES["gambar"])){
            $config = [
                'max_size' => 2*1024*1024,
                "path" => "public/uploads"
            ];
            $file = $_FILES["gambar"];
            $filename = uploader($file, $config);
            $query = "UPDATE users SET photo=? WHERE id=?";

            $stmt = $koneksi->prepare($query);
            $stmt->bind_param("si", $filename, $id);

            $stmt->execute();
            $stmt->close();
        }

        header("Location: tampil_data.php");
    }else{
        echo "Terjadi kesalahan: " .$stmt->error;
        $stmt->close();
    }

    function uploader($file, $config = []) {
        $filename = null;

        $config = array_merge([
            // Default config for uploader
            'max_size' => 2*1024*1024,
            "path" => "public/",
            "filename" => null,
            "allowed_file" => ["jpg", "png", "jpeg"],
            "old_file" => null,
        ], $config);
        $name = $file["name"];
        $file_size = $file["size"];
        $tmp = $file["tmp_name"];
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        if(!in_array($ext, $config["allowed_file"])){
            exit("Hanya boleh upload file ". join("/", $config["allowed_file"]));
        }

        if($file_size <= 0 || (!empty($config['max_size']) && $file_size > $config['max_size'])){
            exit("Hanya boleh upload file dengan ukuran maksimal " . round($config['max_size']/(1024*1024)) . " MB");
        }

        if(empty($config['filename'])){
            $filename = substr(uniqid(), 0, 8) . "." . $ext;
        }else{
            $filename = $config['filename'] .".". $ext;
        }

        $path_dir = str_replace("/", DIRECTORY_SEPARATOR, $config["path"] . DIRECTORY_SEPARATOR . $filename);

        try{
            move_uploaded_file($tmp, $path_dir);
        }catch(\Exception $e){
            exit($e->getMessage());
        }

        return $filename;
    }
?>