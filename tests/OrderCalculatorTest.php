<?php

namespace Tests;

use App\OrderCalculator;
use App\OrderCalculatorFixed;
use PHPUnit\Framework\TestCase;

class OrderCalculatorTest extends TestCase //Immer TestCase mit deiner Klasse erweitern
{
    private OrderCalculatorFixed $calculator;


    /**
     * wir verlegen den arrange part in die setup methode, die vor jedem test durchlaufen wird
     * @return void
     */
    protected function setUp(): void
    {

        $this->calculator = new OrderCalculatorFixed();
    }

    /*
    Was zuerst testen?

    Was wird am häufigsten benutzt? → Happy Paths
    Was kann am meisten Schaden anrichten? → Crashes, Geld
    Was haben wir im Code-Review gefunden? → Bekannte Bugs
    Was ist selten aber möglich? → Edge Cases


    Nicht starten mit:

    Hilfsmethoden
    Formatierung
    Sehr seltene Edge Cases
    */






}