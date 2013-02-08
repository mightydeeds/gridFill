<?php 
	
	require_once('grid1.php');

?>

<!DOCTYPE html>

<html>
<head>
	<title>Grid Test</title>
	<meta charset='utf-8'> 
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
</head>
<body>

	<?php

		$h = 6;
		$w = 12;

		$grid = new rectGrid($w,$h);

		echo "<hr/>Predicted size <br/>";
		echo $grid->testSize($w,$h);

	?>

</body>
</html>