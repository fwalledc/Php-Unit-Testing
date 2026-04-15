<?php

/**
 * OrderCalculator - TRAINER-VERSION MIT BUG-KOMMENTAREN
 * 
 * Diese Version enthaelt alle 15 Bugs mit Kommentaren fuer den Trainer.
 * NICHT an Teilnehmer verteilen!
 * 
 * Fuer Teilnehmer: OrderCalculator.php (ohne Kommentare)
 */
class OrderCalculator
{
    private $taxRate = 0.19;
    private $shippingCost = 5.99;
    
    /**
     * Berechnet den Gesamtpreis einer Bestellung
     * 
     * @param float $subtotal Zwischensumme ohne Steuern
     * @param int $quantity Anzahl der Artikel
     * @param string $customerType Kundentyp: 'regular' oder 'premium'
     * @return float Gesamtpreis
     */
    // BUG #15: Fehlende Typehints in Methoden-Signatur
    // Sollte sein: public function calculateTotal(float $subtotal, int $quantity, string $customerType): float
    // PHP erlaubt ohne Typehints alles - keine Type-Safety!
    public function calculateTotal(float $subtotal,int $quantity,string $customerType) : float
    {
        // BUG #1: Keine Input-Validierung + Division durch Null
        // Was wenn $subtotal = null, negativ, oder 0?
        // Was wenn $quantity = 0 -> Fatal Error: Division by zero
        // Loesung: if ($quantity <= 0) throw new InvalidArgumentException();
        
        $tax = $subtotal * $this->taxRate;
        
        // BUG #14: Unused Variable (Dead Code)
        // $pricePerItem wird berechnet aber NIRGENDWO verwendet!
        // Warum berechnen? Sollte entweder genutzt oder entfernt werden
        $pricePerItem = $subtotal / $quantity;
        
        // BUG #2: Case-sensitiver String-Vergleich
        // 'Premium' != 'premium' != 'PREMIUM'
        // Loesung: strtolower($customerType) === 'premium'
        if ($customerType == 'Premium') {
            $shipping = 0;
        } else {
            $shipping = $this->shippingCost;
        }
        
        $discount = 0;
        if ($quantity > 10) {
            $discount = $subtotal * 0.1;
        }
        
        // BUG #3: Falsche Berechnungsreihenfolge
        // Rabatt wird NACH Steuerberechnung abgezogen
        // Korrekt waere: erst Rabatt, dann Steuer auf reduzierten Betrag berechnen
        // ODER: Steuer auf Bruttobetrag (je nach Geschaeftslogik)
        $total = $subtotal - $discount + $tax + $shipping;
        
        // BUG #4: Floating-Point Ungenauigkeit
        // $total koennte 19.999999999998 sein
        // Loesung: return round($total, 2);
        return $total;
    }
    
    /**
     * Berechnet Versandkosten basierend auf Gewicht
     * 
     * @param float $weight Gewicht in kg
     * @return float Versandkosten
     */
    // BUG #15: Fehlende Typehints
    // Sollte sein: public function calculateShipping(float $weight): float
    public function calculateShipping($weight)
    {
        // BUG #5: Negative Gewichte werden akzeptiert
        // Bei $weight = -100 wird 5.99 EUR zurueckgegeben
        // Loesung: if ($weight <= 0) throw new InvalidArgumentException();
        
        if ($weight < 5) {
            return 5.99;
        } elseif ($weight < 20) {
            return 9.99;
        } else {
            return 15.99;
        }
    }
    
    /**
     * Prueft ob ein Rabattcode gueltig ist
     * 
     * @param string $code Rabattcode
     * @return bool Gueltigkeit
     */
    // BUG #15: Fehlende Typehints
    // Sollte sein: public function isValidDiscountCode(string $code): bool
    public function isValidDiscountCode($code)
    {
        // BUG #6: Hartcodierte Rabattcodes
        // Nicht konfigurierbar, muessen bei Aenderung im Code angepasst werden
        // Loesung: Codes aus DB oder Config laden
        $validCodes = array('SUMMER2024', 'WINTER2024', 'SPRING2024');
        
        // BUG #7: Case-sensitive Rabattcodes
        // 'summer2024' wird als ungueltig betrachtet
        // Loesung: in_array(strtoupper($code), array_map('strtoupper', $validCodes))
        return in_array($code, $validCodes);
    }
    
    /**
     * Berechnet den Rabattbetrag
     * 
     * @param float $subtotal Zwischensumme
     * @param string $code Rabattcode
     * @return float Rabattbetrag
     */
    // BUG #15: Fehlende Typehints
    // Sollte sein: public function calculateDiscount(float $subtotal, string $code): float
    public function calculateDiscount($subtotal, $code)
    {
        // BUG #8: Keine Validierung ob Code gueltig ist
        // Die Methode prueft nicht, ob isValidDiscountCode($code) true ist
        // Inkonsistenz: isValidDiscountCode() kennt 3 Codes, 
        // aber calculateDiscount() kennt nur 2
        
        if ($code == 'SUMMER2024') {
            return $subtotal * 0.2;
        } elseif ($code == 'WINTER2024') {
            return $subtotal * 0.15;
        }
        
        // BUG #9: Fehlender Return-Wert
        // Wenn kein Code matched wird implizit 'return null;' ausgefuehrt
        // PHPDoc sagt '@return float' aber null ist kein float
        // Bei 'SPRING2024' wird null zurueckgegeben
        // Loesung: return 0.0; am Ende
    }
    
    /**
     * Formatiert einen Preis fuer die Anzeige
     * 
     * @param float $price Preis
     * @return string Formatierter Preis
     */
    // BUG #15: Fehlende Typehints
    // Sollte sein: public function formatPrice(float $price): string
    public function formatPrice($price)
    {
        // BUG #10: Deutsche Locale nicht beruecksichtigt
        // In Deutschland erwarten wir: "19,99 EUR"
        // Nicht: "19.99 EUR"
        // Loesung: number_format($price, 2, ',', '.') . ' EUR'
        
        // BUG #11: Keine Rundung auf 2 Dezimalstellen
        // Floating-Point Zahlen koennten viele Nachkommastellen haben
        // z.B. "19.999999999 EUR"
        // Loesung: number_format() rundet automatisch
        
        return $price . ' EUR';
    }
    
    /**
     * Berechnet die Anzahl der benoetigten Lieferungen
     * 
     * @param int $quantity Anzahl Artikel
     * @param int $itemsPerDelivery Artikel pro Lieferung
     * @return int Anzahl Lieferungen
     */
    // BUG #15: Fehlende Typehints
    // Sollte sein: public function calculateDeliveries(int $quantity, int $itemsPerDelivery): int
    public function calculateDeliveries($quantity, $itemsPerDelivery)
    {
        // BUG #12: Integer-Division gibt Float zurueck
        // PHPDoc sagt '@return int' aber Methode gibt float zurueck
        // 7 / 3 = 2.333... (nicht 3 Lieferungen)
        // Loesung: return (int) ceil($quantity / $itemsPerDelivery);
        
        // BUG #13: Division durch Null
        // Wenn $itemsPerDelivery = 0 -> Fatal Error
        // Loesung: Input-Validierung
        
        return $quantity / $itemsPerDelivery;
    }
}

/*
 * ZUSAMMENFASSUNG DER 15 BUGS:
 * 
 * Input-Validierung (3):
 * #1  - Division durch Null in calculateTotal() ($quantity = 0)
 * #5  - Negative Gewichte erlaubt
 * #13 - Division durch Null in calculateDeliveries() ($itemsPerDelivery = 0)
 * 
 * String-Handling (2):
 * #2  - Case-sensitiver customerType Vergleich ('Premium' vs 'premium')
 * #7  - Case-sensitive Rabattcodes
 * 
 * Business-Logic (1):
 * #3  - Falsche Berechnungsreihenfolge (Rabatt/Steuer)
 * 
 * Technische Details (4):
 * #4  - Floating-Point Ungenauigkeit
 * #10 - Deutsche Locale nicht beruecksichtigt
 * #11 - Keine Rundung auf 2 Dezimalstellen
 * #12 - Integer-Division gibt Float zurueck
 * 
 * Design-Probleme/Code Quality (5):
 * #6  - Hartcodierte Rabattcodes
 * #8  - Keine Validierung in calculateDiscount()
 * #9  - Fehlender Return-Wert (null statt 0)
 * #14 - Unused Variable (Dead Code)
 * #15 - Fehlende Typehints in ALLEN Methoden (PHP 7.0+ Feature)
 * 
 * ERWARTUNG FUER DIE SCHULUNG:
 * - Die Gruppe wird 6-10 Bugs finden (das ist gut!)
 * - Bug #15 (Typehints) werden viele finden - ist offensichtlich
 * - Bug #14 (Unused Variable) ist leicht zu uebersehen
 * - Manche Bugs (#4, #10, #11) sind subtil
 * - Ziel ist NICHT alle zu finden, sondern zu lernen:
 *   -> Code-Review ist wertvoll
 *   -> Aber nicht vollstaendig
 *   -> Tests + Static Analysis Tools helfen systematisch zu pruefen
 */
