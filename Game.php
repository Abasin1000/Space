<?php
// Importeer de Spaceship-klasse om toegang te krijgen tot ruimteschipfunctionaliteiten
include_once 'Spaceship.php';

// Definieer de Game-klasse
class Game
{
    public Spaceship $player; // Speler ruimteschip
    public Spaceship $enemy; // Vijand ruimteschip
    public array $playerPosition = ['x' => 0, 'y' => 0]; // Beginpositie van de speler

    // Constructor om een nieuw spelobject te maken
    public function __construct()
    {
        $this->player = new Spaceship(); // Maak een nieuw ruimteschip voor de speler
        $this->enemy = new Spaceship(); // Maak een nieuw ruimteschip voor de vijand
        // De positie is al ingesteld in de eigenschap hierboven
    }

    // Functie voor de speler om te schieten op de vijand
    public function playerShoot()
    {
        $damage = $this->player->shoot(); // Roep de schietmethode van de speler aan
        $this->enemy->hit($damage); // Breng schade toe aan de vijand
    }

    // Functie voor de speler om zich te verplaatsen
    public function playerMove()
    {
        $this->player->move(); // Verbruik brandstof voor de beweging
        
        // Verplaats de speler met 10 pixels naar rechts
        $this->playerPosition['x'] += 10;
    }
}
