<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Animal</title>
</head>
<body>
    <h1>Edit Animal Details</h1>

    <?php if ($animal): ?>
   <form method="POST"action="?action=edit&id=<?php echo htmlspecialchars($animal['id']); ?>"  enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($animal['id']); ?>">
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($animal['name']); ?>" required><br><br>
            
            <label for="species">Species:</label>
            <input type="text" id="species" name="species" value="<?php echo htmlspecialchars($animal['species']); ?>" readonly required><br><br>
            
            <label for="subspecies">Subspecies:</label>
            <input type="text" id="subspecies" name="subspecies" value="<?php echo htmlspecialchars($animal['subspecies']); ?>"><br><br>
            
          <label for="categories">Categories:</label>
          <select name="categories" id="categories" required>
            <option value="Mammals" <?php if ($animal['categories'] == 'Mammals') echo 'selected'; ?>>Mammals</option>
            <option value="Birds" <?php if ($animal['categories'] == 'Birds') echo 'selected'; ?>>Birds</option>
            <option value="Amphibians" <?php if ($animal['categories'] == 'Amphibians') echo 'selected'; ?>>Amphibians</option>
          </select><br><br>

            
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($animal['age']); ?>" required><br><br>
            
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php echo $animal['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $animal['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select><br><br>
            
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($animal['date_of_birth']); ?>" max="<?php echo date('Y-m-d'); ?>" required><br><br>
            
            <label for="avg_lifespan">Average Lifespan:</label>
            <input type="number" id="avg_lifespan" name="avg_lifespan" value="<?php echo htmlspecialchars($animal['avg_lifespan']); ?>" required><br><br>
            
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($animal['description']); ?></textarea><br><br>
            
            <label for="height">Height:</label>
            <input type="text" id="height" name="height" value="<?php echo htmlspecialchars($animal['height']); ?>" required><br><br>
            
            <label for="weight">Weight:</label>
            <input type="text" id="weight" name="weight" value="<?php echo htmlspecialchars($animal['weight']); ?>" required><br><br>
            
            <label for="habitat_id">Habitat ID:</label>
            <input type="text" id="habitat_id" name="habitat_id" value="<?php echo htmlspecialchars($animal['habitat_id']); ?>" required><br><br>
            
            <label for="animal_image">Update Animal Image:</label><br>
            <?php if ($animalImage): ?>
                <img src="<?php echo htmlspecialchars($animalImage); ?>" alt="Animal Image" style="width: 100px;"><br>
                <p>Current Image: <?php echo basename($animalImage); ?></p><br>
            <?php endif; ?>
            <input type="file" name="animal_image" id="animal_image" accept=".jpg, .jpeg, .png" ><br>
            <input type="submit" value="Update Animal">
        </form>
    <?php else: ?>
        <p>Animal not found.</p>
    <?php endif; ?>
</body>
</html>
