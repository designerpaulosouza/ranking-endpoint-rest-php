<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");


$pdo = new PDO("mysql:host=localhost;dbname=ranking_db;charset=utf8", "root", "");


// Verifica se a requisição foi feita via POST ou GET
$data = json_decode(file_get_contents("php://input"), true);
$movement_id = isset($data['movement_id']) ? intval($data['movement_id']) : (isset($_GET['movement_id']) ? intval($_GET['movement_id']) : null);

if (!$movement_id) {
    echo json_encode(["error" => "movement_id é obrigatório"]);
    exit;
}

$query = "SELECT 
            m.name AS movement,
            u.name AS user,
            MAX(pr.value) AS best_record,
            pr.date
        FROM personal_record pr
            JOIN user u ON pr.user_id = u.id
            JOIN movement m ON pr.movement_id = m.id
        WHERE pr.movement_id = :movement_id
        GROUP BY u.id
        ORDER BY best_record DESC, pr.date ASC
    ";

$stmt = $pdo->prepare($query);
$stmt->bindParam(":movement_id", $movement_id, PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ranking = [];
$position = 1;
$last_value = null;
$shared_position = 1;

foreach ($records as $key => $record) {
    if ($last_value !== null && $record['best_record'] < $last_value) {
        $position = $shared_position;
    }
    $shared_position++;

    $ranking[] = [
        "movement_name" => $record['movement'],
        "position" => $position,
        "user" => $record['user'],
        "best_record" => $record['best_record'],
        "date" => $record['date']
    ];

    $last_value = $record['best_record'];
}

echo json_encode([
    "movement" => $records[0]['movement'] ?? "Movimento não encontrado",
    "ranking" => $ranking
]);
?>
