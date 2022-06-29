<?php

$cart = new Database();


// If user isn't connected, redirection to login page :
if (!is_connected()) {
    header('Location: ./index.php?page=Login');
    exit();
}

// Delete cart product method :

if (isset($_POST['confirmer']) && !empty($_POST['confirmer'])) {
    $params = [
        'id' => htmlentities($_POST['confirmer'])
    ];
    $deleteCartProduct = $cart->prepare('DELETE FROM panier WHERE product_id = :id', $params);
}



$params = [
    'user_id' => $_SESSION['id']
];

$showCart = $cart->prepare('SELECT product_id FROM panier WHERE user_id = :user_id', $params);


$pannierProduct = [];
foreach ($showCart as $product) {
    $params = [
        'id' => $product['product_id']
    ];
    $productCartSQL = $cart->prepare('SELECT id, name, price, size, image1 FROM products WHERE id = :id', $params);

    $pannierProduct = array_merge($productCartSQL, $pannierProduct);
};

// Display total price :
$totalPrice = 0;
foreach ($pannierProduct as $cart) {
    $totalPrice += $cart['price'];
}
