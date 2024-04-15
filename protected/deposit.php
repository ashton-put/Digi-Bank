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

// initialize depositAmount
$depositAmount = '';

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
    // set deposit amount
    $depositAmount = getPostData("depositAmount");

    // verify if it is a number, reject if not
    // also verify that it is greater than or equal 0
    if (!is_numeric($depositAmount) || $depositAmount <= 0) {
        echo "Invalid deposit amount.";
    } else {
        // initialize some variables
        $sender_id = $user_id;
        $receiver_id = $user_id;

        // query to fetch user data
        $statement = $pdo->prepare("SELECT user_id, username, account_balance FROM Users WHERE user_id = ?");
        $statement->execute([$user_id]);
        $user = $statement->fetch();

        // check if the user exists
        if ($user === false) {
            $message = "User not found.";
        }

        // calculate new balance
        $newBalance = $user['account_balance'] + $depositAmount;

        // update balance in the DB
        $updateStatement = $pdo->prepare("UPDATE Users SET account_balance = ? WHERE user_id = ?");
        $updateStatement->execute([$newBalance, $user_id]);

        // insert the updated data into the database, set messages to be success or fail
        $insertTransaction = $pdo->prepare("INSERT INTO Transactions (sender_id, receiver_id, amount) VALUES (?, ?, ?)");
        if ($insertTransaction->execute([$user_id, $user_id, $depositAmount])) {
            $message = "Deposit successful!";
        } else {
            $message = "Error processing deposit.";
        }
    }
}
?>

<!-- main element -->
<main class="maindeposit">
    <!-- print out the message from above -->
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- the form to get the deposit amount from the user -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <fieldset class="deposit">
            <legend>Deposit Funds into Your Account</legend>
            <p>
                <label for="txtDepositAmount">Deposit Amount:</label>
                <input type="number" id="txtDepositAmount" name="depositAmount" placeholder="Enter amount:" step="0.01" min="0" value="<?php echo $initial_balance; ?>" tabindex="1">
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