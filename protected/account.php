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

// Fetch user data based on user_id
$statement = $pdo->prepare("SELECT * FROM Users WHERE user_id = ?");
$statement->execute([$user_id]);
$user = $statement->fetch();

// fetch transaction data based on user-id
$transactionStatement = $pdo->prepare("SELECT transaction_id, sender_id, receiver_id, amount, transaction_date FROM Transactions WHERE sender_id = ? OR receiver_id = ? ORDER BY transaction_date DESC");
$transactionStatement->execute([$user_id, $user_id]);
$transactions = $transactionStatement->fetchAll();

// getUsername function to display usernames in table instead of id numbers
function getUsername($pdo, $user_id) {
    $statement = $pdo->prepare("SELECT username FROM Users WHERE user_id = ?");
    $statement->execute([$user_id]);
    $user = $statement->fetch();
    return $user['username'];
}
?>

<!-- main element -->
<main class="mainaccount">
    <!-- print out some header stuff -->
    <h2> Account Summary Page </h2>

    <!-- print the table with account summary info -->
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Account Balance</th>
        </tr>
        <tr>
            <td><?php echo $user['user_id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo '$' . number_format($user['account_balance'], 2, '.', ','); ?></td>
        </tr>

    <!-- second table to print the past transaction info -->
    </table>
    <br>
    <h3>Recent Transactions:</h3>

    <table>
        <tr>
            <th>Transaction #</th>
            <th>Sender</th>
            <th>Receiver</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>

        <!-- loop through and print the necessary info for each transaction -->
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?php echo $transaction['transaction_id']; ?></td>
                <td><?php echo getUsername($pdo, $transaction['sender_id']); ?></td>
                <td><?php echo getUsername($pdo, $transaction['receiver_id']); ?></td>
                <td <?php echo $transaction['amount'] < 0 ? 'style="color: red;"' : ''; ?>><?php echo '$' . number_format($transaction['amount'], 2, '.', ','); ?></td>
                <td><?php echo $transaction['transaction_date']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php
include 'footer.php';
?>