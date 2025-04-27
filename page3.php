<?php
$ip = "127.0.0.1";
$community = "private"; 

$icmp_base_oid = "1.3.6.1.2.1.5";

$icmp_names = [
    1 => "icmpInMsgs",
    2 => "icmpInErrors",
    3 => "icmpInDestUnreachs",
    4 => "icmpInTimeExcds",
    5 => "icmpInParmProbs",
    6 => "icmpInSrcQuenchs",
    7 => "icmpInRedirects",
    8 => "icmpInEchos",
    9 => "icmpInEchoReps",
    10 => "icmpInTimestamps",
    11 => "icmpInTimestampReps",
    12 => "icmpInAddrMasks",
    13 => "icmpInAddrMaskReps",
    14 => "icmpOutMsgs",
    15 => "icmpOutErrors",
    16 => "icmpOutDestUnreachs",
    17 => "icmpOutTimeExcds",
    18 => "icmpOutParmProbs",
    19 => "icmpOutSrcQuenchs",
    20 => "icmpOutRedirects",
    21 => "icmpOutEchos",
    22 => "icmpOutEchoReps",
    23 => "icmpOutTimestamps",
    24 => "icmpOutTimestampReps",
    25 => "icmpOutAddrMasks",
    26 => "icmpOutAddrMaskReps"
];

// Method 1: Get individually
$get_results = [];
foreach ($icmp_names as $x => $name) {
    $result = @snmp2_get($ip, $community, "$icmp_base_oid.$x.0");
    $get_results[$x] = $result !== false ? trim($result) : "N/A";
}

// Method 2: Walk the ICMP group
$walk_results_raw = @snmp2_walk($ip, $community, $icmp_base_oid);
$walk_results = [];
if ($walk_results_raw) {
    foreach ($walk_results_raw as $row) {
        $parts = explode(":", $row, 2);
        $value = isset($parts[1]) ? trim($parts[1]) : "N/A";
        $walk_results[] = $value;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="main_style.css">
    <title>ICMP Statistics - SNMP Manager</title>
    <style>
        .row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .column {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <h1>ICMP Statistics</h1>
    </header>
    <nav>
        <a href="page2.php">Previous Page</a>
        <a href="index.php">Home</a>
    </nav>

    <div class="container">
        <div class="row">
            <div class="column">
                <h2>Method1: By Get</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>
                    <?php foreach ($icmp_names as $id => $name): ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= htmlspecialchars($name) ?></td>
                            <td><?= htmlspecialchars($get_results[$id] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="column">
                <h2>Method2: By Walk</h2>
                <table>
                    <tr>
                        <th>Item #</th>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>
                    <?php foreach ($walk_results as $index => $value): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($icmp_names[$index + 1] ?? "Unknown") ?></td>
                            <td><?= htmlspecialchars(preg_replace('/^\\w+\\s*:/', '', $value)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

</body>

</html>