<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <base href="https://script.google.com/macros/library/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Моя Игра</title>
    <style>
        /* Сброс стилей и базовая настройка */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
            -webkit-user-select: none;
        }
        
        /* Общие стили для тела страницы */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center; /* Центрирование по вертикали */
            min-height: 100vh;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d); /* Градиент */
            background-size: cover;
            touch-action: manipulation;
            font-family: 'Creepster', cursive; /* Шрифт в стиле Хэллоуина */
        }
        
        /* Подключение шрифта Creepster */
        @import url('https://fonts.googleapis.com/css2?family=Creepster&display=swap');
        
        /* Заставка игры */
        #splashScreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: #ffcc00;
            z-index: 3000;
            font-size: 24px;
            padding: 20px;
            text-align: center;
        }
        
        #splashScreen h1 {
            font-size: 48px;
            margin-bottom: 20px;
            animation: fadeInText 2s forwards;
        }
        
        #splashScreen input {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            margin-bottom: 20px;
            font-size: 18px;
            outline: none;
            width: 80%;
            max-width: 300px;
        }
        
        #splashScreen button {
            padding: 15px 30px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(45deg, #ff7518, #ff6600);
            color: white;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            transition: transform 0.2s, background 0.3s;
        }
        
        #splashScreen button:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #ff4500, #ff3300);
        }
        
        @keyframes fadeInText {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Контейнер игры */
        #gameContainer {
            width: 100%; /* Полноэкранный режим */
            max-width: 100vw;
            height: 100vh;
            background: rgba(255, 255, 255, 0.1); /* Прозрачный фон для лучшего видения реального фона */
            border-radius: 0; /* Убираем скругление для полного экрана */
            position: relative;
            overflow: hidden;
            box-shadow: none; /* Убираем тень для полного экрана */
            backdrop-filter: blur(10px);
            perspective: 1000px;
            transition: background 0.5s ease;
        }
        
        /* Элемент очков */
        #points {
            position: absolute;
            top: 10px; /* Отступ сверху */
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.7);
            padding: 10px 20px;
            border-radius: 15px;
            color: #ffcc00;
            font-size: 24px;
            font-weight: bold;
            z-index: 2;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5); /* Добавлена тень для лучшей видимости */
        }
        
        /* Анимации для элементов интерфейса */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(255,140,0,0.5); }
            50% { box-shadow: 0 0 20px rgba(255,140,0,0.8); }
            100% { box-shadow: 0 0 5px rgba(255,140,0,0.5); }
        }
        
        /* Текущий фрукт */
        #currentFruit {
            position: absolute;
            top: 70px;
            padding: 10px;
            background: transparent; 
            border-radius: 15px;
            text-align: center;
            z-index: 1;
            pointer-events: none;
        }
        
        #currentFruit img {
            width: 30px; 
            height: 30px; 
            display: block;
            margin: 0 auto;
            background: transparent; 
        }
        
        /* Следующий фрукт */
        #nextFruit {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px; 
            background: rgba(0,0,0,0.7);
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.5);
            text-align: center;
            transform: scale(0.6);
            transform-origin: top right;
            z-index: 2;
        }
        
        #nextFruit img {
            width: 30px; 
            height: 30px; 
            display: block;
            margin: 0 auto 5px; 
        }
        
        #nextFruit span {
            font-size: 12px; 
            font-weight: bold;
            color: #ffcc00;
            display: block;
        }
        
        /* Контейнер для коллекции фруктов */
        #collectionContainer {
            position: absolute;
            bottom: 20px; /* Отступ снизу */
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 10px;  /* Расстояние между фруктами */
            background: rgba(0,0,0,0.7);
            padding: 10px;  /* Внутренние отступы */
            border-radius: 15px;  
            box-shadow: 0 4px 8px rgba(0,0,0,0.5);
            z-index: 1000;
            overflow: hidden; /* Убираем стандартный скролл */
            max-width: 90%; /* Ограничение максимальной ширины */
        }
        
        /* Контейнер для списка фруктов */
        .collectionList {
            display: flex;
            transition: transform 0.5s ease;
        }
        
        /* Стилевой скролл для коллекции фруктов */
        .collectionList::-webkit-scrollbar {
            height: 8px;
        }
        
        .collectionList::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        .collectionList::-webkit-scrollbar-thumb {
            background: #ff6600;
            border-radius: 4px;
        }
        
        /* Стрелки навигации для коллекции фруктов */
        .collection-nav {
            position: absolute;
            bottom: 20px; /* Размещены ниже коллекции фруктов */
            width: 30px;
            height: 30px;
            background: rgba(255, 140, 0, 0.8);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            transition: background 0.3s, transform 0.2s;
        }
        
        .collection-nav:hover {
            background: rgba(255, 165, 0, 0.9);
            transform: scale(1.1);
        }
        
        .left-nav {
            left: 10px; /* Смещены внутрь контейнера */
        }
        
        .right-nav {
            right: 10px; /* Смещены внутрь контейнера */
        }
        
        .collectionItem {
            width: 30px;  /* Размер элемента */
            height: 30px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
            transition: transform 0.2s;
            flex-shrink: 0;
            position: relative;
            pointer-events: none; /* Отключено взаимодействие */
        }
        
        /* Экран окончания игры */
        #gameOverScreen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            z-index: 3000;
            text-align: center;
            padding: 20px;
        }
        
        #gameOverScreen h2 {
            font-size: 36px;
            margin-bottom: 20px;
            animation: fadeInText 1s forwards;
        }
        
        #gameOverScreen p {
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        #gameOverScreen button {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(45deg, #ff7518, #ff6600);
            color: white;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
            transition: transform 0.2s, background 0.3s;
        }
        
        #gameOverScreen button:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #ff4500, #ff3300);
        }
        
        /* Контролы игры */
        #controls {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            gap: 10px;
            z-index: 3;
        }
        
        .control-btn {
            background: rgba(0,0,0,0.7);
            border: none;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 16px;
            color: white;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
            transition: transform 0.2s, background 0.2s; 
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 80px;
            text-align: center;
        }
        
        .control-btn:hover {
            transform: scale(1.05);    
            background: rgba(255, 140, 0, 0.9); /* Оранжевый оттенок при наведении */
        }
        
        #currentFruit img, #nextFruit img {
            background: transparent;
        }
        
        .fruit {
            position: absolute;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        /* Граница для проверки окончания игры */
        #boundary {
            position: absolute;
            top: 100px; 
            left: 0;
            width: 100%;
            height: 2px; /* Уменьшена высота границы */
            background: linear-gradient(90deg, 
                rgba(0, 255, 0, 0.2) 0%,
                rgba(0, 255, 0, 0.8) 50%,
                rgba(0, 255, 0, 0.2) 100%
            );
            box-shadow: 0 0 10px rgba(0,255,0,0.5);
            z-index: 1;
        }
        
        /* 3D рамка */
        .frame-3d {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            border: 2px solid rgba(255, 140, 0, 0.7); /* Оранжевый бордер */
            transform-style: preserve-3d;
            transform: perspective(1000px) rotateX(1deg);
            box-shadow: 
                inset 0 0 50px rgba(255,140,0,0.1),
                0 0 20px rgba(255,140,0,0.2);
        }
        
        /* Линия траектории */
        #trajectoryLine {
            display: none;
            position: absolute;
            width: 2px;
            height: 400px; 
            background: rgba(255, 140, 0, 0.5);
            border-radius: 1px;
            box-shadow: 0 0 4px rgba(255,140,0,0.5);
            pointer-events: none;
            z-index: 1;
        }
        
        /* Оверлей для меню (Настройки, Магазин, Награды, Рейтинг) */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            padding: 10px;
        }
        
        .menu-content {
            background: #333; /* Темный фон для Хэллоуина */
            padding: 20px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            position: relative;
            max-height: 80vh;
            overflow-y: auto;
            color: #ffcc00; /* Золотистый цвет текста */
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        
        /* Кнопка закрытия меню */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 24px;
            color: #ffcc00;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .close-btn:hover {
            color: #ff6600;
        }
        
        /* Вкладки меню */
        .menu-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .menu-tab {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            background: #555;
            color: #ffcc00;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: bold;
            min-width: 100px;
            text-align: center;
        }
        
        .menu-tab.active {
            background: #ff6600;
            color: white;
        }
        
        .tab-content {
            display: none;
        }
        
        /* Вкладки наград */
        .rewards-tabs {
            display: flex;
            gap: 10px;
            margin: 15px 0;
            justify-content: center;
        }
        
        .reward-tab {
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            background: #555;
            color: #ffcc00;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: bold;
        }
        
        .reward-tab.active {
            background: #ff6600;
            color: white;
        }
        
        /* Контейнер статистики */
        .stats-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #444;
            border-radius: 10px;
            color: #ffcc00;
        }
        
        /* Элементы магазина */
        .shop-items {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .shop-items::-webkit-scrollbar {
            height: 8px;
        }
        
        .shop-items::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        .shop-items::-webkit-scrollbar-thumb {
            background: #ff6600;
            border-radius: 4px;
        }
        
        .shop-item {
            min-width: 120px;
            padding: 10px;
            border-radius: 10px;
            background: #555;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
            transition: transform 0.2s;
            flex-shrink: 0;
        }
        
        .shop-item:hover {
            transform: scale(1.05);
        }
        
        .shop-item div {
            width: 100%;
            height: 100px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .shop-item p {
            margin-bottom: 10px;
            font-size: 14px;
            color: #ffcc00;
        }
        
        .buy-btn, .apply-btn {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            background: #ff6600;
            color: white;
            font-size: 14px;
            transition: background 0.3s, transform 0.2s;
            font-weight: bold;
        }
        
        .buy-btn:hover:not(.disabled) {
            background: #ff4500;
            transform: scale(1.05);
        }
        
        .apply-btn.active {
            background: #ffcc00;
            color: #333;
        }
        
        .apply-btn:hover:not(.active) {
            background: #ffaa00;
            transform: scale(1.05);
        }
        
        /* Отключенные кнопки "Купить" */
        .buy-btn.disabled {
            background: #888;
            cursor: not-allowed;
            color: #ccc;
        }
        
        /* Стилизация уведомлений */
        #notificationContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 3000;
        }
        
        .notification {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin-bottom: 10px;
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeIn 0.5s forwards, fadeOut 0.5s forwards 2.5s;
        }
        
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
        
        /* Прогресс-бар для достижений */
        .progress-bar {
            background: #ddd;
            border-radius: 5px;
            overflow: hidden;
            height: 10px;
            margin: 5px 0;
        }
        
        .progress {
            background: #ff6600;
            height: 100%;
            transition: width 0.3s;
        }
        
        .progress-text {
            font-size: 12px;
            color: #ffcc00;
        }

        /* Стилизация кнопки "Получить" */
        .claim-btn {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            background: linear-gradient(45deg, #ff7518, #ff6600);
            color: white;
            font-size: 14px;
            transition: transform 0.2s, background 0.3s;
            font-weight: bold;
        }

        .claim-btn:hover:not(:disabled) {
            transform: scale(1.05);
            background: linear-gradient(45deg, #ff4500, #ff3300);
        }

        /* Адаптивность для мобильных устройств */
        @media (max-width: 768px) {
            #gameContainer {
                width: 100vw;
                height: 100vh;
                border-radius: 0;
            }

            #points {
                font-size: 20px;
                padding: 8px 16px;
            }

            .menu-content {
                padding: 15px;
            }

            .menu-tab, .reward-tab {
                padding: 8px 16px;
                font-size: 14px;
            }

            .stat-item {
                padding: 8px;
                font-size: 14px;
            }

            .claim-btn {
                font-size: 12px;
                padding: 6px;
            }

            .collection-nav {
                width: 25px;
                height: 25px;
                font-size: 16px;
            }

            /* Увеличение размеров кнопок контролов для удобства на мобильных */
            .control-btn {
                width: 100px;
                height: 40px;
                font-size: 16px;
                padding: 10px;
            }
        }

        /* Стилизация системы рейтинга */
        #leaderboard {
            display: none;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        #leaderboard h3 {
            margin-bottom: 15px;
            font-size: 24px;
            color: #ffcc00;
        }

        #leaderboardList {
            width: 100%;
            max-width: 400px;
            background: #444;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
            overflow-y: auto;
            max-height: 300px;
        }

        .leaderboard-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            border-bottom: 1px solid #555;
            color: white;
        }

        .leaderboard-item:last-child {
            border-bottom: none;
        }

        .podium {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .podium-place {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #ffcc00;
            font-weight: bold;
        }

        .podium-place img {
            width: 50px;
            height: 50px;
            margin-bottom: 5px;
        }

        /* Дополнительные стили для настройки фона */
        .toggle-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            background: #555;
            color: #ffcc00;
            cursor: pointer;
            transition: background 0.3s, color 0.3s;
            margin-top: 10px;
            font-weight: bold;
            width: 100%;
        }

        .toggle-btn.active {
            background: #ff6600;
            color: white;
        }

        .toggle-btn:hover {
            background: #ff4500;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Заставка игры -->
    <div id="splashScreen">
        <h1>Добро пожаловать!</h1>
        <input type="text" id="usernameInput" placeholder="Введите ваш никнейм">
        <button id="startGameBtn">Начать игру</button>
    </div>

    <!-- Контейнер игры -->
    <div id="gameContainer">
        <div id="points">Очки: 0</div> <!-- Перемещён элемент для отображения очков в верхнюю часть -->
        <div id="boundary"></div>
        <div class="frame-3d"></div>
        <div id="controls">
            <button id="menuBtn" class="control-btn">Меню</button>
        </div>
        <div id="currentFruit"></div>
        <div id="nextFruit"></div>
        <!-- Стрелки навигации для коллекции фруктов -->
        <button class="collection-nav left-nav" onclick="scrollCollection('left')">◀️</button>
        <button class="collection-nav right-nav" onclick="scrollCollection('right')">▶️</button>
        <div id="collectionContainer">
            <div class="collectionList">
                <!-- Фрукты будут динамически добавляться сюда -->
            </div>
        </div>
        <div id="trajectoryLine"></div>
        <div id="gameOverScreen">
            <h2>Игра окончена!</h2>
            <p>Ваш баланс: <span id="finalScore">0💲</span></p>
            <button onclick="restartGame()">Играть снова</button>
        </div>
    </div>
    
    <!-- Контейнер для уведомлений -->
    <div id="notificationContainer"></div>
    
    <!-- Оверлей для меню (Настройки, Магазин, Награды, Рейтинг) -->
    <div id="menuOverlay" class="overlay">
        <div class="menu-content">
            <button class="close-btn">✕</button>
            <div class="menu-tabs">
                <button class="menu-tab active" data-tab="settings">Настройки</button>
                <button class="menu-tab" data-tab="shop">Магазин</button>
                <button class="menu-tab" data-tab="rewards">Награды</button>
                <button class="menu-tab" data-tab="leaderboard">Рейтинг</button>
            </div>
            
            <!-- Настройки -->
            <div class="tab-content" id="settingsContent">
                <h3>Настройки</h3>
                <div class="settings-options">
                    <button class="setting-btn" id="musicToggle">🎵 Музыка</button>
                    <button class="setting-btn" id="soundToggle">🔊 Звуки</button>
                    <button class="toggle-btn" id="backgroundToggle">🖼️ Отключить фон</button>
                </div>
            </div>
            
            <!-- Магазин -->
            <div class="tab-content" id="shopContent" style="display: none">
                <h3>Магазин</h3>
                <!-- Баланс отображается только в магазине -->
                <div id="balance">Баланс: 1000💲</div>
                <div class="shop-items">
                    <!-- Фрукты (темы) будут динамически добавляться сюда -->
                </div>
            </div>
            
            <!-- Награды -->
            <div class="tab-content" id="rewardsContent" style="display: none">
                <h3>Награды</h3>
                <div class="rewards-tabs">
                    <button class="reward-tab active" data-type="daily">Сегодня</button>
                    <button class="reward-tab" data-type="achievements">Достижения</button>
                </div>
                <div class="rewards-content"></div>
            </div>
            
            <!-- Рейтинг -->
            <div class="tab-content" id="leaderboardContent" style="display: none">
                <h3>Рейтинг</h3>
                <div class="leaderboard-buttons">
                    <button class="leaderboard-btn" onclick="showTop10()">ТОП 10</button>
                    <button class="leaderboard-btn" onclick="showMyRating()">МОЙ РЕЙТИНГ</button>
                </div>
                <div id="leaderboardList">
                    <!-- Рейтинги будут динамически добавляться сюда -->
                </div>
                <button onclick="downloadLeaderboard()" style="margin-top: 20px; padding: 10px 20px; border: none; border-radius: 15px; background: #ff6600; color: white; cursor: pointer; transition: background 0.3s;">
                    Скачать рейтинг
                </button>
                <div class="podium">
                    <div class="podium-place">
                        <img src="https://i.imgur.com/1stPlace.png" alt="1 место">
                        <span>1 место</span>
                    </div>
                    <div class="podium-place">
                        <img src="https://i.imgur.com/2ndPlace.png" alt="2 место">
                        <span>2 место</span>
                    </div>
                    <div class="podium-place">
                        <img src="https://i.imgur.com/3rdPlace.png" alt="3 место">
                        <span>3 место</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Подключение библиотеки Matter.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/matter-js/0.18.0/matter.min.js"></script>
    <script>
        // Переменные для аудио
        let bgMusic;
        let isMusicPlaying = false;
        let isSoundOn = true;

        // Баланс пользователя
        let balance = parseInt(localStorage.getItem('balance')) || 1000; 
        
        // Очки пользователя
        let points = parseInt(localStorage.getItem('points')) || 0;

        // Загрузка shopItemsData из localStorage или инициализация по умолчанию
        let shopItemsData = JSON.parse(localStorage.getItem('shopItemsData'));
        if (!shopItemsData) {
            shopItemsData = {
                themes: [
                    {id: 'theme1', type: 'theme', name: 'Градиент Космоса', price: 100, gradient: 'linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d)', purchased: false, active: false},
                    {id: 'theme2', type: 'theme', name: 'Градиент Заката', price: 200, gradient: 'linear-gradient(135deg, #ff7f00, #ff4c4c)', purchased: false, active: false},
                    {id: 'theme3', type: 'theme', name: 'Градиент Океана', price: 300, gradient: 'linear-gradient(135deg, #004e92, #000428)', purchased: false, active: false},
                    {id: 'theme4', type: 'theme', name: 'Градиент Леса', price: 400, gradient: 'linear-gradient(135deg, #134e5e, #71b7e6)', purchased: false, active: false},
                    {id: 'theme5', type: 'theme', name: 'Градиент Пустыни', price: 500, gradient: 'linear-gradient(135deg, #b79891, #94716b)', purchased: false, active: false},
                    {id: 'theme6', type: 'theme', name: 'Градиент Северного Сияния', price: 600, gradient: 'linear-gradient(135deg, #2c5364, #203a43, #0f2027)', purchased: false, active: false},
                    {id: 'theme7', type: 'theme', name: 'Градиент Цветущей Сакуры', price: 700, gradient: 'linear-gradient(135deg, #ee9ca7, #ffdde1)', purchased: false, active: false},
                    {id: 'theme8', type: 'theme', name: 'Градиент Неонового Города', price: 800, gradient: 'linear-gradient(135deg, #0f2027, #203a43, #2c5364)', purchased: false, active: false},
                    {id: 'theme9', type: 'theme', name: 'Градиент Глубокого Космоса', price: 900, gradient: 'linear-gradient(135deg, #000000, #434343)', purchased: false, active: false},
                    {id: 'theme10', type: 'theme', name: 'Градиент Радужной Магии', price: 1000, gradient: 'linear-gradient(135deg, #12c2e9, #c471ed, #f64f59)', purchased: false, active: false}
                ]
            };
            localStorage.setItem('shopItemsData', JSON.stringify(shopItemsData));
        }

        // Массив наград
        const rewardsData = {
            daily: [
                { id: 'day1', name: 'День 1', reward: 100, claimed: false },
                { id: 'day2', name: 'День 2', reward: 200, claimed: false },
                { id: 'day3', name: 'День 3', reward: 300, claimed: false }
            ],
            achievements: [
                { id: 'score100', name: 'Набрать 100 очков', reward: 50, progress: 0, target: 100, completed: false },
                { id: 'score500', name: 'Набрать 500 очков', reward: 150, progress: 0, target: 500, completed: false },
                { id: 'matches10', name: 'Соединить 10 пар', reward: 100, progress: 0, target: 10, completed: false },
                { id: 'score1000', name: 'Набрать 1000 очков', reward: 200, progress: 0, target: 1000, completed: false },
                { id: 'matches20', name: 'Соединить 20 пар', reward: 200, progress: 0, target: 20, completed: false }
            ]
        };
        
        // Функция для рендеринга элементов магазина
        function renderShopItems() {
            const shopItemsContainer = document.querySelector('.shop-items');
            const balanceElement = document.getElementById('balance');
            if (!shopItemsContainer || !balanceElement) {
                console.error('Контейнер магазина или баланс не найден.');
                return;
            }
            
            // Обновление баланса с эмодзи
            balanceElement.textContent = `Баланс: ${balance}💲`;
            
            shopItemsContainer.innerHTML = '';
            
            shopItemsData.themes.forEach((item, index) => {
                const itemElement = document.createElement('div');
                itemElement.className = 'shop-item';
                itemElement.dataset.id = item.id;
                
                itemElement.innerHTML = `
                    <div style="width: 100%; height: 100px; border-radius: 8px; background: ${item.gradient}; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-bottom: 10px;">
                        ${item.name}
                    </div>
                    <p>${item.price}💲</p>
                    ${item.purchased ? 
                        `<button class="apply-btn ${item.active ? 'active' : ''}" data-id="${item.id}">
                            ${item.active ? 'Активно' : 'Применить'}
                        </button>` :
                        `<button class="buy-btn ${balance >= item.price ? '' : 'disabled'}" ${balance >= item.price ? '' : 'disabled'} data-id="${item.id}">
                            ${balance >= item.price ? 'Купить' : 'Недостаточно💲'}
                        </button>`
                    }
                `;
                
                shopItemsContainer.appendChild(itemElement);
            });
        }
        
        // Функция для обработки покупки
        function buyItem(item, index) {
            if (balance >= item.price) {
                balance -= item.price;
                item.purchased = true;
                showNotification(`Вы приобрели "${item.name}" за ${item.price}💲.`);
                renderShopItems();
                updateBalanceInGame(); // Обновляем баланс в магазине
                localStorage.setItem('balance', balance);
                localStorage.setItem('shopItemsData', JSON.stringify(shopItemsData)); // Сохраняем состояние магазина
            } else {
                showNotification('Недостаточно средств для покупки.');
            }
        }
        
        // Функция для применения темы
        function toggleItem(item) {
            if (!item.purchased) return;
        
            // Деактивируем все темы
            shopItemsData.themes.forEach(theme => theme.active = false);
            
            // Активируем выбранную тему
            item.active = true;
            
            // Применяем тему к контейнеру игры
            const gameContainer = document.getElementById('gameContainer');
            if (gameContainer) {
                gameContainer.style.background = `${item.gradient}`;
            }
            
            // Показ уведомления о применении темы
            showNotification(`Тема "${item.name}" применена.`);
            
            // Сохранение активной темы в localStorage
            localStorage.setItem('activeTheme', item.id);
            localStorage.setItem('shopItemsData', JSON.stringify(shopItemsData)); // Сохраняем состояние магазина
            
            renderShopItems(); // Обновляем магазин, чтобы отразить изменения
        }
        
        // Инициализация магазина
        function initShop() {
            const shopItemsContainer = document.querySelector('.shop-items');
            if (!shopItemsContainer) {
                console.error('Контейнер магазина не найден.');
                return;
            }
            
            renderShopItems();
        }
        
        // Функция для рендеринга наград
        function renderRewards(type) {
            const rewardsContent = document.querySelector('.rewards-content');
            if (!rewardsContent) {
                console.error('Контейнер наград не найден.');
                return;
            }
            
            rewardsContent.innerHTML = '';
            
            const rewardsList = rewardsData[type];
            if (!rewardsList) {
                console.error(`Тип наград "${type}" не найден.`);
                return;
            }
        
            rewardsList.forEach((reward, index) => {
                if (!reward) return;
                
                const rewardElement = document.createElement('div');
                rewardElement.className = 'reward-item';
                
                if (type === 'daily') {
                    // Задания на день с задержкой открытия
                    setTimeout(() => {
                        rewardElement.innerHTML = `
                            <div class="reward-info">
                                <h4>${reward.name}</h4>
                                <p>${reward.reward}💲</p>
                            </div>
                            <button class="claim-btn ${reward.claimed ? 'disabled' : ''}" data-id="${reward.id}" ${reward.claimed ? 'disabled' : ''}>
                                ${reward.claimed ? 'Получено' : 'Получить'}
                            </button>
                        `;
                        rewardsContent.appendChild(rewardElement);
                    }, 1000 * (index + 1)); // Задержка: 1 секунда на каждый день
                } else {
                    const progress = Math.min(100, (reward.progress / reward.target) * 100);
                    rewardElement.innerHTML = `
                        <div class="reward-info">
                            <h4>${reward.name}</h4>
                            <p>${reward.reward}💲</p>
                            <div class="progress-bar">
                                <div class="progress" style="width: ${progress}%;"></div>
                            </div>
                            <span class="progress-text">${reward.progress}/${reward.target}</span>
                        </div>
                        <button class="claim-btn" data-id="${reward.id}"
                            ${reward.completed && !reward.claimed ? '' : 'disabled'}>
                            ${reward.claimed ? 'Получено' : 'Получить'}
                        </button>
                    `;
                    rewardsContent.appendChild(rewardElement);
                }
            });
        
            // Обработчики для кнопок получения наград
            document.querySelectorAll('.claim-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const rewardId = e.target.dataset.id;
                    const rewardType = document.querySelector('.reward-tab.active').dataset.type;
                    const reward = rewardsData[rewardType].find(r => r.id === rewardId);
                    
                    if (reward && !reward.claimed) {
                        if (rewardType === 'daily') {
                            balance += reward.reward;
                        } else if (rewardType === 'achievements') {
                            points += reward.reward; // Добавляем очки за достижения
                        }
                        reward.claimed = true;
                        showNotification(`Вы получили ${reward.reward}💲!`);
                        renderShopItems(); // Обновляем баланс
                        renderRewards(rewardType);
                        localStorage.setItem('balance', balance);
                        localStorage.setItem('points', points);
                        updatePointsDisplay(); // Обновляем отображение очков
                    }
                });
            });
        }
        
        // Инициализация обработчиков магазина
        function initShopHandlers() {
            const shopItemsContainer = document.querySelector('.shop-items');
            if (!shopItemsContainer) {
                console.error('Контейнер магазина не найден.');
                return;
            }
            
            shopItemsContainer.addEventListener('click', (e) => {
                const button = e.target;
                if (!button.matches('.buy-btn, .apply-btn')) return;
        
                const itemContainer = button.closest('.shop-item');
                if (!itemContainer) {
                    console.error('Контейнер магазина не найден.');
                    return;
                }
        
                const itemId = itemContainer.dataset.id;
                const itemIndex = shopItemsData.themes.findIndex(item => item.id === itemId);
                const item = shopItemsData.themes[itemIndex];
        
                if (!item) {
                    console.error(`Тема с ID "${itemId}" не найдена.`);
                    return;
                }
        
                if (button.classList.contains('buy-btn')) {
                    buyItem(item, itemIndex);
                } else if (button.classList.contains('apply-btn')) {
                    toggleItem(item);
                }
            });
        }
        
        // Система уведомлений (toasts)
        function showNotification(message) {
            const notificationContainer = document.getElementById('notificationContainer');
            if (!notificationContainer) {
                console.error('Контейнер уведомлений не найден.');
                return;
            }
            
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            notificationContainer.appendChild(notification);
            
            // Удаление уведомления через 3 секунды
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Функция обновления баланса в магазине
        function updateBalanceInGame() {
            const balanceElement = document.getElementById('balance');
            if (balanceElement) {
                balanceElement.textContent = `Баланс: ${balance}💲`;
            } else {
                console.error('Элемент баланса не найден.');
            }
        }

        // Функция обновления очков на экране игры
        function updatePointsDisplay() {
            const pointsElement = document.getElementById('points');
            if (pointsElement) {
                pointsElement.textContent = `Очки: ${points}`;
            } else {
                console.error('Элемент очков не найден.');
            }
        }

        // Инициализация игры
        function initGame() {
            initShop();
            renderShopItems();
            renderRewards('daily');
            updateNextFruit();
            updateCollection();
            updatePointsDisplay(); // Обновляем отображение очков
            
            // Автоматический выбор первой вкладки
            const firstTab = document.querySelector('.menu-tab');
            if (firstTab) firstTab.click();
            else console.warn('Вкладка меню не найдена.');
        
            // Обработчики для вкладок наград
            const rewardTabs = document.querySelectorAll('.reward-tab');
            if (rewardTabs.length > 0) {
                rewardTabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        document.querySelectorAll('.reward-tab').forEach(t => t.classList.remove('active'));
                        tab.classList.add('active');
                        renderRewards(tab.dataset.type);
                    });
                });
            } else {
                console.warn('Вкладки наград не найдены.');
            }
        
            // Инициализация обработчиков магазина
            initShopHandlers();

            // Инициализация обработчиков кнопок меню
            initMenuHandlers();

            // Применение сохранённой темы
            const savedThemeId = localStorage.getItem('activeTheme');
            if (savedThemeId) {
                const savedTheme = shopItemsData.themes.find(theme => theme.id === savedThemeId);
                if (savedTheme && savedTheme.purchased) {
                    toggleItem(savedTheme);
                }
            }

            // Восстановление состояния наград
            const savedRewards = JSON.parse(localStorage.getItem('rewards')) || rewardsData;
            Object.keys(savedRewards).forEach(type => {
                savedRewards[type].forEach((reward, index) => {
                    rewardsData[type][index].claimed = reward.claimed;
                    rewardsData[type][index].progress = reward.progress;
                    rewardsData[type][index].completed = reward.completed;
                });
            });
            localStorage.setItem('rewards', JSON.stringify(rewardsData));

            // Восстановление очков
            points = parseInt(localStorage.getItem('points')) || 0;
            updatePointsDisplay();

            if (document.querySelector('.reward-tab.active')) {
                renderRewards(document.querySelector('.reward-tab.active').dataset.type);
            }
        }

        // Функция запуска игры из заставки
        function startGame(username = null) {
            if (!username) {
                const input = document.getElementById('usernameInput');
                if (!input) {
                    showNotification('Поле ввода никнейма не найдено.');
                    return;
                }
                const enteredUsername = input.value.trim();
                if (enteredUsername === '') {
                    showNotification('Пожалуйста, введите никнейм.');
                    return;
                }
                username = enteredUsername;
                localStorage.setItem('username', username);
            }
            // Скрыть заставку
            const splash = document.getElementById('splashScreen');
            if (splash) splash.style.display = 'none';
            else console.error('Элемент заставки не найден.');
            // Инициализировать игру
            initGame();
        }

        // Массив фруктов с уникальными цветами
        const fruits = [
            { name: 'Фрукт 1', color: '#FF3D00', size: 40, score: 10, density: 0.001 },
            { name: 'Фрукт 2', color: '#FF4081', size: 50, score: 20, density: 0.002 },
            { name: 'Фрукт 3', color: '#00B0FF', size: 60, score: 30, density: 0.003 },
            { name: 'Фрукт 4', color: '#FFEA00', size: 70, score: 40, density: 0.004 },
            { name: 'Фрукт 5', color: '#D4E157', size: 80, score: 50, density: 0.005 },
            { name: 'Фрукт 6', color: '#76FF03', size: 90, score: 60, density: 0.006 },
            { name: 'Фрукт 7', color: '#FFD600', size: 100, score: 70, density: 0.007 },
            { name: 'Фрукт 8', color: '#00E5FF', size: 160, score: 80, density: 0.108 },
            { name: 'Фрукт 9', color: '#D5006D', size: 170, score: 90, density: 0.109 },
            { name: 'Фрукт 10', color: '#FF1744', size: 185, score: 100, density: 0.110 }
        ];
        
        // Индексы текущего и следующего фруктов
        let currentFruitIndex = 0;
        let nextFruitIndex = 1;
        let canDrop = true;
        let unlockedFruits = new Set([0, 1, 2, 3, 4]); // Начинаем с первых 5 фруктов

        // Настройка Matter.js
        const Engine = Matter.Engine,
            Runner = Matter.Runner,
            Render = Matter.Render,
            World = Matter.World,
            Bodies = Matter.Bodies,
            Events = Matter.Events;
        
        const engineInstance = Engine.create();
        const runner = Runner.create();
        Runner.run(runner, engineInstance); // Используем Matter.Runner.run вместо Engine.run

        const container = document.getElementById('gameContainer');
        if (!container) {
            console.error('Элемент gameContainer не найден.');
        }
        const containerWidth = container ? container.clientWidth : 600; // Увеличено на 1.5 раза
        const containerHeight = container ? container.clientHeight : 600;
        
        const renderInstance = Render.create({
            element: container,
            engine: engineInstance,
            options: {
                width: containerWidth,
                height: containerHeight,
                wireframes: false,
                background: 'transparent'
            }
        });
        
        // Добавление стен
        const walls = [
            Bodies.rectangle(containerWidth / 2, containerHeight - 30, containerWidth, 60, { isStatic: true }),
            Bodies.rectangle(-30, containerHeight / 2, 60, containerHeight, { isStatic: true }),
            Bodies.rectangle(containerWidth + 30, containerHeight / 2, 60, containerHeight, { isStatic: true })
        ];
        
        World.add(engineInstance.world, walls);
        
        // Запуск рендера
        Render.run(renderInstance);
        
        // Функция для создания SVG изображения фрукта
        function createFruitSVG(fruit) {
            return `
                <svg width="${fruit.size}" height="${fruit.size}" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="${fruit.size / 2}" cy="${fruit.size / 2}" r="${fruit.size / 2}" fill="${fruit.color}" />
                    <circle cx="${fruit.size * 0.3}" cy="${fruit.size * 0.3}" r="${fruit.size * 0.15}" fill="white" />
                </svg>
            `;
        }
        
        // Функция для создания тела фрукта в Matter.js
        function createFruit(x, y, fruitIndex) {
            if (fruitIndex === undefined || !fruits[fruitIndex]) {
                fruitIndex = 0;
            }
            
            const fruit = fruits[fruitIndex];
            if (!fruit) return null;
            
            const circle = Bodies.circle(x, y, (fruit.size / 2), {
                restitution: 0.5,
                friction: 0.8,
                density: fruit.density,
                fruitIndex: fruitIndex,
                render: {
                    sprite: {
                        texture: `data:image/svg+xml,${encodeURIComponent(createFruitSVG(fruits[fruitIndex]))}`,
                        xScale: 1,
                        yScale: 1
                    }
                }
            });
            return circle;
        }
        
        // Функция для случайного выбора индекса фрукта (только доступные)
        function getRandomFruitIndex() {
            const availableFruits = Array.from(unlockedFruits);
            return availableFruits[Math.floor(Math.random() * availableFruits.length)];
        }
        
        // Функция для обновления линии траектории при перетаскивании
        function updateTrajectoryLine(x) {
            const line = document.getElementById('trajectoryLine');
            const currentFruitElement = document.getElementById('currentFruit');
            
            if (!line || !currentFruitElement) {
                console.warn('Элемент линии траектории или текущего фрукта не найден.');
                return;
            }
            
            line.style.display = 'block';
            line.style.left = `${x}px`;
            line.style.top = '150px';
            
            const fruitWidth = fruits[currentFruitIndex].size;
            currentFruitElement.style.left = `${x - fruitWidth / 2}px`;
        }
        
        // Инициализация текущего и следующего фруктов
        currentFruitIndex = getRandomFruitIndex();
        nextFruitIndex = getRandomFruitIndex();
        
        // Обновление отображения следующего фрукта
        function updateNextFruit() {
            const nextFruitHTML = `
                <img src="data:image/svg+xml,${encodeURIComponent(createFruitSVG(fruits[nextFruitIndex]))}" alt="${fruits[nextFruitIndex].name}">
                <span>${fruits[nextFruitIndex].name}</span>
            `;
            const nextFruitDiv = document.getElementById('nextFruit');
            if (nextFruitDiv) {
                nextFruitDiv.innerHTML = nextFruitHTML;
            } else {
                console.error('Элемент nextFruit не найден.');
            }
            
            const currentFruitHTML = `
                <img src="data:image/svg+xml,${encodeURIComponent(createFruitSVG(fruits[currentFruitIndex]))}" alt="${fruits[currentFruitIndex].name}">
            `;
            const currentFruitDiv = document.getElementById('currentFruit');
            if (currentFruitDiv) {
                currentFruitDiv.innerHTML = currentFruitHTML;
                currentFruitDiv.style.animation = 'glow 2s infinite'; 
            } else {
                console.error('Элемент currentFruit не найден.');
            }
        }
        
        // Обновление коллекции фруктов
        function updateCollection() {
            const collectionList = document.querySelector('.collectionList');
            if (!collectionList) {
                console.error('Элемент collectionList не найден.');
                return;
            }
            collectionList.innerHTML = '';
            
            unlockedFruits.forEach(index => {
                const fruit = fruits[index];
                const item = document.createElement('div');
                item.className = 'collectionItem';
                item.innerHTML = `
                    <img src="data:image/svg+xml,${encodeURIComponent(createFruitSVG(fruit))}" alt="${fruit.name}" style="width: 100%; height: 100%; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3)); pointer-events: none;">
                `;
                collectionList.appendChild(item);
            });
        }
        
        // Переменные для перетаскивания
        let isDragging = false;
        let currentX = containerWidth / 2;
        let menuOpen = false;
        let collectionOffset = 0; // Смещение коллекции
        
        // Обработчики событий для перетаскивания
        container.addEventListener('mousedown', startDrag);
        container.addEventListener('mousemove', drag);
        container.addEventListener('mouseup', endDrag);
        container.addEventListener('touchstart', e => {
            e.preventDefault();
            startDrag(e.touches[0]);
        });
        container.addEventListener('touchmove', e => {
            e.preventDefault();
            drag(e.touches[0]);
        });
        container.addEventListener('touchend', endDrag);
        
        // Начало перетаскивания
        function startDrag(e) {
            if (!canDrop || menuOpen) return;
            isDragging = true;
            const rect = container.getBoundingClientRect();
            currentX = Math.min(Math.max(e.clientX - rect.left, fruits[currentFruitIndex].size / 2), containerWidth - fruits[currentFruitIndex].size / 2);
            updateTrajectoryLine(currentX);
        }
        
        // Перетаскивание
        function drag(e) {
            if (!isDragging || !canDrop || menuOpen) return;
            const rect = container.getBoundingClientRect();
            currentX = Math.min(Math.max(e.clientX - rect.left, fruits[currentFruitIndex].size / 2), containerWidth - fruits[currentFruitIndex].size / 2);
            updateTrajectoryLine(currentX);
        }
        
        // Завершение перетаскивания
        function endDrag() {
            if (!isDragging || !canDrop || menuOpen) return;
            isDragging = false;
            const line = document.getElementById('trajectoryLine');
            if (line) {
                line.style.display = 'none';
            } else {
                console.warn('Элемент trajectoryLine не найден.');
            }
        
            if (canDrop) {
                const newFruit = createFruit(currentX, 150, currentFruitIndex);
                if (newFruit) {
                    World.add(engineInstance.world, newFruit);
                } else {
                    console.error('Не удалось создать новый фрукт.');
                    return;
                }
                
                canDrop = false;
                setTimeout(() => {
                    canDrop = true;
                    currentFruitIndex = nextFruitIndex;
                    nextFruitIndex = getRandomFruitIndex();
                    updateNextFruit();
                }, 500);
            }
        }
        
        // Функция для получения последнего открытого фрукта
        function getLastUnlockedIndex() {
            return Math.max(...Array.from(unlockedFruits));
        }
        
        // Функция для создания тела фрукта в Matter.js
        function createFruit(x, y, fruitIndex) {
            if (fruitIndex === undefined || !fruits[fruitIndex]) {
                fruitIndex = 0;
            }
            
            const fruit = fruits[fruitIndex];
            if (!fruit) return null;
            
            const circle = Bodies.circle(x, y, (fruit.size / 2), {
                restitution: 0.5,
                friction: 0.8,
                density: fruit.density,
                fruitIndex: fruitIndex,
                render: {
                    sprite: {
                        texture: `data:image/svg+xml,${encodeURIComponent(createFruitSVG(fruits[fruitIndex]))}`,
                        xScale: 1,
                        yScale: 1
                    }
                }
            });
            return circle;
        }
        
        // Функция для случайного выбора индекса фрукта (только доступные)
        function getRandomFruitIndex() {
            const availableFruits = Array.from(unlockedFruits);
            return availableFruits[Math.floor(Math.random() * availableFruits.length)];
        }
        
        // Функция для обновления линии траектории при перетаскивании
        function updateTrajectoryLine(x) {
            const line = document.getElementById('trajectoryLine');
            const currentFruitElement = document.getElementById('currentFruit');
            
            if (!line || !currentFruitElement) {
                console.warn('Элемент линии траектории или текущего фрукта не найден.');
                return;
            }
            
            line.style.display = 'block';
            line.style.left = `${x}px`;
            line.style.top = '150px';
            
            const fruitWidth = fruits[currentFruitIndex].size;
            currentFruitElement.style.left = `${x - fruitWidth / 2}px`;
        }
        
        // Обработчик столкновений
        function checkCollision(event) {
            const pairs = event.pairs;
            
            pairs.forEach(pair => {
                const bodyA = pair.bodyA;
                const bodyB = pair.bodyB;
                
                if (bodyA.fruitIndex !== undefined && bodyB.fruitIndex !== undefined) {
                    if (bodyA.fruitIndex === bodyB.fruitIndex) {
                        const newIndex = bodyA.fruitIndex + 1;
                        if (newIndex < fruits.length) {
                            const midX = (bodyA.position.x + bodyB.position.x) / 2;
                            const midY = (bodyA.position.y + bodyB.position.y) / 2;
                            
                            World.remove(engineInstance.world, [bodyA, bodyB]);
                            const newFruit = createFruit(midX, midY, newIndex);
                            if (newFruit) {
                                World.add(engineInstance.world, newFruit);
                            } else {
                                console.error('Не удалось создать объединённый фрукт.');
                                return;
                            }
                            
                            // Добавляем новый фрукт в коллекцию только если предыдущий уже открыт
                            if (newIndex === getLastUnlockedIndex() + 1) {
                                unlockedFruits.add(newIndex);
                                updateCollection();
                            }
                            
                            // Начисляем фиксированные 10 очков за объединение
                            points += 10;
                            
                            // Проверяем, достигнут ли порог для увеличения баланса
                            if (points >= 150) {
                                updateBalance(10, 'Вы заработали 10💲 за 150 очков!');
                                points -= 150;
                            }
                            
                            localStorage.setItem('points', points);
                            updatePointsDisplay();
                            
                            // Начисляем 10 очков к балансу за каждую пару
                            updateBalance(10, 'Вы заработали 10💲 за объединение пары!');
                            
                            // Обновление достижений
                            updateAchievements(10); // Передаём фиксированное количество очков
                        }
                    }
                }
            });
        }
        
        // Функция для обновления баланса и отображения уведомлений
        function updateBalance(amount, message) {
            balance += amount;
            localStorage.setItem('balance', balance);
            updateBalanceInGame();
            showNotification(message || `Вы заработали ${amount}💲!`);
        }
        
        // Функция обновления достижений
        function updateAchievements(pointsEarned) {
            const scoreAchievements = rewardsData.achievements.filter(a => a.id.startsWith('score'));
            scoreAchievements.forEach(achievement => {
                if (!achievement.completed) {
                    achievement.progress += pointsEarned;
                    if (achievement.progress >= achievement.target) {
                        achievement.completed = true;
                        showNotification(`Достижение "${achievement.name}" выполнено!`);
                    }
                }
            });
            
            const matchesAchievements = rewardsData.achievements.filter(a => a.id.startsWith('matches'));
            matchesAchievements.forEach(achievement => {
                if (!achievement.completed) {
                    achievement.progress += 1; // Предполагается, что каждое соединение пары увеличивает прогресс
                    if (achievement.progress >= achievement.target) {
                        achievement.completed = true;
                        showNotification(`Достижение "${achievement.name}" выполнено!`);
                    }
                }
            });
            
            const activeTab = document.querySelector('.reward-tab.active');
            if (activeTab?.dataset.type === 'achievements') {
                renderRewards('achievements');
            }

            // Сохранение состояния наград
            localStorage.setItem('rewards', JSON.stringify(rewardsData));
        }
        
        // Проверка окончания игры
        function checkGameOver() {
            const bodies = Matter.Composite.allBodies(engineInstance.world);
            const boundary = document.getElementById('boundary');
            
            for (let body of bodies) {
                if (body.position.y < 100 && !body.isStatic) {
                    if (boundary) {
                        boundary.style.background = 'rgba(255,0,0,0.8)';
                        boundary.style.boxShadow = '0 0 20px red';
                        
                        setTimeout(() => {
                            boundary.style.background = 'linear-gradient(90deg, rgba(0, 255, 0, 0.2) 0%, rgba(0, 255, 0, 0.8) 50%, rgba(0, 255, 0, 0.2) 100%)';
                            boundary.style.boxShadow = '0 0 10px rgba(0,255,0,0.5)';
                        }, 300);
                    }
                    
                    gameOver();
                    break;
                }
            }
        }
        
        // Функция окончания игры
        function gameOver() {
            const gameOverScreen = document.getElementById('gameOverScreen');
            const finalScoreElement = document.getElementById('finalScore');
            if (finalScoreElement) {
                finalScoreElement.textContent = `${balance}💲`;
            } else {
                console.error('Элемент finalScore не найден.');
            }
            
            if (gameOverScreen) {
                gameOverScreen.style.display = 'flex';
            } else {
                console.error('Элемент gameOverScreen не найден.');
            }
            canDrop = false;
            
            // Обновляем общую статистику
            updateStats();
            
            // Добавляем текущего пользователя в рейтинг
            const username = localStorage.getItem('username') || 'Игрок';
            addToLeaderboard(username, balance);
            
            // Сохраняем очки
            localStorage.setItem('points', points);
        }
        
        // Функция перезапуска игры
        function restartGame() {
            const bodies = Matter.Composite.allBodies(engineInstance.world);
            bodies.forEach(body => {
                if (!body.isStatic) {
                    World.remove(engineInstance.world, body);
                }
            });
            
            canDrop = true;
            currentFruitIndex = getRandomFruitIndex();
            nextFruitIndex = getRandomFruitIndex();
            unlockedFruits = new Set([0, 1, 2, 3, 4]); // Сброс доступных фруктов, только первые 5
            
            updateNextFruit();
            updateCollection();
            const gameOverScreen = document.getElementById('gameOverScreen');
            if (gameOverScreen) {
                gameOverScreen.style.display = 'none';
            } else {
                console.error('Элемент gameOverScreen не найден.');
            }
            
            showNotification('Игра перезапущена.');
            
            // Если нужно сбросить очки при перезапуске:
            // points = 0;
            // localStorage.setItem('points', points);
            // updatePointsDisplay();
        }
        
        // Обработчик открытия меню
        const menuBtn = document.getElementById('menuBtn');
        if (menuBtn) {
            menuBtn.addEventListener('pointerdown', () => {
                toggleMenu();
            });
        } else {
            console.warn('Элемент menuBtn не найден.');
        }
        
        // Обработчик закрытия меню
        const closeBtn = document.querySelector('.close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('pointerdown', () => {
                toggleMenu();
            });
        } else {
            console.warn('Кнопка закрытия меню не найдена.');
        }
        
        // Функция переключения меню
        function toggleMenu() {
            const menuOverlay = document.getElementById('menuOverlay');
            if (!menuOverlay) {
                console.error('Элемент menuOverlay не найден.');
                return;
            }
            menuOverlay.style.display = menuOverlay.style.display === 'flex' ? 'none' : 'flex';
            menuOpen = !menuOpen;
        }
        
        // Обработчики для вкладок меню
        const menuTabs = document.querySelectorAll('.menu-tab');
        if (menuTabs.length > 0) {
            menuTabs.forEach(tab => {
                tab.addEventListener('pointerdown', () => {
                    document.querySelectorAll('.menu-tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
                    
                    tab.classList.add('active');
                    const contentId = `${tab.dataset.tab}Content`;
                    const content = document.getElementById(contentId);
                    if (content) {
                        content.style.display = 'block';
                        if (tab.dataset.tab === 'rewards') {
                            renderRewards('daily');
                        }
                    } else {
                        console.warn(`Контент вкладки "${tab.dataset.tab}" не найден.`);
                    }
            
                    // Отображение баланса только в магазине
                    const balanceDisplay = document.getElementById('balance');
                    if (balanceDisplay) {
                        balanceDisplay.style.display = tab.dataset.tab === 'shop' ? 'block' : 'none';
                    } else {
                        console.warn('Элемент баланса не найден.');
                    }
                });
            });
        } else {
            console.warn('Вкладки меню не найдены.');
        }

        // Функция инициализации обработчиков меню для мобильных устройств
        function initMenuHandlers() {
            // Увеличение зоны кликабельности кнопок настроек, магазина и рейтинга
            document.querySelectorAll('.control-btn').forEach(btn => {
                btn.style.touchAction = 'manipulation';
            });
        }
        
        // Обработчики событий Matter.js
        Events.on(engineInstance, 'collisionStart', checkCollision);
        Events.on(engineInstance, 'afterUpdate', checkGameOver);
        
        // Инициализация фруктов
        updateNextFruit();
        updateCollection();
        updatePointsDisplay();
        
        // Функция навигации по коллекции фруктов
        function scrollCollection(direction) {
            const collectionList = document.querySelector('.collectionList');
            if (!collectionList) {
                console.error('Элемент collectionList не найден.');
                return;
            }
            const totalItems = collectionList.children.length;
            const itemWidth = 40; // Ширина элемента + отступ
            const containerWidth = document.querySelector('#collectionContainer').clientWidth;
            const visibleItems = Math.floor(containerWidth / itemWidth);
            
            if (direction === 'left') {
                collectionOffset = Math.min(collectionOffset + itemWidth, 0);
            } else {
                collectionOffset = Math.max(collectionOffset - itemWidth, -(totalItems * itemWidth - visibleItems * itemWidth));
            }
            
            collectionList.style.transform = `translateX(${collectionOffset}px)`;
        }

        // Функция для отправки данных в leaderboard.php
        const SCRIPT_URL = 'leaderboard.php'; // Убедитесь, что путь корректен относительно вашего HTML-файла

        // Функция для добавления/обновления пользователя в рейтинге
        function addToLeaderboard(username, score) {
            const formData = new FormData();
            formData.append('username', username);
            formData.append('score', score);

            fetch(SCRIPT_URL, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Рейтинг обновлён.');
                } else {
                    console.error('Ошибка:', data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка запроса:', error);
            });
        }

        // Функция для получения и отображения рейтинга
        function fetchLeaderboard() {
            fetch(SCRIPT_URL)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayLeaderboard(data.leaderboard);
                } else {
                    console.error('Ошибка:', data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка запроса:', error);
            });
        }

        // Функция для отображения рейтинга на странице
        function displayLeaderboard(leaderboard) {
            const leaderboardList = document.getElementById('leaderboardList');
            if (!leaderboardList) {
                console.error('Элемент leaderboardList не найден.');
                return;
            }
            leaderboardList.innerHTML = ''; // Очистка текущего списка

            leaderboard.forEach((player, index) => {
                const item = document.createElement('div');
                item.className = 'leaderboard-item';
                item.innerHTML = `
                    <span>${index + 1}. ${player.username}</span>
                    <span>${player.score}💲</span>
                `;
                // Выделение первых трех мест
                if (index === 0) {
                    item.style.color = '#FFD700'; // Золотой
                } else if (index === 1) {
                    item.style.color = '#C0C0C0'; // Серебряный
                } else if (index === 2) {
                    item.style.color = '#CD7F32'; // Бронзовый
                }
                leaderboardList.appendChild(item);
            });
        }

        // Функция для скачивания рейтинга как TXT-файл
        function downloadLeaderboard() {
            fetch(SCRIPT_URL)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const leaderboard = data.leaderboard;
                    let content = 'Топ игроков:\n';
                    leaderboard.forEach((player, index) => {
                        content += `${index + 1}. ${player.username} - ${player.score}💲\n`;
                    });

                    const blob = new Blob([content], { type: 'text/plain' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'leaderboard.txt';
                    a.click();
                    URL.revokeObjectURL(url);
                } else {
                    console.error('Ошибка:', data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка запроса:', error);
            });
        }

        // Функция отображения ТОП 10
        function showTop10() {
            fetchLeaderboard();
        }

        // Функция отображения моего рейтинга
        function showMyRating() {
            const leaderboardList = document.getElementById('leaderboardList');
            if (!leaderboardList) {
                console.error('Элемент leaderboardList не найден.');
                return;
            }
            leaderboardList.innerHTML = '';
        
            fetch(SCRIPT_URL)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const leaderboard = data.leaderboard;
                    const username = localStorage.getItem('username') || 'Игрок';
                    const userIndex = leaderboard.findIndex(player => player.username.toLowerCase() === username.toLowerCase());
                    
                    if (userIndex === -1) {
                        leaderboardList.innerHTML = `<div class="leaderboard-item">Вы не в рейтинге.</div>`;
                        return;
                    }
                    
                    const user = leaderboard[userIndex];
                    const rank = userIndex + 1;
                    
                    const userElement = document.createElement('div');
                    userElement.className = 'leaderboard-item';
                    userElement.innerHTML = `
                        <span>${rank}. ${user.username}</span>
                        <span>${user.score}💲</span>
                    `;
                    // Выделение места пользователя
                    if (rank === 1) {
                        userElement.style.color = '#FFD700'; // Золотой
                    } else if (rank === 2) {
                        userElement.style.color = '#C0C0C0'; // Серебряный
                    } else if (rank === 3) {
                        userElement.style.color = '#CD7F32'; // Бронзовый
                    }
                    leaderboardList.appendChild(userElement);
                } else {
                    console.error('Ошибка:', data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка запроса:', error);
            });
        }

        // Функция для добавления игрока в рейтинг
        function addToLeaderboard(username, score) {
            const formData = new FormData();
            formData.append('username', username);
            formData.append('score', score);

            fetch(SCRIPT_URL, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Рейтинг обновлён.');
                } else {
                    console.error('Ошибка:', data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка запроса:', error);
            });
        }

        // Обновление статистики
        function updateStats() {
            // Здесь можно добавить элементы для отображения статистики, если необходимо
        }

        // Обработка столкновений и начисление очков и баланса уже реализовано выше

        // Инициализация фруктов
        updateNextFruit();
        updateCollection();
        updatePointsDisplay();
        
        // Функция показа рейтинга
        function showLeaderboard() {
            fetchLeaderboard();
        }
    </script>
    <script>
        // Инициализация аудио и обработка кнопок настроек
        document.addEventListener('DOMContentLoaded', () => {
            // Переменные для аудио
            let bgMusic;
            let isMusicPlaying = false;
            let isSoundOn = true;
    
            // Инициализация аудио
            function initAudio() {
                if (!bgMusic) {
                    bgMusic = new Audio('https://opengameart.org/sites/default/files/Casual%20Game%20BGM%205.mp3'); // Замените на ваш URL аудио
                    bgMusic.loop = true;
                    bgMusic.volume = 0.3;
                }
            }
    
            // Переключение музыки
            function toggleMusic() {
                initAudio();
                
                if (isMusicPlaying) {
                    bgMusic.pause();
                    isMusicPlaying = false;
                    showNotification('Музыка выключена');
                } else {
                    const playPromise = bgMusic.play();
                    if (playPromise !== undefined) {
                        playPromise
                            .then(() => {
                                isMusicPlaying = true;
                                showNotification('Музыка включена');
                            })
                            .catch(error => {
                                console.log("Ошибка воспроизведения аудио:", error);
                                showNotification('Не удалось воспроизвести музыку.');
                            });
                    }
                }
            }
    
            // Переключение звуков
            function toggleSound() {
                isSoundOn = !isSoundOn;
                showNotification(`Звуки ${isSoundOn ? 'включены' : 'выключены'}`);
            }
    
            // Переключение фона
            function toggleBackground() {
                const gameContainer = document.getElementById('gameContainer');
                const backgroundToggleBtn = document.getElementById('backgroundToggle');
                const activeThemeId = localStorage.getItem('activeTheme');
                const activeTheme = shopItemsData.themes.find(theme => theme.id === activeThemeId);
    
                if (activeTheme && activeTheme.active) {
                    // Отключаем активную тему
                    activeTheme.active = false;
                    gameContainer.style.background = 'rgba(255, 255, 255, 0.1)'; // Возвращаем прозрачный фон
                    backgroundToggleBtn.textContent = '🖼️ Включить фон';
                    showNotification('Фон отключён');
                    localStorage.removeItem('activeTheme');
                    localStorage.setItem('shopItemsData', JSON.stringify(shopItemsData)); // Сохраняем состояние магазина
                    renderShopItems();
                } else {
                    showNotification('Нет активного фона для отключения.');
                }
            }
    
            // Обработчики кнопок настроек
            const musicToggleBtn = document.getElementById('musicToggle');
            const soundToggleBtn = document.getElementById('soundToggle');
            const backgroundToggleBtn = document.getElementById('backgroundToggle');
    
            if (musicToggleBtn) musicToggleBtn.addEventListener('pointerdown', toggleMusic);
            else console.warn('Element with ID "musicToggle" not found.');
    
            if (soundToggleBtn) soundToggleBtn.addEventListener('pointerdown', toggleSound);
            else console.warn('Element with ID "soundToggle" not found.');
    
            if (backgroundToggleBtn) backgroundToggleBtn.addEventListener('pointerdown', toggleBackground);
            else console.warn('Element with ID "backgroundToggle" not found.');
        });
    </script>
    <script>
        // Добавление обработчика загрузки страницы
        document.addEventListener('DOMContentLoaded', () => {
            // Проверяем, сохранён ли никнейм
            const savedUsername = localStorage.getItem('username');
            if (savedUsername) {
                startGame(savedUsername);
            } else {
                // Показать заставку
                const splash = document.getElementById('splashScreen');
                if (splash) splash.style.display = 'flex';
                else console.error('Элемент заставки не найден.');
            }

            // Обработчик кнопки "Начать игру"
            const startGameBtn = document.getElementById('startGameBtn');
            if (startGameBtn) {
                startGameBtn.addEventListener('pointerdown', () => {
                    startGame();
                });
            } else {
                console.warn('Элемент startGameBtn не найден.');
            }
        });
    </script>
</body>
</html>
