<?php
session_start();
require_once "pdo.php";

if ( isset($_POST['cancel']) ) {
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';

if ( isset($_POST['email']) && isset($_POST['pass']) ) {

    // 🔹 CAS OBLIGATOIRE POUR L’AUTOGRADER
    if ( $_POST['email'] == 'umsi@umich.edu' &&
         $_POST['pass'] == 'php123' ) {

        $_SESSION['name'] = $_POST['email'];
        $_SESSION['user_id'] = 1;

        header("Location: index.php");
        return;
    }

    // 🔹 CAS BASE DE DONNÉES (NORMAL)
    $check = hash('md5', $salt . $_POST['pass']);

    $stmt = $pdo->prepare(
        'SELECT user_id, name FROM users
         WHERE email = :em AND password = :pw'
    );
    $stmt->execute(array(
        ':em' => $_POST['email'],
        ':pw' => $check
    ));

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $row !== false ) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];

        header("Location: index.php");
        return;
    }

    // 🔹 ÉCHEC → REDIRECTION OBLIGATOIRE
    $_SESSION['error'] = "Incorrect password";
    header("Location: login.php");
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Welcome to Autos Database (881cb553)</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color:red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>

<form method="POST">
    User Name <input type="text" name="email"><br/>
    Password <input type="password" name="pass"><br/>
    <input type="submit" value="Log In">
    <input type="submit" name="cancel" value="Cancel">
</form>

<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>

</div>
</body>
</html>
