<?php
	namespace App\Http\Controllers;
	use App\Http\Controllers\Controller;












	
	class TestController extends Controller
	{
		public function show()
		{
			



			
			
			return view('test.show', ['var1' => '1', 'var2' => '2']);


		}
	}