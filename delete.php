<?php

session_start();

require_once 'config/database.php';
require_once 'helpers/helpers.php';

$db = Database::getInstance();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    setFlash('error', 'ID produk tidak valid.');
    redirect('index.php');
}

$stmt = $db->prepare("
    DELETE FROM produk
    WHERE id_produk = ?
");

$stmt->execute([$id]);

setFlash('success', 'Produk berhasil dihapus.');

redirect('index.php');