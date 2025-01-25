<?php
// ডাটাবেস কানেকশন
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $is_valid = true; // to check the validation
    $student_name = ''; // Default value initialization
    $student_id = '';   // Default value initialization
    $email_id = '';     // Default value initialization
    $book_title = '';   // Default value initialization
    $borrow_date = '';  // Default value initialization
    $token = '';        // Default value initialization
    $return_date = '';  // Default value initialization
    $fees = '';         // Default value initialization

    // Student Name Validation
    if (empty(trim($_POST['student_name']))) {
        echo "Student Full Name is required.<br>";
        $is_valid = false;
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $_POST['student_name'])) {
        echo "Student Full Name must contain only alphabetic characters and spaces.<br>";
        $is_valid = false;
    } else {
        $student_name = $_POST['student_name'];
    }

    // Student ID Validation
    if (empty(trim($_POST['student_id']))) {
        echo "Student AIUB ID is required.<br>";
        $is_valid = false;
    } elseif (!preg_match("/^\d{2}-\d{5}-\d{1}$/", $_POST['student_id'])) {
        echo "Student AIUB ID must be in the format 'XX-XXXXX-X'.<br>";
        $is_valid = false;
    } else {
        $student_id = $_POST['student_id'];
    }

    // Email Validation
    if (empty(trim($_POST['email_id']))) {
        echo "Email is required.<br>";
        $is_valid = false;
    } elseif (!filter_var($_POST['email_id'], FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.<br>";
        $is_valid = false;
    } else {
        $email_id = $_POST['email_id'];
    }

    // Book Title Validation
    if (empty($_POST['book_title']) || $_POST['book_title'] === "") {
        echo "Book Title is required.<br>";
        $is_valid = false;
    } else {
        $book_title = $_POST['book_title'];
    }

    // Borrow Date Validation
    if (empty(trim($_POST['borrow_date']))) {
        echo "Borrow Date is required.<br>";
        $is_valid = false;
    } elseif (!preg_match("/\d{4}-\d{2}-\d{2}/", $_POST['borrow_date'])) {  
        echo "Borrow Date must be in the format YYYY-MM-DD.<br>";
        $is_valid = false;
    } else {
        $borrow_date = $_POST['borrow_date'];
    }

    // Token Validation
    $token = $_POST['token'];
    if ($is_valid) {
        $available_tokens = json_decode(file_get_contents("token.json"), true)['tokens'];

        if (empty($token)) {
            echo "Token is required.<br>";
            $is_valid = false;
        } else {
            $used_tokens_data = json_decode(file_get_contents("used_token.json"), true);
            if (isset($used_tokens_data['used_tokens']) && in_array($token, $used_tokens_data['used_tokens'])) {
                echo "This token is already used, try new token.<br>";
                $is_valid = false;
            } elseif (!in_array($token, $available_tokens)) {
                echo "Invalid token. Please enter a valid token from the available tokens.<br>";
                $is_valid = false;
            }
        }
    }

    // Return Date Validation
    if (empty(trim($_POST['return_date']))) {
        echo "Return Date is required.<br>";
        $is_valid = false;
    } elseif (!preg_match("/\d{4}-\d{2}-\d{2}/", $_POST['return_date'])) {  
        echo "Return Date must be in the format YYYY-MM-DD.<br>";
        $is_valid = false;
    } else {
        $return_date = $_POST['return_date'];
        $borrow_date_obj = new DateTime($borrow_date);
        $return_date_obj = new DateTime($return_date);
        $interval = $borrow_date_obj->diff($return_date_obj);
        if ($interval->days < 10) {
            echo "Return Date must be at least 10 days after Borrow Date.<br>";
            $is_valid = false;
        }
    }

    // Fees Validation
    if (empty(trim($_POST['fees']))) {
        echo "Fees are required.<br>";
        $is_valid = false;
    } elseif (!is_numeric($_POST['fees'])) {
        echo "Fees must be a numeric value.<br>";
        $is_valid = false;
    } else {
        $fees = $_POST['fees'];
    }

    // If all validations pass, process the form and handle book borrowing
    if ($is_valid) {
        $safe_book_title = preg_replace('/[=,; \t\r\n\013\014]/', '_', $book_title);

        if (isset($_COOKIE[$safe_book_title])) {
            echo "This book is already borrowed by " . htmlspecialchars($_COOKIE[$safe_book_title]) . ". Try again later.<br>";
            exit;
        }

        setcookie($safe_book_title, $student_name, time() + 30);

        $stmt = $conn_library_system->prepare("INSERT INTO borrow_records (student_name, student_id, email_id, book_title, borrow_date, token, return_date, fees) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $student_name, $student_id, $email_id, $book_title, $borrow_date, $token, $return_date, $fees);

        if ($stmt->execute()) {
            echo "Form submitted successfully!<br>";
            echo "Student Name: " . htmlspecialchars($student_name) . "<br>";
            echo "Student ID: " . htmlspecialchars($student_id) . "<br>";
            echo "Email ID: " . htmlspecialchars($email_id) . "<br>";
            echo "Book Title: " . htmlspecialchars($book_title) . "<br>";
            echo "Borrow Date: " . htmlspecialchars($borrow_date) . "<br>";
            echo "Token: " . htmlspecialchars($token) . "<br>";
            echo "Return Date: " . htmlspecialchars($return_date) . "<br>";
            echo "Fees: " . htmlspecialchars($fees) . "<br><br>";
            echo "Book borrowed successfully by " . htmlspecialchars($student_name) . "!<br>";

            $available_tokens = array_diff($available_tokens, [$token]);
            file_put_contents("token.json", json_encode(["tokens" => $available_tokens], JSON_PRETTY_PRINT));

            if (!isset($used_tokens_data['used_tokens'])) {
                $used_tokens_data['used_tokens'] = [];
            }
            $used_tokens_data['used_tokens'][] = $token;
            file_put_contents("used_token.json", json_encode($used_tokens_data, JSON_PRETTY_PRINT));
        } else {
            echo "Error: " . $stmt->error . "<br>";
        }

        $stmt->close();
    }
}
?>
