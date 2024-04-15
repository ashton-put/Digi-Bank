<?php
include 'top.php';

// initialize some variables
$username = '';
$email = '';
$password = '';
$initial_balance = '';

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

// function to verify if a string contains only allowed characters
function verifyAlphaNum($testString) {
    // check for letters, numbers, dash, period, space, single quote, ampersand, semicolon, and hash only.
    return (preg_match ("/^([[:alnum:]]|-|\.| |\'|&|;|#)+$/", $testString));
}

// if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // set variables
    $username = getPostData("username");
    $email = getPostData("email");
    $password = getPostData("password");
    $initial_balance = isset($_POST["initial_balance"]) ? floatval($_POST["initial_balance"]) : 0.0;

    // query to get user info 
    $checkStatement = $pdo->prepare("SELECT COUNT(*) as count FROM Users WHERE username = ? OR email = ?");
    $checkStatement->execute([$username, $email]);
    $result = $checkStatement->fetch();

    // if the account exists, give error message, or invalid characters
    if ($result['count'] > 0) {
        echo "Username or email already exists.";
    } elseif (!verifyAlphaNum($initial_balance)) {
        echo "Invalid characters in initial balance.";
    } else {
        // hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // insert into the DB
        $insertStatement = $pdo->prepare("INSERT INTO Users (username, email, password, account_balance) VALUES (?, ?, ?, ?)");
        $insertStatement->execute([$username, $email, $hashed_password, $initial_balance]);

        // redirect to login page if sign up success
        header("Location: login.php");
    }
}
?>

<!-- main element -->
<main class="mainsignup">
    <!-- form to get the sign up information -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <fieldset class="signup">
            <legend>Sign-Up</legend>
            <p>
                <label for="txtUsername">Username:</label>
                <input type="text" id="txtUsername" name="username" placeholder="Enter your username" value="<?php echo $username; ?>" tabindex="1">
            </p>
            <p>
                <label for="txtEmail">Email:</label>
                <input type="email" id="txtEmail" name="email" placeholder="Enter your email" value="<?php echo $email; ?>" tabindex="2">
            </p>
            <p>
                <label for="txtPassword">Password:</label>
                <input type="password" id="txtPassword" name="password" placeholder="Enter your password" tabindex="3">
            </p>
            <p>
                <label for="txtInitialBalance">Initial Balance:</label>
                <input type="number" id="txtInitialBalance" name="initial_balance" placeholder="Enter initial balance" step="0.01" min="0" value="<?php echo $initial_balance; ?>" tabindex="4">
            </p>
            <p>
                <input class="button" type="submit" value="Sign Up" tabindex="5">
            </p>
        </fieldset>
    </form>
</main>

<?php
include 'footer.php';
?>