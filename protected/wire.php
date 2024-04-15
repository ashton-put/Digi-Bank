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

// initialize some variables
$wireAmount = '';
$destAccount = '';

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
    // get the wire amount and the destination account number
    $wireAmount = getPostData("wireAmount");
    $destAccount = getPostData("destAccount");

    // verify if it is a number, reject if not
    // also verify that it is greater than or equal 0
    if (!is_numeric($wireAmount) || $wireAmount <= 0 || !is_numeric($destAccount) || $destAccount <= 0) {
        $message = "Invalid input. Please enter valid amounts.";
    } else {
        // initialize some variables
        $sender_id = $user_id;
        $receiver_id = $destAccount;

        // query to fetch user data
        $statement = $pdo->prepare("SELECT user_id, username, account_balance FROM Users WHERE user_id = ?");
        $statement->execute([$user_id]);
        $user1 = $statement->fetch();

        // check if the user1 exists
        if ($user1 === false) {
            $message = "User not found.";
        }

        // query to fetch user data
        $statement = $pdo->prepare("SELECT user_id, username, account_balance FROM Users WHERE user_id = ?");
        $statement->execute([$destAccount]);
        $user2 = $statement->fetch();

        // check to see if the destination account user exits, print message if not
        if ($user2 === false) {
            $message = "User not found.";
        } else {
            // calculate new balance for main account user
            $newBalance1 = $user1['account_balance'] - $wireAmount;
            // calculate new balance for destination account user
            $newBalance2 = $user2['account_balance'] + $wireAmount;

            // update balance in the DB for main account user
            $updateStatement = $pdo->prepare("UPDATE Users SET account_balance = ? WHERE user_id = ?");
            $updateStatement->execute([$newBalance1, $user_id]);

            // update the balance in the DB for destination account user
            $updateStatement = $pdo->prepare("UPDATE Users SET account_balance = ? WHERE user_id = ?");
            $updateStatement->execute([$newBalance2, $destAccount]);

            // insert the updated data into the database
            $insertTransactionSender = $pdo->prepare("INSERT INTO Transactions (sender_id, receiver_id, amount) VALUES (?, ?, ?)");
            $insertTransactionReceiver = $pdo->prepare("INSERT INTO Transactions (sender_id, receiver_id, amount) VALUES (?, ?, ?)");

            //set messages to be success or fail
            if ($insertTransactionSender->execute([$user_id, $destAccount, -$wireAmount]) and $insertTransactionReceiver->execute([$user_id, $destAccount, $wireAmount])) {
                $message = "Wire successful!";
            } else {
                $message = "Error processing wire.";
            }
        }
    }
}
?>

<!-- main element -->
<main class="mainwire">
    <!-- print out the message from above -->
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- the form to get the wire info from the user -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <fieldset class="wire">
            <legend>Wire Funds to Another Account</legend>
            <p>
                <label for="txtWireAmount">Wire Amount:</label>
                <input type="number" id="txtWireAmount" name="wireAmount" placeholder="Enter amount:" step="0.01" min="0" value="<?php echo $initial_balance; ?>" tabindex="1">
            </p>
            <p>
                <label for="txtDestAccount">Destination Account:</label>
                <input type="number" id="txtDestAccount" name="destAccount" placeholder="Enter account number:" min="0" value="<?php echo $initial_balance; ?>" tabindex="1">
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