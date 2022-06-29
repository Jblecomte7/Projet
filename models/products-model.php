<?php

$showProducts = new Database();
$productInfos = $showProducts->query('SELECT products.id, products.name, products.description, products.price, products.size, products.image1, products.image2, products.image3, categories.type FROM products JOIN categories ON products.categories_id = categories.id');


// Add products to Cart : 

if (isset($_GET['addToCart']) && !empty($_GET['addToCart'])) {

    // If user isn't connected, redirection to login page :
    if (!is_connected()) {
        header('Location: ./index.php?page=Login');
        exit();
    }

    $params = [
        'user_id' => $_SESSION['id'],
        'product_id' => $_GET['addToCart']
    ];
    $checkCart = $showProducts->prepare('SELECT id FROM panier WHERE user_id = :user_id AND product_id = :product_id', $params, true);

    if ($checkCart === false) {
        $addCart = $showProducts->prepare('INSERT INTO panier (user_id, product_id) VALUES ((SELECT id FROM users WHERE id = :user_id), (SELECT id FROM products WHERE id = :product_id))', $params);
        header('Location: ./index.php?page=Produits');
        exit();
    } else {
        $errors['addToCart'] = 'Ce produit est déjà dans votre panier !';
    }
}

// Add products to Fav : 

if (isset($_GET['addToFav']) && !empty($_GET['addToFav'])) {

    // If user isn't connected, redirection to login page :
    if (!is_connected()) {
        header('Location: ./index.php?page=Login');
        exit();
    }

    $params = [
        'user_id' => $_SESSION['id'],
        'product_id' => $_GET['addToFav']
    ];
    $checkFav = $showProducts->prepare('SELECT id FROM wishlist WHERE user_id = :user_id AND product_id = :product_id', $params, true);

    if ($checkFav === false) {
        $addFav = $showProducts->prepare('INSERT INTO wishlist (user_id, product_id) VALUES ((SELECT id FROM users WHERE id = :user_id), (SELECT id FROM products WHERE id = :product_id))', $params);
        header('Location: ./index.php?page=Produits');
        exit();
    } else {
        $errors['addToFav'] = 'Ce produit est déjà dans votre liste d\'envies !';
    }
}

// Method to delete URL values :

function strip_param_from_url($param)
{
    $stripedURL = '';
    foreach ($_GET as $key => $value) {
        if (is_array($param)) {
            if (!in_array($key, $param)) {
                $stripedURL .= '&' . $key . '=' . $value;
            }
        } else {
            if ($key !== $param) {
                $stripedURL .= '&' . $key . '=' . $value;
            }
        }
    }
    return $stripedURL;
}

// FILTER

$filterResult = [];
foreach ($_POST as $elementK => $elementV) {
    $filterResult = [...$filterResult, $elementK];
}
