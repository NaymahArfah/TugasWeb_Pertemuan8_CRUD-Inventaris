<?php

session_start();

require_once 'config/database.php';
require_once 'helpers/helpers.php';

$db = Database::getInstance();

$sql = "
    SELECT
        produk.id_produk,
        produk.nama_produk,
        produk.stok,
        produk.harga,
        kategori.nama_kategori,
        supplier.nama_supplier
    FROM produk
    INNER JOIN kategori
        ON produk.id_kategori = kategori.id_kategori
    INNER JOIN supplier
        ON produk.id_supplier = supplier.id_supplier
    ORDER BY produk.id_produk DESC
";

$stmt = $db->query($sql);
$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);

$flash = getFlash();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CRUD Inventaris</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body>

    <div class="container">

        <h1>CRUD Inventaris</h1>

        <p>Data Produk</p>

        <?php if ($flash): ?>

            <div class="flash <?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>

        <?php endif; ?>

        <a href="create.php" class="btn">
            + Tambah Produk
        </a>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (count($produk) > 0): ?>

                    <?php foreach ($produk as $item): ?>

                        <tr>

                            <td>
                                <?= e($item['id_produk']) ?>
                            </td>

                            <td>
                                <?= e($item['nama_produk']) ?>
                            </td>

                            <td>
                                <?= e($item['nama_kategori']) ?>
                            </td>

                            <td>
                                <?= e($item['nama_supplier']) ?>
                            </td>

                            <td>
                                <?= e($item['stok']) ?>
                            </td>

                            <td>
                                Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                            </td>

                            <td>

                                <a href="edit.php?id=<?= e($item['id_produk']) ?>">
                                    Edit
                                </a>

                                <a
                                    href="delete.php?id=<?= e($item['id_produk']) ?>"
                                    onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                >
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7">
                            Belum ada data produk.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</body>

</html>