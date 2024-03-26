<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document metadata -->
    <meta charset="utf-8">
    <title>Search Page</title>
    <!-- Link to external stylesheet -->
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
    <h1>Search for a book</h1>
</header>

<main>
    <?php
    // Start or resume the existing session.
    session_start();

    // Require the database connection file.
    require_once "database.php";

    // Check if the user is logged in
    if (!isset($_SESSION["account"])) {
        ?>
        <!-- Display message if user is not logged in -->
        <p>Please <a href="login.php">Log In</a> to start.</p>
        <?php
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            // Initialize errors array
            $errors = array();

            // Validate at least one field is filled out
            $searchName = isset($_GET['name']) ? trim($_GET['name']) : '';
            $searchAuthor = isset($_GET['author']) ? trim($_GET['author']) : '';
            $searchCategory = isset($_GET['category']) ? trim($_GET['category']) : '';

            if (empty($searchName) && empty($searchAuthor) && empty($searchCategory)) {
                $errors[] = "Please fill out at least one search field.";
            }

            // Display errors if any
            if (!empty($errors)) {
                echo '<div class="error">' . implode('<br>', $errors) . '</div>';
            } else {
                // Proceed with the search query
                $sql = "SELECT books.*, category.categoryDepartment FROM books 
                        JOIN category ON books.categoryID = category.categoryID 
                        WHERE ";

                $conditions = [];

                if (!empty($searchName)) {
                    $conditions[] = "books.bookTitle LIKE '%" . $conn->real_escape_string($searchName) . "%'";
                }

                if (!empty($searchAuthor)) {
                    $conditions[] = "books.author LIKE '%" . $conn->real_escape_string($searchAuthor) . "%'";
                }

                if (!empty($searchCategory)) {
                    $conditions[] = "category.categoryDepartment LIKE '%" . $conn->real_escape_string($searchCategory) . "%'";
                }

                $sql .= implode(" AND ", $conditions);

                // Execute the query
                $result = $conn->query($sql);

                if ($result) {
                    if ($result->num_rows > 0) {
                        // Display search results
                        echo '<table>';
                        echo "<tr><td><h2> Book Title </h2></td>
                        <td><h2> Author </h2></td>
                        <td><h2> Genre </h2></td>
                        <td><h2>Reserve</h2></td></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            $bookTitle = isset($row['bookTitle']) ? $row['bookTitle'] : 'N/A';
                            $author = isset($row['author']) ? $row['author'] : 'N/A';
                            $categoryType = isset($row['categoryDepartment']) ? $row['categoryDepartment'] : 'N/A';
                            $isbn = isset($row['ISBN']) ? $row['ISBN'] : 'N/A';
                            echo '<td>' . $bookTitle . '</td>';
                            echo '<td>' . $author . '</td>';
                            echo '<td>' . $categoryType . '</td>';
                            echo '<td><a href="reserve.php?ISBN=' . $isbn . '">Reserve</a></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo 'No results found.';
                    }
                } else {
                    echo 'Query Error: ' . $conn->error;
                }
            }
        }
    ?>
    <section>
        <!-- Search Form -->
        <form method="get" action="search.php">
            <h2>Search by name:</h2>
            <input type="text" name="name" placeholder="Enter book name" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>">
            
            <h2>Search by author:</h2>
            <input type="text" name="author" placeholder="Enter author name" value="<?php echo isset($_GET['author']) ? htmlspecialchars($_GET['author']) : ''; ?>">
            
            <h2>Search by category:</h2>
            <select name="category">
                <option value="">Select Category</option>

                <?php
                // Display category options
                $categoryQuery = "SELECT * FROM category";
                $categoryResult = $conn->query($categoryQuery);

                while ($category = $categoryResult->fetch_assoc()) {
                    $categoryType = isset($category['CategoryDepartment']) ? $category['CategoryDepartment'] : 'N/A';
                    $selected = (isset($_GET['category']) && $_GET['category'] == $categoryType) ? 'selected' : '';
                    echo '<option value="' . $categoryType . '" ' . $selected . '>' . $categoryType . '</option>';
                }
                ?>
            </select>

            <input type="submit" value="Go">
            <p><br></p>
        </form>
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
