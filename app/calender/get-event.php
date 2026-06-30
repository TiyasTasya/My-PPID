<?php
// C:\xampp\htdocs\my-ppid\app\calender\get-event.php
header('Content-Type: application/json');

if (!isset($_SESSION)) { session_start(); }
require_once "../../conf/auth.php";
require_once "../../conf/koneksi.php";

/** @var mysqli $koneksi */
$query = mysqli_query($koneksi, "SELECT * FROM calendar_events");
$events = array();

if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $events[] = array(
            'id'    => $row['id'],
            'title' => $row['title'],
            'start' => $row['start_date'],
            'end'   => !empty($row['end_date']) ? $row['end_date'] : null,
            'color' => !empty($row['color']) ? $row['color'] : '#0d6efd',
            'allDay'=> (int)$row['is_all_day'] === 1 ? true : false
        );
    }
}
echo json_encode($events);