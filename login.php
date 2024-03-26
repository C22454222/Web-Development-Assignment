<?php
// Start a session or resume the existing session.
session_start();

// Include the database connection file.
include "database.php";

// Unset the 'account' session variable to ensure a clean login/logout process.
unset($_SESSION["account"]);

// Check if the login form has been submitted.
if (isset($_POST["account"]) && isset($_POST["pw"])) {
    // Sanitize and escape user input.
    $u = $conn->real_escape_string($_POST['account']);
    $p = $conn->real_escape_string($_POST['pw']);

    // Prepare and execute a SQL query to check the user's credentials.
    $sql = "SELECT username FROM users WHERE username = '$u' AND password = '$p'";
    $result = $conn->query($sql);

    // If the query is successful and returns exactly one row
    // set the session variables and redirect to the home page.
    if ($result && $result->num_rows === 1) {
        $_SESSION["account"] = $_POST["account"];
        $_SESSION["success"] = "Logged in.";
        header("Location: home.php");
        return;
    } else {
        // If the credentials are incorrect, set an error message and redirect back to the login page.
        $_SESSION["error"] = "Incorrect username or password.";
        header("Location: login.php");
        return;
    }
} elseif (count($_POST) > 0) {
    // If there are POST parameters but they don't match the expected ones, set an error message and redirect
    // back to the login page.
    $_SESSION["error"] = "Missing required information somewhere";
    header("Location: login.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login page</title>
    <link rel="stylesheet" type="text/css" href="styling.css">
</head>
<body class="home-page">

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
        <h1>Login</h1>
    </header>

    <main>
        <?php
        // Display success message if set.
        if (isset($_SESSION["success"])) {
            echo ('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
            unset($_SESSION["success"]);
        }

        // Display error message if set.
        if (isset($_SESSION["error"])) {
            echo ('<p style="color:red">' . $_SESSION["error"] . "</p>\n");
            unset($_SESSION["error"]);
        }
        ?>

        <!-- Login Form -->
        <form method="post" action="login.php">
            <label for="account">Username:</label>
            <input type="text" name="account" required>

            <label for="pw">Password:</label>
            <input type="password" name="pw" required>

            <input type="submit" value="Log in">
            <p> Are you not a member? <a href="registration.php">Sign up</a></p>
        </form>
    </main>

    <!-- Page Footer -->
    <footer>
    Page Paradise Library Services 2023
    </footer>

</body>
</html>
