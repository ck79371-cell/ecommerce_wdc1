<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

try {
    $pdo = getPDO();
} catch (Exception $e) {
    die("DB Error");
}

$user = current_user();

$stmt = $pdo->query("
    SELECT id, name, price, image 
    FROM products 
    ORDER BY created_at DESC 
    LIMIT 8
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>DemoShop – Online Shopping</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
/* ===== RESET ===== */
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,Helvetica,sans-serif}
body{background:#f1f3f6}

/* ===== HEADER ===== */
.header{
    background:#2874f0;
    padding:12px 0;
    position:sticky;
    top:0;
    z-index:1000;
}
.header-inner{
    max-width:1200px;
    margin:auto;
    display:flex;
    align-items:center;
    gap:20px;
    padding:0 15px;
}
.logo{
    color:#fff;
    font-size:22px;
    font-weight:bold;
    text-decoration:none;
}
.search{
    flex:1;
}
.search input{
    width:100%;
    padding:10px 14px;
    border-radius:4px;
    border:none;
    outline:none;
}
.nav{
    display:flex;
    align-items:center;
    gap:10px;
}
.nav a{
    color:#fff;
    text-decoration:none;
    font-weight:500;
    padding:6px 10px;
    border-radius:4px;
}
.nav a:hover{
    background:rgba(255,255,255,0.15);
}
.nav .user{
    background:rgba(0,0,0,0.25);
    color:#fff;
    font-weight:bold;
    padding:6px 10px;
    border-radius:4px;
}

/* ===== HERO ===== */
.hero{
    max-width:1200px;
    margin:30px auto;
    padding:40px;
    background:linear-gradient(120deg,#2874f0,#4facfe);
    color:#fff;
    border-radius:8px;
}
.hero h1{font-size:36px;margin-bottom:10px}
.hero p{font-size:18px;margin-bottom:20px}
.hero a{
    background:#ff9f00;
    color:#fff;
    padding:12px 26px;
    border-radius:4px;
    text-decoration:none;
    font-weight:bold;
}

/* ===== SECTIONS ===== */
.container{max-width:1200px;margin:auto;padding:0 15px}
.section-title{
    font-size:24px;
    margin:40px 0 20px;
    color:#333;
}

/* ===== CATEGORIES ===== */
.categories{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
}
.cat{
    background:#fff;
    padding:25px;
    border-radius:8px;
    text-align:center;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

/* ===== PRODUCTS ===== */
.products{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
}
.card{
    background:#fff;
    padding:15px;
    border-radius:6px;
    box-shadow:0 2px 8px rgba(0,0,0,.1);
    transition:.2s;
}
.card:hover{transform:translateY(-5px)}
.card img{
    width:100%;
    height:180px;
    object-fit:contain;
}
.card h3{font-size:15px;margin:10px 0;color:#333}
.price{
    font-size:18px;
    font-weight:bold;
    color:#388e3c;
    margin-bottom:10px;
}
.card a{
    display:block;
    background:#ff9f00;
    color:#fff;
    text-align:center;
    padding:10px;
    border-radius:4px;
    text-decoration:none;
}

/* ===== FOOTER ===== */
footer{
    background:#172337;
    color:#ccc;
    text-align:center;
    padding:20px;
    margin-top:40px;
}
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-inner">
        <a class="logo" href="index.php">DemoShop</a>

        <div class="search">
            <input type="text" placeholder="Search for products, brands and more">
        </div>

        <div class="nav">
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>

            <?php if ($user && is_admin()): ?>
                <!-- ADMIN MENU -->
                <a href="admin_dashboard.php">Admin</a>
                <!-- <a href="admin.php">Products</a> -->
                <a href="admin_products.php">Products</a>
                <a href="categories_admin.php">Categories</a>
                <a href="orders_admin.php">Orders</a>
                <a href="users.php">Users</a>
            <?php endif; ?>

            <?php if ($user): ?>
                <span class="user">Hi, <?= htmlspecialchars($user['name']) ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Shop Smart. Shop Fast.</h1>
    <p>Discover amazing products at unbeatable prices. Quality you can trust.</p>
    <a href="#products">Start Shopping</a>
</div>

<div class="container">

    <!-- CATEGORIES -->
    <div class="section-title">Popular Categories</div>
    <div class="categories">
        <div class="cat"><strong>Electronics</strong><br>Latest gadgets</div>
        <div class="cat"><strong>Fashion</strong><br>Trending styles</div>
        <div class="cat"><strong>Home & Kitchen</strong><br>Daily needs</div>
        <div class="cat"><strong>Accessories</strong><br>Smart & stylish</div>
    </div>

    <!-- PRODUCTS -->
    <div class="section-title" id="products">Featured Products</div>
    <div class="products">
        <?php foreach ($products as $p): ?>
        <div class="card">
            <img src="<?= htmlspecialchars($p['image'] ?: 'assets/no-image.png') ?>">
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <div class="price">₹<?= number_format($p['price'],2) ?></div>
            <a href="product.php?id=<?= $p['id'] ?>">View Product</a>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<footer>
    © <?= date('Y') ?> DemoShop. All rights reserved.
</footer>

</body>
</html>
