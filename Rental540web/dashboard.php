<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db_connect.php');

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manager Dashboard - Rental540</title>
<style>
    body {font-family:'Segoe UI',Arial,sans-serif;margin:0;padding:0;background-color:#f8f9fb;}
    header {background:#073334;color:white;padding:20px 40px;display:flex;justify-content:space-between;align-items:center;}
    header h1 {color:#66B2FF;margin:0;}
    .container {max-width:850px;margin:50px auto;padding:30px;border-radius:10px;box-shadow:0 2px 15px rgba(0,0,0,0.3);background-color:#ffffff;}
    button,select {padding:10px 14px;border-radius:6px;border:none;font-size:16px;}
    button{background:#004080;color:white;cursor:pointer;}
    button:hover{background:#0059B3;}
    table{width:100%;border-collapse:collapse;margin-top:20px;background:#1A2E4A;}
    th,td{border-bottom:1px solid #2C4C6E;padding:12px;text-align:left;color:#EAEAEA;}
    th{background-color:#004080;}
</style>
</head>
<body>
<header>
    <h1>Rental540 Manager Dashboard</h1>
    <div>
        <span>Welcome, <?php echo htmlspecialchars($_SESSION['UserName']); ?> (<?php echo $_SESSION['UserRole']; ?>)</span> |
        <a href="logout.php" style="color:#66B2FF;text-decoration:none;">Logout</a>
    </div>
</header>

<div class="container">
    <h2 style="text-align:center;color:#073334;">Revenue Overview</h2>
    <form method="POST" action="">
        <label for="location">Select a Location:</label>
        <select name="location" id="location">
            <option value="">-- All Locations --</option>
            <?php
            $locSql = "SELECT LocationID, LocationName FROM LOCATION ORDER BY LocationName";
            $locResult = $conn->query($locSql);
            if ($locResult->num_rows > 0) {
                while ($loc = $locResult->fetch_assoc()) {
                    $selected = (isset($_POST['location']) && $_POST['location'] == $loc['LocationID']) ? "selected" : "";
                    echo "<option value='{$loc['LocationID']}' $selected>{$loc['LocationName']}</option>";
                }
            }
            ?>
        </select>
        <button type="submit" name="showRevenue">Show Revenue</button>
        <button type="submit" name="showTotal">Show Total Revenue</button>
    </form>

    <?php
    if (isset($_POST['showRevenue']) && !empty($_POST['location'])) {
        $locationID = intval($_POST['location']);
        $stmt = $conn->prepare("
            SELECT l.LocationID, l.LocationName, SUM(p.Amount) AS TotalRevenue 
            FROM RENTAL r 
            INNER JOIN PAYMENT p ON r.RentalID = p.RentalID 
            INNER JOIN PICKUP_LOCATION pl ON r.RentalID = pl.RentalID 
            INNER JOIN LOCATION l ON pl.LocationID = l.LocationID 
            WHERE p.Status = 'Completed' AND l.LocationID = ? 
            GROUP BY l.LocationID, l.LocationName
        ");
        $stmt->bind_param("i", $locationID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table><tr><th>Location ID</th><th>Location Name</th><th>Total Revenue ($)</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['LocationID']}</td><td>{$row['LocationName']}</td><td>$" . number_format($row['TotalRevenue'], 2) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No revenue data found for this location.</p>";
        }
        $stmt->close();
    } elseif (isset($_POST['showTotal'])) {
        $sql = "SELECT SUM(p.Amount) AS TotalRevenue FROM PAYMENT p WHERE p.Status = 'Completed'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if ($row['TotalRevenue'] !== null) {
            echo "<h3 style='text-align:center;'>Total Revenue: $" . number_format($row['TotalRevenue'], 2) . "</h3>";
        } else {
            echo "<p>No completed payments found.</p>";
        }
    }
    ?>
</div>
</body>
</html>
