<?php
// Enable CORS and JSON response
header("Access-Control-Allow-Origin: *"); // you can replace * with a specific domain.
header("Access-Control-Allow-Methods: GET, POST, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include db.php for PDO connection
require "db.php";

// Function for consistent JSON response
function send_json($data, $code = 200){
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// Preflight request (OPTIONS) — browser sends this before POST/PATCH/DELETE
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // no content
    exit;
}

// Get request method
$method = $_SERVER["REQUEST_METHOD"];

// ========================================
// Task-related functions
// ========================================

// Return all tasks
function getAllTasks() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        send_json(["error" => "Failed to fetch tasks", "details" => $e->getMessage()], 500);
    }
}

// Add a new task
function addTask($title, $description){
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, done) VALUES (:title, :description, 0)");
        $stmt->execute(['title' => $title, 'description' => $description]);
        return getAllTasks(); // return updated list
    } catch (PDOException $e) {
        send_json(["error" => "Failed to add task", "details" => $e->getMessage()], 500);
    }
}

// Delete a task by id
function deleteTask($id){
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return getAllTasks(); // return updated list
    } catch (PDOException $e) {
        send_json(["error" => "Failed to delete task", "details" => $e->getMessage()], 500);
    }
}

// Mark task as done
function makeTaskDone($id){
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE tasks SET done = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return getAllTasks(); // return updated list
    } catch (PDOException $e) {
        send_json(["error" => "Failed to update task", "details" => $e->getMessage()], 500);
    }
}

// ========================================
// Main logic: request handling
// ========================================

try {
    if ($method === 'GET') {
        send_json(getAllTasks());
    }

    $input = json_decode(file_get_contents("php://input"), true);

    if ($method === 'POST') {
        if (empty($input['title']) || empty($input['description'])) {
            send_json(["error" => "Title and description are required"], 400);
        }
        send_json(addTask($input['title'], $input['description']));
    }

    elseif ($method === 'DELETE') {
        if (empty($input['id'])) {
            send_json(["error" => "Task id required"], 400);
        }
        send_json(deleteTask($input['id']));
    }

    elseif ($method === 'PATCH') {
        if (empty($input['id'])) {
            send_json(["error" => "Task id required"], 400);
        }
        send_json(makeTaskDone($input['id']));
    }

    else {
        send_json(["error" => "Method not allowed"], 405);
    }

} catch (Exception $e) {
    send_json(["error" => "Unexpected error", "details" => $e->getMessage()], 500);
}

?>