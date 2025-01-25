<?php
include('db_connection.php');

$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';

if (!empty($search_query)) {
    $sql = "SELECT * FROM books WHERE book_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_query = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($book = $result->fetch_assoc()) {
            ?>
            <h3>Book Details</h3>
            <form action="update_book.php" method="post">
                <p><strong>Book ID:</strong> <input type="text" name="book_id" value="<?php echo htmlspecialchars($book['book_id']); ?>" required></p>
                <p><strong>Book Name:</strong> <input type="text" name="book_name" value="<?php echo htmlspecialchars($book['book_name']); ?>" required></p>
                <p><strong>Author:</strong> <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required></p>
                <p><strong>Quantity:</strong> <input type="number" name="quantity" value="<?php echo htmlspecialchars($book['quantity']); ?>" required></p>
                <p><strong>Price:</strong> <input type="text" name="price" value="<?php echo htmlspecialchars($book['price']); ?>" required></p>
                <input type="submit" value="Update Book">
            </form>
            <?php
        }
    } else {
        echo "<p>No books found matching your search.</p>";
    }
} else {
    echo "<p>Please enter a book name to search.</p>";
}

$conn->close();
?>
