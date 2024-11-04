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
       
