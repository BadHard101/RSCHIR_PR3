<?php
header('Content-Type: application/json');

$servername = "database";
$username = "user";
$password = "password";
$dbname = "appDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // GET request to retrieve products
    $sql = "SELECT * FROM items";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = array();
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
    } else {
        echo json_encode([]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST request to create a new product
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];
    $category = $data['category'];

    $sql = "INSERT INTO items (name, description, price, category) VALUES ('$name', '$description', $price, $category)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Product created successfully'));
    } else {
        echo json_encode(array('error' => 'Error creating product: ' . $conn->error));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // PUT request to update a product
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];
    $category = $data['category'];

    $sql = "UPDATE items SET name='$name', description='$description', price=$price, category=$category WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Product updated successfully'));
    } else {
        echo json_encode(array('error' => 'Error updating product: ' . $conn->error));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // DELETE request to delete a product
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $sql = "DELETE FROM items WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Product deleted successfully'));
    } else {
        echo json_encode(array('error' => 'Error deleting product: ' . $conn->error));
    }
}

$conn->close();
?>
