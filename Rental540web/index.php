<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connect.php';

// Manager session check
$isManagerLoggedIn = !empty($_SESSION['manager_logged_in']);

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Rental540 - Car Rental System</title>
<style>
/* --- BASE --- */
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    color: #1A1A1A;
    background: #fff url('dashboard-bg.png') no-repeat center top / cover;
}
h1, h2, h3 { margin: 0 0 12px 0; }

/* --- HEADER --- */
header {
    background: #073334;
    color: #fff;
    padding: 20px 40px;
    display: flex; align-items: center; justify-content: space-between;
}
header img { height: 60px; border-radius: 8px; }
.header-left { display: flex; gap: 15px; align-items: center; }
header h1 { color: #66B2FF; }
nav a {
    color: #66B2FF; text-decoration: none; margin: 0 10px; font-weight: 500;
}
nav a:hover { text-decoration: underline; }

.dark-mode-btn {
    background: #004080; color: #fff; border: none;
    padding: 10px 14px; border-radius: 6px; cursor: pointer;
}
.dark-mode-btn:hover { background: #0059B3; }

/* --- MANAGER REVENUE CONTAINER --- */
.container {
    max-width: 850px; margin: 40px auto; padding: 30px;
    background: #fff; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.3);
}

/* --- TABLE --- */
table { width: 100%; border-collapse: collapse; margin-top: 16px; background: #1A2E4A; border-radius: 8px; overflow: hidden; }
th, td { padding: 12px; border-bottom: 1px solid #2C4C6E; color: #EAEAEA; text-align: left; }
th { background: #004080; color: #fff; text-transform: uppercase; letter-spacing: 0.05em; }
tr:nth-child(even) { background: #203A5C; }
tr:hover { background: #2C4C6E; }

/* --- VEHICLE GALLERY --- */
.vehicle-gallery { display: none; max-width: 1200px; margin: 40px auto; padding: 0 20px; }
.vehicle-cards { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
.vehicle-card {
    width: 200px; background: #fff; border-radius: 10px; overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: 0.3s;
}
.vehicle-card:hover { transform: translateY(-3px); }
.vehicle-image { height: 150px; background-size: cover; background-position: center; }
.vehicle-info { padding: 12px; text-align: center; }
.vehicle-info h3 { color: #073334; margin-bottom: 6px; }

/* --- RESERVATION SECTION --- */
.reservation-section {
    display: none; max-width: 900px; margin: 40px auto; padding: 30px;
    background: #fff; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.3);
}
.reservation-choice {
    display: flex; justify-content: center; gap: 20px; margin: 20px 0 10px;
}
.reservation-choice-btn {
    padding: 15px 22px; background: #004080; color: #fff;
    border: none; border-radius: 8px; cursor: pointer;
}
.reservation-choice-btn:hover { background: #0059B3; }
.reservation-form { display: none; margin-top: 16px; }
label { display: block; margin: 10px 0 6px; font-weight: 500; }
input, select {
    width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; box-sizing: border-box;
    margin-bottom: 8px;
}
.submit-btn {
    background: #004080; color: #fff; border: none; padding: 12px 22px; border-radius: 6px; cursor: pointer;
}
.submit-btn:hover { background: #0059B3; }

/* --- FLASH MESSAGES --- */
.flash-message {
    position: relative;
    width: 90%;
    max-width: 700px;
    margin: 12px auto;
    padding: 12px 40px 12px 16px;
    border-radius: 6px;
    text-align: center;
    font-weight: 500;
}
.flash-message.success { background: #d4edda; color: #155724; }
.flash-message.error { background: #f8d7da; color: #721c24; }
.flash-close {
    position: absolute;
    right: 10px;
    top: 8px;
    font-size: 18px;
    font-weight: bold;
    color: inherit;
    background: none;
    border: none;
    cursor: pointer;
    opacity: 0.7;
}
.flash-close:hover { opacity: 1; }

/* --- DARK MODE --- */
.dark-mode { background: #1b1b1b; color: #f1f1f1; }
.dark-mode .container, .dark-mode .reservation-section, .dark-mode .vehicle-card { background: #2C2C2C; color: #f1f1f1; }
.dark-mode table { background: #333; }
.dark-mode th { background: #222; }
.dark-mode td { color: #eaeaea; }
</style>
</head>
<body>

<header>
    <div class="header-left">
        <img src="logo.png" alt="Company Logo" />
        <div>
            <h1>RENTAL540</h1>
            <nav>
                <a href="#" id="vehicles-link">Vehicles</a>
                <a href="#" id="reservations-link">Reservations</a>
                <a href="locations.php">Locations</a>
            </nav>
        </div>
    </div>
    <div>
        <?php if ($isManagerLoggedIn): ?>
            <span style="color:#66B2FF; margin-right: 12px;">Manager: <?= htmlspecialchars($_SESSION['manager_name'] ?? ''); ?></span>
            <a href="?logout=1" style="color:#66B2FF; margin-right: 12px;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="color:#66B2FF; margin-right: 12px;">Manager Login</a>
        <?php endif; ?>
        <button class="dark-mode-btn" onclick="document.body.classList.toggle('dark-mode')">Dark Mode</button>
    </div>
</header>

<!-- VEHICLE GALLERY -->
<section class="vehicle-gallery" id="vehicle-gallery">
    <h2 style="text-align:center;">Our Vehicle Fleet</h2>
    <div class="vehicle-cards">
        <div class="vehicle-card"><div class="vehicle-image" style="background-image:url('compact-car.png')"></div><div class="vehicle-info"><h3>Compact</h3><p>Small fuel-efficient cars for city driving</p></div></div>
        <div class="vehicle-card"><div class="vehicle-image" style="background-image:url('standard-suv.png')"></div><div class="vehicle-info"><h3>SUV</h3><p>Extra space and capability</p></div></div>
        <div class="vehicle-card"><div class="vehicle-image" style="background-image:url('luxury-car.png')"></div><div class="vehicle-info"><h3>Luxury</h3><p>Premium comfort and features</p></div></div>
        <div class="vehicle-card"><div class="vehicle-image" style="background-image:url('minivan.png')"></div><div class="vehicle-info"><h3>Minivan</h3><p>Ample seating and storage</p></div></div>
        <div class="vehicle-card"><div class="vehicle-image" style="background-image:url('pickup-truck.png')"></div><div class="vehicle-info"><h3>Truck</h3><p>Hauling and towing power</p></div></div>
    </div>
</section>

<!-- RESERVATION SECTION -->
<section class="reservation-section" id="reservation-section">
    <h2>Reservation Management</h2>

    <!-- CHOICE BUTTONS -->
    <div class="reservation-choice">
        <button class="reservation-choice-btn" id="make-res-btn">Make a Reservation</button>
        <button class="reservation-choice-btn" id="modify-res-btn">Modify a Reservation</button>
    </div>

    <!-- MAKE RESERVATION FORM -->
    <form class="reservation-form" id="make-reservation-form" method="POST" action="">
        <h3>Make a Reservation</h3>
        <label>First Name</label><input type="text" name="FirstName" required />
        <label>Last Name</label><input type="text" name="LastName" required />
        <label>Date of Birth</label><input type="date" name="DateOfBirth" required />
        <label>Driver License Number</label><input type="text" name="DriverLicenseNumber" required />
        <label>Email</label><input type="email" name="Email" required />
        <label>Phone (10-digit)</label><input type="tel" name="Phone" required />

        <label>Pickup Location</label>
        <select name="PickupLocationID" required>
            <option value="">Select Pickup Location</option>
            <?php
            $locs = $conn->query("SELECT LocationID, LocationName FROM LOCATION ORDER BY LocationName");
            while ($l = $locs->fetch_assoc()) {
                echo "<option value='{$l['LocationID']}'>{$l['LocationName']}</option>";
            }
            ?>
        </select>

        <label>Return Location</label>
        <select name="ReturnLocationID" required>
            <option value="">Select Return Location</option>
            <?php
            $locs->data_seek(0);
            while ($l = $locs->fetch_assoc()) {
                echo "<option value='{$l['LocationID']}'>{$l['LocationName']}</option>";
            }
            ?>
        </select>

        <label>Car Type</label>
        <select name="CarType" required>
            <option value="">Select Car Type</option>
            <option>Compact Car</option><option>Midsize Car</option>
            <option>Full Size Car</option><option>Standard SUV</option>
            <option>Luxury Car</option><option>Minivan</option>
            <option>Pickup Truck</option>
        </select>

        <label>Start Date</label><input type="date" name="StartDate" required />
        <label>End Date</label><input type="date" name="EndDate" required />
        <button type="submit" class="submit-btn" name="makeReservation">Submit Reservation</button>
    </form>

    <!-- MODIFY RESERVATION FORM -->
    <form class="reservation-form" id="modify-reservation-form" method="POST" action="">
        <h3>Modify Reservation</h3>
        <label>Reservation ID</label><input type="text" name="ReservationID" required />
        <label>Email Address</label><input type="email" name="EmailVerify" required />
        <button type="submit" class="submit-btn" name="findReservation">Find Reservation</button>
    </form>

    <?php include 'reservation_logic.php'; ?>
</section>

<!-- MANAGER REVENUE SECTION -->
<?php if ($isManagerLoggedIn): ?>
<div class="container" id="main-container">
    <h2>Revenue Overview</h2>
    <form method="POST" action="">
        <label>Select Location:</label>
        <select name="location">
            <option value="">-- All Locations --</option>
            <?php
            $locs = $conn->query("SELECT LocationID, LocationName FROM LOCATION ORDER BY LocationName");
            while ($l = $locs->fetch_assoc()) {
                echo "<option value='{$l['LocationID']}'>{$l['LocationName']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="showRevenue">Show Revenue</button>
        <button type="submit" name="showTotal">Show Total</button>
    </form>

    <?php
    if (isset($_POST['showRevenue']) && $_POST['location'] !== "") {
        $id = (int)$_POST['location'];
        $stmt = $conn->prepare("SELECT l.LocationName, SUM(p.Amount) AS TotalRevenue
                                FROM RENTAL r
                                JOIN PAYMENT p ON r.RentalID=p.RentalID
                                JOIN PICKUP_LOCATION pl ON r.RentalID=pl.RentalID
                                JOIN LOCATION l ON pl.LocationID=l.LocationID
                                WHERE l.LocationID=? AND p.Status='Completed'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            echo "<table><tr><th>Location</th><th>Total Revenue ($)</th></tr>";
            while ($r = $res->fetch_assoc()) {
                echo "<tr><td>{$r['LocationName']}</td><td>$".number_format($r['TotalRevenue'], 2)."</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='flash-message error'>No data found for that location.</div>";
        }
    } elseif (isset($_POST['showTotal'])) {
        $r = $conn->query("SELECT SUM(Amount) AS TotalRevenue FROM PAYMENT WHERE Status='Completed'");
        $val = $r ? number_format($r->fetch_assoc()['TotalRevenue'] ?? 0, 2) : '0.00';
        echo "<h3>Total Revenue: $$val</h3>";
    }
    ?>
</div>
<?php endif; ?>

<script>
// Toggle views
document.getElementById('vehicles-link').addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('vehicle-gallery').style.display = 'block';
    document.getElementById('reservation-section').style.display = 'none';
    const main = document.getElementById('main-container'); if (main) main.style.display = 'none';
});

document.getElementById('reservations-link').addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('vehicle-gallery').style.display = 'none';
    const main = document.getElementById('main-container'); if (main) main.style.display = 'none';
    document.getElementById('reservation-section').style.display = 'block';
    document.querySelector('.reservation-choice').style.display = 'flex';
    document.getElementById('make-reservation-form').style.display = 'none';
    document.getElementById('modify-reservation-form').style.display = 'none';
});

// Show forms
document.getElementById('make-res-btn').addEventListener('click', () => {
    document.querySelector('.reservation-choice').style.display = 'none';
    document.getElementById('make-reservation-form').style.display = 'block';
});
document.getElementById('modify-res-btn').addEventListener('click', () => {
    document.querySelector('.reservation-choice').style.display = 'none';
    document.getElementById('modify-reservation-form').style.display = 'block';
});

// Add close buttons to flash messages
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash-message').forEach(msg => {
        const btn = document.createElement('button');
        btn.className = 'flash-close';
        btn.innerHTML = '&times;';
        btn.onclick = () => msg.remove();
        msg.appendChild(btn);
    });
});
</script>
</body>
</html>
