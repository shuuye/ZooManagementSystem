<?php
/*Author name: Lim Shuye*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve common fields from the POST request
    $inventoryId = $_POST['inventoryId'];
    $itemType = $_POST['itemType'];
    $itemName = $_POST['itemName'];
    $storageLocation = $_POST['storageLocation'];
    $reorderThreshold = $_POST['reorderThreshold'];

    // Collect common data into an associative array
    $data = [
        'inventoryId' => $inventoryId,
        'itemType' => $itemType,
        'itemName' => $itemName,
        'storageLocation' => $storageLocation,
        'reorderThreshold' => $reorderThreshold,
    ];

    
    // Instantiate control classes
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryItemFactory.php';

    // Create the appropriate item
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($inventoryId, $itemType, null, null);

    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryManagement.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryCommand.php';

    // Execute the edit command
    $inventoryManager = new InventoryManagement();
    $success = $inventoryManager->executeCommand(new editItemCommand($inventory, $data));

    // Redirect back or show a success message
    if ($success) {
        header("Location: index.php?controller=inventory&action=inventoryTracking&status=successEdit");
    } else {
        header("Location: index.php?controller=inventory&action=inventoryTracking&status=errorEdit");
    }
    exit;
}
?>

