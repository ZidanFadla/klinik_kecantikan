<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$requiresAdmin = true;
include('../auth.php'); // Proteksi akses admin
include('../assets/db/database.php'); // Koneksi database
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
</head>

<body class="bg-gray-100">
    <!-- Sidebar -->
    <?php include "sidebar.php"; ?>

    <!-- Konten Utama -->
    <div class="bg-gradient-to-r from-blue-100 via-blue-200 to-blue-300 ml-64 p-6">
        <!-- Manajemen Promo -->
        <section id="promo" class="mb-12">
            <div class="container mx-auto px-4 py-6">
                <div class="bg-blue-300 shadow-lg rounded-lg p-6 mb-8 max-w-3xl mx-auto flex justify-center items-center">
                    <h2 class="text-3xl font-bold text-gray-800 text-center">MANAJEMEN PROMO</h2>
                </div>
            </div>
            <!-- Konten Promo -->
            <?php include "promo/promos.php"; ?>
        </section>

        <!-- Manajemen Produk -->
        <section id="product" class="mb-12">
            <div class="container mx-auto px-4 py-6">
                <div class="bg-blue-300 shadow-lg rounded-lg p-6 mb-8 max-w-3xl mx-auto flex justify-center items-center">
                    <h2 class="text-3xl font-bold text-gray-800 text-center">MANAJEMEN PRODUK</h2>
                </div>
            </div>
            <!-- Konten Produk -->
            <?php include "products/products.php"; ?>
        </section>

        <!-- Manajemen Treatment -->
        <section id="treatment" class="mb-12">
            <div class="container mx-auto px-4 py-6">
                <div class="bg-blue-300 shadow-lg rounded-lg p-6 mb-8 max-w-3xl mx-auto flex justify-center items-center">
                    <h2 class="text-3xl font-bold text-gray-800 text-center">MANAJEMEN TREATMENT</h2>
                </div>
            </div>
            <!-- Konten Treatment -->
            <?php include "treatments/treatments.php"; ?>
        </section>
    </div>
</body>

</html>