<?php
$movement_id = isset($_GET['movement_id']) ? intval($_GET['movement_id']) : 1;
$api_url = "http://localhost/ranking-endpoint-rest-php/api.php";

// Configuração do payload para enviar à API
$data = json_encode(["movement_id" => $movement_id]);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
curl_close($ch);

// Decodifica a resposta JSON
$ranking = json_decode($response, true);

// Verifica se a API retornou dados válidos
$movement_name = 'Movimento ' . $ranking['movement'] ?? ' Desconhecido';
$records = $ranking['ranking'] ?? [];

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking de Movimentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h2 class="text-center">Ranking - <?php echo htmlspecialchars($movement_name); ?></h2>

    <form method="GET" class="my-4">
        <label for="movement_id" class="form-label">Escolha um Movimento:</label>
        <select name="movement_id" id="movement_id" class="form-select" onchange="this.form.submit()">
            <option value="1" <?php echo $movement_id == 1 ? 'selected' : ''; ?>>Deadlift</option>
            <option value="2" <?php echo $movement_id == 2 ? 'selected' : ''; ?>>Back Squat</option>
            <option value="3" <?php echo $movement_id == 3 ? 'selected' : ''; ?>>Bench Press</option>
        </select>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Movimento</th>
                <th>Posição</th>
                <th>Usuário</th>
                <th>Recorde Pessoal</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($records)) : ?>
            <?php foreach ($records as $record): ?>
                <tr>
                    <td><?= $record['movement_name'] ?></td>
                    <td><?= $record['position'] ?></td>
                    <td><?= htmlspecialchars($record['user']) ?></td>
                    <td><?= $record['best_record'] ?> kg</td>
                    <td><?= date('d/m/Y', strtotime($record['date'])) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4" class="text-center">Nenhum recorde encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>