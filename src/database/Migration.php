<?php

abstract class Migration{
	protected $conn;
	public function __construct($conn){
		$this->conn = $conn;
	}
	abstract public function up();
	abstract public function down();
}