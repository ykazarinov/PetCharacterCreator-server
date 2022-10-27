<?php
	namespace App\Http\Controllers;
	use App\Http\Controllers\Controller;
	
	class test extends Controller
	{
		public function sum($param1, $param2) // задаем параметры
		{
			return $param1 + $param2;	
		}
	}