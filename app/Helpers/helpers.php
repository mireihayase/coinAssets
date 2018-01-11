<?php

if(!function_exists('num2per')) {
	function num2per($number, $total, $precision = 1) {
		if ($number < 0) {
			return 0;
		}

		try {
			$percent = ($number / $total) * 100;
			return round($percent, $precision);
		} catch (Exception $e) {
			return 0;
		}
	}
}

if(!function_exists('getPlusOrMinusClass')) {
	function getPlusOrMinusClass($number) {
		$class = $number >= 0 ? 'success' : 'danger';
		return $class;
	}
}