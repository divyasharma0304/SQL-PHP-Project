<!DOCTYPE html>
<html>
<head>
	<title>Update Instructor Information</title>
	<style>
		body {
			background-color: #f2f2f2;
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
		}
		h1 {
			background-color: #006699;
			color: #fff;
			margin: 0;
			padding: 20px;
			text-align: center;
		}
		form {
			background-color: #fff;
			border: 1px solid #ccc;
			border-radius: 5px;
			box-shadow: 0 0 5px #ccc;
			margin: 20px auto;
			max-width: 500px;
			padding: 20px;
		}
		label {
			display: block;
			font-weight: bold;
			margin-bottom: 10px;
		}
		input[type="text"], input[type="password"], input[type="email"] {
			border: 1px solid #ccc;
			border-radius: 3px;
			font-size: 16px;
			padding: 10px;
			width: 100%;
		}
		input[type="submit"] {
			background-color: #006699;
			border: none;
			border-radius: 3px;
			color: #fff;
			cursor: pointer;
			font-size: 16px;
			padding: 10px;
			margin-top: 20px;
			width: 100%;
		}
		input[type="submit"]:hover {
			background-color: #005580;
		}
	</style>
</head>
<body>
	<h1>Update Instructor Information</h1>
    <?php
		// check if the form has been submitted
		if(isset($_POST['submit'])){
			// connect to the database
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "University";
			$conn = new mysqli($servername, $username, $password, $dbname);

			// check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			}

			// retrieve form data
			$ID = $_POST['ID'];
			$name = $_POST['name'];
			$dept_name = $_POST['dept_name'];
			$salary = $_POST['salary'];

			// update data in database
			$sql = "UPDATE instructor SET name='$name', dept_name='$dept_name', salary='$salary' WHERE ID='$ID'";
			if ($conn->query($sql) === TRUE) {
			  echo "Instructor information updated successfully";
			} else {
			  echo "Error: " . $sql . "<br>" . $conn->error;
			}

			// close database connection
			$conn->close();
		} else {
			// connect to the database
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "University";
			$conn = new mysqli($servername, $username, $password, $dbname);

			// check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			}

			// retrieve instructor information based on ID
			$ID = $_POST['ID'];
			$sql = "SELECT * FROM instructor WHERE ID='$ID'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$name = $row['name'];
				$dept_name = $row['dept_name'];
				$salary = $row['salary'];
				echo "<form method='post'>";
				echo "<label for='name'>Name:</label>";
				echo "<input type='text' name='name' value='$name' required><br>";
				echo "<label for='dept_name'>Department Name:</label>";
				echo "<input type='dept_name' name='dept_name' value='$dept_name' required><br>";
				echo "<label for='salary'>Salary:</label>";
				echo "<input type='salary' name='salary' value='$salary' required><br>";
				echo "<input type='hidden' name='ID' value='$ID'>";
				echo "<input type='submit' name='submit' value='Update Instructor'>";
				echo "</form>";
			} else {
				echo "No instructor found with ID: $ID";
			}

			// close database connection
			$conn->close();
		}
	?>
    <form method="post">
		<label for="ID">Instructor ID:</label>
		<input type="text" name="ID" required><br>
		<input type="submit" name="search" value="Search">
	</form>
</body>
</html>
