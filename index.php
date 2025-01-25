<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">

        <!-- Box 9: Display used tokens -->
        <div class="box3-left">
            <h3>Used Tokens</h3>
            <?php
            // Read used tokens from used_token.json
            $used_tokens_data = json_decode(file_get_contents("used_token.json"), true);
            $used_tokens = isset($used_tokens_data['used_tokens']) ? $used_tokens_data['used_tokens'] : [];

            // Display used tokens in Box 9
            if (count($used_tokens) > 0) {
                echo "<ul>";
                foreach ($used_tokens as $token) {
                    echo "<li>" . htmlspecialchars($token) . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No tokens have been used yet.</p>";
            }
            ?>
        </div>

        <div class="center_content"> 
            <div class="container">
                <!-- Box 1 -->
                                <!-- Box 1 -->
              <!-- Box 1 - Search Book -->
                <div class="search-box">
                    <div class="search-box">
                        <div class="header">
                            <h3>AIUB LIBRARY</h3>
                            <!-- Search Form -->
                            <form action="search_result.php" method="post" class="search-form">
                                <input type="text" name="search_query" placeholder="Search Book by Name" required>
                                <input type="submit" value="Search">
                            </form>
                        </div>
                    </div>
                    <!-- Display Search Results here -->
                    <?php
                    if (isset($_POST['search_query'])) {
                        // Database connection
                        include('db_connection.php');

                        // Get search query
                        $search_query = $_POST['search_query'];
                        
                        // Search books based on the search query
                        $sql = "SELECT * FROM books WHERE book_name LIKE ?";
                        $stmt = $conn->prepare($sql);
                        $search_query = "%" . $search_query . "%";
                        $stmt->bind_param("s", $search_query);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Display search result
                        if ($result->num_rows > 0) {
                            echo "<h4>Search Results:</h4>";
                            echo "<ul>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<li>" . htmlspecialchars($row['book_name']) . " - " . htmlspecialchars($row['author']) . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>No books found matching your search.</p>";
                        }

                        // Close the connection
                        $conn->close();
                    }
                    ?>
                </div>






                <!-- Box 2: All Book List -->
                <!-- Box 2: All Book List -->
                <div class="box-all-books">
                    <h3>
                        <button id="showBooksBtn">All Book List</button>
                    </h3>
                    <div id="bookList" style="display: none;">
                        <ul>
                            <?php
                            // ডাটাবেস কানেকশন
                            include('db_connection.php');
                            
                            // ডাটাবেস থেকে সমস্ত বইয়ের নাম ফেচ করা
                            $sql = "SELECT book_id, book_name FROM books";
                            $result = $conn->query($sql);
                            
                            // যদি কোনো বই থাকে
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // বইয়ের নাম ক্লিক করলে book_details.php তে পাঠানো হবে
                                    echo "<li><a href='book_details.php?book_id=" . $row['book_id'] . "'>" . htmlspecialchars($row['book_name']) . "</a></li>";
                                }
                            } else {
                                echo "<li>No books available.</li>";
                            }
                            
                            $conn->close();
                            ?>
                        </ul>
                    </div>
                </div>


                <script>
                    // "All Book List" ক্লিক করলে বইয়ের তালিকা দেখাবে বা লুকাবে
                    document.getElementById('showBooksBtn').addEventListener('click', function() {
                        var bookList = document.getElementById('bookList');
                        if (bookList.style.display === 'none' || bookList.style.display === '') {
                            bookList.style.display = 'block';  // Show the book list
                        } else {
                            bookList.style.display = 'none';   // Hide the book list
                        }
                    });
                </script>



                <div class="add-book-box">
            <h3>Add Books</h3>
            <form name="addBookForm" action="add_book_process.php" method="post">
                <input type="text" id="book_id" name="book_id" placeholder="Book Id" required>
                <input type="text" id="book_name" name="book_name" placeholder="Book Name" required>
                <input type="text" id="author" name="author" placeholder="Author" required>
                <input type="number" id="quantity" name="quantity" placeholder="Quantity" required>
                <input type="text" id="price" name="price" placeholder="Price" required>
                <input type="submit" value="Add Book">
            </form>

        </div>


            </div>

            <div class="container1">
                <!-- Box 1 -->
                <div class="box1">
                    <img src="images/book1.png" alt="Book 1" class="box-image">
                </div>

                <!-- Box 2 -->
                <div class="box1">
                    <img src="images/book2.png" alt="Book 2" class="box-image">
                </div>

                <!-- Box 3 -->
                <div class="box1">
                    <img src="images/book3.png" alt="Book 3" class="box-image">
                </div>
            </div>


            <div class="container2">
                <div class="box2-large">
                    <h2>Borrow Form</h2>
                    <form name="borrowform" action="process.php" method="post">
                        <input type="text" name="student_name" placeholder="Student Full Name" required>
                        <input type="text" name="student_id" placeholder="Student AIUB ID" required>
                        <input type="text" name="email_id" placeholder="Student Email" required>
                        <label for="book_title"></label>
                        <select name="book_title" id="book_title" required>
                            <option value="" disabled selected>Select a Book</option>
                            <option value="Book 1">Book 1</option>
                            <option value="Book 2">Book 2</option>
                            <option value="Book 3">Book 3</option>
                            <option value="Book 4">Book 4</option>
                            <option value="Book 5">Book 5</option>
                            <option value="Book 6">Book 6</option>
                            <option value="Book 7">Book 7</option>
                            <option value="Book 8">Book 8</option>
                            <option value="Book 9">Book 9</option>
                            <option value="Book 10">Book 10</option>
                        </select>
                        <input type="date" name="borrow_date" placeholder="Borrow Date" required>

                        <!-- Token Text Box -->
                        <input type="text" name="token" id="token" required placeholder="Enter Token Number">

                        <input type="date" name="return_date" placeholder="Return Date" required>
                        <input type="text" name="fees" placeholder="Fees" required>
                        <input type="submit" value="Submit">
                    </form>
                </div>

                <!-- Box 8 - Available Tokens -->
                <div class="box2-small">
                    <h2>Available Tokens</h2>
                    <ul>
                        <?php
                        $tokens = json_decode(file_get_contents("token.json"), true)['tokens'];
                        foreach ($tokens as $token) {
                            echo "<li>$token</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="box3-right">
            <img src="images/id.png" alt="Box Image" class="box-image">
        </div>

    </div>
</body>
</html>