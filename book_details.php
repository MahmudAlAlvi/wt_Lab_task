<?php
// ডাটাবেস কানেকশন
include('db_connection.php');

// বইয়ের আইডি নেওয়া URL থেকে
$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 0;

// বইয়ের তথ্য ফেচ করা
$sql = "SELECT * FROM books WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

// যদি বইয়ের তথ্য পাওয়া যায়
if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    echo "<h2>Book Details</h2>";
    echo "<p><strong>Book ID:</strong> " . htmlspecialchars($book['book_id']) . "</p>";  
    echo "<p><strong>Book Name:</strong> " . htmlspecialchars($book['book_name']) . "</p>";
    echo "<p><strong>Author:</strong> " . htmlspecialchars($book['author']) . "</p>";
    echo "<p><strong>Quantity:</strong> " . htmlspecialchars($book['quantity']) . "</p>";
    echo "<p><strong>Price:</strong> " . htmlspecialchars($book['price']) . "</p>";
} else {
    echo "<p>Book not found.</p>";
}

$conn->close();
?>
