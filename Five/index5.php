<!DOCTYPE html>
<html>
<head>
	<title>Add Classroom</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-image: url('https://images.pexels.com/photos/256490/pexels-photo-256490.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1');
			background-size: cover;
		}
		h1 {
			text-align: center;
			margin-top: 50px;
			color: #ffffff;
		}
		form {
			background-color: #ffffff;
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
			margin: auto;
			max-width: 500px;
		}
		label {
			display: block;
			margin-bottom: 10px;
			color: #333333;
		}
		input[type="text"], input[type="number"], textarea {
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			width: 100%;
			margin-bottom: 20px;
			box-sizing: border-box;
			font-size: 16px;
			resize: none;
		}
		input[type="submit"] {
			background-color: #4CAF50;
			color: #fff;
			border: none;
			padding: 10px 20px;
			border-radius: 5px;
			cursor: pointer;
			font-size: 16px;
			transition: background-color 0.3s ease;
		}
		input[type="submit"]:hover {
			background-color: #3e8e41;
		}
	</style>
</head>
<body>
	<h1>Add Classroom</h1>
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
			$building = $_POST['building'];
			$room_number = $_POST['room_number'];
			$capacity = $_POST['capacity'];

			// insert data into database
			$sql = "INSERT INTO classroom (building, room_number, capacity) VALUES ('$building', '$room_number', '$capacity')";
			if ($conn->query($sql) === TRUE) {
			  echo "<p style='color:green;'>Classroom added successfully</p>";
			} else {
			  echo "<p style='color:red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
			}

			// close database connection
			$conn->close();
		}
	?>

	<form method="post">
		<label for="building">Building:</label>
		<input type="text" name="building" required>

		<label for="room_number">Room Number:</label>
		<input type="text" name="room_number" required>

		<label for="capacity">Capacity:</label>
		<input type="number" name="capacity" required>

		<input type="submit" name="submit" value="Add Classroom">
	</form>
</body>
</html>