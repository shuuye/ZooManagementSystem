<?php
/*Author name: Lim Shuye*/
//used to route users to the pages

include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\InventoryModel.php';
require_once 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryView.php';

class InventoryController extends InventoryModel {

    private $model;
    private $view;

    public function __construct(InventoryModel $model, $view) {
        $this->model = $model;
        $this->view = $view;
        $this->model->updateXML();
    }

    // Routing logic based on a simple 'action' parameter
    public function route() {
        $controller = isset($_GET['controller']) ? $_GET['controller'] : "inventory";
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        if ($controller == "inventory") {
            switch ($action) {
                case 'addInventoryItem':
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    $error = isset($_GET['error']) ? $_GET['error'] : '';
                    switch ($status) {
                        case 'errorAddItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                        case 'errorEditItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                    }
                    $this->addInventoryItem();
                    break;
                case 'habitatItem':
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    $error = isset($_GET['error']) ? $_GET['error'] : '';
                    switch ($status) {
                        case 'successRemoveInv':
                            echo "<p class='alert alert-success'>Inventory removed successfully.</p>";
                            break;
                        case 'errorRemoveInv':
                            echo "<p class='alert alert-error'>Failed to remove Inventory. Please try again.</p>";
                            break;
                        case 'errorAddItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                        case 'errorEditItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                    }
                    $this->viewHabitatItem();
                    break;
                case 'foodItem':
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    $error = isset($_GET['error']) ? $_GET['error'] : '';
                    switch ($status) {
                        case 'successRemoveInv':
                            echo "<p class='alert alert-success'>Inventory removed successfully.</p>";
                            break;
                        case 'errorRemoveInv':
                            echo "<p class='alert alert-error'>Failed to remove Inventory. Please try again.</p>";
                            break;
                        case 'errorAddItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                        case 'errorEditItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                    }
                    $this->viewFoodItem();
                    break;
                case 'cleaningItem':
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    $error = isset($_GET['error']) ? $_GET['error'] : '';
                    switch ($status) {
                        case 'successRemoveInv':
                            echo "<p class='alert alert-success'>Inventory removed successfully.</p>";
                            break;
                        case 'errorRemoveInv':
                            echo "<p class='alert alert-error'>Failed to remove Inventory. Please try again.</p>";
                            break;
                        case 'errorAddItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                        case 'errorEditItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                    }

                    $this->viewCleaningItem();
                    break;
                case 'viewItembasedOnInventoryID':
                    $inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : null;
                    $itemType = isset($_GET['itemType']) ? $_GET['itemType'] : null;
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    $error = isset($_GET['error']) ? $_GET['error'] : '';
                    switch ($status) {
                        case 'success':
                            echo "<p class='alert alert-success'>New brand added successfully.</p>";
                            break;
                        case 'error':
                            echo "<p class='alert alert-error'>Failed to add new brand. Please try again.</p>";
                            break;
                        case 'successEdit':
                            echo "<p class='alert alert-success'>Brand details edited successfully.</p>";
                            break;
                        case 'errorEdit':
                            echo "<p class='alert alert-error'>Failed to edit brand details. Please try again.</p>";
                            break;
                        case 'successRemove':
                            echo "<p class='alert alert-success'>Item removed successfully.</p>";
                            break;
                        case 'errorRemove':
                            echo "<p class='alert alert-error'>Failed to remove item. Please try again.</p>";
                            break;
                        case 'errorAddItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                        case 'errorEditItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                    }
                    $this->viewItembasedOnInventoryID($inventoryId, $itemType);
                    break;
                case 'viewSpecificDetails':
                    $inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : null;
                    $itemType = isset($_GET['itemType']) ? $_GET['itemType'] : null;
                    $itemID = isset($_GET['itemID']) ? $_GET['itemID'] : null;
                    $this->viewSpecific($inventoryId, $itemType, $itemID);
                    break;
                case 'createPO':
                    $inventoryId = isset($_GET['inventoryId']) ? $_GET['inventoryId'] : null;
                    $itemType = isset($_GET['itemType']) ? $_GET['itemType'] : null;
                    $itemID = isset($_GET['itemID']) ? $_GET['itemID'] : null;
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    switch ($status) {
                        case 'POCreationFailed':
                            echo "<p class='alert alert-error'>One or more fields have invalid values. Please review and try again.</p>";
                            echo "<p class='alert alert-warning'>
                                <strong>Tips:</strong><br/>
                                <ul style='margin: 0; padding-left: 20px;'>
                                    <li><strong>Shipping Address & Billing Address:</strong> Ensure both addresses are complete and valid. (Min. 5 characters, max. 100 characters; alphanumeric with spaces, commas, periods, and hyphens allowed.)</li>
                                    <li><strong>Select a Supplier:</strong> Don’t forget to choose a supplier from the list.</li>
                                    <li><strong>Preferred Shipping Date:</strong> The shipping date must be at least 3 days from today.</li>
                                    <li><strong>Preferred Shipping Time:</strong> Choose a time between 9:00 AM and 5:00 PM.</li>
                                </ul>
                            </p>";
                            break;
                    }
                    $this->createPO($inventoryId, $itemType, $itemID);
                    break;
                case 'sendPO':
                    $POid = isset($_GET['POid']) ? $_GET['POid'] : null;
                    $this->sendPO($POid);
                    break;
                case 'reportMain':
                    $this->reportMain();
                    break;
                case 'logusage':
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    $newQuantity = isset($_GET['newQuantity']) ? $_GET['newQuantity'] : '';
                    switch ($status) {
                        case 'successPO':
                            echo "<p class='alert alert-success'>Inventory usage logged successfully. New available quantity: " . $newQuantity . "</p>";
                            echo "<p class='alert alert-notification'>Out of stock !! A new Purchase Order is automatic generated. </p>";

                            break;
                        case 'errorPO':
                            echo "<p class='alert alert-success'>Inventory usage logged successfully. New available quantity: " . $newQuantity . "</p>";
                            echo "<p class='alert alert-error'>Out of stock !! Falied to automatic generate new Purchase Order.</p>";
                            echo "<p class='alert alert-error'>No purchase order is sent to supplier, please create it manually. </p>";
                            break;
                        case 'successWeb':
                            echo "<p class='alert alert-success'>Inventory usage logged successfully. New available quantity: " . $newQuantity . "</p>";
                            echo "<p class='alert alert-notification'>Out of stock !! A new Purchase Order is automatic generated. </p>";
                            echo "<p class='alert alert-notification'>The new purchase order is sent to supplier for processing. </p>";
                            break;
                        case 'errorWeb':
                            echo "<p class='alert alert-success'>Inventory usage logged successfully. New available quantity: " . $newQuantity . "</p>";
                            echo "<p class='alert alert-notification'>Out of stock !! A new Purchase Order is automatic generated. </p>";
                            echo "<p class='alert alert-error'>No purchase order is sent to supplier, please send it manually. </p>";
                            break;
                        case 'success':
                            echo "<p class='alert alert-success'>Inventory usage logged successfully. New available quantity: " . $newQuantity . "</p>";
                            break;
                        case 'error':
                            echo "<p class='alert alert-error'>Error logging inventory usage.</p>";
                            break;
                        case 'itemNotfound':
                            echo "<p class='alert alert-error'>Error: Inventory item not found.</p>";
                            break;
                        case 'invalidRequest':
                            echo "<p class='alert alert-warning'>Invalid request method.</p>";
                            break;
                    }
                    $this->logUsage();
                    break;
                case 'showPO':
                    $status = isset($_GET['status']) ? $_GET['status'] : '';

                    switch ($status) {
                        case 'updateSuccess':
                            echo "<p class='alert alert-success'>Purchase order status updated successfully.</p>";
                            break;
                        case 'updateError':
                            echo "<p class='alert alert-error'>Failed to update purchase order status.</p>";
                            break;
                        case 'successPO':
                            echo "<p class='alert alert-success'>Purchase order created successfully.</p>";
                            break;
                        case 'errorPO':
                            echo "<p class='alert alert-error'>Failed to create new purchase order.</p>";
                            break;
                        case 'success':
                            echo "<p class='alert alert-success'>Purchase order deleted successfully.</p>";
                            break;
                        case 'error':
                            echo "<p class='alert alert-error'>Failed to delete purchase order.</p>";
                            break;
                        case 'invalidPOid':
                            echo "<p class='alert alert-warning'>Invalid Purchase Order ID.</p>";
                            break;
                        case 'invalidRequest':
                            echo "<p class='alert alert-warning'>Invalid request method.</p>";
                            break;
                        default:
                            break;
                    }
                    $this->showPO();
                    break;
                case 'generateReport':
                    $report = isset($_GET['report']) ? $_GET['report'] : '';

                    switch ($report) {
                        case 'inventorySummaryReport':
                            $this->InventorySummary();
                            break;
                        case 'cleaninginventorySummaryReport':
                            $this->cleaningInventorySummary();
                            break;
                        case 'habitatinventorySummaryReport':
                            $this->habitatInventorySummary();
                            break;
                        case 'foodinventorySummaryReport':
                            $this->foodInventorySummary();
                            break;
                        case 'outStockinventorySummaryReport':
                            $this->outStockInventorySummary();
                            break;
                        case 'inStockinventorySummaryReport':
                            $this->inStockInventorySummary();
                            break;
                        case 'lowStockinventorySummaryReport':
                            $this->lowStockInventorySummary();
                            break;
                        case 'cleaninginventoryRecordReport':
                            $this->cleaninginventoryRecordReport();
                            break;
                        case 'foodinventoryRecordReport':
                            $this->foodinventoryRecordReport();
                            break;
                        case 'habitatinventoryRecordReport':
                            $this->habitatinventoryRecordReport();
                            break;
                        case 'poSummaryReport':
                            $this->poSummaryReport();
                            break;
                    }


                    break;
                case 'inventoryTracking':
                    $status = isset($_GET['status']) ? $_GET['status'] : '';
                    $error = isset($_GET['error']) ? $_GET['error'] : '';
                    switch ($status) {
                        case 'successRemoveInv':
                            echo "<p class='alert alert-success'>Inventory removed successfully.</p>";
                            break;
                        case 'errorRemoveInv':
                            echo "<p class='alert alert-error'>Failed to remove Inventory. Please try again.</p>";
                            break;
                        case 'success':
                            echo "<p class='alert alert-success'>Item added successfully.</p>";
                            break;
                        case 'error':
                            echo "<p class='alert alert-error'>Failed to add item. Please try again.</p>";
                            break;
                        case 'invalidRequest':
                            echo "<p class='alert alert-warning'>Invalid request method.</p>";
                            break;
                        case 'successEdit':
                            echo "<p class='alert alert-success'>Inventory details edited successfully.</p>";
                            break;
                        case 'errorEdit':
                            echo "<p class='alert alert-error'>Failed to edit inventory details. Please try again.</p>";
                            break;
                        case 'errorAddItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                        case 'errorEditItem':
                            echo "<p class='alert alert-error'>" . $error . "</p>";
                            break;
                        default:
                            break;
                    }
                    $this->inventory();
                    break;
                case 'index':
                default:
                    $this->index();
                    break;
            }
        } else {
            header("Location: /ZooManagementSystem/index.php?controller=$controller&action=$action");
        }
    }

    public function index() {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\cleaninginventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\foodinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\habitatinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\/purchaseorderlineitem.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\supplier.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventoryusagelog.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\itemimage.xml'
        ];
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\inventorySystemMain.xsl';
        $data = [
            'activePage' => 'Dashboard',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/inventory.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function inventory() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/mainInventoryTracking.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

// Method to handle the Add Inventory Item page
    public function addInventoryItem() {
        $data = [
            'activePage' => 'Add Inventory Item',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/addInventoryItem.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => false
        ];

        $this->view->render('AddNewInventItem', $data);
    }

    public function reportMain() {
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/reportMain.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => false,
        ];

        $this->view->render('reportMain', $data);
    }

    public function logUsage() {
        $inventoryData = $this->model->getInventory();
        $data = [
            'activePage' => 'Log Usage',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/InventoryUsage.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => false,
            'inventoryData' => $inventoryData
        ];

        $this->view->render('InventoryUsage', $data);
    }

    public function viewHabitatItem() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\HabitatInventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/mainInventoryTracking.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function viewFoodItem() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\FoodInventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/mainInventoryTracking.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function viewCleaningItem() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\CleaningInventoryCatalog.xsl';
        $data = [
            'activePage' => 'Inventory Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/mainInventoryTracking.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function viewSpecific($inventoryId, $itemType, $itemID) {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\cleaninginventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\foodinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\habitatinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorderlineitem.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\itemImage.xml'
        ];

        switch ($itemType) {
            case 'Food':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\FoodInventoryItemDetails.xsl';
                break;
            case 'Habitat':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\HabitatInventoryItemDetails.xsl';
                break;
            case 'Cleaning':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\CleaningInventoryItemDetails.xsl';
                break;
            // add more cases for other item types
            default:
                throw new Exception("Unknown itemType: $itemType");
        }

        $data = [
            'activePage' => 'Inventory Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/InventoryItemDetails.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true,
            'inventoryID' => $inventoryId,
            'itemID' => $itemID,
            'imageDirectory' => '/ZooManagementSystem/assests/InventoryImages/'
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function viewItembasedOnInventoryID($inventoryId, $itemType) {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\cleaninginventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\foodinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\habitatinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorderlineitem.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml'
        ];

        switch ($itemType) {
            case 'Food':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDFooditem.xsl';
                break;
            case 'Habitat':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDHabitatItem.xsl';
                break;
            case 'Cleaning':
                $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\InventoryIDCleaningItem.xsl';
                break;
            // add more cases for other item types
            default:
                throw new Exception("Unknown itemType: $itemType");
        }

        $data = [
            'activePage' => 'Inventory Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/mainInventoryTracking.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'inventoryID' => $inventoryId,
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function createPO($inventoryId, $itemType, $itemID) {
        $POid = $this->model->getLatestPOID() + 1;
        $itemName = $this->model->getItemNameById($itemID, $itemType);
        $Allprice = $this->model->getSupplyUnitPrice($itemID, $itemType);
        $suppliersID = $this->model->getSupplierIdBasedOnItemId($itemID, $itemType);
        $imagePath = $this->model->getImageByid($itemID, $itemType);

        foreach ($suppliersID as $supplierId) {
            $details = $this->model->getSupplierDetailsById($supplierId);
            if ($details) {
                $supplierDetails[$supplierId] = $details; // Store details with supplierId as key
            }
        }
        $int = 0;
        foreach ($Allprice as $oneRecord) {

            if ($oneRecord) {
                $price[$suppliersID[$int]] = $oneRecord; // Store details with supplierId as key
                $int++;
            }
        }
        $inventoryDetails = [
            'inventoryId' => $inventoryId,
            'itemType' => $itemType,
            'itemID' => $itemID,
        ];

        $data = [
            'InventoryDetails' => $inventoryDetails,
            'POid' => $POid,
            'itemName' => $itemName,
            'itemID' => $itemID,
            'price' => $price,
            'supplierDetails' => $supplierDetails,
            'image' => "/ZooManagementSystem/assests/InventoryImages/" . $imagePath,
            'activePage' => 'Inventory Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/purchaseorder.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => false
        ];

        $this->view->render('PurchaseOrder', $data);
    }

    public function sendPO($POid) {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\cleaninginventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\foodinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\habitatinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorderlineitem.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\supplier.xml'
        ];
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\processPO.xsl';
        $data = [
            'activePage' => 'Purchase Order Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/processPO.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'POid' => $POid,
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function showPO() {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\supplier.xml'
        ];
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\showAllPO.xsl';
        $data = [
            'activePage' => 'Purchase Order Management',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/mainInventoryTracking.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function InventorySummary() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function cleaningInventorySummary() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportcleaningInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function habitatInventorySummary() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReporthabitatInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function foodInventorySummary() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportfoodInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function outStockInventorySummary() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportoutStockInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function inStockInventorySummary() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportInStockInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function lowStockInventorySummary() {
        $xmlFile = 'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml';
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportLowStockInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFile, $xslFile, $data);
        echo $output;
    }

    public function cleaninginventoryRecordReport() {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\cleaninginventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\foodinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\habitatinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorderlineitem.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\supplier.xml'
        ];
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportRecordcleaningInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function foodinventoryRecordReport() {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\cleaninginventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\foodinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\habitatinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorderlineitem.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\supplier.xml'
        ];
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportRecordfoodInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function habitatinventoryRecordReport() {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\cleaninginventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\foodinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\habitatinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorderlineitem.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\supplier.xml'
        ];
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportRecordhabitatInventorySummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }

    public function poSummaryReport() {
        $xmlFiles = [
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\cleaninginventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\foodinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\habitatinventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorder.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\purchaseorderlineitem.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\inventory.xml',
            'C:\xampp\htdocs\ZooManagementSystem\Model\Xml\supplier.xml'
        ];
        $xslFile = 'C:\xampp\htdocs\ZooManagementSystem\View\InventoryView\ReportPOsummary.xsl';
        $data = [
            'activePage' => 'Reports',
            'cssFiles' => [
                '/ZooManagementSystem/Css/Inventory/ReportdisplayingTable.css',
                '/ZooManagementSystem/Css/Inventory/InventoryMasterPage.css',
                '/ZooManagementSystem/Css/Inventory/displayingTable.css'
            ],
            'xslt_transform' => true
        ];

        $output = $this->view->renderXML($xmlFiles, $xslFile, $data);
        echo $output;
    }
}
