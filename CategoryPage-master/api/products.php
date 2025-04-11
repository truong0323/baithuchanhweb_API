<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$allProducts = [
    ['id' => 1, 'name' => 'Gradient Graphic T-shirt', 'category' => 'T-Shirt', 'price' => 2500000, 'old_price' => 3200000, 'imageUrl' => 'images/product1.png'],
    ['id' => 2, 'name' => 'Polo with Tipping Details', 'category' => 'Polo', 'price' => 800000, 'old_price' => null, 'imageUrl' => 'images/product2.png'],
    ['id' => 3, 'name' => 'Black Striped T-shirt', 'category' => 'T-Shirt', 'price' => 1200000, 'old_price' => 1500000, 'imageUrl' => 'images/product3.png'],
    ['id' => 4, 'name' => 'Skinny Fit Jeans', 'category' => 'Jeans', 'price' => 500000, 'old_price' => null, 'imageUrl' => 'images/product4.png'],
    ['id' => 5, 'name' => 'Checkered Shirt', 'category' => 'Shirt', 'price' => 950000, 'old_price' => null, 'imageUrl' => 'images/product5.png'],
    ['id' => 6, 'name' => 'Sleeve Striped T-Shirt', 'category' => 'T-Shirt', 'price' => 300000, 'old_price' => 450000, 'imageUrl' => 'images/product6.png'],
    ['id' => 7, 'name' => 'Virtical Striped Shirt', 'category' => 'Shirt', 'price' => 1800000, 'old_price' => 2200000, 'imageUrl' => 'images/product7.png'],
    ['id' => 8, 'name' => 'Courage Graphic T-Shirt', 'category' => 'T-Shirt', 'price' => 400000, 'old_price' => null, 'imageUrl' => 'images/product8.png'],
    ['id' => 9, 'name' => 'Loose Fit Bermula Shorts', 'category' => 'Jeans', 'price' => 750000, 'old_price' => null, 'imageUrl' => 'images/product9.png'],
    ['id' => 10, 'name' => 'Faded Skinny Jeans', 'category' => 'Jeans', 'price' => 3100000, 'old_price' => 3500000, 'imageUrl' => 'images/product10.png'],
    ['id' => 11, 'name' => 'Polo with Contrast Trims', 'category' => 'Polo', 'price' => 1100000, 'old_price' => null, 'imageUrl' => 'images/product11.png'],
    ['id' => 12, 'name' => 'One Life Graphic T-shirt', 'category' => 'T-Shirt', 'price' => 2900000, 'old_price' => null, 'imageUrl' => 'images/product12.png'],
    ['id' => 13, 'name' => 'Bohemian Throw Pillow', 'category' => 'Decoration', 'price' => 250000, 'old_price' => 300000, 'imageUrl' => 'images/product6.png'],
    ['id' => 14, 'name' => 'Modern Dining Table', 'category' => 'Furniture', 'price' => 5500000, 'old_price' => null, 'imageUrl' => 'images/product3.png'],
    ['id' => 15, 'name' => 'Industrial Pendant Light', 'category' => 'Lamp', 'price' => 1300000, 'old_price' => 1600000, 'imageUrl' => 'images/product9.png'],
    ['id' => 16, 'name' => 'Velvet Ottoman', 'category' => 'Furniture', 'price' => 900000, 'old_price' => null, 'imageUrl' => 'images/product10.png'],
    ['id' => 17, 'name' => 'Geometric Wall Art', 'category' => 'Decoration', 'price' => 600000, 'old_price' => null, 'imageUrl' => 'images/product11.png'],
    ['id' => 18, 'name' => 'Minimalist Side Table', 'category' => 'Furniture', 'price' => 1000000, 'old_price' => 1200000, 'imageUrl' => 'images/product1.png'],
    ['id' => 19, 'name' => 'Woven Storage Basket', 'category' => 'Storage', 'price' => 350000, 'old_price' => null, 'imageUrl' => 'images/product2.png'],
    ['id' => 20, 'name' => 'Leather Accent Chair', 'category' => 'Chair', 'price' => 4200000, 'old_price' => null, 'imageUrl' => 'images/product4.png'],
    ['id' => 21, 'name' => 'Glass Vase Set', 'category' => 'Decoration', 'price' => 480000, 'old_price' => 600000, 'imageUrl' => 'images/product7.png'],
    ['id' => 22, 'name' => 'Marble Coffee Table', 'category' => 'Furniture', 'price' => 3800000, 'old_price' => null, 'imageUrl' => 'images/product8.png'],
    ['id' => 23, 'name' => 'Arched Floor Mirror', 'category' => 'Decoration', 'price' => 2100000, 'old_price' => null, 'imageUrl' => 'images/product3.png'],
    ['id' => 24, 'name' => 'Floating Wall Shelves', 'category' => 'Storage', 'price' => 700000, 'old_price' => 850000, 'imageUrl' => 'images/product10.png'],
    ['id' => 25, 'name' => 'Cozy Knit Blanket', 'category' => 'Textile', 'price' => 950000, 'old_price' => null, 'imageUrl' => 'images/product12.png'],
];

$selectedCategories = isset($_GET['category']) && is_array($_GET['category']) ? $_GET['category'] : [];
$minPrice = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (int)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (int)$_GET['max_price'] : null;

$filteredProducts = $allProducts;

if (!empty($selectedCategories)) {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($selectedCategories) {
        return isset($product['category']) && in_array($product['category'], $selectedCategories);
    });
}

if ($minPrice !== null) {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($minPrice) {
        return isset($product['price']) && $product['price'] >= $minPrice;
    });
}

if ($maxPrice !== null) {
    $filteredProducts = array_filter($filteredProducts, function($product) use ($maxPrice) {
        return isset($product['price']) && $product['price'] <= $maxPrice;
    });
}

$filteredProducts = array_values($filteredProducts);

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 12;

$totalItems = count($filteredProducts); 
$totalPages = $totalItems > 0 ? ceil($totalItems / $limit) : 0;
$offset = ($page - 1) * $limit;

$productsOnPage = array_slice($filteredProducts, $offset, $limit);

function formatCurrency($number) {
    if ($number === null) return null;
    return number_format($number, 0, ',', '.') . '₫';
}

foreach ($productsOnPage as &$product) {
    $product['formatted_price'] = formatCurrency($product['price']);
    $product['formatted_old_price'] = formatCurrency($product['old_price']);
}
unset($product);

$response = [
    'products' => $productsOnPage,
    'pagination' => [
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalItems' => $totalItems,
        'limit' => $limit,
        'itemFrom' => $totalItems > 0 ? min($offset + 1, $totalItems) : 0,
        'itemTo' => $totalItems > 0 ? min($offset + $limit, $totalItems) : 0,
    ]
];


echo json_encode($response);

?>