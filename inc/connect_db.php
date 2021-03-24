<?php
	$env = "dev";

	if ($env == "dev") {
		$connect = mysqli_connect("localhost","root","") or die("Database connection failed.");
		// $connect = mysqli_connect("localhost","jasondashboard","n93uxa*{t+9-") or die("Database connection failed.");
		mysqli_select_db($connect, "irving_db");
	} else if ($env == "staging") {
		$connect = mysqli_connect("localhost","","") or die("Database connection failed.");
		mysqli_select_db($connect, "");
	} else {
		$connect = mysqli_connect("localhost","","") or die("Database connection failed.");
		mysqli_select_db($connect, "");
	}

	function getEmployees() {
		global $connect;
		$query = "
			SELECT * FROM	`employees` ";
		$result = mysqli_query($connect, $query);
		return mysqli_fetch_all($result, MYSQLI_ASSOC);
	}

	function checkProject($id, $name) {
		global $connect;

		$query = "
			SELECT
				p.*
			FROM
				projects AS p,
				(SELECT e.`name`, e.`position` FROM `employees` AS e WHERE e.id = $id) AS temp
			WHERE 
				p.project_name = '$name' AND
				p.employee_name = temp.name AND 
				p.position = temp.position 
			";
		$result = mysqli_query($connect, $query);
		$cnt = mysqli_num_rows($result);
		if ($cnt < 1){
			saveProject($id, $name);
		}
		return $cnt;
	}

	function saveProject($id, $name) {
		global $connect;
	
		$query = "
			INSERT INTO projects (`project_name`, `employee_name`, `position`)
			SELECT '$name', `name`, `position` FROM employees WHERE `id` = $id";
		mysqli_query($connect, $query);
		return mysqli_insert_id($connect);
	}

