<?php

// Definieer de Spaceship-klasse
class Spaceship
{
    // Eigenschappen van het ruimteschip
    public bool $isAlive; // Of het ruimteschip nog actief is
    public int $ammo; // Munitievoorraad
    public int $fuel; // Brandstofvoorraad
    public int $hitPoints; // Gezondheidspunten van het ruimteschip

    // Constructor om het ruimteschip te maken met standaardwaarden
    public function __construct($ammo = 100, $fuel = 100, $hitPoints = 100)
    {
        $this->ammo = $ammo;
        $this->fuel = $fuel;
        $this->hitPoints = $hitPoints;
        $this->isAlive = true; // Het ruimteschip begint als "levend"
    }

    // Methode om te schieten; verlaagt munitie en geeft schade terug
    public function shoot(): int
    {
        $shotCost = 5; // Kosten per schot in munitie
        $damage = 10; // Schade die het schot toebrengt
        if ($this->ammo >= $shotCost) { // Controleer of er genoeg munitie is
            $this->ammo -= $shotCost; // Verminder munitie na schot
            return $damage; // Schade wordt teruggegeven
        }
        return 0; // Geen schade als er onvoldoende munitie is
    }

    // Methode om schade aan het ruimteschip toe te brengen
    public function hit(int $damage)
    {
        $this->hitPoints -= $damage; // Verminder gezondheidspunten
        if ($this->hitPoints <= 0) { // Controleer of het ruimteschip vernietigd is
            $this->isAlive = false; // Markeer als niet meer levend
        }
    }

    // Methode om het ruimteschip te laten bewegen; verbruikt brandstof
    public function move()
    {
        $fuelUsage = 10; // Brandstofkosten per beweging
        if ($this->fuel >= $fuelUsage) { // Controleer of er genoeg brandstof is
            $this->fuel -= $fuelUsage; // Verminder brandstof
        }
    }
}

