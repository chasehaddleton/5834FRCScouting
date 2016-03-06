<?php

class Sponsors {
	private $sponsorData = array();

	// Store the ID of the sponsor.
	public function __construct($id) {
		$query = "SELECT * FROM sponsors WHERE id = :id";
		$query_params = array(':id' => $id);

		$stmt = executeSQL($query, $query_params);
		$results = $stmt->fetch();

		foreach ($results as $col => $data) {
			$this->sponsorData[$col] = $data;
		}
	}

	public function delete() {
		$query = "DELETE FROM sponsors WHERE id = :id";

		$query_params = array(':id' => $this->sponsorData['id']);

		executeSQL($query, $query_params);
	}

	public function __get($name) {
		return $this->sponsorData[$name];
	}

	public function __set($name, $value) {
		$query = "UPDATE sponsors SET $name = :value WHERE id = :id";
		$query_params = array(':id' => $this->sponsorData['id'], ':value' => htmlspecialchars($value));

		executeSQL($query, $query_params);
	}

	public function isDonor() {
		if (strcasecmp($this->sponsorData['donor'], "1") == 0) {
			return "checked";
		}

		return null;
	}
}