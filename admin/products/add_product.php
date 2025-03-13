<?php
include('../../assets/db/database.php'); 
include('../../auth.php'); 

if (!isset($conn)) {
    die("Error: Koneksi database tidak tersedia. Periksa file database.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $image = null;
    $modelPath = null;

    $targetDir = "../../uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // **1. Upload Gambar Produk**
    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        $targetFile = $targetDir . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            die("Gagal mengunggah gambar.");
        }
    }

    // **2. Upload & Ekstrak Model 3D (ZIP)**
    if (!empty($_FILES['model']['name'])) {
        $zipFile = $_FILES['model']['name'];
        $zipTmp = $_FILES['model']['tmp_name'];
        $zipExt = pathinfo($zipFile, PATHINFO_EXTENSION);
    
        if ($zipExt !== 'zip') {
            die("File harus berformat ZIP.");
        }
    
        // Tentukan folder tujuan berdasarkan nama produk
        $modelDir = "../../uploads/models/" . preg_replace("/[^a-zA-Z0-9]/", "_", $name);
        
        // Pastikan folder tujuan ada
        if (!file_exists($modelDir)) {
            mkdir($modelDir, 0777, true);
        }
    
        $zipPath = $modelDir . "/" . basename($zipFile);
        move_uploaded_file($zipTmp, $zipPath);
    
        // Ekstrak ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($modelDir);
            $zip->close();
    
            // Hapus file ZIP setelah ekstrak
            unlink($zipPath);
    
            // Cek apakah scene.gltf ada
            if (file_exists($modelDir . "/scene.gltf")) {
                $modelPath = "uploads/models/" . basename($modelDir) . "/scene.gltf";
            } else {
                die("File scene.gltf tidak ditemukan dalam ZIP.");
            }
        } else {
            die("Gagal mengekstrak ZIP.");
        }
    } else {
        $modelPath = null; // Tidak ada model 3D
    }
    
    // Insert data ke database
    $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image, category, 3D_model) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $name, $price, $stock, $image, $category, $modelPath);
    
    if ($stmt->execute()) {
        header("Location: ../dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-300 via-blue-100 to-blue-300">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Tambah Produk</h2>
            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Nama Produk</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-semibold mb-2">Harga</label>
                    <input type="number" name="price" id="price" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-gray-700 font-semibold mb-2">Stok</label>
                    <input type="number" name="stock" id="stock" class="w-full px-4 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700 font-semibold mb-2">Kategori</label>
                    <input type="text" name="category" id="category" class="w-full px-4 py-2 border rounded-md" placeholder="Contoh: Product-Kosmetik" required>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-semibold mb-2">Gambar Produk</label>
                    <input type="file" name="image" id="image" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="3d_model" class="block text-gray-700 font-semibold mb-2">Upload Model 3D (ZIP):</label>
                    <input type="file" name="model_zip" accept=".zip" required>
                </div>
                <div class="flex justify-between">
                    <a href="../dashboard.php" class="bg-gray-400 text-white py-2 px-4 rounded hover:bg-gray-500 transition">
                        Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
                        Tambah Produk
                    </button>
                </div>
            </form>

        </div>
    </div>
</body>
</html>
