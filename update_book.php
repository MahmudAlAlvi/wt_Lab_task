<?php
// ডাটাবেজ কানেকশন
include('db_connection.php');

// বইয়ের তথ্য আপডেট করার জন্য
if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $author = $_POST['author'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // SQL কোয়েরি দিয়ে বইয়ের তথ্য আপডেট করা
    $sql = "UPDATE books SET book_name = ?, author = ?, quantity = ?, price = ? WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $book_name, $author, $quantity, $price, $book_id);
    
    if ($stmt->execute()) {
        echo "<p>Book details updated successfully.</p>";
    } else {
        echo "<p>Error updating book details.</p>";
    }
}

$conn->close();
?>
