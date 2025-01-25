<?php
// ডাটাবেস কানেকশন ফাইল অন্তর্ভুক্ত করা
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $is_valid = true;

    // Initializing variables
    $book_id = '';
    $book_name = '';
    $author = '';
    $quantity = '';
    $price = '';

    // Book ID Validation
    if (empty(trim($_POST['book_id']))) {
        echo "Book ID is required.<br>";
        $is_valid = false;
    } elseif (!preg_match("/^\d+$/", $_POST['book_id'])) { // Only numbers allowed
        echo "Book ID must be a numeric value.<br>";
        $is_valid = false;
    } else {
        $book_id = $_POST['book_id'];
    }

    // Book Name Validation
    if (empty(trim($_POST['book_name']))) {
        echo "Book Name is required.<br>";
        $is_valid = false;
    } elseif (!preg_match("/^[a-zA-Z0-9\s]+$/", $_POST['book_name'])) { // Alphanumeric and spaces allowed
        echo "Book Name must contain only letters, numbers, and spaces.<br>";
        $is_valid = false;
    } else {
        $book_name = $_POST['book_name'];
    }

    // Author Name Validation
    if (empty(trim($_POST['author']))) {
        echo "Author Name is required.<br>";
        $is_valid = false;
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $_POST['author'])) { // Only alphabets and spaces allowed
        echo "Author Name must contain only alphabetic characters and spaces.<br>";
        $is_valid = false;
    } else {
        $author = $_POST['author'];
    }

    // Quantity Validation
    if (empty(trim($_POST['quantity']))) {
        echo "Quantity is required.<br>";
        $is_valid = false;
    } elseif (!filter_var($_POST['quantity'], FILTER_VALIDATE_INT) || $_POST['quantity'] <= 0) { // Only positive integers allowed
        echo "Quantity must be a positive integer.<br>";
        $is_valid = false;
    } else {
        $quantity = $_POST['quantity'];
    }

    // Price Validation
    if (empty(trim($_POST['price']))) {
        echo "Price is required.<br>";
        $is_valid = false;
    } elseif (!is_numeric($_POST['price']) || $_POST['price'] <= 0) { // Only positive numbers allowed
        echo "Price must be a positive number.<br>";
        $is_valid = false;
    } else {
        $price = $_POST['price'];
    }

    // If all validations pass
    if ($is_valid) {
        // SQL Insert Query
        $sql = "INSERT INTO books (book_id, book_name, author, quantity, price) VALUES (?, ?, ?, ?, ?)";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("ssssi", $book_id, $book_name, $author, $quantity, $price);

            // Execute the statement
            if ($stmt->execute()) {
                echo "New book added successfully!<br>";
                echo "Book ID: " . htmlspecialchars($book_id) . "<br>";
                echo "Book Name: " . htmlspecialchars($book_name) . "<br>";
                echo "Author: " . htmlspecialchars($author) . "<br>";
                echo "Quantity: " . htmlspecialchars($quantity) . "<br>";
                echo "Price: " . htmlspecialchars($price) . "<br>";
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $conn->error . "<br>";
        }
    }
}

// Close the connection
$conn->close();
?>
