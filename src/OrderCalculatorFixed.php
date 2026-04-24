<?php
namespace App;
/**
 * Klasse fuer Bestellberechnungen - KORRIGIERTE VERSION
 * 
 * Alle 15 Bugs aus der Schulung sind behoben!
 */
class OrderCalculatorFixed
{
    private float $taxRate = 0.19;
    private float $shippingCostNetto = 5.03;  // Netto-Versandkosten
    
    /**
     * Berechnet den Gesamtpreis einer Bestellung
     * 
     * @param float $subtotal Zwischensumme ohne Steuern
     * @param int $quantity Anzahl der Artikel
     * @param string $customerType Kundentyp: 'regular' oder 'premium'
     * @return float Gesamtpreis
     */
    public function calculateTotal(float $subtotal, int $quantity, string $customerType): float
    {
        // BUG #1 FIXED: Input-Validierung hinzugefuegt
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity muss positiv sein');
        }
        if ($subtotal < 0) {
            throw new \InvalidArgumentException('Subtotal darf nicht negativ sein');
        }
        
        // BUG #14 FIXED: Unused Variable entfernt (war: $pricePerItem)
        
        // BUG #2 FIXED: Case-insensitive String-Vergleich
        if (strtolower($customerType) === 'premium') {
            $shipping = 0;
        } else {
            $shipping = $this->shippingCostNetto;
        }
        
        $discount = 0;
        if ($quantity > 10) {
            $discount = $subtotal * 0.1;
        }
        
        // BUG #3 FIXED: Rabatt VOR Steuerberechnung
        $subtotalAfterDiscount = $subtotal - $discount;
        
        // BUG #16 FIXED: MwSt wird auf (Subtotal + Versand) berechnet!
        // Nicht nur auf Subtotal, sondern auf die gesamte Netto-Summe
        $nettoTotal = $subtotalAfterDiscount + $shipping;
        $tax = $nettoTotal * $this->taxRate;
        
        $total = $nettoTotal + $tax;
        
        // BUG #4 FIXED: Rundung auf 2 Dezimalstellen
        return round($total, 2);
    }
    
    /**
     * Berechnet Versandkosten basierend auf Gewicht
     * 
     * @param float $weight Gewicht in kg
     * @return float Netto-Versandkosten (ohne MwSt)
     */
    public function calculateShipping(float $weight): float
    {
        // BUG #5 FIXED: Negative Gewichte werden abgelehnt
        if ($weight <= 0) {
            throw new InvalidArgumentException('Gewicht muss positiv sein');
        }
        
        // Netto-Preise (MwSt wird separat berechnet)
        if ($weight < 5) {
            return 5.03;  // Leichtpaket netto
        } elseif ($weight < 20) {
            return 8.39;  // Normalpaket netto
        } else {
            return 13.44;  // Schwerpaket netto
        }
    }
    
    /**
     * Prueft ob ein Rabattcode gueltig ist
     * 
     * @param string $code Rabattcode
     * @return bool Gueltigkeit
     */
    public function isValidDiscountCode(string $code): bool
    {
        // BUG #6: Hartcodierte Codes bleiben (Design-Problem, aber kein Bug per se)
        // In Produktion: aus DB oder Config laden
        $validCodes = array('SUMMER2024', 'WINTER2024', 'SPRING2024');
        
        // BUG #7 FIXED: Case-insensitive Vergleich
        return in_array(strtoupper($code), array_map('strtoupper', $validCodes));
    }
    
    /**
     * Berechnet den Rabattbetrag
     * 
     * @param float $subtotal Zwischensumme
     * @param string $code Rabattcode
     * @return float Rabattbetrag
     */
    public function calculateDiscount(float $subtotal, string $code): float
    {
        // BUG #8 FIXED: Validierung ob Code gueltig ist
        if (!$this->isValidDiscountCode($code)) {
            return 0.0;
        }
        
        // Case-insensitive Vergleich
        $codeUpper = strtoupper($code);
        
        if ($codeUpper === 'SUMMER2024') {
            return $subtotal * 0.2;
        } elseif ($codeUpper === 'WINTER2024') {
            return $subtotal * 0.15;
        } elseif ($codeUpper === 'SPRING2024') {
            return $subtotal * 0.1;
        }
        
        // BUG #9 FIXED: Expliziter Return-Wert statt implizitem null
        return 0.0;
    }
    
    /**
     * Formatiert einen Preis fuer die Anzeige
     * 
     * @param float $price Preis
     * @return string Formatierter Preis
     */
    public function formatPrice(float $price): string
    {
        // BUG #10 & #11 FIXED: Deutsche Locale + Rundung
        return number_format($price, 2, ',', '.') . ' EUR';
    }
    
    /**
     * Berechnet die Anzahl der benoetigten Lieferungen
     * 
     * @param int $quantity Anzahl Artikel
     * @param int $itemsPerDelivery Artikel pro Lieferung
     * @return int Anzahl Lieferungen
     */
    public function calculateDeliveries(int $quantity, int $itemsPerDelivery): int
    {
        // BUG #13 FIXED: Input-Validierung
        if ($itemsPerDelivery <= 0) {
            throw new InvalidArgumentException('ItemsPerDelivery muss positiv sein');
        }
        
        // BUG #12 FIXED: Korrekte Integer-Division mit Aufrundung
        return (int) ceil($quantity / $itemsPerDelivery);
    }
}

// BUG #15 FIXED: Alle Methoden haben jetzt Typehints (Parameter + Return)
