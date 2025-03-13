<?php
include '../auth.php';
include '../assets/db/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
</head>

<body>

    <!-- BG -->
    <div class="bg-[url('../uploads/rumah_sakit.jpg')] bg-cover bg-contain">

        <!-- NAVBAR -->
        <?php include "../layout/navbar.php" ?>

        <!-- Produk Section -->
        <div>
            <section class="py-12 px-6">
                <div class="max-w-7xl mx-auto">
                    <h2 class="relative text-3xl font-extrabold text-center text-gray-800 mb-8 p-4 border-4 border border-blue-500 rounded-xl shadow-md bg-gradient-to-r from-blue-200 via-white to-blue-100">
                        <span class="bg-gradient-to-r from-blue-400 to-blue-600 text-transparent bg-clip-text">
                            Daftar Produk
                        </span>
                    </h2>

                    <!-- Grid for Products -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        $query = "SELECT 
                            products.id, 
                            products.name, 
                            products.category, 
                            products.price, 
                            products.stock, 
                            products.image, 
                            promos.discount, 
                            promos.valid_until
                        FROM 
                            products
                        LEFT JOIN 
                            promos 
                        ON 
                            products.category = promos.category COLLATE utf8mb4_general_ci 
                        WHERE 
                            promos.valid_until >= CURDATE() 
                            OR promos.category IS NULL
                        ORDER BY 
                            CASE 
                                WHEN promos.discount IS NOT NULL THEN 1 
                                ELSE 0 
                            END DESC, 
                            promos.discount DESC";

                        $result = $conn->query($query);

                        while ($product = $result->fetch_assoc()):
                            // Harga diskon jika promo berlaku
                            $isPromoValid = !empty($product['discount']) && strtotime($product['valid_until']) >= time();
                            $discountedPrice = $isPromoValid
                                ? $product['price'] * (1 - ($product['discount'] / 100))
                                : $product['price'];
                        ?>
                            <!-- Product Card -->
                            <div class="bg-white shadow-md rounded-lg overflow-hidden transform hover:scale-105 transition duration-300">
                                <div class="relative">
                                    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                                        alt="<?= htmlspecialchars($product['name']) ?>"
                                        class="w-full h-48 object-cover">
                                    <?php if ($isPromoValid): ?>
                                        <span class="absolute top-4 left-4 bg-gradient-to-r from-blue-400 to-blue-600 text-white py-1 px-3 rounded-full text-sm">
                                            Diskon <?= $product['discount'] ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-6 flex flex-col bg-gradient-to-r from-blue-100 to-blue-200 min-h-[300px]">
                                    <h3 class="text-xl font-bold text-gray-800 "><?= htmlspecialchars($product['name']) ?></h3>
                                    <p class="text-lg font-semibold mb-1">Kategori:
                                        <?= htmlspecialchars(str_replace('Product-', '', $product['category'])) ?>
                                    </p>
                                    <p class="text-gray-600 text-sm flex-grow">Stok: <?= htmlspecialchars($product['stock']) ?></p>
                                    <?php if ($isPromoValid): ?>
                                        <p class="text-gray-400 line-through text-sm mt-2">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                                    <?php endif; ?>
                                    <p class="text-blue-500 text-lg font-bold mt-1">Rp <?= number_format($discountedPrice, 0, ',', '.') ?></p>
                                    <a href="https://wa.me/6281234567890" target="_blank" class="block mt-4 w-full">
                                        <button class="w-full bg-gradient-to-r from-blue-400 to-blue-600 text-white py-2 px-4 rounded-lg transition-transform transform hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2">
                                            <i class="fa-brands fa-whatsapp"></i> Beli Sekarang
                                        </button>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        </div>



        <!-- Footer -->
        <?php include "../layout/footer.php" ?>
    </div>

</body>

</html>