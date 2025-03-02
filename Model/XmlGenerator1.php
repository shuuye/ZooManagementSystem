<?php

require_once __DIR__ . '/../Config/databaseConfig.php';

class XmlGenerator extends databaseConfig {
    
    private function fetchData($tableName, $db) {
        if (empty($tableName)) {
            throw new Exception("Table name cannot be empty.");
        }

        // Prepare the SQL query
        $sql = "SELECT * FROM " . $tableName;
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new Exception("Statement preparation failed: " . implode(" ", $db->errorInfo()));
        }

        // Execute the query
        $stmt->execute();

        return $stmt;
    }

    private function setXMLElements($xml, $dataOfTable, $rootElementName, $elementsName, $attributeForFirstElement) {
        // Create root element
        $root = $xml->createElement($rootElementName);
        $xml->appendChild($root);
        
        // Fetching data and adding to XML
        while ($row = $dataOfTable->fetch(PDO::FETCH_ASSOC)) {
            // Create a new element within the root
            $elementInRoot = $xml->createElement($elementsName);

            // Get the first column key
            $firstColumnKey = key($row);

            // Check if the first column is same as the attribute passed in
            if ($firstColumnKey === $attributeForFirstElement) {
                //set the attribute to the element
                $elementInRoot->setAttribute($attributeForFirstElement, htmlspecialchars($row[$firstColumnKey]));
                unset($row[$firstColumnKey]); // Remove the attribute from the row to avoid duplication as child element
            }
            
            foreach ($row as $column => $value) {
                // Create child elements for each column
                $element = $xml->createElement($column, htmlspecialchars($value));
                $elementInRoot->appendChild($element);
            }

            $root->appendChild($elementInRoot);
        }

        return $xml;
    }

    private function saveXmlFile($xml, $outputFileName){
        // Define the directory path for the XML files
        $directory = __DIR__ . "/../Model/Xml";
        
        // Check if the directory exists
        if (!is_dir($directory)) {
            // Create the directory if it doesn't exist
            mkdir($directory, 0755, true);
        }

        // Save the XML file in the directory
        $filePath = $directory . "/" . $outputFileName;
        $xml->save($filePath);       
    }

    private function generateXMLDataFromTable($tableName, $outputFileName, $db, $rootElementName, $elementsName, $attributeForFirstElement){
        try {
            $dataOfTable = $this->fetchData($tableName, $db);

            if ($dataOfTable->rowCount() > 0) {
                // Start the XML document
                $xml = new DOMDocument('1.0', 'UTF-8');
                $xml->formatOutput = true;

                //set the value in the elements
                $xml = $this->setXMLElements($xml, $dataOfTable, $rootElementName, $elementsName, $attributeForFirstElement);

                $this->saveXmlFile($xml, $outputFileName);
                
            } else {
                echo "0 results found in the table.";
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }         
        
    }

    public function createXMLFileByTableName($tableName = "",  $outputFileName = "", $rootElementName = "", $elementsName = "", $attributeForFirstElement = ""){
        //connect to database
        $db = $this->getConnection();
        
        if(!empty($tableName) && !empty($outputFileName)){
            //generate the XML data from the data in the database by using the table name
            $this->generateXMLDataFromTable($tableName, $outputFileName, $db, $rootElementName, $elementsName, $attributeForFirstElement);
        }else{
            echo "tableName or outputFileName cannot be Empty";
        }
        
    }

    public function displayXMLData($xmlFileName) {
        if (file_exists($xmlFileName)) {
            $xml = new DOMDocument();
            $xml->load($xmlFileName);
            $xml->preserveWhiteSpace = false;
            $xml->formatOutput = true;

            echo "<pre>" . htmlspecialchars($xml->saveXML()) . "</pre>";
        } else {
            echo "The file $xmlFileName does not exist.";
        }
    }

}
?>
