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

		$h = 10;
		$w = 9;
		$target = 4;
		$grid1 = new rectGrid($w,$h,$target);

		$grid2 = new rectGrid(10,15,30);

		$grid3 = new rectGrid(6,3,3);


	?>

</body>
</html>