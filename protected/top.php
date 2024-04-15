<?php
$phpSelf = htmlspecialchars($_SERVER['PHP_SELF']);
$pathParts = pathinfo($phpSelf);
?>
<!-- the top document, all the head stuff and meta stuff and links to the connecting to the DB files -->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DIGI-BANK</title>
        <meta name="author" content="Ashton Putnam">
        <meta name="description" content="This page is the personification of the best digital banking system ever.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="SHORTCUT ICON" type="image/x-icon" href="../images/logo.ico">
        <link rel="stylesheet" href="../css/style.css?version=<?php print time(); ?>" type="text/css" media="screen">
    </head>

<?php
print '<body id="' . $pathParts['filename'] . '">';

include '../connect-DB.php';

include '../header.php';

include 'nav.php';
?>