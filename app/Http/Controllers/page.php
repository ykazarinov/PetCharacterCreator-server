<?php
	namespace App\Http\Controllers;
	use App\Http\Controllers\Controller;
			
	class page extends Controller
	{




		public function showAll($id)
		{
			$pages = [
			1 => 'страница 1',
			2 => 'страница 2',
			3 => 'страница 3',
			4 => 'страница 4',
			5 => 'страница 5',
			];

			if($id <= count($pages) AND $id > 0 ){
				return $pages[$id];
			}
			else{
				return 'error';
			}
			
		}

	
	}