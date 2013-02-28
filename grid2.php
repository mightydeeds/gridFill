<?php

	// GRID # 1
	// this carves a grid 
	// into a collection of rectangles

	class rectGrid {

		public $supply_grid = array();
		public $temp_shapes = array();
		public $final_grid  = array();
		public $g_width     = 0;
		public $g_height    = 0;
		public $target      = false;
		private static $target_area = 0;

		function __construct($g_width=6,$g_height=6,$target=false) {
			
			// $this->g_width  = $g_width;
			// $this->g_height = $g_height;
			// $this->target   = $target;

			// if($this->target){
			// 	self::$target_area = $this->g_width * $this->g_height / $this->target;
			// }

			// $this->fillGrid();

			// while (count($this->supply_grid)>0) {
				
			// 	$this->chooseShape();
				

			// }

			// echo $this->checkGrid(40,30);
			$w = 20;
			$h = 20;
			$shapes = $this->findAll($w,$h);


			echo "expected: ".$this->testSize($w,$h)."\n";
			echo "result: ".count($shapes);

		}

		public function testSize($w,$h){

			// the summation of one dimension 
			// multiplied by the summation of the other

			$size = ($w*($w+1)/2) * ($h*($h+1)/2);

			return $size;

		}

		public function testShape($shape,$scale_x = 20,$scale_y = 20,$color="red") {


			$background = "rgb(".mt_rand(0,255).",".mt_rand(0,255).",".mt_rand(0,255).")";


			list($x,$y,$w,$h) = explode(".", $shape);

			$tmpl = "<div style='background:".$background.";position:absolute;width:%dpx;height:%dpx;top:%dpx;left:%dpx;'></div>";

			return sprintf($tmpl,$w*$scale_x,$h*$scale_y,$y*$scale_y,$x*$scale_x);

		}

		public function checkGrid($scale_x = 20,$scale_y = 20) {

			$supplyHTML = "";

			foreach ($this->supply_grid as $coor => $data) {
				
				$x = $data[0]*$scale_x;
				$y = $data[1]*$scale_y;
				$w = $scale_x;
				$h = $scale_y;

				$tmpl = "<div style='float:left;border:1px solid gainsboro;position:absolute;width:%dpx;height:%dpx;top:%dpx;left:%dpx;'></div>";

				$supplyHTML .= sprintf($tmpl,$w,$h,$y,$x);

			}

			$usedHTML = "";

			foreach ($this->final_grid as $shape) {
				
				$usedHTML .= $this->testShape($shape,$scale_x,$scale_y,"blue");

			}

			$gridTmpl = "<div class='grid' style='float:left;border:2px solid green;margin:10px;position:relative;width:%dpx;height:%dpx;'>%s</div>";

			return sprintf($gridTmpl,$scale_x*$this->g_width,$scale_y*$this->g_height,$supplyHTML.$usedHTML);

		}

		public function fillGrid() {

			$grid = array();
			
			foreach (range(0, $this->g_height-1) as $y) {

				foreach (range(0, $this->g_width-1) as $x) {

					$this->supply_grid[$x.".".$y] = array($x,$y);

				}

			}	

		}

		public function buildShape($x1,$y1,$x2,$y2){

			$shape = array();

			foreach(range($y1,$y2) as $yc){

				foreach (range($x1,$x2) as $xc) {
					
					$shape[] = $xc.".".$yc;

				}

			}

			return $shape;

		}

		public function findAll($w,$h) {

			$shapes = array();

			for($yi=0; $yi < $h; $yi++){

				for($xi=0; $xi < $w; $xi++) {
					
					// for every starting point

					for($xf=$xi; $xf < $w; $xf++) {
						
						for( $yf=$yi; $yf < $h; $yf++) {

							$shapes[] = $xi.".".$yi.".".$xf.".".$yf;
							// $shapes[] = array($xi,$yi,$xf,$yf);
							// $shapes[] = $this->buildShape($xi,$yi,$xf,$yf);

						}

					}

				}

			}

			return $shapes;

		}

		public function subtract_shape($shape){

			list($xi,$yi,$w,$h) = explode(".", $shape);

			for ($y=$yi; $y < $yi+$h; $y++) { 

				for ($x=$xi; $x < $xi+$w; $x++) { 
					
					// echo "remove ".$x.".".$y."<br/>";

					unset($this->supply_grid[$x.".".$y]);

				}

			}


		}

		private static function sort_shapes($a,$b){

			$target_area = self::$target_area;

			list($xa,$ya,$wa,$ha) = explode(".", $a);
			list($xb,$yb,$wb,$hb) = explode(".", $b);

			return ( abs($target_area - $wb*$hb) < abs($target_area - $wa*$ha) ) ? +1 : -1;

		}

		public function chooseShape(){

			
			$this->findAll();

			// initial idea for specifying how 
			// large i want the chunks to be based on "target"
			shuffle($this->temp_shapes);

			if($this->target){
				usort($this->temp_shapes, array('rectGrid','sort_shapes'));
			}
			
			$k = 0;

			$shape = $this->temp_shapes[0];
			$this->subtract_shape($shape);
			$this->final_grid[] = $shape;			


		}

	}


	$test = new rectGrid();


?>