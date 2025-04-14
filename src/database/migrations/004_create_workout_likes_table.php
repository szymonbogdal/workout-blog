<?php

require_once __DIR__ . '/../Migration.php';

class Migration_004_create_workout_likes_table extends Migration{
	public function up(){
		$this->conn->query("CREATE TABLE IF NOT EXISTS workout_likes(
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			workout_id INT UNSIGNED NOT NULL,
			user_id INT UNSIGNED NOT NULL,
			FOREIGN KEY (workout_id) REFERENCES workouts(id) ON DELETE CASCADE,
			FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
		)");
	}
	public function down(){
		$this->conn->query("DROP TABLE IF EXISTS workout_likes");
	}
}