<?php
function currency_format($number){
	$result = number_format($number, 2, ',','.');
	return $result;
}