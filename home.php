<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Home Page</title>
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
        <!-- Library Header -->
        <h1>Welcome to Page Paradise Library</h1>
    </header>

    <main>
        <?php
        // Start PHP Session and Include Database Configuration
        session_start();
        include "database.php";

        // Check if User is Logged In
        if (!isset($_SESSION["account"])) 
        {
          ?>
          <!-- Display Welcome Message and Invitation to Log In -->
          <section>
              <p>
                  Welcome to Page Paradise Library, where reading comes to life. Established in<br>
                  2010, we are dedicated to providing a haven for book enthusiasts. Explore our<br>
                  user-friendly system, featuring easy registration, efficient book searches,<br> 
                  and a convenient reservation process. Dive into the <br>joy of reading with Page
                  Paradise Library.
              </p>
              <p>
                  Thank you for being a part of our reading community. Happy reading!
              </p>
          </section>
          <p>Please <a href="login.php">Log In</a> to start.</p>
          <?php  
        } 
        else 
        {
          // Display Welcome Message for Logged-In User
          $name = isset($_SESSION["account"]) ? $_SESSION["account"] : '';
          ?>
          <section>
            <h1>Welcome back to the library, <?php echo(htmlentities($name)); ?></h1>
            <!-- Display User Information and Library Features -->
            <ul>
                <strong>User Registration and Login:</strong> Access the system by registering
                 or logging in. New users can easily create an<br> account with a unique username for
                  identification.<strong>Search for a Book:</strong> Find books by title,
                  author, or category. We <br>support partial searches for titles and authors, and categories
                   are retrieved from the database.<strong>Search Results:</strong> <br>Search results
                    are neatly displayed
                    results. View book details and reserve available books.
                <strong>Reserve a Book:</strong> Secure <br>your book reservation if it's available. The system checks for prior
                  reservations before confirming your booking and <br>records the reservation date.
                <strong>View Reserved Books:</strong> Easily see a list of books you've reserved. Manage your <br>reservations
                  by removing them as needed.
            </ul>
          </section>
        <?php
        } 
        ?>
    </main>

    <!-- Library Logo -->
    <img src="library.png" alt="Library logo" class="logo">

    <!-- Library Footer -->
    <footer>
      Page Paradise Library Services 2023
    </footer>

</body>
</html>
