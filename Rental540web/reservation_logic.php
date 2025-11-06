<?php
// Handle Reservation Creation
if (isset($_POST['makeReservation'])) {
    $f = trim($_POST['FirstName']);
    $l = trim($_POST['LastName']);
    $dob = $_POST['DateOfBirth'];
    $dl = trim($_POST['DriverLicenseNumber']);
    $em = trim($_POST['Email']);
    $ph = preg_replace('/[^0-9]/', '', $_POST['Phone']); // keep digits only
    $pick = $_POST['PickupLocationID'];
    $ret = $_POST['ReturnLocationID'];
    $car = $_POST['CarType'];
    $start = $_POST['StartDate'];
    $end = $_POST['EndDate'];

    // ✅ Calculate and validate age
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    if ($age < 18) {
        echo "<div style='background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin:20px;'>
                ❌ Sorry, you must be at least 18 years old to make a reservation.
              </div>";
        return;
    }

    // ✅ Format phone to 319-555-1100 pattern
    if (strlen($ph) == 10) {
        $ph = substr($ph, 0, 3) . '-' . substr($ph, 3, 3) . '-' . substr($ph, 6);
    } else {
        echo "<div style='background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin:20px;'>
                ❌ Invalid phone number format. Please enter a 10-digit number (e.g., 3195551100).
              </div>";
        return;
    }

    // ✅ Map form car names to DB names
    $carMap = [
        'Compact Car' => 'Compact',
        'Midsize Car' => 'Midsize',
        'Full Size Car' => 'Full Size',
        'Standard SUV' => 'SUV',
        'Luxury Car' => 'Luxury',
        'Minivan' => 'Minivan',
        'Pickup Truck' => 'Truck'
    ];
    $lookupName = $carMap[$car] ?? $car;

    // 🔹 Find or create customer
    $c = $conn->prepare("SELECT CustomerID FROM CUSTOMER WHERE Email=?");
    $c->bind_param("s", $em);
    $c->execute();
    $c->store_result();
    if ($c->num_rows > 0) {
        $c->bind_result($cid);
        $c->fetch();
    } else {
        $add = $conn->prepare("INSERT INTO CUSTOMER (FirstName, LastName, DateOfBirth, DriverLicenseNumber, Email, Phone)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $add->bind_param("ssssss", $f, $l, $dob, $dl, $em, $ph);
        $add->execute();
        $cid = $add->insert_id;
        $add->close();
    }
    $c->close();

    // 🔹 Get vehicle type ID
    $t = $conn->prepare("SELECT TypeID FROM VEHICLE_TYPE WHERE TypeName=?");
    $t->bind_param("s", $lookupName);
    $t->execute();
    $t->bind_result($tid);
    $t->fetch();
    $t->close();

    if (empty($tid)) {
        echo "<div style='background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin:20px;'>
                ❌ Invalid vehicle type selected. Please ensure VEHICLE_TYPE table matches dropdown names.
              </div>";
        return;
    }

    // 🔹 Insert reservation
    $r = $conn->prepare("INSERT INTO RESERVATION (CustomerID, PickupLocationID, ReturnLocationID, TypeID, StartDate, EndDate)
                         VALUES (?, ?, ?, ?, ?, ?)");
    $r->bind_param("iiiiss", $cid, $pick, $ret, $tid, $start, $end);

    if ($r->execute()) {
        echo "<div style='background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin:20px;'>
                ✅ Reservation created successfully!<br>
                <strong>Name:</strong> $f $l<br>
                <strong>Age:</strong> $age<br>
                <strong>Phone:</strong> $ph<br>
                <strong>Vehicle Type:</strong> $lookupName<br>
                <strong>Rental Period:</strong> $start to $end
              </div>";
    } else {
        echo "<div style='background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin:20px;'>
                ❌ Error saving reservation: " . htmlspecialchars($r->error) . "
              </div>";
    }
    $r->close();
}

// Handle Find Reservation
if (isset($_POST['findReservation'])) {
    $id = (int)$_POST['ReservationID'];
    $em = $_POST['EmailVerify'];

    $q = $conn->prepare("SELECT r.*, vt.TypeName 
                         FROM RESERVATION r 
                         JOIN CUSTOMER c ON r.CustomerID=c.CustomerID 
                         JOIN VEHICLE_TYPE vt ON r.TypeID=vt.TypeID 
                         WHERE r.ReservationID=? AND c.Email=?");
    $q->bind_param("is", $id, $em);
    $q->execute();
    $res = $q->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        echo "<div class='container'>
                <h3>Edit Reservation #{$row['ReservationID']}</h3>
                <form method='POST'>
                    <input type='hidden' name='ReservationID' value='{$row['ReservationID']}'>
                    <label>Car Type</label>
                    <input type='text' name='TypeName' value='{$row['TypeName']}' required>
                    <label>Start Date</label>
                    <input type='date' name='StartDate' value='{$row['StartDate']}' required>
                    <label>End Date</label>
                    <input type='date' name='EndDate' value='{$row['EndDate']}' required>
                    <label>Status</label>
                    <input type='text' name='Status' value='{$row['Status']}' required>
                    <button type='submit' name='editReservation' class='submit-btn'>Save Changes</button>
                </form>
              </div>";
    } else {
        echo "<div style='background:#f8d7da;color:#721c24;padding:10px;margin:10px;border-radius:6px;'>
                ❌ Reservation not found.
              </div>";
    }
    $q->close();
}

// Handle Edit Reservation
if (isset($_POST['editReservation'])) {
    $id = $_POST['ReservationID'];
    $type = $_POST['TypeName'];
    $start = $_POST['StartDate'];
    $end = $_POST['EndDate'];
    $status = $_POST['Status'];

    $t = $conn->prepare("SELECT TypeID FROM VEHICLE_TYPE WHERE TypeName=?");
    $t->bind_param("s", $type);
    $t->execute();
    $t->bind_result($tid);
    $t->fetch();
    $t->close();

    $u = $conn->prepare("UPDATE RESERVATION SET TypeID=?, StartDate=?, EndDate=?, Status=? WHERE ReservationID=?");
    $u->bind_param("isssi", $tid, $start, $end, $status, $id);

    if ($u->execute()) {
        echo "<div style='background:#d4edda;color:#155724;padding:10px;margin:10px;border-radius:6px;'>
                ✅ Reservation updated successfully!
              </div>";
    } else {
        echo "<div style='background:#f8d7da;color:#721c24;padding:10px;margin:10px;border-radius:6px;'>
                ❌ Error updating reservation: " . htmlspecialchars($u->error) . "
              </div>";
    }
    $u->close();
}
?>

