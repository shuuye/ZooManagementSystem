<?php
/*Author name: Lim Shuye*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $dateTime = $_POST['date-time'];
    $inventoryType = $_POST['inventory-type'];
    $inventoryItemId = $_POST['inventory-item'];
    $quantityUsed = $_POST['quantity-used'];
    $reasonForUse = isset($_POST['reason-for-use']) ? $_POST['reason-for-use'] : null;

    $data = [
        'dateTime' => $dateTime,
        'inventoryType' => $inventoryType,
        'inventoryItemId' => $inventoryItemId,
        'quantityUsed' => $quantityUsed,
        'reasonForUse' => $reasonForUse
    ];

    // Validate required fields
    if (empty($inventoryItemId) || empty($quantityUsed)) {
        echo "Error: Inventory item and quantity are required.";
        exit;
    }

    // Instantiate control class
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryItemFactory.php';

    // Create item
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($inventoryItemId, $inventoryType, NULL, Null);

    $item = $inventory->checkInventory($inventoryItemId); // Array ( [0] => Array ( [itemName] => Tiger [quantity] => 1 ) )
    $itemDetails = $item[0];

    if ($item) {
        $availableQuantity = $itemDetails['quantity'];

        // Check if the quantity used is less than or equal to the available quantity
        if ($quantityUsed > $availableQuantity) {
            echo "Error: The quantity used exceeds the available stock for " . $itemDetails['itemName'] . ".";
            exit;
        }

        // Update the inventory to reflect the new quantity after usage
        $newQuantity = $availableQuantity - $quantityUsed;

        // Running error handling
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryManagement.php';
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryCommand.php';
        $inventoryManager = new InventoryManagement();
        $success = $inventoryManager->executeCommand(new UpdateItemCommand($inventory, $newQuantity, $itemDetails['quantity'], $inventoryItemId));

        if ($success) {//
            $logsuccess = $inventory->logInventoryUsage($data);
        } else {
            header("Location: index.php?controller=inventory&action=logusage&status=itemNotfound");
            exit();
        }


        if ($logsuccess) {//
            if ($newQuantity == 0) {
                include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\PurchaseOrder.php';
                include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\PurchaseOrderLineItem.php';

                // Format the date and time
                $orderDate = date('Y-m-d H:i:s');
                $dateObj = new DateTime($orderDate);

                // Add 3 days to the order date to get the shipping date
                $dateObj->modify('+3 days');
                $shippingDate = $dateObj->format('Y-m-d H:i:s');  // Get the shipping date in 'Y-m-d' format
                //see the inventory type

                include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\SupplierRecord.php';
                //get the first brand of the type from supplier_record use inventoryid get lowest price
                $supplierRecord = new SupplierRecord();
                $lowestPriceRecord = $supplierRecord->getFirstBrandWithLowestPrice($inventoryItemId);
                //Array ( [supplierId] => 2 [inventoryId] => 26 [supplyUnitPrice] => 50.00 [cleaningId] => 1 [habitatId] => [foodId] => )
                // Determine which ID is not empty and set the corresponding item ID
                // Initialize all IDs as null
                $foodId = null;
                $habitatId = null;
                $cleaningId = null;

            // Check which ID field is not empty and assign $itemID to it
                $foodId = !empty($lowestPriceRecord["foodId"]) ? $lowestPriceRecord["foodId"] : null;
                $habitatId = !empty($lowestPriceRecord["habitatId"]) ? $lowestPriceRecord["habitatId"] : null;
                $cleaningId = !empty($lowestPriceRecord["cleaningId"]) ? $lowestPriceRecord["cleaningId"] : null;

                // Set the ID for the inventory based on which ID is non-null
                if (!empty($habitatId)) {
                    $inventory->setId($habitatId);
                } elseif (!empty($foodId)) {
                    $inventory->setId($foodId);
                } elseif (!empty($cleaningId)) {
                    $inventory->setId($cleaningId);
                }



                include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\Supplier.php';
                $supplier = new Supplier();
                $lowestSupplierDetails = $supplier->getSupplierRecordById($lowestPriceRecord["supplierId"]);
                //Array ( [supplierId] => 5 [supplierName] => Food Suppliers Sdn Bhd [contactName] => Tan Chong Wei 
                //[contactEmail] => chong.wei@example.com [contactPhone] => 03-5678-9012 [address] => 202 Jalan Makanan 
                //[city] => Subang Jaya [state] => Selangor [postalCode] => 47500 [country] => Malaysia [
                //website] => www.foodsuppliers.com.my [rating] => 4.30 [lastOrderDate] => 2024-05-05 
                //[paymentTerms] => Net 30 [deliveryTime] => 7 [supplierNotes] => Good variety of products. )
                $supplier->setId($lowestPriceRecord["supplierId"]);

                $total = $itemDetails['reorderThreshold'] * $lowestPriceRecord["supplyUnitPrice"];

                try {
                    // Create a new Purchase Order = orderdate shipping date shipping address ]]]] billingaddress = supplier  total = reorderthreshold * supplyunitPrice
                    $newPO = new PurchaseOrder($supplier, $orderDate, $shippingDate,
                            $lowestSupplierDetails["address"], "Jalan Taman Zooview, Taman Zooview, 68000 Ampang, Selangor", $total, "Pending");
                    $newPOId = $newPO->addNewPO();

                    // Add Line Item to the Purchase Order = newpoid inventoryid ]]] quantity = threshold, price = supplyUnitPrice,
                    //id from supplier_record
                    $lineItem = $newPO->addLineItem($newPOId, $inventoryItemId, $itemDetails['reorderThreshold'] + 10, $lowestPriceRecord["supplyUnitPrice"], $inventory);
                    $lineItem->addNewPOLine();

                    try {
                        // Include the RestfulPoNotiService_Consume class
                        include_once 'C:\xampp\htdocs\ZooManagementSystem\public\InventoryWebService\RestfulPoNotiService_Consume.php';

                        // Create an instance of the RestfulPoNotiService_Consume class
                        $webService = new RestfulPoNotiService_Consume();

                        // Pass the formatted strings along with the other details to the web service
                        $webService->sendPOtoSupplier(
                                $newPOId, // Purchase Order ID
                                $itemDetails['itemName'], // Item name
                                $itemDetails['reorderThreshold'] + 10, // Quantity
                                $orderDate, // Formatted Order Date
                                $shippingDate, // Formatted Shipping Date
                                "Jalan Taman Zooview, Taman Zooview, 68000 Ampang, Selangor", // Shipping Address (string)
                                $total // Total amount
                        );

                        // Redirect to the desired page with status and new quantity
                        header("Location: index.php?controller=inventory&action=logusage&status=successWeb&newQuantity=$newQuantity");
                        exit();
                    } catch (Exception $ex) {
                        header("Location: index.php?controller=inventory&action=logusage&status=errorWeb&newQuantity=$newQuantity");
                        exit();
                    }
                    // Redirect to success page
                    header("Location: index.php?controller=inventory&action=logusage&status=successPO&newQuantity=$newQuantity");
                    exit();
                } catch (Exception $e) {
                    // Optionally redirect to an error page
                    header("Location: index.php?controller=inventory&action=logusage&status=errorPO&newQuantity=$newQuantity");
                    exit();
                }
            }
            header("Location: index.php?controller=inventory&action=logusage&status=success&newQuantity=$newQuantity");
            exit();
        } else {
            header("Location: index.php?controller=inventory&action=logusage&status=error&newQuantity=$newQuantity");
            exit();
        }
    } else {
        header("Location: index.php?controller=inventory&action=logusage&status=itemNotfound");
        exit();
    }
} else {
    header("Location: index.php?controller=inventory&action=logusage&status=invalidRequest");
    exit();
}
?>
