<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delete reservation page</title>
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
        <h1>Delete your reservation for a book</h1>
    </header>
    <main>
        <?php
        // Start or resume the existing session.
        session_start();
        
        // Require the database connection file.
        require_once "database.php";

        // Check if the user is not logged in.
        if (!isset($_SESSION["account"])) {
            ?>
            <!-- Display message if user is not logged in -->
            Please <a href="login.php">Log In</a> to start.
            <?php
        } else {
            ?>
            <section>
                <?php
                // Check if the 'id' is set in the POST request.
                if (isset($_POST['id'])) {
                    // Use prepared statement to prevent SQL injection
                    $id = $conn->real_escape_string($_POST['id']);
                    $sql = "UPDATE books SET reserved = '0' WHERE ISBN = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $id);
                    $stmt->execute();

                    $sql2 = "DELETE FROM reservations WHERE ISBN = ?";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param("s", $id);
                    $stmt2->execute();

                    echo '<h2>Reservation Deleted</h2> <br> <a href="home.php">Go back to the home page</a>';
                    return;
                }

                // Get the ISBN from the GET request.
                $id = $conn->real_escape_string($_GET['isbn']);
                $sql = "SELECT bookTitle, author, ISBN FROM books WHERE ISBN=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $n = htmlentities($row['bookTitle']);
                $a = htmlentities($row['author']);
                $id = htmlentities($row['ISBN']);

                echo <<< _END
                <h3>Confirm deletion</h3>
                <form method="post">
                    <p>Are you sure you want to remove the reservation for "$n" by "$a"?</p>
                    <input type="hidden" name="id" value="$id">
                    <p>
                        <input type="submit" value="Yes">
                        <a href="view.php">Cancel</a>
                    </p>
                </form>
                _END;
                ?>
            </section>
        <?php } ?>
    </main>
    <!-- Page Footer -->
    <footer>
    Page Paradise Library Services 2023
    </footer>
</body>
</html>
