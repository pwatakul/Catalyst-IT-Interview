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

?>