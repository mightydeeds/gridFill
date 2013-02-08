<?php

	// GRID # 1
	// this carves a grid 
	// into a collection of rectangles

	class rectGrid {

		var $supply_grid = array();
		var $temp_shapes = array();
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

			$this->findAll();

			$this->chooseShape();

		}

		public function testSize($w,$h){

			// the summation of one dimension 
			// multiplied by the summation of the other

			$size = ($w*($w+1)/2) * ($h*($h+1)/2);

			return $size;

		}

		public function testShape($shape,$scale = 50) {

			list($x,$y,$w,$h) = explode(".", $shape);

			$html = "<div style='border:1px solid red;background:lightyellow;position:absolute;width:%dpx;height:%dpx;top:%dpx;left:%dpx;'></div>";

			return sprintf($html,$w*$scale,$h*$scale,$y*$scale,$x*$scale);

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

						$shape = array($data[0],$data[1],$current_x+1,$current_y+1);
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

		public function chooseShape(){

			echo "<pre>";
			print_r($this->temp_shapes);
			echo "</pre>";

			// shapes are in the format x.y.w.h

			// sort by area ? maybe.....

			// choose random

			$k = array_rand($this->temp_shapes);


			echo $this->testShape($this->temp_shapes[$k],10);

			// remove all units used by shape from supply grid


		}

	}

?>