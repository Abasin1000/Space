<?php
// Importeer de benodigde klassen en start een sessie
include_once 'Spaceship.php';
include_once 'Game.php';
session_start();

// Controleer of er al een spelobject bestaat in de sessie
if (!isset($_SESSION['game'])) {
    $_SESSION['game'] = new Game(); // Maak een nieuw spelobject als dat niet het geval is
}
$game = $_SESSION['game']; // Haal het spelobject op uit de sessie

// Verwerk de actie van de gebruiker (schieten of bewegen)
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'shoot') { // Als de actie "schieten" is
        $game->playerShoot(); // Roep de shoot-methode aan
    } elseif ($action === 'move') { // Als de actie "bewegen" is
        $game->playerMove(); // Roep de move-methode aan
    }
}

// Verwerk de score
if (!isset($_SESSION['score'])) { 
    $_SESSION['score'] = 0; // Start met een score van 0 als er nog geen score is
}
// Als de vijand geen hitpoints meer heeft, verhoog de score en start een nieuw spel
if ($game->enemy->hitPoints <= 0) {
    $_SESSION['score'] += 10; // Voeg 10 punten toe aan de score
    $_SESSION['game'] = new Game(); // Start een nieuw spel
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spaceship Game</title>
    <style>
        /* Basis styling */
        body {
            background-image: url('img/space.png');
            background-repeat: no-repeat;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .scoreboard, .game-info {
            font-size: 18px;
            margin: 20px auto;
            padding: 10px;
            width: 80%;
            max-width: 400px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 8px;
        }

        .spaceship {
            width: 100px;
            position: relative;
        }

        #playerSpaceship {
            position: absolute;
            left: 100px; /* Beginpositie van de speler */
            top: 300px;
        }

        #enemySpaceship {
            position: absolute;
            left: 500px; /* Positie van de vijand */
            top: 300px;
        }

        .controls {
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        /* Kogel styling */
        .bullet {
            width: 10px;
            height: 10px;
            background-color: yellow;
            position: absolute;
            top: 320px; /* Beginpositie op dezelfde hoogte als speler */
            left: 120px;
            border-radius: 50%;
            display: none; /* Verberg kogel standaard */
            transition: transform 0.5s linear;
        }

        

img{
    background-color: transparent;
    background: none;
}
    </style>
</head>
<body>

<h1>Spaceship Game</h1>

<!-- Scorebord -->
<div class="scoreboard">
    <h2>Scoreboard</h2>
    <p>Current Score: <span id="score"><?php echo $_SESSION['score']; ?></span></p>
</div>

<!-- Informatie over het spel -->
<div class="game-info">
    <div>
        <h2>Player</h2>
        <img src="img/ship2.png" alt="Player Spaceship" id="playerSpaceship" class="spaceship">
        <p>Ammo: <?php echo $game->player->ammo; ?></p> <!-- Toon het aantal kogels -->
        <p>HP: <?php echo $game->player->hitPoints; ?></p> <!-- Toon de gezondheid van de speler -->
    </div>

    <div>
        <h2>Enemy</h2>
        <img src="img/ship.jpg" alt="Enemy Spaceship" id="enemySpaceship" class="spaceship">
        <p>Ammo: <?php echo $game->enemy->ammo; ?></p> <!-- Toon het aantal kogels van de vijand -->
        <p>HP: <?php echo $game->enemy->hitPoints; ?></p> <!-- Toon de gezondheid van de vijand -->
    </div>
</div>

<!-- Kogel element -->
<div id="bullet" class="bullet"></div>

<!-- Actieknoppen voor de speler -->
<div class="controls">
    <form method="post" id="actionForm">
        <button type="button" onclick="submitAction('shoot')">Shoot</button>
        <button type="button" onclick="submitAction('move')">Move</button>
    </form>
</div>

<script>
// Functie om een actie naar de server te sturen
function submitAction(action) {
    const form = document.getElementById('actionForm');
    const formData = new FormData(form); // Maak een nieuw formulier object
    formData.append('action', action); // Voeg de actie toe (schieten of bewegen)

    // Stuur de actie naar de server via een fetch-aanroep
    fetch('', { method: 'POST', body: formData })
        .then(response => response.text())
        .then(() => {
            if (action === 'move') { // Controleer of de actie "bewegen" is
                movePlayer(); // Roep de functie aan om de speler te bewegen
            } else if (action === 'shoot') { // Als de actie "schieten" is
                shootBullet(); // Roep de functie aan om een schot te simuleren
            }
            location.reload(); // Herlaad de pagina om nieuwe gegevens op te halen
        });
}

// Functie om het spelersschip te verplaatsen
function movePlayer() {
    let playerShip = document.getElementById('playerSpaceship');
    let currentX = parseInt(playerShip.getAttribute('data-x') || 100); // Huidige positie ophalen
    currentX += 10; // Verhoog de positie met 10px
    playerShip.style.left = currentX + 'px'; // Zet de nieuwe positie
    playerShip.setAttribute('data-x', currentX); // Sla de nieuwe positie op
}

// Functie om een kogel af te schieten
function shootBullet() {
    const bullet = document.getElementById('bullet');
    const enemyShip = document.getElementById('enemySpaceship');

    // Zet de kogelpositie en maak deze zichtbaar
    bullet.style.display = 'block';
    bullet.style.left = '120px'; // Startpositie van de kogel

    // Bereken de afstand naar de vijand
    const enemyX = parseInt(getComputedStyle(enemyShip).left);
    const bulletDistance = enemyX - 120; // Verschil tussen kogel en vijand

    // Animeren van de kogel richting de vijand
    bullet.style.transform = `translateX(${bulletDistance}px)`;

    // Na de animatie, verberg de kogel en reset de positie
    setTimeout(() => {
        bullet.style.display = 'none';
        bullet.style.transform = 'translateX(0)'; // Reset voor het volgende schot
    }, 500); // Wachttijd moet overeenkomen met de CSS-transitie
}
</script>

</body>
</html>
