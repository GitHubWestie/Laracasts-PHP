# Create a MySQL Database

To run a local mysql database mysql needs to be installed to the local machine. 

## Installing MySQL

1. **Download the Installer**

    Visit the [MySQL download page](https://dev.mysql.com/downloads/installer/) and choose the MySQL Installer for Windows. You can select either the web installer or the offline installer

2. **Run the Installer**

    Double-click the downloaded file to run the installer. Follow the prompts to select the components you need. For a minimal install, choose the "Server only" option

3. **Configure MySQL**

    After installation, the installer will prompt you to configure the MySQL server. Select "Standalone MySQL Server" and set a strong password for the root account

4. **Start MySQL**

    Once configured, you can start the MySQL server from the Windows start menu by searching for "MySQL Command Line Client"

    ***Note:** If this doesn't work and an error 10061 is encountered start the mysql service from the task manager*

## Creating a Database

Database commands can be run in the command line or using a GUI.

### Terminal

To open the mysql shell in terminal:

```sql
mysql -u root 
```

If the database is password protected add the -p flag and you will be prompted to enter the password
```sql
mysql -u root -p
```

### Use a GUI

Alternatively use a GUI like TablePlus. 

1. **Create a new connection**

    Open TablePlus and add a new connection by clicking on the plus icon. Here you can name the databse and assign a tag. This for reference within TablePlus only and is not required.

2. **Add Database Credentials**

    Still in the new connection window fill out the mandatory fields:
    - Host/IP
    - Port
    - User
    - Password (if database is password protected)

3. **Test Connection**

    Click Test at the bottom of the new connection window. If the test is successful click save or connect to open the database in Table Plus.

4. **Open a Database**

    With TablePlus open press `ctrl+k` to open the list of databases available on that connection. If no databases exist yet one can be created from this window too.

5. **Set a Default - Optional**

    From the table plus connection window, right click on the connection and select edit then add the database name to the databse name field. Table Plus will then default to this database whenever the connection is opened.

6. **Create Data**

    From inside the TablePlus GUI column and row structure and data can be defined.