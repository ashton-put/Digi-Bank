<?php
include 'top.php';

// start the session to remember the user
session_start();

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
    // set variables (username and password)
    $username = getPostData("username");
    $password = getPostData("password");

    // query to fetch user data
    $statement = $pdo->prepare("SELECT user_id, username, password FROM Users WHERE username = ?");
    $statement->execute([$username]);
    $user = $statement->fetch();

    // redirect user to protected/home.php if password is correct
    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["user_id"];
        header("Location: protected/home.php");
    } elseif (!verifyAlphaNum($username)) {
        echo "Invalid characters in username.";
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!-- main element -->
<main class="mainlogin">

    <!-- form to get the user login info -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <fieldset class="login">
            <legend>Login</legend>
            <p>
                <label for="txtUsername">Username:</label>
                <input type="text" id="txtUsername" name="username" placeholder="Enter your username" tabindex="1">
            </p>
            <p>
                <label for="txtPassword">Password:</label>
                <input type="password" id="txtPassword" name="password" placeholder="Enter your password" tabindex="2">
            </p>
            <p>
                <input class="button" type="submit" value="Login" tabindex="3">
            </p>
        </fieldset>
    </form>
</main>

<?php
include 'footer.php';
?>