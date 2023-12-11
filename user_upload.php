<?php

  // Function to display manual of script

  // • -u – MySQL username (Required username value)
  // • -p – MySQL password  (Required password value)
  // • -h – MySQL host  (Required Host name)

  // • --file [csv file name] – this is the name of the CSV to be parsed
  // • --create_table – this will cause the MySQL users table to be built (and no further
  // • action will be taken)
  // • --dry_run – this will be used with the --file directive in case we want to run the script but not
  // insert into the DB. All other functions will be executed, but the database won't be altered
  // • --help – which will output the above list of directives with details.

  function displayHelp() {
    echo "Usage:\n";

    echo "
    php script.php --file [csv file name] --create_table --dry_run -u [MySQL username] -p [MySQL password] -h [MySQL host] --help\n";

    echo "
    • -u                      MySQL username (Required username value)
    • -p                      MySQL password  (Required password value)
    • -h                      MySQL host  (Required Host name)\n";
    echo "
    • --file [csv file name]  this is the name of the CSV to be parsed
    • --dbName                 MySQL Database Name  (Default = myDB)
    • --create_table          this will cause the MySQL users table to be built (and no further action will be taken)
    • --dry_run               this will be used with the --file directive in case we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered
    • --help                  which will output the above list of directives with details.\n";
  
  }

  // Function to validate CSV File (Validate File exist and Also validate file name)
  function validateCsvFile($fileName) {
    if (!file_exists($fileName)) {
      echo "Error: The specified CSV file '$fileName' does not exist.\n";
      exit(1);
    }

    if (!is_readable($fileName) || pathinfo($fileName, PATHINFO_EXTENSION) !== 'csv') {
      echo "Error: The specified file '$fileName' is not a valid CSV file.\n";
      exit(1);
    }
  }

  // Function to check required Argument
  function checkRequiredArgument($options) {
    // $requiredArguments = ['file', 'u', 'p', 'h'];
    $requiredArguments = ['file', 'u', 'h'];
    foreach ($requiredArguments as $arg) {
      if (!isset($options[$arg])) {
        echo "\033[01;31m Error: Missing required argument: '$arg'. \033[0m\n";
        exit(1);
      }
    }
  }

  // Function to Convert CSV file
  function readCSVtoArray($csvFileName) {
    // Read CSV file into array
    try {
      $csvData = array_map('str_getcsv', file($csvFileName));

      // Remove header row if it exists
      if (count($csvData) > 0 && array_keys($csvData[0]) === [0, 1, 2]) {
        array_shift($csvData);
      }
      return $csvData;
    }  catch(Exception $e) {
      echo "\033[01;31m Read CSV Error: " . $e->getMessage() . "\033[0m\n";
      exit(0);
    }
    
  }

  // Function to make connection with mysql server
  function mysqlConnect($mysqlUsername, $mysqlPassword, $mysqlHost ) {
    try {
      $mysqli = new mysqli( $mysqlHost, $mysqlUsername, $mysqlPassword);

      if ($mysqli->connect_error) {
        echo "Error connecting to MySQL: " . $mysqli->connect_error . "\n";
        exit(1);
      }
      echo "Connect to server: \033[92msuccessful \033[0m\n";
      return $mysqli;
    }
    catch(Exception $e) {
      $message = $e->getMessage();
      echo "\033[01;31m Unsuccessful make a connection with Database: $message  \033[0m";
      exit(0);
    }
  }

  // Create Table function
  function createTable($mysqli) {
    $query = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                surname VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE
              )";
    try {
      if ($mysqli->query($query) === TRUE) {
        echo "MySQL users table created \033[92m successful.\033[0m\n";
      }
    } catch (Exception $e) {
      echo "\033[01;31mError creating MySQL users table: " . $e->getMessage() . "\033[0m\n";
      exit(1);
    }
  }

  // Create Table function
  function insertData($mysqli, $csvData, $isDryRun) {
    foreach ($csvData as $row) {
      $name = $mysqli->real_escape_string(ucfirst(strtolower($row[0]))); // Assuming the first column is 'name'
      $surname = $mysqli->real_escape_string(ucfirst(strtolower($row[1]))); // Assuming the second column is 'surname'
      $email = $mysqli->real_escape_string(strtolower($row[2])); // Assuming the third column is 'email'

      // Validate Email

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "\033[33m Warning: Invalid email format for '$email'. No insert made to the database.\033[0m\n";
        continue; // Skip to the next iteration
      }

      $query = "INSERT INTO users (name, surname, email) VALUES ('$name', '$surname', '$email')";
      
      try {
        if (!$isDryRun) {
          $mysqli->query($query);
        }
        echo "\033[92m Insert: " . "Name: " . $name . " Surname: " . $surname . " Email:" . $email . " successfully\033[0m\n";
      }
      //catch exception
      catch(Exception $e) {
        echo "\033[31m Warning: Inserting data into MySQL users table: " . $e->getMessage() . "\033[0m\n";
      }
    }

   
  }

  // Create Database
  function createDatabase($mysqli,  $dbName) {
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    try {
      if ($mysqli->query($sql) === TRUE) {
        echo "Database: $dbName created \033[92msuccessful \033[0m\n";
      }
    } catch (Exception $e) {
      echo "Error creating database: " . $e->getMessage();
      exit(1);
    }

  }

  // ***************************************
  // Script Start Here
  // ***************************************

  $shortopts = "u:p:h:";

  $longopts  = array(
    "file:",   
    "dbName:",   
    "create_table",   
    "dry_run",      
    "help",    
  );

  $options = getopt($shortopts, $longopts);

  // Display help if requested
  if (isset($options['help'])) {
    displayHelp();
    exit(0);
  }

  // Check does the user put all requried arguments
  checkRequiredArgument($options);
  
  // Get CSV file and validate it
  $csvFileName = $options['file'];
  validateCsvFile($csvFileName);
  echo "CSV File: $csvFileName\n";

  // Get is create value
  $isCreateTable = isset($options['create_table']) ? true : false;
  $isCreateTableText = isset($options['create_table']) ? "true" : "false";
  echo "Is Create new Table? : $isCreateTableText\n";

  // Get Is Dry run
  $isDryRun = isset($options['dry_run']);
  $isDryRunText = isset($options['dry_run']) ? "true" : "false";

  echo "Is Dry run? : $isDryRunText\n";

  // Get username string
  $mysqlUsername = isset($options['u']) ? $options['u'] : "";
  // \e[42mGreen
  echo "MySQL Username: \033[42m$mysqlUsername\033[0m\n";

  // Get password string
  $mysqlPassword = isset($options['p']) ? $options['p'] : "";
  echo "MySQL Password: \033[42m$mysqlPassword\033[0m\n";

  // Get mysqlHost string
  $mysqlHost = $options['h'];
  echo "MySQL Host: \033[42m$mysqlHost\033[0m\n";

  // Read CSV file and convert it into array
  $csvData = readCSVtoArray($csvFileName);

  // Make connection

  $mysqli = mysqlConnect($mysqlUsername, $mysqlPassword, $mysqlHost);


  // Create new database
  // Set DB Name (Default --> myDB)
  $dbName = isset($options['dbName']) ? $options['dbName'] : "myDB";

  createDatabase($mysqli, $dbName);
  
  $mysqli->query("USE $dbName");
  // Make a new user table if requested
  if ($isCreateTable) {
    createTable($mysqli);
  }

  // Insert data into database if not dry run
  insertData($mysqli, $csvData, $isDryRun);
  if ($isDryRun) {
    echo "Dry run mode activated. Database won't be altered.\n";
  }
  echo "\033[92mData inserted into MySQL users table successfully. Hooray!!!!!!!!!!!!!!!!\033[0m\n";

  $mysqli->close();

