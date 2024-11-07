<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Movies Database</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<div class="container">
		<h1 class="headline">Movies Database</h1>
		<div id="cover">
			<form method="get" action="search_person.php">
				<div class="tb">
					<div class="td"><input type="text" id="input" name="input" placeholder="Search" required></div>
					<div class="td" id="s-cover">
						<button type="submit">
							<div id="s-circle"></div>
							<span></span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>

</html>