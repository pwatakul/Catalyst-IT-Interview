<?php



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
    
    exit(0);
  }



?>