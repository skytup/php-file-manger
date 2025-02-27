<?php
// backend.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include_once($_SERVER['DOCUMENT_ROOT'] . '/skytup/database/db_connect.php');

class DatabaseHandler {
    private $connection ;
    private $queryLog = [];
    
    public function __construct($con) {
        // $this->connect();
        $this->connection = $con;
    }
    
    // private function connect() {
    //     try {
    //         $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
    //         if ($this->connection->connect_error) {
    //             throw new Exception("Connection failed: " . $this->connection->connect_error);
    //         }
            
    //         $this->connection->set_charset("utf8mb4");
    //     } catch (Exception $e) {
    //         $this->sendResponse(false, null, $e->getMessage());
    //     }
    // }
    
    public function executeQuery($query) {
        try {
            // Trim and clean the query
            $query = trim($query);
            
            // Check if it's a SHOW DATABASES query
            if (strtoupper($query) === 'SHOW DATABASES') {
                $result = $this->connection->query($query);
                $databases = [];
                while ($row = $result->fetch_assoc()) {
                    $databases[] = $row;
                }
                $this->sendResponse(true, $databases);
                return;
            }
            
            // Basic SQL injection prevention
            // if (!preg_match('/^SELECT|^SHOW/i', $query)) {
            //     throw new Exception("Only SELECT and SHOW queries are permitted");
            // }
            
            $result = $this->connection->query($query);
            
            if (!$result) {
                throw new Exception($this->connection->error);
            }
            
            $data = [];
            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $result->free();
                
                // Log successful query
                $this->logQuery($query, true);
                
                $this->sendResponse(true, $data);
            } else {
                throw new Exception("Query did not return any results");
            }
            
        } catch (Exception $e) {
            // Log failed query
            $this->logQuery($query, false, $e->getMessage());
            $this->sendResponse(false, null, $e->getMessage());
        }
    }
    
    private function logQuery($query, $success, $error = '') {
        $this->queryLog[] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'query' => $query,
            'success' => $success,
            'error' => $error
        ];
    }
    
    private function sendResponse($success, $data = null, $error = null) {
        echo json_encode([
            'success' => $success,
            'data' => $data,
            'error' => $error,
            'timestamp' => date('Y-m-d H:i:s')
        ], JSON_PRETTY_PRINT);
    }
    
    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}

// Handle incoming requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new DatabaseHandler($con);
    
    if (isset($_POST['query'])) {
        $db->executeQuery($_POST['query']);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'No query provided',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}