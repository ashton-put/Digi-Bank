<?php
include 'top.php';

// start the session to remember the user
session_start();

// grab user info from login.php
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// set the user_id
$user_id = $_SESSION["user_id"];

// fetch username based on user_id
$statement = $pdo->prepare("SELECT username FROM Users WHERE user_id = ?");
$statement->execute([$user_id]);
$user = $statement->fetch();
?>

<!-- main element -->
<main class="home">
    <h2> Home Page </h2>
    <!-- print personalized hello message -->
    <h3> Hello, <?php echo $user['username']; ?></h3>
    <br>

    <!-- bank emoji image -->
    <figure>
        <img src="../images/main_image.png" alt="An emoji of a bank">
    </figure>

    <!-- some text -->
    <br>
    <p>Here is your personal home page! Above you will find the <strong>Account Summary</strong>, <strong>Deposit</strong>, <strong>Withdraw</strong>, and <strong>Wire</strong> pages to perform each transaction, respectively. Off to the right is the <strong>Log Out</strong> button, please click that when you are all done.</p>
    <br>
</main>

<?php
include 'footer.php';
?>