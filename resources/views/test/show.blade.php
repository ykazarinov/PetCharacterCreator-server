<!DOCTYPE html>
<html>
	<head>
		<title>My view</title>
	</head>
	<body>
        Первая переменная: {{ $var1 }}
		Вторая переменная: {{ $var2 }}


<?php
		


		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE');
		header('Access-Control-Allow-Headers: Content-Type');
		
		
		$message = 'Ваш текст: ' . $_POST['name'];
			$result = array('message' => $message);
		 
			echo json_encode(array('message' => $message));




?>
	</body>
</html>