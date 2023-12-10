<?php

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
    $requiredArguments = ['file', 'u', 'p', 'h'];
    foreach ($requiredArguments as $arg) {
      if (!isset($options[$arg])) {
        echo "Error: Missing required argument: '$arg'.\n";
        displayHelp();
        exit(1);
      }
    }
  }

  function readCSVtoArray($csvFileName) {
    // Read CSV file into array
    $csvData = array_map('str_getcsv', file($csvFileName));

    // Remove header row if it exists
    if (count($csvData) > 0 && array_keys($csvData[0]) === [0, 1, 2]) {
      array_shift($csvData);
    }
    return $csvData;
  }

  function mysqlConnect($mysqlUsername, $mysqlPassword, $mysqlHost ) {
    $mysqli = new mysqli( $mysqlHost, $mysqlUsername, $mysqlPassword);

    if ($mysqli->connect_error) {
      echo "Error connecting to MySQL: " . $mysqli->connect_error . "\n";
      exit(1);
    }

    return $mysqli;
  }

  // Create Table function
  function createTable($mysqli) {
    $query = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                surname VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE
              )";


    if ($mysqli->query($query) === TRUE) {
      echo "MySQL users table created successfully.\n";
    } else {
      echo "Error creating MySQL users table: " . $mysqli->error . "\n";
      exit(1);
    }
  }

  // Create Table function
  function insertData($mysqli, $csvData) {
    foreach ($csvData as $row) {
      $name = $mysqli->real_escape_string($row[0]); // Assuming the first column is 'name'
      $surname = $mysqli->real_escape_string($row[1]); // Assuming the second column is 'surname'
      $email = $mysqli->real_escape_string($row[2]); // Assuming the third column is 'email'

      $query = "INSERT INTO users (name, surname, email) VALUES ('$name', '$surname', '$email')";

      if ($mysqli->query($query) !== TRUE) {
          echo "Error inserting data into MySQL users table: " . $mysqli->error . "\n";
          exit(1);
      }
    }

    echo "Data inserted into MySQL users table successfully.\n";
  }

  // ***************************************
  // Script Start Here
  // ***************************************

  // • -u – MySQL username (Required username value)
  // • -p – MySQL password  (Required password value)
  // • -h – MySQL host  (Required Host name)
  $shortopts = "u:p:h:";

  // • --file [csv file name] – this is the name of the CSV to be parsed
  // • --create_table – this will cause the MySQL users table to be built (and no further
  // • action will be taken)
  // • --dry_run – this will be used with the --file directive in case we want to run the script but not
  // insert into the DB. All other functions will be executed, but the database won't be altered
  // • --help – which will output the above list of directives with details.
  $longopts  = array(
    "file:",   
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

  checkRequiredArgument($options);

  $csvFileName = $options['file'];
  validateCsvFile($csvFileName);
  echo "CSV File: $csvFileName\n";

  $isCreateTable = isset($options['create_table']);
  echo "Is Create new Table? : $isCreateTable";

  $isDryRun = isset($options['dry_run']);
  echo "Is Dry run? : $isDryRun";

  $mysqlUsername = $options['u'];
  echo "MySQL Username: $mysqlUsername\n";

  $mysqlPassword = $options['p'];
  echo "MySQL Password: $mysqlPassword\n";

  $mysqlHost = $options['h'];
  echo "MySQL Host: $mysqlHost\n";


  $csvData = readCSVtoArray($csvFileName);

  $mysqli = mysqlConnect($mysqlUsername, $mysqlPassword, $mysqlHost);

  if ($isCreateTable) {
    createTable($mysqli);
  }

  if (!$isDryRun) {
    insertData($mysqli, $csvData);
  } else {
    echo "Dry run mode activated. Database won't be altered.\n";
  }

  $mysqli->close();

?>