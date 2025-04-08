<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_interest = $_POST['service_interest'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    //creating variables to hold user data
    // Format the booking date
    $date = DateTime::createFromFormat('Y-m-d', $booking_date);
    $formatted_date = $date->format('d/m/Y');
   

    // Prepare an SQL statement
    $sql = "INSERT INTO consultation (service_interest, first_name, last_name, booking_date, booking_time, phone, message) VALUES (?, ?, ?, ?, ?, ?, ?)";
   //inserting into the consultation table and inside is the fields, the question marks are placeholders for the variables)
    if ($stmt = $conn->prepare($sql)) { //validating the statement
        // Bind variables to the prepared statement as parameters, the 'sssssss' define the variables type
        $stmt->bind_param("sssssss", $service_interest, $firstname, $lastname, $booking_date, $booking_time, $phone, $message);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Booking successfully added!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>
<form action="booking.php" method="post">
    <input type="text" placeholder="Enter Service Interest" name="service_interest" required>
    <input type="text" placeholder="First Name" name="firstname" required>
    <input type="text" placeholder="Last Name" name="lastname" required>
    <input type="Date" placeholder="Date" name="booking_date" required>
    <input type="text" placeholder="Time" name="booking_time" required>
    <input type="text" placeholder="Phone" name="phone" required>
    <textarea name="message" cols="30" rows="2" placeholder="Message" required></textarea>
    <input type="submit" value="Book Now">
</form>
