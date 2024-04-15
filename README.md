# Digi-Bank
## A website project by Ashton Putnam

* add link to website here

### Summary
This website is a product of the Advanced Programming course I'm taking at the University of Vermont. The program builds upon the website structure learned in Prof. Robert Ericksons Intro to Web Development and Database Design for the Web classes. The website is an excercise in using multiple progamming languages to accomplish the goal of providing users with software that fits their needs by providing a necessary service. Digi-Bank is a simple banking website that enables users to create accounts and track debits and credits to their account. While not as sophisticated as actual banking software, the website is a step in that direction. User accounts are kept in a SQL database and thier passwords are hashed and stored along with information about deposits into their account and withdrawals from their account. Users can interact with each other by wiring funds to other Digi-Bank accounts within the database. Users can access account summaries and fill out forms to accomplish different types of transactions. The website is built using exclusively PHP, HTML, and CSS. I really enjoy working with these languages but I plan on learning some modern web frameworks over the summer of 2024 to improve my viability in the web development industry. 

### Note:
a CRITICAL file is not included in this repo for security purposes. connect-DB.php is necessary for connecting to the database and accessing the tools required to read data and write data. The file should follow this structure:

...
  '''php
  
    <?php
    $databaseName = 'XXXXXXXXXXX';
    $dsn = 'mysql:host=webdb.uvm.edu;dbname=' . $databaseName;
    $username = 'XXXXXXXXXXX';
    $password = 'XXXXXXXXXXX';
    $pdo = new PDO($dsn, $username, $password);
    ?>
    
  '''
...


### Sources
* favicon logo from: https://favicon.io/emoji-favicons/bank



