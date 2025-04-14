<?php

require_once __DIR__ . '/../Migration.php';

class Migration_003_create_workout_days_table extends Migration{
	public function up(){
		$this->conn->query("CREATE TABLE IF NOT EXISTS workout_days(
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			workout_id INT UNSIGNED NOT NULL,
			day_order INT UNSIGNED,
			body TEXT NOT NULL,
			FOREIGN KEY (workout_id) REFERENCES workouts(id) ON DELETE CASCADE
		)");
	}
	public function down(){
		$this->conn->query("DROP TABLE IF EXISTS workout_days");
	}
}