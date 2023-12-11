# Summary
This repo has a purpose for job interviews. There are two tasks (Script Task and Logic Task) 

Implementer: Patawat Watakul
Date: 11/12/2023
# Logic Task
Just run the following command

```
php foobar.php 
```

# Script Task
Note: I added one more option (dbName);

## Usage:\n";

```
php script.php --file [csv file name] --create_table --dry_run -u [MySQL username] -p [MySQL password] -h [MySQL host] --help;
```
| Options   |      Description      | 
|----------|:-------------:|
| -u |  MySQL username (Required username value) |
| -p | MySQL password  (Required password value) |
| -h |  MySQL host  (Required Hostname) |
| --file [csv file name] |  his is the name of the CSV to be parsed |
| --dbName |   MySQL Database Name  (Default = myDB) |
|--create_table |  This will cause the MySQL users table to be built (and no further action will be taken) |
| --dry_run |  This will be used with the --file directive in case we want to run the script but not insert it into the DB. All other functions will be executed, but the database won't be altered |
| --help |  which will output the above list of directives with details. |

## Test case list

### Check the Help function
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --help | (Test Help Function) | Pass |

### Check add all options
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv --create_table --dry_run -u root -h localhost |  | Pass |

### Check insert without dry run (Normal running)
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv -u root -h localhost |  | Pass |

### Check connection error with database (Permission) 
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv --create_table -u pwatakul -h localhost | Check Deny Access (Miss Password) | Pass |
| php user_upload.php --file users.csv --create_table -u pwatakul -p 12345 -h localhost | Check Deny Access | Pass |


### Check about file
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php  --create_table -u root -h localhost | Check Missing File | Pass |
| php user_upload.php  --file user.csv --create_table -u root -h localhost | Check File does not exist | Pass |


### Check about creating a table (Note Have been checking both databases exists and the database does not exist)
### Check about file
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv --create_table -u pwatakul -h localhost | Check table is exist and table is not exist | Pass |
| php user_upload.php --file users.csv -u pwatakul -h localhost | Check table is exist and table is not exist | Pass |

### Check about host
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv --create_table -u pwatakul -p 12345 -h locat | Check wrong host name | Pass |
| php user_upload.php --file users.csv --create_table -u pwatakul -p 12345 | Check missing hostname | Pass |

### Check about password
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv --create_table -u pwatakul -p 12345 -h localhost | Check Right Password | Pass |
| php user_upload.php --file users.csv --create_table -u pwatakul -p 12 -h localhost | Check Wrong Password | Pass |
| php user_upload.php --file users.csv --create_table -u pwatakul -h localhost |  Don't have password | Pass |


### Check about username
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv -u asd -h localhost | Wrong username | Pass |
| php user_upload.php --file users.csv -h localhost | Wrong username | Pass |


### Check about creating a new database (Check both conditions that database already exists and not already exist)
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv -u asd -h localhost | Dont put database name | Pass |
| php user_upload.php --file users.csv -u root -h localhost --create_table --dbName test1 | Put database name | Pass |


### Check Dry run
| command   |      Description      |      Pass?      | 
|----------|:-------------:|:-------------:|
| php user_upload.php --file users.csv --create_table --dry_run -u root -h localhost | | Pass |

