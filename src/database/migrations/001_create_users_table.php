<?php

require_once __DIR__ . '/../Migration.php';

class Migration_001_create_users_table extends Migration{
	public function up(){
		$this->conn->query("CREATE TABLE IF NOT EXISTS users(
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			username VARCHAR(50) NOT NULL UNIQUE,
			password VARCHAR(255) NOT NULL
		)");
	}
	public function down(){
		$this->conn->query("DROP TABLE IF EXISTS users");
	}
}