<?php include('db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental540 - Car Rental System</title>
    <style>
        /* --- BODY & BACKGROUND --- */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1A1A1A;
            transition: background-color 0.4s, color 0.4s;
            background-color: #ffffff;
            background-image: url('dashboard-bg.png');
            background-repeat: no-repeat;
            background-position: center top;
            background-size: cover;
        }

        /* --- HEADER --- */
        header {
            background: #073334ff;
            color: white;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        header img {
            height: 60px;
            border-radius: 8px;
            margin-right: 15px;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        header h1 {
            font-size: 28px;
            margin: 0;
            letter-spacing: 1px;
            color: #66B2FF;
        }

        nav a {
            color: #66B2FF;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
        }

        nav a:hover {
            color: #073334;
            text-decoration: underline;
        }

        .dark-mode-btn {
            background: #004080;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .dark-mode-btn:hover {
            background: #0059B3;
        }

        /* --- CONTAINER --- */
        .container {
            max-width: 850px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.3);
            background-color: #ffffff;
            color: #1A1A1A;
        }

        /* --- FORM ELEMENTS --- */
        form {
            text-align: center;
            margin-bottom: 30px;
        }

        select, button, input {
            padding: 10px 14px;
            font-size: 16px;
            margin: 5px;
            border-radius: 6px;
            border: none;
        }

        select {
            background-color: #1E3A5F;
            color: #EAEAEA;
            border: 1px solid #2F4F6F;
        }

        select:hover {
            background-color: #284B74;
        }

        button {
            background: #004080;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0059B3;
        }

        input {
            background-color: #f5f5f5;
            color: #1A1A1A;
            border: 1px solid #ddd;
            width: 200px;
        }

        /* --- TABLE --- */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #1A2E4A;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            border-bottom: 1px solid #2C4C6E;
            padding: 12px;
            text-align: left;
            color: #EAEAEA;
        }

        th {
            background-color: #004080;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:nth-child(even) {
            background-color: #203A5C;
        }

        tr:hover {
            background-color: #2C4C6E;
        }

        .no-data {
            text-align: center;
            color: #2F4F6F;
            font-style: italic;
        }

        h2 {
            color: #66B2FF;
            text-align: center;
        }

        /* --- VEHICLE GALLERY --- */
        .vehicle-gallery {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: none; /* Hidden by default */
        }

        .vehicle-gallery h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #073334;
            font-size: 32px;
        }

        .vehicle-cards {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .vehicle-card {
            flex: 1;
            min-width: 180px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .vehicle-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .vehicle-image {
            height: 150px;
            width: 100%;
            background-size: cover;
            background-position: center;
        }

        .vehicle-info {
            padding: 15px;
            text-align: center;
        }

        .vehicle-info h3 {
            margin: 0 0 10px 0;
            color: #073334;
        }

        .vehicle-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        /* --- RESERVATION SECTION --- */
        .reservation-section {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.3);
            background-color: #ffffff;
            color: #1A1A1A;
            display: none; /* Hidden by default */
        }

        .reservation-section h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #073334;
            font-size: 32px;
        }

        .reservation-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #073334;
        }

        .reservation-tab {
            padding: 12px 24px;
            background: #f0f0f0;
            border: none;
            border-radius: 6px 6px 0 0;
            margin: 0 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .reservation-tab.active {
            background: #073334;
            color: white;
        }

        .reservation-tab:hover:not(.active) {
            background: #ddd;
        }

        .reservation-form {
            display: none;
            max-width: 600px;
            margin: 0 auto;
        }

        .reservation-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #073334;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-actions {
            text-align: center;
            margin-top: 30px;
        }

        .submit-btn {
            background: #004080;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #0059B3;
        }

        .age-display {
            background: #e9f7fe;
            padding: 8px 12px;
            border-radius: 4px;
            margin-top: 5px;
            font-weight: 500;
            color: #004080;
            display: inline-block;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
        }

        /* --- DARK MODE --- */
        .dark-mode {
            background-color: #1b1b1b;
            color: #f1f1f1;
        }

        .dark-mode .container {
            background-color: #2C2C2C;
            color: #f1f1f1;
        }

        .dark-mode header {
            background-color: #111;
        }

        .dark-mode table {
            background-color: #444;
        }

        .dark-mode th {
            background-color: #222;
        }

        .dark-mode select {
            background-color: #222;
            color: #ddd;
        }

        .dark-mode .vehicle-gallery h2 {
            color: #66B2FF;
        }

        .dark-mode .vehicle-card {
            background: #2C2C2C;
        }

        .dark-mode .vehicle-info h3 {
            color: #66B2FF;
        }

        .dark-mode .vehicle-info p {
            color: #ddd;
        }

        .dark-mode .reservation-section {
            background-color: #2C2C2C;
            color: #f1f1f1;
        }

        .dark-mode .reservation-section h2 {
            color: #66B2FF;
        }

        .dark-mode .reservation-tab {
            background: #444;
            color: #ddd;
        }

        .dark-mode .reservation-tab.active {
            background: #004080;
            color: white;
        }

        .dark-mode .reservation-tab:hover:not(.active) {
            background: #555;
        }

        .dark-mode .form-group label {
            color: #66B2FF;
        }

        .dark-mode input {
            background-color: #444;
            color: #f1f1f1;
            border: 1px solid #666;
        }

        .dark-mode .age-display {
            background: #1a3a5f;
            color: #66B2FF;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <img src="logo.png" alt="Company Logo">
            <div>
                <h1>RENTAL540</h1>
                <p>Your One-Stop Car Rental Business</p>
                <nav>
                    <a href="#" id="reservations-link">Reservations</a>
                    <a href="#" id="vehicles-link">Vehicles</a>
                    <a href="locations.php">Locations</a>
                    <a href="about.php">About</a>
                </nav>
            </div>
        </div>
        <button class="dark-mode-btn" onclick="toggleDarkMode()">Dark Mode</button>
    </header>

    <!-- Vehicle Gallery Section -->
    <section class="vehicle-gallery" id="vehicle-gallery">
        <h2>Our Vehicle Fleet</h2>
        <div class="vehicle-cards">
            <div class="vehicle-card">
                <div class="vehicle-image" style="background-image: url('compact-car.png');"></div>
                <div class="vehicle-info">
                    <h3>Compact Cars</h3>
                    <p>Fuel-efficient and perfect for city driving</p>
                </div>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-image" style="background-image: url('standard-suv.png');"></div>
                <div class="vehicle-info">
                    <h3>Standard SUV</h3>
                    <p>Spacious and versatile for family trips</p>
                </div>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-image" style="background-image: url('luxury-car.png');"></div>
                <div class="vehicle-info">
                    <h3>Luxury Car</h3>
                    <p>Premium comfort and style for special occasions</p>
                </div>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-image" style="background-image: url('minivan.png');"></div>
                <div class="vehicle-info">
                    <h3>Minivan</h3>
                    <p>Maximum space for groups and cargo</p>
                </div>
            </div>
            <div class="vehicle-card">
                <div class="vehicle-image" style="background-image: url('pickup-truck.png');"></div>
                <div class="vehicle-info">
                    <h3>Pickup Trucks</h3>
                    <p>Powerful and practical for work and play</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Reservation Section -->
    <section class="reservation-section" id="reservation-section">
        <h2>Reservation Management</h2>
        
        <div class="reservation-tabs">
            <button class="reservation-tab active" data-tab="make-reservation">Make a Reservation</button>
            <button class="reservation-tab" data-tab="modify-reservation">Modify Reservation</button>
        </div>
        
        <!-- Make Reservation Form -->
        <form class="reservation-form active" id="make-reservation-form" method="POST" action="">
            <h3>Personal Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="FirstName" class="required">First Name</label>
                    <input type="text" id="FirstName" name="FirstName" required>
                </div>
                <div class="form-group">
                    <label for="LastName" class="required">Last Name</label>
                    <input type="text" id="LastName" name="LastName" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="DateOfBirth" class="required">Date of Birth</label>
                <input type="date" id="DateOfBirth" name="DateOfBirth" required onchange="calculateAge()">
                <div id="ageDisplay" class="age-display" style="display: none;">Age: <span id="ageValue"></span></div>
            </div>
            
            <div class="form-group">
                <label for="DriverLicenseNumber" class="required">Driver License Number</label>
                <input type="text" id="DriverLicenseNumber" name="DriverLicenseNumber" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="Email" class="required">Email</label>
                    <input type="email" id="Email" name="Email" required>
                </div>
                <div class="form-group">
                    <label for="Phone" class="required">Phone</label>
                    <input type="tel" id="Phone" name="Phone" required>
                </div>
            </div>
            
            <h3>Reservation Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="PickupLocationID" class="required">Pickup Location</label>
                    <select id="PickupLocationID" name="PickupLocationID" required>
                        <option value="">Select Pickup Location</option>
                        <?php
                        $locationSql = "SELECT LocationID, LocationName FROM LOCATION ORDER BY LocationName";
                        $locationResult = $conn->query($locationSql);
                        if ($locationResult->num_rows > 0) {
                            while ($location = $locationResult->fetch_assoc()) {
                                echo "<option value='{$location['LocationID']}'>{$location['LocationName']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ReturnLocationID" class="required">Return Location</label>
                    <select id="ReturnLocationID" name="ReturnLocationID" required>
                        <option value="">Select Return Location</option>
                        <?php
                        $locationResult = $conn->query($locationSql);
                        if ($locationResult->num_rows > 0) {
                            $locationResult->data_seek(0); // Reset pointer
                            while ($location = $locationResult->fetch_assoc()) {
                                echo "<option value='{$location['LocationID']}'>{$location['LocationName']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="CarType" class="required">Car Type</label>
                <select id="CarType" name="CarType" required>
                    <option value="">Select Car Type</option>
                    <option value="Compact Car">Compact Car</option>
                    <option value="Standard SUV">Standard SUV</option>
                    <option value="Luxury Car">Luxury Car</option>
                    <option value="Minivan">Minivan</option>
                    <option value="Pickup Truck">Pickup Truck</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="StartDate" class="required">Start Date</label>
                    <input type="date" id="StartDate" name="StartDate" required onchange="validateDates()">
                </div>
                <div class="form-group">
                    <label for="EndDate" class="required">End Date</label>
                    <input type="date" id="EndDate" name="EndDate" required onchange="validateDates()">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-btn" name="makeReservation">Submit Reservation</button>
            </div>
        </form>
        
        <!-- Modify Reservation Form -->
        <form class="reservation-form" id="modify-reservation-form" method="POST" action="">
            <div class="form-group">
                <label for="ReservationID" class="required">Reservation ID</label>
                <input type="text" id="ReservationID" name="ReservationID" required>
            </div>
            
            <div class="form-group">
                <label for="EmailVerify" class="required">Email Address</label>
                <input type="email" id="EmailVerify" name="EmailVerify" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-btn" name="findReservation">Find Reservation</button>
            </div>
        </form>
    </section>

    <div class="container" id="main-container">
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
                echo "<p class='no-data'>No revenue data found for this location.</p>";
            }
            $stmt->close();
        } elseif (isset($_POST['showTotal'])) {
            $sql = "SELECT SUM(p.Amount) AS TotalRevenue FROM PAYMENT p WHERE p.Status = 'Completed'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            
            if ($row['TotalRevenue'] !== null) {
                echo "<h2>Total Revenue: <span style='color:#66B2FF;'>$" . number_format($row['TotalRevenue'], 2) . "</span></h2>";
            } else {
                echo "<p class='no-data'>No completed payments found.</p>";
            }
        }
        
        // Handle reservation form submission
        if (isset($_POST['makeReservation'])) {
            // Process the reservation form data
            $firstName = $_POST['FirstName'];
            $lastName = $_POST['LastName'];
            $dateOfBirth = $_POST['DateOfBirth'];
            $driverLicense = $_POST['DriverLicenseNumber'];
            $email = $_POST['Email'];
            $phone = $_POST['Phone'];
            $pickupLocation = $_POST['PickupLocationID'];
            $returnLocation = $_POST['ReturnLocationID'];
            $carType = $_POST['CarType'];
            $startDate = $_POST['StartDate'];
            $endDate = $_POST['EndDate'];
            
            // Calculate age from date of birth
            $dob = new DateTime($dateOfBirth);
            $today = new DateTime();
            $age = $today->diff($dob)->y;
            
            // Here you would typically insert the data into your database
            // For now, we'll just display a success message with the details
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-top: 20px;'>
                    <strong>Success!</strong> Your reservation request has been received.<br>
                    <strong>Name:</strong> $firstName $lastName<br>
                    <strong>Age:</strong> $age years<br>
                    <strong>Car Type:</strong> $carType<br>
                    <strong>Pickup Location:</strong> $pickupLocation<br>
                    <strong>Return Location:</strong> $returnLocation<br>
                    <strong>Rental Period:</strong> $startDate to $endDate<br>
                    We'll contact you shortly at $email or $phone.
                  </div>";
        }
        ?>
    </div>

    <script>
        function toggleDarkMode(){
            document.body.classList.toggle("dark-mode");
        }

        // Calculate age from date of birth
        function calculateAge() {
            const dobInput = document.getElementById('DateOfBirth');
            const ageDisplay = document.getElementById('ageDisplay');
            const ageValue = document.getElementById('ageValue');
            
            if (dobInput.value) {
                const dob = new Date(dobInput.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                ageValue.textContent = age;
                ageDisplay.style.display = 'block';
                
                // Check if user is at least 18 years old
                if (age < 18) {
                    ageDisplay.style.background = '#f8d7da';
                    ageDisplay.style.color = '#721c24';
                    alert('You must be at least 18 years old to rent a vehicle.');
                } else {
                    ageDisplay.style.background = '#e9f7fe';
                    ageDisplay.style.color = '#004080';
                }
            } else {
                ageDisplay.style.display = 'none';
            }
        }

        // Validate that end date is after start date
        function validateDates() {
            const startDate = document.getElementById('StartDate');
            const endDate = document.getElementById('EndDate');
            
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                
                if (end <= start) {
                    alert('End date must be after start date.');
                    endDate.value = '';
                }
                
                // Check if start date is in the past
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (start < today) {
                    alert('Start date cannot be in the past.');
                    startDate.value = '';
                }
            }
        }

        // Show vehicle gallery when Vehicles link is clicked
        document.getElementById('vehicles-link').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hide other sections
            document.getElementById('main-container').style.display = 'none';
            document.getElementById('reservation-section').style.display = 'none';
            
            // Show the vehicle gallery
            document.getElementById('vehicle-gallery').style.display = 'block';
            
            // Scroll to the vehicle gallery
            document.getElementById('vehicle-gallery').scrollIntoView({ behavior: 'smooth' });
        });

        // Show reservation section when Reservations link is clicked
        document.getElementById('reservations-link').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hide other sections
            document.getElementById('main-container').style.display = 'none';
            document.getElementById('vehicle-gallery').style.display = 'none';
            
            // Show the reservation section
            document.getElementById('reservation-section').style.display = 'block';
            
            // Scroll to the reservation section
            document.getElementById('reservation-section').scrollIntoView({ behavior: 'smooth' });
        });

        // Show main container when other nav links are clicked
        document.querySelectorAll('nav a').forEach(link => {
            if (link.id !== 'vehicles-link' && link.id !== 'reservations-link') {
                link.addEventListener('click', function() {
                    document.getElementById('main-container').style.display = 'block';
                    document.getElementById('vehicle-gallery').style.display = 'none';
                    document.getElementById('reservation-section').style.display = 'none';
                });
            }
        });

        // Tab functionality for reservation section
        document.querySelectorAll('.reservation-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.reservation-tab').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all forms
                document.querySelectorAll('.reservation-form').forEach(form => {
                    form.classList.remove('active');
                });
                
                // Show the corresponding form
                const tabId = this.getAttribute('data-tab');
                document.getElementById(`${tabId}-form`).classList.add('active');
            });
        });

        // Set minimum date for start date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('StartDate').setAttribute('min', today);
            document.getElementById('EndDate').setAttribute('min', today);
        });
    </script>
</body>
</html>