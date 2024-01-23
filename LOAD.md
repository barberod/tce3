# Load the tce3 app with data and files from tce2

## Drop the database

```bash
mysql -u someoneexisting -p
```

```sql
DROP DATABASE tce3_db;
```

## Create the database

```bash
mysql -u someoneexisting -p
```

```sql
CREATE DATABASE tce3_db;
```

## Set up the database

```bash
# ~/projects/tce3
php bin/console tce3:database-setup Risk-Accepted
```

## Set auto increment higher on evaluations

More on that later.

## Instructions for loading data

The slash at the end is necessary.

```bash
# ~/projects/tce3
export TCE2=https://something.registrar.gatech.edu/something/
```

### users

```bash
# ~/projects/tce3
./data/command/user/download.sh
```

```bash
sudo chmod +x ./data/command/user/load.sh
```

```bash
./data/command/user/load.sh
```

### course 

```bash
# ~/projects/tce3
./data/command/course/download.sh
```

```bash
sudo chmod +x ./data/command/course/load.sh
```

```bash
./data/command/course/load.sh
```

### institution

```bash
# ~/projects/tce3
./data/command/institution/download.sh
```

```bash
sudo chmod +x ./data/command/institution/load.sh
```

```bash
./data/command/institution/load.sh
```

### department

```bash
# ~/projects/tce3
./data/command/department/download.sh
```

```bash
sudo chmod +x ./data/command/department/load.sh
```

```bash
./data/command/department/load.sh
```

### evaluation

```bash
# ~/projects/tce3
./data/command/evaluation/download.sh
```

```bash
sudo chmod +x ./data/command/evaluation/load.sh
```

```bash
./data/command/evaluation/load.sh
```

### trail

```bash
# ~/projects/tce3
./data/command/trail/download.sh
```

```bash
sudo chmod +x ./data/command/trail/load.sh
```

```bash
./data/command/trail/load.sh
```

### note

```bash
# ~/projects/tce3
./data/command/note/download.sh
```

```bash
sudo chmod +x ./data/command/note/load.sh
```

```bash
./data/command/note/load.sh
```

### affiliation

```bash
# ~/projects/tce3
./data/command/affiliation/download.sh
```

```bash
sudo chmod +x ./data/command/affiliation/load.sh
```

```bash
./data/command/affiliation/load.sh
```

## Instructions for loading files

_Nota bene:_

Be sure you've moved the files folders to `data/files-prep/out-of-order`.

### zipping the file directories in tce2

#### zip the documents

```bash
# /var/www/tce2.registrar.gatech.edu/tce2/sites/default/files/private
sudo zip -r documents.zip documents
```

#### zip the lab documents

```bash
# /var/www/tce2.registrar.gatech.edu/tce2/sites/default/files/private
sudo zip -r lab_documents.zip lab_documents
```

#### zip the lab syllabi

```bash
# /var/www/tce2.registrar.gatech.edu/tce2/sites/default/files/private
sudo zip -r lab_syllabi.zip lab_syllabi
```

#### zip the supplemental files (aka attached files)

```bash
# /var/www/tce2.registrar.gatech.edu/tce2/sites/default/files/private
sudo zip -r supplemental.zip supplemental
```

#### zip the syllabi

```bash
# /var/www/tce2.registrar.gatech.edu/tce2/sites/default/files/private
sudo zip -r syllabi.zip syllabi
```

### syllabus

```bash
# ~/projects/tce3
./data/files-prep/command/syllabus/download.sh
```

```bash
sudo chmod +x ./data/files-prep/command/syllabus/load.sh
```

```bash
./data/files-prep/command/syllabus/load.sh
```

### document

```bash
# ~/projects/tce3
./data/files-prep/command/document/download.sh
```

```bash
sudo chmod +x ./data/files-prep/command/document/load.sh
```

```bash
./data/files-prep/command/document/load.sh
```

### lab-syllabus

```bash
# ~/projects/tce3
./data/files-prep/command/lab-syllabus/download.sh
```

```bash
sudo chmod +x ./data/files-prep/command/lab-syllabus/load.sh
```

```bash
./data/files-prep/command/lab-syllabus/load.sh
```

### lab-document

```bash
# ~/projects/tce3
./data/files-prep/command/lab-document/download.sh
```

```bash
sudo chmod +x ./data/files-prep/command/lab-document/load.sh
```

```bash
./data/files-prep/command/lab-document/load.sh
```

### attached-file

```bash
# ~/projects/tce3
./data/files-prep/command/attached-file/download.sh
```

```bash
sudo chmod +x ./data/files-prep/command/attached-file/load.sh
```

```bash
./data/files-prep/command/attached-file/load.sh
```

## Zip up the project minus the unorganized files

```bash
# ~/projects
zip -r tce3.zip tce3 -x 'tce3/data/*'
```

## Generate a MySQL backup file

```bash
# ~/projects
export DBUN=someusername
export DBPW=somepassword
export DBDB=someschema
export DBTS=sometimestamp

mysqldump --no-tablespaces -u $DBUN -p$DBPW $DBDB > ~/projects/tce3/$DBDB$DBTS.sql
```

## Redeploy?

```bash
zip -r tce3.zip tce3 -x 'tce3/data/*' -x 'tce3/files/*'
```