let balance = Number(localStorage.getItem('balance')) || 0;
let energy = Number(localStorage.getItem('energy')) || 500;
let maxEnergy = Number(localStorage.getItem('maxEnergy')) || 500;
let clickEnergyCost = 1;
let clickReward = 1;
let autoTapBought = localStorage.getItem('autoTapBought') === 'true';
let usageCount = Number(localStorage.getItem('usageCount')) || 1;
let cyberTapLevel = Number(localStorage.getItem('cyberTapLevel')) || 1;
let energyUpgradeLevel = Number(localStorage.getItem('energyUpgradeLevel')) || 1;
let skinsBought = JSON.parse(localStorage.getItem('skinsBought')) || [];
let currentSkin = localStorage.getItem('currentSkin') || 'default';
let autoTapInterval = null;
const maxUsage = 4;

const corgi = document.getElementById('corgi');
const balanceDisplay = document.getElementById('balance');
const energyBar = document.getElementById('energy-bar');
const energyText = document.getElementById('energy-text');

// Обновление локального хранилища
function updateLocalStorage() {
    localStorage.setItem('balance', balance);
    localStorage.setItem('energy', energy);
    localStorage.setItem('maxEnergy', maxEnergy);
    localStorage.setItem('autoTapBought', autoTapBought);
    localStorage.setItem('usageCount', usageCount);
    localStorage.setItem('cyberTapLevel', cyberTapLevel);
    localStorage.setItem('energyUpgradeLevel', energyUpgradeLevel);
    localStorage.setItem('skinsBought', JSON.stringify(skinsBought));
    localStorage.setItem('currentSkin', currentSkin);
}

// Установка текущего скина при загрузке
document.getElementById('corgi').src = getSkinImage(currentSkin);

// Обновление энергии и баланса при нажатии на корги
corgi.addEventListener('click', () => {
    if (energy >= clickEnergyCost) {
        balance += clickReward * cyberTapLevel;
        energy -= clickEnergyCost;
        updateEnergyBar();
        balanceDisplay.textContent = balance;
        const animation = document.createElement('div');
        animation.className = 'animation';
        animation.textContent = `+${clickReward * cyberTapLevel}`;
        animation.style.left = `${Math.random() * window.innerWidth}px`;
        animation.style.top = `${Math.random() * window.innerHeight}px`;
        document.body.appendChild(animation);
        setTimeout(() => animation.remove(), 1000);
        updateLocalStorage();
    }
});

// Обновление полосы энергии
function updateEnergyBar() {
    const energyPercentage = (energy / maxEnergy) * 100;
    energyBar.style.width = `${energyPercentage}%`;
    energyText.textContent = `${energy}/${maxEnergy}`;
}

// Восстановление энергии каждую секунду
setInterval(() => {
    if (energy < maxEnergy) {
        energy += clickEnergyCost;
        updateEnergyBar();
        updateLocalStorage();
    }
}, 1000);

// Сброс энергии
function resetEnergy() {
    energy = 0;
    const rechargeInterval = setInterval(() => {
        if (energy < maxEnergy) {
            energy++;
            updateEnergyBar();
            updateLocalStorage();
        } else {
            clearInterval(rechargeInterval);
        }
    }, 50);
}

// Открытие магазина с улучшениями и скинами
function openShop() {
    document.body.classList.add('shop');
    document.body.innerHTML = `
        <h2 class="section-title">Прокачайся</h2>
        <div class="upgrade-menu">
            <div class="upgrade-card">
                <img src="https://i.postimg.cc/w33kDKWs/energy.png" alt="Запас энергии" class="upgrade-image">
                <p class="upgrade-name">Запас энергии (Уровень: ${energyUpgradeLevel})</p>
                <div class="upgrade-price" id="price-1" onclick="buyEnergyUpgrade()">
                    <img src="https://i.postimg.cc/K3zdFKfc/coin.png" alt="Монета">
                    <span id="price-text-1">${500 * Math.pow(2, energyUpgradeLevel - 1)}</span>
                </div>
            </div>
            <div class="upgrade-card">
                <img src="https://i.postimg.cc/DmDPgCDp/multiclick.png" alt="Кибертап" class="upgrade-image">
                <p class="upgrade-name">Кибертап (Уровень: ${cyberTapLevel})</p>
                <div class="upgrade-price" id="price-2" onclick="buyCyberTap()">
                    <img src="https://i.postimg.cc/K3zdFKfc/coin.png" alt="Монета">
                    <span id="price-text-2">${500 * Math.pow(2, cyberTapLevel - 1)}</span>
                </div>
            </div>
            <div class="upgrade-card">
                <img src="https://i.postimg.cc/LYQBGvsX/reload.png" alt="Скины" class="upgrade-image">
                <p class="upgrade-name">Скины</p>
                <span class="usage-indicator">${usageCount}/4</span>
                <div class="upgrade-price" id="price-3" onclick="useFreeItem(3)">Бесплатно</div>
            </div>
        </div>
        <div class="menu">
            <div class="item" id="item-4">
                <img src="https://i.postimg.cc/Wtc4ws3D/prizes-button.png" alt="Фото" class="item-image">
                <div class="item-info">
                    <div class="coin">
                        <img src="https://i.postimg.cc/K3zdFKfc/coin.png" alt="Монета">
                        <p class="name">Автотап</p>
                    </div>
                    <p class="level">Ур. 1</p>
                </div>
                <p class="price" id="price-4" onclick="buyAutoTap()">500</p>
            </div>
        </div>
        <button class="back-button" onclick="goBack()">Назад</button>
    `;
}

// Покупка улучшений для энергии
function buyEnergyUpgrade() {
    let price = 500 * Math.pow(2, energyUpgradeLevel - 1);
    if (balance >= price) {
        balance -= price;
        energyUpgradeLevel++;
        maxEnergy *= 2;
        updateEnergyBar();
        displayMessage(`Запас Энергии улучшен! Уровень: ${energyUpgradeLevel}.`);
        updateLocalStorage();
    } else {
        displayMessage("Недостаточно монет для улучшения.");
    }
}

// Покупка улучшения кибертапа
function buyCyberTap() {
    let price = 500 * Math.pow(2, cyberTapLevel - 1);
    if (balance >= price) {
        balance -= price;
        cyberTapLevel++;
        clickReward++;
        updateLocalStorage();
        displayMessage(`Кибертап улучшен до уровня ${cyberTapLevel}!`);
    } else {
        displayMessage("Недостаточно монет для улучшения кибертапа.");
    }
}

// Покупка Автотапа
function buyAutoTap() {
    if (!autoTapBought && balance >= 500) {
        balance -= 500;
        autoTapBought = true;
        startAutoTap();
        document.getElementById('price-4').style.display = 'none';
        updateLocalStorage();
        displayMessage("Автотап активирован!");
    } else {
        displayMessage("Недостаточно средств для покупки автотапа.");
    }
}

// Запуск автотапа
function startAutoTap() {
    autoTapInterval = setInterval(() => {
        balance += 1;
        updateLocalStorage();
        balanceDisplay.textContent = balance;
    }, 1000);
}

// Функция для отображения сообщений
function displayMessage(message) {
    const messageBox = document.createElement('div');
    messageBox.classList.add('message-box');
    messageBox.textContent = message;
    document.body.appendChild(messageBox);
    setTimeout(() => messageBox.remove(), 3000);
}

// Функция использования перезагрузки
function useFreeItem(itemId) {
    if (usageCount < maxUsage) {
        usageCount++;
        document.querySelector('.usage-indicator').textContent = `${usageCount}/4`;
        updateLocalStorage();
    } else {
        alert('Максимальное количество использований достигнуто.');
    }
}

// Функция возврата
function goBack() {
    window.location.reload();
}

// Открытие экрана призов
function openPrizes() {
    document.body.innerHTML = ` 
    <div class="container"> 
      <div class="day" data-coins="500" data-day="1">1<br>День<br><span class="coins">500 монет</span></div> 
      <div class="day" data-coins="1000" data-day="2">2<br>День<br><span class="coins">1,000 монет</span></div> 
      <div class="day" data-coins="2500" data-day="3">3<br>День<br><span class="coins">2,500 монет</span></div> 
      <div class="day" data-coins="5000" data-day="4">4<br>День<br><span class="coins">5,000 монет</span></div> 
      <div class="day" data-coins="15000" data-day="5">5<br>День<br><span class="coins">15,000 монет</span></div> 
      <div class="day" data-coins="25000" data-day="6">6<br>День<br><span class="coins">25,000 монет</span></div> 
      <div class="day" data-coins="100000" data-day="7">7<br>День<br><span class="coins">100,000 монет</span></div> 
      <div class="day" data-coins="500000" data-day="8">8<br>День<br><span class="coins">500,000 монет</span></div> 
      <div class="day" data-coins="1000000" data-day="9">9<br>День<br><span class="coins">1,000,000 монет</span></div> 
      <div class="day" data-coins="5000000" data-day="10">10<br>День<br><span class="coins">5,000,000 монет</span></div> 
    </div> 
    <button class="reward-button" disabled>Получить награду</button> 
    <div class="message"></div> 
    <button class="back-button" onclick="goBack()">Назад</button> 
  `;

    let lastClaimedTime = localStorage.getItem('lastClaimedTime') || 0;
    const claimInterval = 86400000;
    const currentTime = Date.now();
    const passedTime = currentTime - lastClaimedTime;

    document.querySelectorAll('.day').forEach((day, index) => {
        const dayStatus = localStorage.getItem(`day${index + 1}Claimed`);
        if (dayStatus === 'true') {
            day.classList.add('claimed');
            day.querySelector('.coins').textContent = 'Возвращайся завтра';
        }
    });

    if (passedTime < claimInterval) {
        startCountdown(claimInterval - passedTime);
    } else {
        enableNextDay();
    }

    function enableNextDay() {
        const claimedDays = document.querySelectorAll('.day.claimed').length;
        const nextDay = document.querySelector(`.day[data-day="${claimedDays + 1}"]`);
        if (nextDay) {
            nextDay.classList.add('active');
            document.querySelector('.reward-button').disabled = false;
            document.querySelector('.reward-button').addEventListener('click', () => {
                handleDayClick(nextDay);
            });
        }
    }

    function handleDayClick(day) {
        const coins = parseInt(day.dataset.coins);
        balance += coins;
        localStorage.setItem('balance', balance);
        document.getElementById('balance').textContent = balance;
        const currentTime = Date.now();
        localStorage.setItem(`day${day.dataset.day}Claimed`, 'true');
        localStorage.setItem('lastClaimedTime', currentTime);
        alert(`Вы получили ${coins.toLocaleString()} монет.`);
        day.classList.add('claimed');
        day.classList.remove('active');
        day.querySelector('.coins').textContent = 'Возвращайся завтра';
        document.querySelector('.reward-button').disabled = true;
        enableNextDay();
    }

    function startCountdown(timeLeft) {
        const countdownInterval = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                document.querySelector('.message').textContent = 'Награда доступна!';
                enableNextDay();
                return;
            }

            const hours = Math.floor((timeLeft / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((timeLeft / (1000 * 60)) % 60);
            const seconds = Math.floor((timeLeft / 1000) % 60);

            document.querySelector('.message').textContent = `До следующей награды: ${hours}ч ${minutes}м ${seconds}с`;

            timeLeft -= 1000;
        }, 1000);
    }
}

// Админ панель
let isAdmin = false;
let adminPassword = "admin123";

function toggleAdminMenu() {
    const menu = document.getElementById('adminMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

function handleLogin(event) {
    event.preventDefault();
    const inputPassword = document.getElementById('adminPassword').value;
    if (inputPassword === adminPassword) {
        isAdmin = true;
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('adminActions').style.display = 'block';
        alert('Успешная авторизация!');
    } else {
        alert('Неверный пароль!');
    }
}

function addBalance() {
    if (isAdmin) {
        const amount = prompt("Введите сумму для пополнения баланса:");
        if (amount && !isNaN(amount)) {
            balance += parseInt(amount);
            updateLocalStorage();
            alert(`Ваш баланс пополнен на ${amount} монет.`);
            document.getElementById('balance').textContent = balance;
        } else {
            alert('Неверное значение.');
        }
    } else {
        alert('Вы не авторизованы как администратор.');
    }
}

// Магазин скинов
function openSkinsShop() {
    document.body.classList.add('shop');
    document.body.innerHTML = `
    <h2 class="section-title">Магазин Скинов</h2>
    <div class="menu">
        <div class="item" id="item-default">
            <img src="https://i.postimg.cc/ns1FhD4q/corgi-image.jpg" alt="Стандартный" class="item-image">
            <div class="item-info">
                <div class="coin">
                    <p class="name">Стандартный</p>
                </div>
                <p class="level">Бесплатно</p>
            </div>
            <button class="wear-button" onclick="wearSkin('default')">Одеть</button>
        </div>

        <div class="item" id="item-skin1">
            <img src="https://i.postimg.cc/YCP0Xn7N/skin1.png" alt="Скин 1" class="item-image">
            <div class="item-info">
                <div class="coin">
                    <p class="name">Скин 1</p>
                </div>
                <p class="level">Цена: 500 монет</p>
            </div>
            ${skinsBought.includes('skin1') ? 
                '<button class="wear-button" onclick="wearSkin(\'skin1\')">Одеть</button>' : 
                '<p class="price" id="price-1" onclick="buySkin(\'skin1\', 500)">500</p>'}
        </div>

        <div class="item" id="item-skin2">
            <img src="https://i.postimg.cc/YCP0Xn7N/skin2.png" alt="Скин 2" class="item-image">
            <div class="item-info">
                <div class="coin">
                    <p class="name">Скин 2</p>
                </div>
                <p class="level">Цена: 1000 монет</p>
            </div>
            ${skinsBought.includes('skin2') ? 
                '<button class="wear-button" onclick="wearSkin(\'skin2\')">Одеть</button>' : 
                '<p class="price" id="price-2" onclick="buySkin(\'skin2\', 1000)">1000</p>'}
        </div>

        <div class="item" id="item-skin3">
            <img src="https://i.postimg.cc/YCP0Xn7N/skin3.png" alt="Скин 3" class="item-image">
            <div class="item-info">
                <div class="coin">
                    <p class="name">Скин 3</p>
                </div>
                <p class="level">Цена: 1500 монет</p>
            </div>
            ${skinsBought.includes('skin3') ? 
                '<button class="wear-button" onclick="wearSkin(\'skin3\')">Одеть</button>' : 
                '<p class="price" id="price-3" onclick="buySkin(\'skin3\', 1500)">1500</p>'}
        </div>

        <div class="item" id="item-skin4">
            <img src="https://i.postimg.cc/YCP0Xn7N/skin4.png" alt="Скин 4" class="item-image">
            <div class="item-info">
                <div class="coin">
                    <p class="name">Скин 4</p>
                </div>
                <p class="level">Цена: 2000 монет</p>
            </div>
            ${skinsBought.includes('skin4') ? 
                '<button class="wear-button" onclick="wearSkin(\'skin4\')">Одеть</button>' : 
                '<p class="price" id="price-4" onclick="buySkin(\'skin4\', 2000)">2000</p>'}
        </div>

        <div class="item" id="item-skin5">
            <img src="https://i.postimg.cc/YCP0Xn7N/skin5.png" alt="Скин 5" class="item-image">
            <div class="item-info">
                <div class="coin">
                    <p class="name">Скин 5</p>
                </div>
                <p class="level">Цена: 2500 монет</p>
            </div>
            ${skinsBought.includes('skin5') ? 
                '<button class="wear-button" onclick="wearSkin(\'skin5\')">Одеть</button>' : 
                '<p class="price" id="price-5" onclick="buySkin(\'skin5\', 2500)">2500</p>'}
        </div>
    </div>
    <button class="back-button" onclick="goBack()">Назад</button>
    `;
}

// Покупка скина
function buySkin(skinName, price) {
    if (balance >= price && !skinsBought.includes(skinName)) {
        balance -= price;
        skinsBought.push(skinName);
        updateLocalStorage();
        displayMessage(`${skinName} куплен!`);
        openSkinsShop(); // Обновляем магазин для отображения статуса покупки
    } else {
        displayMessage("Недостаточно монет или скин уже куплен.");
    }
}

// Ношение скина
function wearSkin(skinName) {
    currentSkin = skinName;
    document.getElementById('corgi').src = getSkinImage(currentSkin); // Меняем изображение корги
    localStorage.setItem('currentSkin', skinName); // Сохраняем текущий скин
    displayMessage(`${skinName} одет!`);
}

// Получение URL для изображения скина
function getSkinImage(skinName) {
    switch(skinName) {
        case 'skin1': return 'https://i.postimg.cc/ns1FhD4q/corgi-image.jpg';
        case 'skin2': return 'https://i.postimg.cc/YCP0Xn7N/skin2-corgi.png';
        case 'skin3': return 'https://i.postimg.cc/YCP0Xn7N/skin3-corgi.png';
        case 'skin4': return 'https://i.postimg.cc/YCP0Xn7N/skin4-corgi.png';
        case 'skin5': return 'https://i.postimg.cc/YCP0Xn7N/skin5-corgi.png';
        default: return 'https://i.postimg.cc/ns1FhD4q/corgi-image.jpg';
    }
}

// Инициализация отображения значений из localStorage
balanceDisplay.textContent = balance;
updateEnergyBar();
if (autoTapBought) {
    startAutoTap();
}