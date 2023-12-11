# The following is test cases that used to test functionality of code

# Check Help function
- php user_upload.php --help | (Test Help Function) | Pass

# Check add all options
- php user_upload.php --file users.csv --create_table --dry_run -u root -h localhost || Pass

# Check insert without dry run (Normal running)
- php user_upload.php --file users.csv -u root -h localhost || Pass

# Check conection error with database (Permission) 
- php user_upload.php --file users.csv --create_table -u pwatakul -h localhost | Check Deny Access (Miss Password)| Pass
- php user_upload.php --file users.csv --create_table -u pwatakul -p 12345 -h localhost | Check Deny Access| Pass

# Check about file
- php user_upload.php  --create_table -u root -h localhost | Check Missing File| Pass
- php user_upload.php  --file user.csv --create_table -u root -h localhost | Check File does not exist| Pass

# Check about create table (Note Have been check both database exist and database not exist)
- php user_upload.php --file users.csv --create_table -u pwatakul -h localhost | Check table is exist and table is not exist| Pass
- php user_upload.php --file users.csv -u pwatakul -h localhost | Check table is exist and table is not exist| Pass

# Check about host
- php user_upload.php --file users.csv --create_table -u pwatakul -p 12345 -h locat | Check wrong host name| Pass
- php user_upload.php --file users.csv --create_table -u pwatakul -p 12345 | Check missing hostname| Pass

# Check about password
- php user_upload.php --file users.csv --create_table -u pwatakul -p 12345 -h localhost | Check Right Password| Pass
- php user_upload.php --file users.csv --create_table -u pwatakul -p 12 -h localhost | Check Wrong Password| Pass
- php user_upload.php --file users.csv --create_table -u pwatakul -h localhost | Don't have password| Pass

# Check about username
- php user_upload.php --file users.csv -u asd -h localhost | Wrong username| Pass
- php user_upload.php --file users.csv -h localhost | Wrong username| Pass

# Check about create new database (Check both condition that database is already exist and not already exist)
- php user_upload.php --file users.csv -u asd -h localhost | Dont put database name| Pass
- php user_upload.php --file users.csv -u root -h localhost --create_table --dbName test1 | Put database name| Pass

# Check Dry run
- php user_upload.php --file users.csv --create_table --dry_run -u root -h localhost || Pass
