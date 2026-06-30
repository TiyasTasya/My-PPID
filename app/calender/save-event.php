<?php
// C:\xampp\htdocs\my-ppid\app\calender\save-event.php
header('Content-Type: application/json');

if (!isset($_SESSION)) { session_start(); }
require_once "../../conf/auth.php";
require_once "../../conf/koneksi.php";

/** @var mysqli $koneksi */
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(array('status' => 'error', 'message' => 'No data received'));
    exit;
}

$action     = isset($input['action']) ? $input['action'] : '';
$title      = isset($input['title']) ? mysqli_real_escape_string($koneksi, $input['title']) : '';
$start_date = isset($input['start_date']) ? mysqli_real_escape_string($koneksi, $input['start_date']) : '';
$end_date   = isset($input['end_date']) ? mysqli_real_escape_string($koneksi, $input['end_date']) : NULL;
$color      = isset($input['color']) ? mysqli_real_escape_string($koneksi, $input['color']) : '#0d6efd';
$id         = isset($input['id']) ? (int)$input['id'] : 0;

if ($action === 'insert') {
    $sql = "INSERT INTO calendar_events (title, start_date, end_date, color, is_all_day) VALUES ('$title', '$start_date', '$end_date', '$color', 1)";
    if (mysqli_query($koneksi, $sql)) {
        echo json_encode(array('status' => 'success', 'id' => mysqli_insert_id($koneksi)));
    } else {
        echo json_encode(array('status' => 'error'));
    }
} 
// 1. UPDATE KARENA DIGESER TANGGALNYA
elseif ($action === 'update_date' && $id > 0) {
    $sql = "UPDATE calendar_events SET start_date = '$start_date', end_date = " . ($end_date ? "'$end_date'" : "NULL") . " WHERE id = $id";
    if (mysqli_query($koneksi, $sql)) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error'));
    }
} 
// 2. UPDATE KARENA DIUBAH JUDULNYA
elseif ($action === 'update_title' && $id > 0) {
    $sql = "UPDATE calendar_events SET title = '$title' WHERE id = $id";
    if (mysqli_query($koneksi, $sql)) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error'));
    }
} 
// 3. HAPUS AGENDA
elseif ($action === 'delete' && $id > 0) {
    $sql = "DELETE FROM calendar_events WHERE id = $id";
    if (mysqli_query($koneksi, $sql)) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid action'));
}