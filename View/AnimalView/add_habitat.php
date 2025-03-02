<?php
// Include the controller
include_once '../../Control/AnimalControllerN/HabitatControllerObserver.php';
$habitatController = new HabitatControllerObserver();// Create an instance of the controller
$habitatController->handleFormSubmission();// Handle form submission
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Habitat</title>
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/ani_Nav.css">
    <link rel="stylesheet" type="text/css" href="../../Css/AnimalN/animal_form.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="habitatViewOnly.php">Back </a></li>
            <li><a href="list_habitats.php">Edit and Delete Habitat</a></li>
        </ul>
    </nav>
    
    <h1>Add New Habitat</h1>
    
    <form action="add_habitat.php" method="POST">
        
        <!-- CSRF token for security -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        
        <input type="hidden" name="habitat_id" id="habitat_id"> <!-- Hidden field for habitat ID -->

        <label for="habitat_name">Habitat Name:</label>
        <input type="text" id="habitat_name" name="habitat_name" required maxlength="15"><br>

        <label for="availability">Availability:</label>
        <select id="availability" name="availability" required>
            <option value="">Select Availability</option>
            <option value="Available">Available</option>
            <option value="Unavailable">Unavailable</option>
        </select><br>

        <label for="capacity">Capacity:</label>
        <input type="number" id="capacity" name="capacity" min="1" required><br>

        <label for="environment">Environment:</label>
        <select id="environment" name="environment" required>
            <option value="">Select Environment</option>
            <option value="hot">Hot</option>
            <option value="cold">Cold</option>
            <option value="water">Water</option>
        </select><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required ></textarea><br>

        <button type="submit" name="submit">Save Habitat</button>
    </form>
  
</body>
</html>