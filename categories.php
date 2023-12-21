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
    // GET request to retrieve categories
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $categories = array();
        while($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        echo json_encode($categories);
    } else {
        echo json_encode([]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST request to create a new category
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];

    $sql = "INSERT INTO categories (name) VALUES ('$name')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Category created successfully'));
    } else {
        echo json_encode(array('error' => 'Error creating category: ' . $conn->error));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // PUT request to update a category
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $name = $data['name'];

    $sql = "UPDATE categories SET name='$name' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Category updated successfully'));
    } else {
        echo json_encode(array('error' => 'Error updating category: ' . $conn->error));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // DELETE request to delete a category
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];

    $sql = "DELETE FROM categories WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('message' => 'Category deleted successfully'));
    } else {
        echo json_encode(array('error' => 'Error deleting category: ' . $conn->error));
    }
}

$conn->close();
?>
