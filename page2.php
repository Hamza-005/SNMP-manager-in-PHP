<?php
$ip = "127.0.0.1";
$community = "private";

$tcpConnStateOID = "1.3.6.1.2.1.6.13.1.1";
$tcpConnLocalAddrOID = "1.3.6.1.2.1.6.13.1.2";
$tcpConnLocalPortOID = "1.3.6.1.2.1.6.13.1.3";
$tcpConnRemAddrOID = "1.3.6.1.2.1.6.13.1.4";
$tcpConnRemPortOID = "1.3.6.1.2.1.6.13.1.5";

$states = @snmp2_walk($ip, $community, $tcpConnStateOID) ?: [];
$local_addrs = @snmp2_walk($ip, $community, $tcpConnLocalAddrOID) ?: [];
$local_ports = @snmp2_walk($ip, $community, $tcpConnLocalPortOID) ?: [];
$remote_addrs = @snmp2_walk($ip, $community, $tcpConnRemAddrOID) ?: [];
$remote_ports = @snmp2_walk($ip, $community, $tcpConnRemPortOID) ?: [];

function clean_value($val)
{
    return trim(preg_replace('/^\\w+\\s*:/', '', $val));
}

$tcp_states = [
    1 => "CLOSED",
    2 => "LISTEN",
    3 => "SYN_SENT",
    4 => "SYN_RECEIVED",
    5 => "ESTABLISHED",
    6 => "FIN_WAIT_1",
    7 => "FIN_WAIT_2",
    8 => "CLOSE_WAIT",
    9 => "LAST_ACK",
    10 => "CLOSING",
    11 => "TIME_WAIT"
];
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="main_style.css">
    <title>TCP Connections - SNMP Manager</title>
</head>

<body>
    <header>
        <h1>TCP Connections</h1>
    </header>
    <nav>
        <a href="page1.php">Previous Page</a>
        <a href="index.php">Home</a>
        <a href="page3.php">Next Page</a>
    </nav>

    <div class="container">
        <h2>TCP Connection Table</h2>
        <table>
            <tr>
                <th>State</th>
                <th>Local Address</th>
                <th>Local Port</th>
                <th>Remote Address</th>
                <th>Remote Port</th>
            </tr>
            <?php
            $total = min(count($states), count($local_addrs), count($local_ports), count($remote_addrs), count($remote_ports));
            for ($i = 0; $i < $total; $i++):
                $state = intval(clean_value($states[$i]));
                $local_ip = clean_value($local_addrs[$i]);
                $local_port = clean_value($local_ports[$i]);
                $remote_ip = clean_value($remote_addrs[$i]);
                $remote_port = clean_value($remote_ports[$i]);
            ?>
                <tr>
                    <td><?= htmlspecialchars($tcp_states[$state] ?? "Unknown ($state)") ?></td>
                    <td><?= htmlspecialchars($local_ip) ?></td>
                    <td><?= htmlspecialchars($local_port) ?></td>
                    <td><?= htmlspecialchars($remote_ip) ?></td>
                    <td><?= htmlspecialchars($remote_port) ?></td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>

</body>

</html>