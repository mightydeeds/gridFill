<?php

	// GRID # 1
	// this carves a grid 
	// into a collection of rectangles

	class rectGrid {

		var $supply_grid = array();
		var $temp_shapes = array();
		var $final_grid  = array();
		var $g_width     = 0;
		var $g_height    = 0;

		function __construct($g_width=6,$g_height=6) {
			
			$this->g_width  = $g_width;
			$this->g_height = $g_height;

			$this->fillGrid();

			// unset($this->supply_grid["1.1"]);
			// unset($this->supply_grid["2.1"]);
			// unset($this->supply_grid["2.2"]);
			// unset($this->supply_grid["5.5"]);
			// unset($this->supply_grid["4.5"]);

			while (count($this->supply_grid)>0) {
				
				$this->chooseShape();
				echo $this->checkGrid(30);

			}
			


		}

		public function testSize($w,$h){

			// the summation of one dimension 
			// multiplied by the summation of the other

			$size = ($w*($w+1)/2) * ($h*($h+1)/2);

			return $size;

		}

		public function testShape($shape,$scale = 20,$color="red") {


			list($x,$y,$w,$h) = explode(".", $shape);

			$tmpl = "<div style='border:1px solid ".$color.";position:absolute;width:%dpx;height:%dpx;top:%dpx;left:%dpx;'></div>";

			return sprintf($tmpl,$w*$scale,$h*$scale,$y*$scale,$x*$scale);

		}

		function checkGrid($scale = 20) {

			$supplyHTML = "";

			foreach ($this->supply_grid as $coor => $data) {
				
				$x = $data[0]*$scale;
				$y = $data[1]*$scale;
				$w = $scale;
				$h = $scale;

				$tmpl = "<div style='border:1px solid yellow;position:absolute;width:%dpx;height:%dpx;top:%dpx;left:%dpx;'></div>";

				$supplyHTML .= sprintf($tmpl,$w,$h,$y,$x);

			}

			$usedHTML = "";

			foreach ($this->final_grid as $shape) {
				
				$usedHTML .= $this->testShape($shape,$scale,"blue");

			}

			$gridTmpl = "<div class='grid' style='border:1px solid green;margin:10px;float:left;position:relative;width:%dpx;height:%dpx;'>%s</div>";

			return sprintf($gridTmpl,$scale*$this->g_width,$scale*$this->g_height,$supplyHTML.$usedHTML);

		}

		function checkChoices($scale = 20) {

			$choiceHTML = "";

			foreach ($this->temp_shapes as $shape) {
				
				$choiceHTML .= $this->testShape($shape,$scale,"salmon");

			}

			$gridTmpl = "<div class='grid' style='border:1px solid green;margin:10px;float:left;position:relative;width:%dpx;height:%dpx;'>%s</div>";

			return sprintf($gridTmpl,$scale*$this->g_width,$scale*$this->g_height,$choiceHTML);

		}

		public function fillGrid() {

			$grid = array();
			
			foreach (range(0, $this->g_height-1) as $y) {

				foreach (range(0, $this->g_width-1) as $x) {

					$this->supply_grid[$x.".".$y] = array($x,$y);

				}

			}	

		}

		public function findAll() {

			$possible_shapes = array();

			foreach ($this->supply_grid as $coor => $data) {

				$current_x = $data[0];
				$current_y = $data[1];
				$y_max     = $this->g_height;

				$x_available = true;
				
				while (array_key_exists($current_x.".".$current_y, $this->supply_grid)) {

					while (array_key_exists($current_x.".".$current_y, $this->supply_grid) && $current_y < $y_max) {

						// shapes are in the format x.y.w.h
						$shape = array($data[0],$data[1],$current_x-$data[0]+1,$current_y-$data[1]+1);
						$possible_shapes[] = implode(".", $shape);
						$current_y ++;

					}

					$y_max = $current_y;
					$current_y = $data[1];
					$current_x ++;

				}

			}

			$this->temp_shapes = $possible_shapes;

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

		public function chooseShape(){

			
			$this->findAll();
			// sort by area ? maybe..... modify temp_shapes
			$k = array_rand($this->temp_shapes);
			$shape = $this->temp_shapes[$k];
			$this->subtract_shape($shape);
			$this->final_grid[] = $shape;			


		}

	}

?>