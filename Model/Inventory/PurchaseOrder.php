<?php

include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\InventoryModel.php';

class PurchaseOrder extends InventoryModel {

    private $poId;
    private $supplierId;
    private $orderDate;
    private $deliveryDate;
    private $billingAddress;
    private $shippingAddress;
    private $totalAmount;
    private $status;
    private $lineItems = []; // Array of PurchaseOrderLineItem objects

    public function __construct(
            $supplierId = null,
            $orderDate = null,
            $deliveryDate = null,
            $billingAddress = null,
            $shippingAddress = null,
            $totalAmount = null,
            $status = null
    ) {
        $this->supplierId = $supplierId;
        $this->orderDate = $orderDate;
        $this->deliveryDate = $deliveryDate;
        $this->totalAmount = $totalAmount;
        $this->status = $status;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
    }

    public function addLineItem($poId, $inventoryId, $quantity, $unitPrice, $cleaningId = null, $habitatId = null, $foodId = null) {
        $lineItem = new PurchaseOrderLineItem($poId, $inventoryId, $cleaningId, $habitatId, $foodId, $quantity, $unitPrice);
        $this->lineItems[] = $lineItem;

        return $lineItem;
    }

    public function addNewPO() {
        $this->poId = $this->addPOIntoDB($this->supplierId, $this->orderDate, $this->deliveryDate, $this->billingAddress, $this->shippingAddress, $this->totalAmount, $this->status);

        //initialize id by getting it from database
        echo '<script>alert("New PO added with ID: ' . $this->poId . '");</script>';

        return $this->poId;
    }

    public function deletePurchaseOrder($poId) {
        $success = $this->removePOfromDB($poId);
        return $success;
    }
    
    public function  updatePurchaseOrder($poId,$status) {
        $success = $this->updatePOStatusDB($poId, $status);
        return $success;
    }
    
    
    public function updateInventoryQuantity($poId){
        $success = $this->updateInventoryQuantityDB($poId);
        return $success;
    }
   

//    public function showPO($email) {
//        require_once 'InventoryModel.php';
//        $inventoryModel = new InventoryModel();
//        $POdetails = $inventoryModel->getPODetails($email);
//
//        // Check if the data is an array and not empty
//        if (is_array($POdetails) && !empty($POdetails)) {
//            // You can pass the whole data to the view
//            $data = $POdetails;
//        } else {
//            $data = "Data not found";
//        }
//
//        // Pass the data to the View
//        require '../../View/InventoryView/fetchPOview.php';
//    }
}
