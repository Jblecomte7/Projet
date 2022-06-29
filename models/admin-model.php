<?php

if (!is_connected()) {
    header('Location: ./index.php?page=Login');
    exit();
}

/////////////////////////////////
///////// USERS SECTION /////////
/////////////////////////////////

// New instance of Database class : 
$users = new Database();

// Delete user method :

if (isset($_POST['confirmer']) && !empty($_POST['confirmer'])) {
    $params = [
        'id' => htmlentities($_POST['confirmer'])
    ];
    $deleteUser = $users->prepare('DELETE FROM users WHERE id = :id', $params);
}

// Show all users in a new table :
$allUsers = $users->query('SELECT users.id, users.Name, users.FirstName, users.email, users.register_date, role.name FROM users JOIN role ON role.id = users.role_id');


////////////////////////////////////
///////// PRODUCTS SECTION /////////
////////////////////////////////////

$products = new Database();

// Delete product
if (isset($_POST['confirmer']) && !empty($_POST['confirmer'])) {
    $params = [
        'id' => htmlentities($_POST['confirmer'])
    ];
    $deleteProduct = $products->prepare('DELETE FROM products WHERE id = :id', $params);
}

// Show all products

$allProducts = $products->query('SELECT products.id, products.name, products.price, products.size, categories.type  FROM products JOIN categories ON products.categories_id = categories.id');

// Upload image method :

$nameOfInputUpload = "image";
$isPost = $_SERVER['REQUEST_METHOD'] === "POST";
$myImage = isset($_FILES[$nameOfInputUpload]) ? $_FILES[$nameOfInputUpload] : null;
$fileLocation = './vendor/images/';

// si il y a un fichier qui a été envoyé sans erreur et qu'il a le name image
if ((isset($myImage) && $myImage['error'] == 0)) {
    // If image <= 3mo :
    if ($myImage['size'] <= 3000000) {
        $informationImage = pathinfo($myImage['name']);
        $extensionImage = $informationImage['extension'];
        $extensionArray = ['jpg', 'gif', 'png', 'jpeg', 'webp'];
        // si l'extension de l'image est dans le tableau $extensionArray
        if (in_array($extensionImage, $extensionArray)) {
            // on lui dit ou deplacer l'image et on la nomme
            $urlImage  = $fileLocation . time() . rand() . '.' . $extensionImage;
            // on déplace l'image dans le dossier Upload
            move_uploaded_file($myImage['tmp_name'], $urlImage);
            $message = "Votre image est prise en compte ! ";
        } else {
            $message = " Votre fichier n'est pas de type jpeg jpg gif ou png !";
        }
    } else {
        $message = " Votre image depasse les 3mo !";
    }
}


// Envoi des données produits vers le serveur

if (!empty($_POST['category']) && !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_POST['size']) && !empty($_POST['image1']) && !empty($_POST['image2']) && !empty($_POST['image3'])) {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $description = nl2br(htmlentities($_POST['description']));
    $price = $_POST['price'];
    $size = $_POST['size'];
    $image1 = $fileLocation . $_POST['image1'];
    $image2 = $fileLocation . $_POST['image2'];
    $image3 = $fileLocation . $_POST['image3'];

    $params = [
        'category' => $category,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'size' => $size,
        'image1' => $image1,
        'image2' => $image2,
        'image3' => $image3
    ];

    $addProduct = $products->prepare('INSERT INTO products (categories_id, name, description, price, size, image1, image2, image3) VALUES (:category, :name, :description, :price, :size, :image1, :image2, :image3)', $params, true);
}

// EDIT PRODUCTS

$showProducts = new Database();
$productInfos = $showProducts->query('SELECT products.id, products.name, products.description, products.price, products.size, products.image1, products.image2, products.image3, categories.type FROM products JOIN categories ON products.categories_id = categories.id');

////////////////////////////////////
///////// CONTACT SECTION /////////
////////////////////////////////////

$messages = new Database;

// ARCHIVE MESSAGES

if (isset($_POST['confirmer']) && !empty($_POST['confirmer'])) {
    $parameters = [
        ':archive' => '1',
        'id' => htmlentities($_POST['confirmer'])
    ];
    $archiveMsg = $messages->prepare('UPDATE messages SET archive = :archive WHERE id = :id', $parameters);
}


// SHOW MESSAGES
$parameters = [
    ':archive' => '1'
];
$archMessages = $messages->prepare('SELECT id, name, firstname, email, message, date, archive FROM messages WHERE archive = :archive ORDER BY date DESC', $parameters);

$parameters = [
    'archive' => '0'
];
$allMessages = $messages->prepare('SELECT id, name, firstname, email, message, date, archive FROM messages WHERE archive = :archive ORDER BY date DESC', $parameters);
