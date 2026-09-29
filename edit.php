<?php

session_start();

require_once 'config/database.php';
require_once 'helpers/helpers.php';

$db = Database::getInstance();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    redirect('index.php');
}


$stmt = $db->prepare("
    SELECT *
    FROM produk
    WHERE id_produk = ?
");

$stmt->execute([$id]);

$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    setFlash('error', 'Produk tidak ditemukan.');
    redirect('index.php');
}


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
        redirect("edit.php?id=$id");
    }

    $sql = "
        UPDATE produk
        SET
            nama_produk = ?,
            stok = ?,
            harga = ?,
            id_kategori = ?,
            id_supplier = ?
        WHERE id_produk = ?
    ";

    $stmt = $db->prepare($sql);

    $stmt->execute([
        $nama_produk,
        $stok,
        $harga,
        $id_kategori,
        $id_supplier,
        $id
    ]);

    setFlash('success', 'Produk berhasil diperbarui.');

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Produk</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <h1>Edit Produk</h1>

    <form method="POST">

        <p>
            <label>Nama Produk</label><br>

            <input
                type="text"
                name="nama_produk"
                value="<?= e($produk['nama_produk']) ?>"
                required
            >
        </p>

        <p>
            <label>Stok</label><br>

            <input
                type="number"
                name="stok"
                min="0"
                value="<?= e($produk['stok']) ?>"
                required
            >
        </p>

        <p>
            <label>Harga</label><br>

            <input
                type="number"
                name="harga"
                min="0"
                value="<?= e($produk['harga']) ?>"
                required
            >
        </p>

        <p>
            <label>Kategori</label><br>

            <select name="id_kategori" required>

                <?php foreach ($kategori as $item): ?>

                    <option
                        value="<?= e($item['id_kategori']) ?>"
                        <?= $item['id_kategori'] == $produk['id_kategori'] ? 'selected' : '' ?>
                    >
                        <?= e($item['nama_kategori']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </p>

        <p>
            <label>Supplier</label><br>

            <select name="id_supplier" required>

                <?php foreach ($supplier as $item): ?>

                    <option
                        value="<?= e($item['id_supplier']) ?>"
                        <?= $item['id_supplier'] == $produk['id_supplier'] ? 'selected' : '' ?>
                    >
                        <?= e($item['nama_supplier']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </p>

        <button type="submit" class="btn">
            Update
        </button>

        <a href="index.php" class="btn">
            Kembali
        </a>

    </form>

</div>

</body>

</html>