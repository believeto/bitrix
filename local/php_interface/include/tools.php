<?php

function dump() {
//	global $USER;
//	if (!$USER->IsAdmin()) {
//		return false;
//	}

	echo "<pre>";
	print_r(
		func_get_args()
	);
	echo "</pre>";
}

