<?php
// Path to your log file
$logFile = __DIR__ . '/log.json';

// Handle POST request to save data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $entry = [
        'time' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'referrer' => $data['referrer'],
        'userAgent' => $data['userAgent']
    ];

    // Read existing logs
    $logs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
    $logs[] = $entry;

    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
    exit;
}

// Handle GET request to display logs
$logs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Website Traffic Log</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f2f5;
    padding: 20px;
}
h2 {
    color: #333;
}
table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}
th {
    background: #4a90e2;
    color: #fff;
    text-align: left;
    padding: 12px;
    font-size: 14px;
}
td {
    padding: 10px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
    word-break: break-all;
}
tr:hover {
    background: #f1f1f1;
}
tr:nth-child(even){
    background: #fafafa;
}
.container {
    max-width: 1200px;
    margin: auto;
}
</style>
</head>
<body>
<div class="container">
<h2>🌐 Website Traffic Log</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Time</th>
            <th>IP Address</th>
            <th>Referrer</th>
            <th>User Agent</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($logs)): ?>
        <?php foreach (array_reverse($logs) as $i => $log): ?>
        <tr>
            <td><?= count($logs) - $i ?></td>
            <td><?= htmlspecialchars($log['time']) ?></td>
            <td><?= htmlspecialchars($log['ip']) ?></td>
            <td><?= htmlspecialchars($log['referrer']) ?></td>
            <td><?= htmlspecialchars($log['userAgent']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5">No traffic data yet.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>
</body>
</html>
