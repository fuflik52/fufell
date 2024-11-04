<?php
// Разрешаем доступ только с определенного домена (для предотвращения CORS-проблем)
header("Access-Control-Allow-Origin: https://fuflik52.github.io");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Определяем путь к файлу для хранения данных рейтинга
$file_path = 'leaderboard.txt';

// Проверяем, был ли получен POST-запрос с данными игрока
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем JSON-данные из POST-запроса
    $input = json_decode(file_get_contents('php://input'), true);

    // Проверяем, есть ли нужные данные
    if (isset($input['username']) && isset($input['score'])) {
        $username = htmlspecialchars($input['username']); // Экранируем данные для безопасности
        $score = (int)$input['score']; // Убедимся, что очки - это целое число

        // Считываем текущий рейтинг из файла
        $leaderboard = [];
        if (file_exists($file_path)) {
            $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                list($name, $points) = explode(":", $line);
                $leaderboard[$name] = (int)$points;
            }
        }

        // Обновляем очки игрока или добавляем его в рейтинг
        if (isset($leaderboard[$username])) {
            // Если у игрока уже есть запись, обновляем, если новый результат выше
            if ($leaderboard[$username] < $score) {
                $leaderboard[$username] = $score;
            }
        } else {
            // Если игрока еще нет в рейтинге, добавляем
            $leaderboard[$username] = $score;
        }

        // Сортируем рейтинг по убыванию очков
        arsort($leaderboard);

        // Сохраняем обновленный рейтинг в файл
        $file_content = '';
        foreach ($leaderboard as $name => $points) {
            $file_content .= "$name:$points\n";
        }
        file_put_contents($file_path, $file_content);
    }
}

// Отправляем данные рейтинга в формате JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $leaderboard = [];

    // Считываем данные из файла рейтинга
    if (file_exists($file_path)) {
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($name, $points) = explode(":", $line);
            $leaderboard[] = [
                'username' => $name,
                'score' => (int)$points
            ];
        }
    }

    // Возвращаем JSON-ответ
    header('Content-Type: application/json');
    echo json_encode($leaderboard);
}
?>
