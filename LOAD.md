# Drop the database

```bash
mysql -u someoneexisting -p
```

```sql
DROP DATABASE tce3_db;
```

# Create the database

```bash
mysql -u someoneexisting -p
```

```sql
CREATE DATABASE tce3_db;
```

# Set up the database

```bash
# ~/projects/tce3
php bin/console tce3:database-setup Risk-Accepted
```

# Instructions for loading data

The slash at the end is necessary.

```bash
# ~/projects/tce3
export TCE2=https://something.registrar.gatech.edu/something/
```

users

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

course 

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

institution

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

department

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

evaluation

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

trail

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

note

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

affiliation

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

syllabus

```bash
# ~/projects/tce3
./data/files-prep/command/syllabus/download.sh
```

document

```bash
# ~/projects/tce3
./data/files-prep/command/document/download.sh
```

lab-syllabus

```bash
# ~/projects/tce3
./data/files-prep/command/lab-syllabus/download.sh
```

lab-document

```bash
# ~/projects/tce3
./data/files-prep/command/lab-document/download.sh
```

attached-file

```bash
# ~/projects/tce3
./data/files-prep/command/attached-file/download.sh
```


