<!DOCTYPE html>
<html>
<head>
    <!-- Document metadata -->
    <meta charset="utf-8">
    <title>View page</title>
    <!-- Link to external stylesheet -->
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
        <h1>Take a look at your reserved books</h1>
    </header>
    <main>
        <?php
        // Start or resume the existing session.
        session_start();

        // Require the database connection file.
        require_once "database.php";

        if (!isset($_SESSION["account"])) {
            ?>
            <!-- Display message if user is not logged in -->
            Please <a href="login.php">Log In</a> to start.
            <?php
        } else {
            ?>  
            <section>
                <!-- Display reserved books for the logged-in user -->
                <h1>Here are all your reserved books!</h1>
                <?php
                // Get the username from the session
                $u = $conn->real_escape_string($_SESSION["account"]);

                // SQL query to retrieve reserved books for the user
                $sql = "SELECT books.ISBN, bookTitle, author, reserved, reservedDate
                FROM books
                JOIN reservations
                ON books.ISBN = reservations.ISBN
                WHERE reservations.username = ?";

                // Prepare and execute the query
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $u);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if there are reserved books
                if ($result->num_rows > 0) {
                    echo '<table>';
                    echo "<tr><td><h2> Book Title </h2></td>
                    <td><h2> Author </h2></td>
                    <td><h2> Reserve date </h2></td>
                    <td><h2>End this reservation</h2></td></tr>";

                    // Display each reserved book in a table row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>";
                        echo (htmlentities($row["bookTitle"]));
                        echo "       </td><td>";
                        echo (htmlentities($row["author"]));
                        echo "       </td><td>";
                        echo (htmlentities($row["reservedDate"]));
                        echo "       </td><td>";
                        echo ('<a href= "remove.php?isbn=' . htmlentities($row["ISBN"]) . '">End</a>');
                        echo "       </td></tr>";
                    }

                    echo '</table>'; // Add the closing tag here
                } else {
                    echo "No results";
                }  
                ?>
            </section>
        <?php
        }?>
    </main>
    <footer>
    <!-- Page Footer -->
    Page Paradise Library Services 2023
    </footer>
</body>
</html>
