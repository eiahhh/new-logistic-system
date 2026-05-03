<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'core/dbConfig.php'; 

// Fetch all parcels with their warehouse names
$stmt = $pdo->query("SELECT parcels.*, warehouse.location_name, users.username AS un 
                    FROM parcels 
                    LEFT JOIN warehouse ON parcels.warehouse_id = warehouse.warehouse_id
                    LEFT JOIN users ON parcels.added_by = users.user_id");
$parcels = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parcel Management Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>🚚 Parcel Management Dashboard</h2>
            <p class="text-muted mb-0">Logged in as: <strong><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></strong></p>
        </div>

        <div>
            <a href="create.php" class="btn btn-success">+ Add New Parcel</a>
            <a href="logout.php" class="btn btn-danger ms-2" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
        </div>
    </div>

    <table class="table table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Tracking Number</th>
                <th>Weight (kg)</th>
                <th>Status</th>
                <th>Warehouse</th>
                <th>Added by</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($parcels as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['tracking_number']) ?></td>
                <td><?= htmlspecialchars($p['weight']) ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td><?= htmlspecialchars($p['location_name']) ?></td>
                <td><?= htmlspecialchars($p['un']) ?></td>
                <td><?= htmlspecialchars($p['last_updated']) ?></td>
                <td>
                    <a href="update.php?id=<?= $p['parcel_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete.php?id=<?= $p['parcel_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this parcel?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>