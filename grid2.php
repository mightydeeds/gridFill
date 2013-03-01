<?php

	// GRID # 1
	// this carves a grid 
	// into a collection of rectangles

	class rectGrid {

		public $supply_grid = array();
		public $final_grid  = array();
		public $g_width     = 0;
		public $g_height    = 0;
		public $target      = false;
		private static $target_area = 0;
		private static $temp_shape = array();

		function __construct($g_width=4,$g_height=4,$target=false) {
			
			$this->g_width  = $g_width;
			$this->g_height = $g_height;
			$this->target   = $target;

			$start = microtime(true); // **********************************
			
			$this->fillSupply($this->g_width,$this->g_height);
			
			$stop = microtime(true); // **********************************
			printf("\ngrid:\t%.5f seconds\n", $stop-$start); // ******
			
			$start = microtime(true); // **********************************
			shuffle($this->supply_grid);
			$stop = microtime(true); // **********************************
			printf("\nshuff:\t%.5f seconds\n", $stop-$start); // ******

			if($this->target){
				self::$target_area = $this->g_width * $this->g_height / $this->target;

				$start = microtime(true); // **********************************
				usort($this->supply_grid, array('rectGrid','sortShapes'));
				$stop = microtime(true); // **********************************
				printf("sort:\t%.5f seconds\n", $stop-$start); // ******
			}

			$total_choosing = 0;

			while (count($this->supply_grid)>0) {
				
				$start = microtime(true); // **********************************
				$this->chooseShape();
				$stop = microtime(true); // **********************************
				$total_choosing += $stop-$start;
				printf("shape:\t%.5f \n", $stop - $start); // ******

			}

			printf("shapes:\t%.5f \n", $total_choosing); // ******

			// echo $this->checkGrid($this->final_grid,40,30);

		}

		public function testSize($w,$h){

			// the summation of one dimension 
			// multiplied by the summation of the other

			$size = ($w*($w+1)/2) * ($h*($h+1)/2);

			return $size;

		}

		public function testShape($shape,$scale_x = 20,$scale_y = 20,$color="red") {

			list($x1,$y1,$x2,$y2) = explode(".", $shape);
			$w = $x2 - $x1 + 1;
			$h = $y2 - $y1 + 1;
			$background = "rgb(".mt_rand(0,255).",".mt_rand(0,255).",".mt_rand(0,255).")";

			$tmpl = "<div style='background:".$background.";position:absolute;width:%dpx;height:%dpx;top:%dpx;left:%dpx;'></div>";

			return sprintf($tmpl,$w*$scale_x,$h*$scale_y,$y1*$scale_y,$x1*$scale_x);

		}

		public function checkGrid($grid,$scale_x = 20,$scale_y = 20) {

			$supplyHTML = "";

			foreach ($grid as $shape) {
				
				$supplyHTML .= $this->testShape($shape,$scale_x,$scale_y);

			}

			$gridTmpl = "<div class='grid' style='float:left;border:2px solid green;margin:10px;position:relative;width:%dpx;height:%dpx;'>%s</div>";

			return sprintf($gridTmpl,$scale_x*$this->g_width,$scale_y*$this->g_height,$supplyHTML.$usedHTML);

		}

		public function fillSupply($w,$h) {

			$shapes = array();

			for($yi=0; $yi < $h; $yi++){
				for($xi=0; $xi < $w; $xi++) {
					// for every starting point
					for($xf=$xi; $xf < $w; $xf++) {
						for( $yf=$yi; $yf < $h; $yf++) {
							$shapes[] = $xi.".".$yi.".".$xf.".".$yf;
						}

					}
				}
			}

			$this->supply_grid = $shapes;

		}

		public function chooseShape(){

			self::$temp_shape = array_pop($this->supply_grid);
			$this->final_grid[] = self::$temp_shape;
			$this->supply_grid = array_filter($this->supply_grid,array('rectGrid','filterOverlaps'));

		}

		private static function doesIntersect($shape1,$shape2) {

			list($ax1,$ay1,$ax2,$ay2) = explode(".", $shape1);
			list($bx1,$by1,$bx2,$by2) = explode(".", $shape2);

			if ($ax1 <= $bx2 && $ax2 >= $bx1 && $ay1 <= $by2 && $ay2 >= $by1){
				return true;
			}
			else{
				return false;
			}

		}

		private static function sortShapes($a,$b){

			// sorts in reverse so that the items i want can be popped off the array one at a time

			$target_area = self::$target_area;

			list($ax1,$ay1,$ax2,$ay2) = explode(".", $a);
			$a_area = ($ay2 - $ay1 + 1) * ($ax2 - $ax1 + 1);
			
			list($bx1,$by1,$bx2,$by2) = explode(".", $b);
			$b_area = ($by2 - $by1 + 1) * ($bx2 - $bx1 + 1);

			return ( abs($target_area - $b_area) < abs($target_area - $a_area) ) ? -1 : 1;

		}

		private static function filterOverlaps($shape){

			if(self::doesIntersect(self::$temp_shape,$shape)){
				return false;
			}
			else{
				return true;
			}

		}

	}

	$start = microtime(true);
		// $test1 = new rectGrid(10,10,3);
		// $test2 = new rectGrid(10,10,5);
		// $test3 = new rectGrid(10,10,10);
		$test4 = new rectGrid(15,15,15);
		// $test5 = new rectGrid(10,10,50);
	$stop = microtime(true);
	printf("\nall grids: %.5f seconds\n", $stop-$start);


?>