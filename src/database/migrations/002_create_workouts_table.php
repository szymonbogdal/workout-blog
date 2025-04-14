<?php

require_once __DIR__ . '/../Migration.php';

class Migration_002_create_workouts_table extends Migration{
	public function up(){
		$this->conn->query("CREATE TABLE IF NOT EXISTS workouts(
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			user_id INT UNSIGNED NOT NULL,
			title VARCHAR(255) NOT NULL,
			difficulty VARCHAR(255) NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		)");
	}
	public function down(){
		$this->conn->query("DROP TABLE IF EXISTS workouts");
	}
}