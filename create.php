<?php
session_start();
require 'core/dbConfig.php';

// Fetch warehouses for the dropdown
$stmt = $pdo->query("SELECT * FROM warehouse");
$warehouse = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_SESSION['user_id'])) {
        $current_user_id = $_SESSION['user_id'];
    } else {
        header("Location: login.php");
        exit();
    }
    
    $sql = "INSERT INTO parcels (tracking_number, weight, status, warehouse_id, added_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([$_POST['tracking'], $_POST['weight'], $_POST['status'], $_POST['warehouse_id'], $current_user_id]);
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Parcel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">📦 Register New Parcel</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tracking Number</label>
                        <input type="text" name="tracking" class="form-control" placeholder="e.g. PH-0210-DLP" required>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="text" name="weight" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Stored">Stored</option>
                                <option value="In Transit">In Transit</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assign Warehouse</label>
                            <select name="warehouse_id" class="form-select" required>
                                <option value="" selected disabled>Select Warehouse</option>

                                <?php 
                                
                                if (!empty($warehouse)):
                                    foreach ($warehouse as $w): 
                                ?>
                                    <option value="<?= $w['warehouse_id'] ?>">
                                        <?= htmlspecialchars($w['location_name']) ?></option>
                                <?php 
                                    endforeach;
                                endif;
                                ?>
                            </select><br>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                        <button type="submit" class="btn btn-success">Save Parcel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>