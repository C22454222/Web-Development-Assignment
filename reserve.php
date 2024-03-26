<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reserve page</title>
    <link rel="stylesheet" type="text/css" href="styling.css">
</head>
<body>
    <header>
        <!-- Navigation Bar -->
        <div id="navbar">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="search.php">Search here for a book</a></li>
                <li><a href="view.php">View reserved books</a></li>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </div>
        <!-- Page Header -->
        <h1>Reserve a book here</h1>
    </header>
    <main>
    <?php
// Start or resume the existing session.
session_start();

// Require the database connection file.
require_once "database.php";

// Function to display error message and exit
function showError($message) {
    echo $message . " <a href='home.php'>Go back to home</a>";
    exit();
}

// Check if the user is not logged in.
if (!isset($_SESSION["account"])) {
    showError("Please <a href='login.php'>Log In</a> to start.");
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Process reservation here
        $isbn = isset($_POST['isbn']) ? $_POST['isbn'] : null;

        // Use a transaction to ensure atomicity
        $conn->begin_transaction();

        try {
            // Check if the book is already reserved by any user
            $checkBookReservation = "SELECT reserved FROM books WHERE ISBN = '$isbn' FOR UPDATE";
            $bookReservationResult = $conn->query($checkBookReservation);

            if ($bookReservationResult && $bookReservationResult->num_rows > 0) {
                $row = $bookReservationResult->fetch_assoc();
                $isReserved = $row['reserved'];

                if (!$isReserved) {
                    // Book is not reserved, proceed with the reservation
                    $username = $_SESSION["account"];

                    // Check if the user has already reserved the book
                    $checkUserReservation = "SELECT * FROM reservations WHERE ISBN = '$isbn' AND username = '$username'";
                    $userReservationResult = $conn->query($checkUserReservation);

                    if ($userReservationResult && $userReservationResult->num_rows === 0) {
                        // Book is not reserved by the user, proceed with the reservation
                        $reservedDate = date("Y-m-d"); // Current date

                        // Insert reservation into the reservations table
                        $insertReservation = "INSERT INTO reservations (ISBN, username, reservedDate) VALUES ('$isbn', '$username', '$reservedDate')";
                        $result = $conn->query($insertReservation);

                        if ($result) {
                            // Update the books table to mark the book as reserved
                            $updateBooksTable = "UPDATE books SET reserved = 1 WHERE ISBN = '$isbn'";
                            $conn->query($updateBooksTable);

                            // Commit the transaction
                            $conn->commit();
                            echo "Reservation successful. <a href='home.php'>Go back to home</a>";
                        } else {
                            // Rollback the transaction on error
                            $conn->rollback();
                            echo "Error in reservation: " . $conn->error;
                        }
                    } else {
                        // User has already reserved this book
                        echo "You have already reserved this book. <a href='home.php'>Go back to home</a>";
                    }
                } else {
                    // Book is already reserved by another user
                    echo "This book is already reserved. <a href='home.php'>Go back to home</a>";
                }
            } else {
                // Error checking reservation status
                echo "Error checking reservation status: " . $conn->error;
            }
        } catch (Exception $e) {
            // Rollback the transaction on exception
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }

        // Close the database connection
        $conn->close();
    } else {
        // Display confirmation form
        $isbn = isset($_GET['ISBN']) ? $_GET['ISBN'] : null;

        $sql = "SELECT bookTitle, author, ISBN FROM books WHERE ISBN = '$isbn'";
        $result = $conn->query($sql);

        if (!$result) {
            die("Error: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $bookTitle = isset($row['bookTitle']) ? $row['bookTitle'] : 'N/A';
            $author = isset($row['author']) ? $row['author'] : 'N/A';

            echo <<< _END
                    <!-- Display confirmation form -->
                    <h3>Confirm your reservation</h3>
                    <form method="post" action="reserve.php">
                        <p>Are you sure you would like to reserve "$bookTitle" by $author?</p>
                        <input type="hidden" name="isbn" value="$isbn">
                        <p><input type="submit" value="Yes, I'm sure"/>
                        <a href="search.php">Cancel</a></p>
                    </form>
                _END;
        } else {
            echo "Book not found or an error occurred.";
        }

        // Close the database connection
        $conn->close();
    }
}
?>
    </main>
    <!-- Page Footer -->
    <footer>
        Page Paradise Library Services 2023
    </footer>
</body>
</html>
