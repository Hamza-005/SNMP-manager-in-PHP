<?php
$ip = "127.0.0.1"; 
$community = "private"; 

$oids = [
    "sysDescr.0",
    "sysObjectID.0",
    "sysUpTime.0",
    "sysContact.0",
    "sysName.0",
    "sysLocation.0",
];

$names = [
    "System Description",
    "System Object ID",
    "System Uptime",
    "System Contact",
    "System Name",
    "System Location"
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fields = ['sysContact', 'sysName', 'sysLocation'];
    foreach ($fields as $field) {
        if (!empty($_POST[$field])) {
            snmp2_set($ip, $community, "1.3.6.1.2.1.1." . ($field == 'sysContact' ? 4 : ($field == 'sysName' ? 5 : 6)) . ".0", 's', $_POST[$field]);
        }
    }
    header("Location: page1.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="main_style.css">
    <title>System Group - SNMP Manager</title>
</head>

<body>
    <header>
        <h1>System Group</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="page2.php">Next Page</a>
    </nav>
    <div class="container">
        <form method="post">
            <table>
                <tr>
                    <th>Information Type</th>
                    <th>Information</th>
                    <th>Edit</th>
                </tr>
                <?php foreach ($oids as $index => $oid): ?>
                    <tr>
                        <td><?= $names[$index] ?></td>
                        <td><?= @snmp2_get($ip, $community, "1.3.6.1.2.1.1." . ($index + 1) . ".0") ?></td>
                        <?php if (in_array($index, [3, 4, 5])): ?>
                            <td><input type="text" name="<?= explode('.', $oid)[0] ?>" placeholder="New Value"></td>
                        <?php else: ?>
                            <td>-</td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
            <input type="submit" value="Update Editable Fields">
        </form>
    </div>
</body>

</html>