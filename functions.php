<?php
	function use_template($str_path, $array_data) {
		$result = '';
		if (file_exists($str_path)) {
			foreach($array_data as $var_name => $value) {
				${$var_name} = $value;
			};
			ob_start();
			require_once($str_path);
			$result = ob_get_clean();
		};
		return $result;
	}
?>