<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Путь к файлу рейтинга
$leaderboardFile = 'leaderboard.txt';

// Функция для получения рейтинга
function getLeaderboard($file) {
    if (!file_exists($file)) {
        return [];
    }
    $data = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $leaderboard = [];
    foreach ($data as $line) {
        list($username, $score) = explode(',', $line);
        $leaderboard[] = ['username' => trim($username), 'score' => intval(trim($score))];
    }
    // Сортировка по убыванию очков
    usort($leaderboard, function($a, $b) {
        return $b['score'] - $a['score'];
    });
    return $leaderboard;
}

// Функция для добавления или обновления пользователя
function updateLeaderboard($file, $username, $score) {
    $leaderboard = getLeaderboard($file);
    $found = false;
    for ($i = 0; $i < count($leaderboard); $i++) {
        if (strcasecmp($leaderboard[$i]['username'], $username) == 0) {
            if ($score > $leaderboard[$i]['score']) {
                $leaderboard[$i]['score'] = $score;
            }
            $found = true;
            break;
        }
    }
    if (!$found) {
        $leaderboard[] = ['username' => $username, 'score' => $score];
    }
    // Сортировка по убыванию очков после обновления
    usort($leaderboard, function($a, $b) {
        return $b['score'] - $a['score'];
    });
    // Сохранение рейтинга обратно в файл
    $fileHandle = fopen($file, 'w');
    if ($fileHandle) {
        foreach ($leaderboard as $player) {
            fwrite($fileHandle, $player['username'] . ',' . $player['score'] . PHP_EOL);
        }
        fclose($fileHandle);
        return true;
    } else {
        return false;
    }
}

// Обработка запроса
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Получение данных из POST-запроса
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;

    if ($username === '' || $score <= 0) {
        echo json_encode(['success' => false, 'message' => 'Неверные данные.']);
        exit;
    }

    // Обновление рейтинга
    $updated = updateLeaderboard($leaderboardFile, $username, $score);
    if ($updated) {
        echo json_encode(['success' => true, 'message' => 'Рейтинг обновлён.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Не удалось обновить рейтинг.']);
    }

} elseif ($method === 'GET') {
    // Получение рейтинга
    $leaderboard = getLeaderboard($leaderboardFile);
    echo json_encode(['success' => true, 'leaderboard' => $leaderboard]);
} else {
    // Недопустимый метод запроса
    echo json_encode(['success' => false, 'message' => 'Недопустимый метод запроса.']);
}
?>
