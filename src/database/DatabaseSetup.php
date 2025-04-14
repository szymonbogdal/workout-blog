<?php
class DatabaseSetup{
	private $conn;
	public function __construct(){
		$dbConfig = require_once __DIR__ . '/../config/config.php';
		try{
			$this->conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password']);
			$this->createDatabase($dbConfig['database']);
			$this->conn->select_db($dbConfig['database']);
			$this->createMigrationsTable();

		}catch(mysqli_sql_exception $e){
			echo "Connection failed: " . $e->getMessage() . "\n";
		}
	}

	private function createDatabase($db){
		$this->conn->query("CREATE DATABASE IF NOT EXISTS $db");
	}

	private function createMigrationsTable(){
		$this->conn->query("CREATE TABLE IF NOT EXISTS migrations(
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			migration VARCHAR(255) NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)");
	}

	public function runMigrations(){
		echo "Running migrations...\n";

		try{
			//Get the list of executed migrations
			$executedMigrations = [];
			$result = $this->conn->query("SELECT migration FROM migrations");
			while($row = $result->fetch_assoc()){
				$executedMigrations[] = $row['migration'];
			}

			//Get the list of migration files
			$migrations = glob(__DIR__ . '/migrations/*.php');
			sort($migrations);
			foreach($migrations as $migration){
				//Check if the migration has already been executed
				if(in_array(basename($migration, '.php'), $executedMigrations)){
					continue;
				}

				//Include the migration file and run the up method
				require_once $migration;
				$name = basename($migration, '.php');
				$className = "Migration_$name";
				$migrationInstance = new $className($this->conn);
				$migrationInstance->up();
				$this->conn->query("INSERT INTO migrations (migration) VALUES ('$name')");
				echo "Migrated: $name\n";
			}
	
			echo "All migrations have been run.\n";
		}catch (Exception $e){
			echo "Error running migrations: " . $e->getMessage() . "\n";
			exit(1);
		}
		
	}

	public function rollbackMigrations(){
		echo "Rollback migrations...\n";

		$result = $this->conn->query("SELECT migration FROM migrations ORDER BY migration DESC");
		while($row = $result->fetch_assoc()){
			require_once __DIR__ . "/migrations/$row[migration].php";
			$className = "Migration_$row[migration]";
			$migrationInstance = new $className($this->conn);
			$migrationInstance->down();
			$this->conn->query("DELETE FROM migrations WHERE migration = '$row[migration]'");
			echo "Removed migration: $row[migration]\n";
		}

		echo "All migrations have been rolled back.\n";
	}
}