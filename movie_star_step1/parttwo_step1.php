<!DOCTYPE html>
<html>
<head>
    <title>Person Information</title>
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80 ');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: #fff;
            text-align: center;
            margin: 0;
            padding-top: 100px;
        }
        form {
            margin-top: 50px;
            display: inline-block;
            padding: 20px;
            border: 1px solid #fff;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 20px;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            background-color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
            width: 300px;
            color: #000;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #3e8e41;
        }
        h1 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <?php
    // Database credentials
    $db_file = 'C:\xampp\htdocs\fifth\movies.db';

    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve the input value from the form
        $input = $_POST['input'];

        // Connect to the database using PDO
        try {
            $pdo = new PDO("sqlite:$db_file");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("ERROR: Could not connect to the database: " . $e->getMessage());
        }

        // Prepare a query to retrieve data from the database
        if (is_numeric($input)) {
            // If the input is a number, consider it to be the ID
            $query = "SELECT * FROM people WHERE id = :input";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':input', $input);
        } else {
            // If the input is a text, check if it matches the ending of a name
           $query = "SELECT * FROM people WHERE name LIKE :input";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':input', "%$input%", PDO::PARAM_STR);

    

        }

        // Execute the query and retrieve the result
        $stmt->execute();
        $result = $stmt->fetchAll();

        // Display the result
        if (count($result) == 0) {
            // If no results are found
            if (is_numeric($input)) {
                echo "<p>ID not found.</p>";
            } else {
                echo "<p>No such name.</p>";
            }
        } elseif (count($result) == 1) {
            // If a unique match is found
            $person = $result[0];
            echo "<h1>" . $person['name'] . "</h1>";
            echo "<p>ID: " . $person['id'] . "</p>";
            echo "<p>Born: " . $person['birth'] . "</p>";
            // Add more fields here as needed
        } else {
             // If multiple matches are found, display them in a table
        echo "<h1>Multiple matches found:</h1>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Birth Year</th></tr>";
        foreach ($result as $result) {
            echo "<tr>";
            echo "<td>" . $result['id'] . "</td>";
            echo "<td>" . $result['name'] . "</td>";
            echo "<td>" . $result['birth'] . "</td>";
            echo "</tr>";
              }
            echo "</table>";

        }

        // Close the database connection
        $pdo = null;
    }
    ?>

    <!-- Display the HTML form -->
    <form method="post">
        <label for="input">Enter ID or Name:</label>
        <input type="text" name="input" id="input" placeholder="e.g. John Doe">
        <br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>