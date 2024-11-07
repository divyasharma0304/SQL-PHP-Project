<!DOCTYPE html>
<html>

<head>
    <style>
        .result-box {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            padding: 20px;
            text-align: center;
            width: 40%;
        }
    </style>
    <title>Movie Database</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

    <?php
    // Connect to the database
    try {
        $db = new PDO('sqlite:movies.db');
    } catch (PDOException $e) {
        echo '<p>Error connecting to database: ' . $e->getMessage() . '</p>';
        exit;
    }

    // Retrieve input from the user
    if (isset($_GET['input'])) {
        $input = $_GET['input'];
    } else {
        echo '<p>No input received.</p>';
        exit;
    }

    // Check if input is a number (ID)
    if (is_numeric($input)) {
        // Prepare SQL statement
        $stmt = $db->prepare('SELECT * FROM people WHERE id = :id');
        $stmt->bindParam(':id', $input, PDO::PARAM_INT);
    } else { // Assume input is a name
        // Prepare SQL statement
        $stmt = $db->prepare('SELECT * FROM people WHERE name LIKE :surname');
        $stmt->bindValue(':surname', "%$input", PDO::PARAM_STR);
    }

    // Execute SQL statement and retrieve results
    try {
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<p>Error executing SQL statement: ' . $e->getMessage() . '</p>';
        exit;
    }

    // Check number of results
    $num_results = count($results);
    if ($num_results == 0) { // No results found
        echo '<h2 style="text-align: center; margin: 20px;">No matches found.</h2>';
        exit;
    } elseif ($num_results == 1) { // One result found
        foreach ($results as $result) {
            echo '<div class="result-box">';
            echo '<h2>Name: ' . $result['name'] . '</h2>';
            echo '<h3>ID: ' . $result['id'] . '</p>';
            echo '<h3>Birth Year: ' . $result['birth'] . '</p>';
            echo '</div>';
        }

        $stmt2 = $db->prepare('SELECT m.year, COUNT(*) AS num_films 
        FROM movies m 
        JOIN stars s ON m.id = s.movie_id 
        JOIN people p ON s.person_id = p.id 
        WHERE (:id IS NOT NULL AND s.person_id = :id) OR (:name IS NOT NULL AND p.name LIKE :name)
        GROUP BY m.year 
        ORDER BY m.year DESC
        ');

        if (is_numeric($input)) {
            $stmt2->bindParam(':id', $input, PDO::PARAM_INT);
            $stmt2->bindValue(':name', null, PDO::PARAM_NULL); // set :name to NULL when using :id
        } else {
            $stmt2->bindValue(':id', null, PDO::PARAM_NULL); // set :id to NULL when using :name
            $stmt2->bindValue(':name', "%$input%", PDO::PARAM_STR); // use wildcard search for names
        }

        try {
            $stmt2->execute();
            $results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '<p>Error executing SQL statement: ' . $e->getMessage() . '</p>';
            exit;
        }


        $num_results2 = count($results2);
        if ($num_results2 == 0) {
            echo '<h2 style="text-align: center; margin: 20px;">The Person has not starred in any movie.</h2>';
        } else {
            // justify-content: center;
            echo '<div style="display: flex;">';
            echo '<div style="width: 50%;">';
            echo '<table style="margin: 0 auto; width: 80%; border-collapse: collapse; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Year</th>';
            echo '<th>Number of films</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($results2 as $result) {
                echo '<tr>';
                echo '<td>' . $result['year'] . '</td>';
                echo '<td>' . $result['num_films'] . '</td>';
                echo '</tr>';
            }
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';

            // CSS styles
            echo '<style>';
            echo 'thead th { background-color: #ddd; font-weight: bold; text-align: center; }';
            echo 'tbody tr:nth-child(odd) { background-color: #f2f2f2; }';
            echo 'table td, table th { padding: 8px; }';
            echo 'table { border-collapse: collapse; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }';
            echo 'tbody tr:last-child td { border-bottom: none; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; }';
            echo '</style>';
            echo '</div>';


            if (is_numeric($input)) {
                $stmt3 = $db->prepare('SELECT m.title AS movie_title, p.name AS director_name, COUNT(*) AS num_films FROM movies m JOIN stars s ON m.id = s.movie_id JOIN directors d ON m.id = d.movie_id JOIN people p ON d.person_id = p.id WHERE s.person_id = :id GROUP BY m.title, p.name ORDER BY num_films DESC');
                
                $stmt3->bindParam(':id', $input, PDO::PARAM_INT);
            } else {
                $stmt3 = $db->prepare('SELECT m.title AS movie_title, p.name AS director_name, COUNT(*) AS num_films FROM movies m JOIN stars s ON m.id = s.movie_id JOIN directors d ON m.id = d.movie_id JOIN people p ON d.person_id = p.id WHERE s.person_id = (SELECT id FROM people WHERE name = :surname) GROUP BY m.title, p.name ORDER BY num_films DESC');

                $stmt3->bindParam(':surname', $input, PDO::PARAM_STR);
            }

            $stmt3->execute();
            $results3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);



            $num_results3 = count($results3);

            if ($num_results3 == 0) {
                echo '<h2 style="text-align: center; margin: 20px;">No Data found.</h2>';
            }


            // Display table of directors and the number of films directed with the star
            echo '<div style="display: flex;">';
            echo '<div style="width: 100%;">';

            echo '<table style="margin: 0 auto; width: 100%; border-collapse: collapse; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Movie Name</th>';
            echo '<th>Director</th>';
            echo '<th>Number of Films</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($results3 as $result) {
                echo '<tr>';
                echo '<td>' . $result['movie_title'] . '</td>';
                echo '<td>' . $result['director_name'] . '</td>';
                echo '<td>' . $result['num_films'] . '</td>';
                echo '</tr>';
            }
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';

            echo '</div>';
            echo '</div>';
        }


    } else { // Multiple results found
        echo '<div style="display: flex; justify-content: center;">';
        echo '<div style="width: 80%;">';
        echo '<h2 style="text-align: center; margin: 20px 0;">Multiple results found</h2>';
        echo '<table style="margin: 0 auto; width: 80%; border-collapse: collapse; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Name</th>';
        echo '<th>Birth Year</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($results as $result) {
            echo '<tr>';
            echo '<td>' . $result['id'] . '</td>';
            echo '<td>' . $result['name'] . '</td>';
            echo '<td>' . $result['birth'] . '</td>';
            echo '</tr>';
        }
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';

        // CSS styles
        echo '<style>';
        echo 'thead th { background-color: #ddd; font-weight: bold; text-align: center; }';
        echo 'tbody tr:nth-child(odd) { background-color: #f2f2f2; }';
        echo 'table td, table th { padding: 8px; }';
        echo 'table { border-collapse: collapse; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }';
        echo 'tbody tr:last-child td { border-bottom: none; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; }';
        echo '</style>';
    }
    ?>
</body>

</html>