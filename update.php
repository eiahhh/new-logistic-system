<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'core/dbConfig.php';

$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);

// Get the current parcel data
if ('id') {
    $stmt = $pdo->prepare("SELECT * FROM parcels WHERE parcel_id = ?");
    $stmt->execute([$id]);
    $parcel = $stmt->fetch();

    if (!$parcel) {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// Fetch warehouses for the dropdown
$warehouses = $pdo->query("SELECT * FROM warehouse")->fetchAll();

// Handle the update request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_editor_id = $_SESSION['user_id'];


    $sql = "UPDATE parcels SET tracking_number = ?, weight = ?, status = ?, warehouse_id = ?, last_updated_by = ? WHERE parcel_id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$_POST['tracking'], $_POST['weight'], $_POST['status'], $_POST['warehouse_id'], $current_editor_id, $id])) {
        echo "<script>
                alert('Updated successfully!');
                window.location.href = 'index.php';
            </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Parcel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">✏️ Edit Parcel Details</h4>
                <small class="text-muted">Logged in as: <?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></small>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" name="tracking" class="form-control" value="<?= htmlspecialchars($parcel['tracking_number']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" class="form-control" value="<?= $parcel['weight'] ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Stored" <?= $parcel['status'] == 'Stored' ? 'selected' : '' ?>>Stored</option>
                                <option value="In Transit" <?= $parcel['status'] == 'In Transit' ? 'selected' : '' ?>>In Transit</option>
                                <option value="Delivered" <?= $parcel['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warehouse</label>
                            <select name="warehouse_id" class="form-select" required>
                                <?php foreach ($warehouses as $w): ?>
                                    <option value="<?= $w['warehouse_id'] ?>" <?= $w['warehouse_id'] == $parcel['warehouse_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($w['location_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update Parcel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
