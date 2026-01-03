<?php
session_start();
require_once "pdo.php";

// Récupérer tous les profils
$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline 
                     FROM users JOIN Profile ON users.user_id = Profile.user_id");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chuck Severance's Resume Registry - Maroua RHAFOUR da3f7152</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h2>Chuck Severance's Resume Registry</h2>

    <?php if (isset($_SESSION['name'])): ?>
        <p><a href="logout.php">Logout</a></p>
    <?php endif; ?>

    <?php
    if (isset($_SESSION['success'])) {
        echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
        unset($_SESSION['success']);
    }
    ?>

    <?php if (!isset($_SESSION['name'])): ?>
        <p><a href="login.php">Please log in</a></p>
    <?php endif; ?>

    <?php if (count($rows) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Headline</th>
                    <?php if (isset($_SESSION['name'])) echo "<th>Action</th>"; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td>
                        <a href="view.php?profile_id=<?= $row['profile_id'] ?>">
                            <?= htmlentities($row['first_name'] . ' ' . $row['last_name']) ?>
                        </a>
                    </td>
                    <td><?= htmlentities($row['headline']) ?></td>
                    <?php if (isset($_SESSION['name'])): ?>
                        <td>
                            <a href="edit.php?profile_id=<?= $row['profile_id'] ?>">Edit</a> /
                            <a href="delete.php?profile_id=<?= $row['profile_id'] ?>">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No rows found</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['name'])): ?>
        <p><a href="add.php">Add New Entry</a></p>
    <?php endif; ?>

    <p>
        <b>Note:</b> Your implementation should retain data across multiple
        logout/login sessions. This sample implementation clears all its
        data periodically - which you should not do in your implementation.
    </p>
</div>
</body>
</html>
