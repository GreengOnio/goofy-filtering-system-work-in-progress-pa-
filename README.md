# DATABASE PHPYMYADMIN SETUP
Use the data.sql and structure.sql from the "Official_Database" folder to import the database in your own localhost/phpmyadmin

1. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
2. Open <http://localhost/phpmyadmin>.
3. Click **New** and create a database named **`projecttest`**.
4. Click `projecttest` in the left sidebar.
5. Open the **Import** tab in the top menu bar.

> ### Important: import order!! (DON'T FUCK IT UP)
> Import **`structure.sql`** first, then **`data.sql`**.
> `data.sql` inserts rows that depend on the tables created by `structure.sql`.

6. Click **Choose File**, open `Official_Database` folder from the extracted zip file, select **`structure.sql`**, scroll down and click **Import**.
7. Repeat the import with **`data.sql`** from the same folder.

When both imports finish you should see green success messages, and the tables will appear in the sidebar.
