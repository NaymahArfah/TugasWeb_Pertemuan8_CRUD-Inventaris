<?php

session_start();

require_once 'config/database.php';
require_once 'helpers/helpers.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_produk = trim($_POST['nama_produk']);
    $stok = (int) $_POST['stok'];
    $harga = (float) $_POST['harga'];
    $id_kategori = (int) $_POST['id_kategori'];
    $id_supplier = (int) $_POST['id_supplier'];

    if (
        $nama_produk === '' ||
        $stok < 0 ||
        $harga < 0 ||
        $id_kategori <= 0 ||
        $id_supplier <= 0
    ) {
        setFlash('error', 'Data yang dimasukkan tidak valid.');
        redirect('create.php');
    }

    $sql = "
        INSERT INTO produk
        (nama_produk, stok, harga, id_kategori, id_supplier)
        VALUES (?, ?, ?, ?, ?)
    ";

    $stmt = $db->prepare($sql);

    $stmt->execute([
        $nama_produk,
        $stok,
        $harga,
        $id_kategori,
        $id_supplier
    ]);

    setFlash('success', 'Produk berhasil ditambahkan.');

    redirect('index.php');
}

$kategori = $db
    ->query("SELECT * FROM kategori ORDER BY nama_kategori")
    ->fetchAll(PDO::FETCH_ASSOC);

$supplier = $db
    ->query("SELECT * FROM supplier ORDER BY nama_supplier")
    ->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Produk</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <h1>Tambah Produk</h1>

    <form method="POST">

        <p>
            <label>Nama Produk</label><br>

            <input
                type="text"
                name="nama_produk"
                required
            >
        </p>

        <p>
            <label>Stok</label><br>

            <input
                type="number"
                name="stok"
                min="0"
                required
            >
        </p>

        <p>
            <label>Harga</label><br>

            <input
                type="number"
                name="harga"
                min="0"
                required
            >
        </p>

        <p>
            <label>Kategori</label><br>

            <select name="id_kategori" required>

                <option value="">
                    -- Pilih Kategori --
                </option>

                <?php foreach ($kategori as $item): ?>

                    <option value="<?= e($item['id_kategori']) ?>">
                        <?= e($item['nama_kategori']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </p>

        <p>
            <label>Supplier</label><br>

            <select name="id_supplier" required>

                <option value="">
                    -- Pilih Supplier --
                </option>

                <?php foreach ($supplier as $item): ?>

                    <option value="<?= e($item['id_supplier']) ?>">
                        <?= e($item['nama_supplier']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </p>

        <button type="submit" class="btn">
            Simpan
        </button>

        <a href="index.php" class="btn">
            Kembali
        </a>

    </form>

</div>

</body>

</html>