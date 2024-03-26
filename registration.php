<?php
// Start a session or resume the existing session.
session_start();

// Require the database connection file.
require_once "database.php";

// Initialize an empty array to store validation errors.
$errors = array();
$registrationSuccess = false; // Variable to track registration status

// Check if the registration form has been submitted (HTTP POST request).
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (!isset($_POST['username']) || empty($_POST['username'])) {
        $errors[] = "Please enter a username.";
    } else {
        // Check if the username is unique
        $username = $conn->real_escape_string($_POST['username']);
        $checkUsernameQuery = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($checkUsernameQuery);

        if ($result->num_rows > 0) {
            $errors[] = "Username already exists. Please choose a different one.";
        }
    }

    // Validate password and password confirmation
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors[] = "Please enter a password.";
    } elseif (strlen($_POST['password']) < 6) {
        $errors[] = "Password should be at least six characters long.";
    } elseif ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = "Passwords do not match.";
    }

    // Validate other fields
    $requiredFields = ['fname', 'lname', 'addr1', 'addr2', 'city', 'telephone', 'mobile'];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errors[] = "Please fill out all fields.";
            break;
        }
    }

    // Validate telephone number
    if (!isset($_POST['telephone']) || empty($_POST['telephone'])) {
        $errors[] = "Please enter a telephone number.";
    } elseif (!is_numeric($_POST['telephone']) || strlen($_POST['telephone']) !== 10) {
        $errors[] = "Telephone number should be numeric and 10 characters in length.";
    }

    // Validate mobile phone number
    if (!isset($_POST['mobile']) || empty($_POST['mobile'])) {
        $errors[] = "Please enter a mobile phone number.";
    } elseif (!is_numeric($_POST['mobile']) || strlen($_POST['mobile']) !== 10) {
        $errors[] = "Mobile phone number should be numeric and 10 characters in length.";
    }

    // If there are no validation errors, proceed with data insertion
    if (empty($errors)) {
        // Sanitize and escape user input
        $u = $conn->real_escape_string($_POST['username']);
        $pw = $conn->real_escape_string($_POST['password']);
        $f = $conn->real_escape_string($_POST['fname']);
        $l = $conn->real_escape_string($_POST['lname']);
        $a1 = $conn->real_escape_string($_POST['addr1']);
        $a2 = $conn->real_escape_string($_POST['addr2']);
        $c = $conn->real_escape_string($_POST['city']);
        $t = $conn->real_escape_string($_POST['telephone']);
        $m = $conn->real_escape_string($_POST['mobile']);

        // Construct the SQL query for user registration
        $sql1 = "INSERT INTO users (username, password, fname, lname, addr1, addr2, city, telephone, mobile)
                 VALUES ('$u', '$pw', '$f', '$l', '$a1', '$a2', '$c', '$t', '$m')";

        // Execute the query
        $conn->query($sql1);

        // Check for database errors
        if ($conn->errno) {
            die("Error: " . $conn->error);
        }

        // Set registration success to true
        $registrationSuccess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Registration page</title>
    <link rel="stylesheet" type="text/css" href="styling.css">
</head>
<body>

    <header>
        <!-- Navigation Bar -->
        <div id="navbar">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="search.php">Search for a book</a></li>
                <li><a href="view.php">View reserved books</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
        <!-- Page Header -->
        <h1>Register here</h1>
    </header>

    <main>
        <?php
        // Display validation errors or registration confirmation
        if (!empty($errors)) {
            echo '<div class="error">' . implode('<br>', $errors) . '</div>';
        } elseif ($registrationSuccess) {
            echo '<div class="success">Registration successful. <a href="login.php">Login here</a></div>';
        }
        ?>
        <!-- Registration Form -->
        <form method="post">
            <p>Username: <input type="text" name="username"></p>
            <p>Password: <input type="password" name="password"></p>
            <p>Confirm Password: <input type="password" name="confirm_password"></p>
            <p>First name: <input type="text" name="fname"></p>
            <p>Last name: <input type="text" name="lname"></p>
            <p>Address line 1: <input type="text" name="addr1"></p>
            <p>Address line 2: <input type="text" name="addr2"></p>
            <p>City: <input type="text" name="city"></p>
            <p>Telephone: <input type="number" name="telephone"></p>
            <p>Mobile: <input type="number" name="mobile"></p>
            <p>
                <input type="submit" value="Submit" />
                <a href="login.php">Cancel</a>
            </p>
            <p><br></p>
        </form>
    </main>
    <!-- Page Footer -->
    <footer>
        Page Paradise Library Services 2023
    </footer>
</body>
</html>
