<?php
require_once "authenticate.php";

header('Content-Type: application/json; charset=utf-8');

$file = $_POST["file"];
$data = $_POST["data"];

switch ($file) {
	case "notesTxt":
		if (file_put_contents("notes.txt", $data)) {
			echo json_encode(["success" => true]);
		} else {
			echo json_encode(["success" => false, "error" => "Could not write to file."]);
		}
		break;
	case "notesHtml":
		if (file_put_contents("notes.html", $data)) {
			echo json_encode(["success" => true]);
		} else {
			echo json_encode(["success" => false, "error" => "Could not write to file."]);
		}
		break;
	default:
		echo json_encode(["success" => false, "error" => "Unknown file."]);
		return;
}
