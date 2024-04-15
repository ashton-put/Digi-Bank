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

// initialize a variable
$withdrawAmount = '';

// function to get POST data
function getPostData($field) {
    if (!isset($_POST[$field])) {
        $data = '"';
    } else {
        $data = trim($_POST[$field]);
        $data = htmlspecialchars($data);
    }
    return $data;
}

// if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get the withdraw amount
    $withdrawAmount = getPostData("withdrawAmount");

    // verify if it is a number, reject if not
    // also verify that it is greater than or equal 0
    if (!is_numeric($withdrawAmount) || $withdrawAmount <= 0) {
        echo "Invalid deposit amount.";
    } else {
        // initialize some variables
        $sender_id = $user_id;
        $receiver_id = $user_id;

        // query to fetch user data
        $statement = $pdo->prepare("SELECT user_id, username, account_balance FROM Users WHERE user_id = ?");
        $statement->execute([$user_id]);
        $user = $statement->fetch();

        // check if the user exits
        if ($user === false) {
            $message = "User not found.";
        }

        // calculate new balance
        $newBalance = $user['account_balance'] - $withdrawAmount;

        // update balance in the DB
        $updateStatement = $pdo->prepare("UPDATE Users SET account_balance = ? WHERE user_id = ?");
        $updateStatement->execute([$newBalance, $user_id]);

        // insert the updated data into the database
        $insertTransaction = $pdo->prepare("INSERT INTO Transactions (sender_id, receiver_id, amount) VALUES (?, ?, ?)");

        //set messages to be success or fail
        if ($insertTransaction->execute([$user_id, $user_id, -$withdrawAmount])) {
            $message = "Withdrawal successful!";
        } else {
            $message = "Error processing withdrawal.";
        }
    }
}
?>

<!-- main element -->
<main class="mainwithdraw">
    <!-- print out the message from above -->
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- the form to get the withdraw amount from the user -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <fieldset class="withdraw">
            <legend>Withdraw Funds from Your Account</legend>
            <p>
                <label for="txtWithdrawAmount">Withdraw Amount:</label>
                <input type="number" id="txtWithdrawAmount" name="withdrawAmount" placeholder="Enter amount:" step="0.01" min="0" value="<?php echo $initial_balance; ?>" tabindex="1">
            </p>
            <p>
                <input class="button" type="submit" value="ENTER" tabindex="2">
            </p>
        </fieldset>
    </form>
</main>

<?php
include 'footer.php';
?>