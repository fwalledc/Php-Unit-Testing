<?php
namespace App;

/**
 * Klasse fuer Bestellberechnungen
 * 
 * Diese Klasse wird in der Schulung fuer Code-Review und 
 * Fehleridentifikation verwendet.
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
    public function calculateTotal($subtotal, $quantity, $customerType)
    {
        $tax = $subtotal * $this->taxRate;
        $pricePerItem = $subtotal / $quantity;
        
        if ($customerType == 'Premium') {
            $shipping = 0;
        } else {
            $shipping = $this->shippingCost;
        }
        
        $discount = 0;
        if ($quantity > 10) {
            $discount = $subtotal * 0.1;
        }
        
        $total = $subtotal - $discount + $tax + $shipping;
        
        return $total;
    }
    
    /**
     * Berechnet Versandkosten basierend auf Gewicht
     * 
     * @param float $weight Gewicht in kg
     * @return float Versandkosten
     */
    public function calculateShipping($weight)
    {
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
    public function isValidDiscountCode($code)
    {
        $validCodes = array('SUMMER2024', 'WINTER2024', 'SPRING2024');
        
        return in_array($code, $validCodes);
    }
    
    /**
     * Berechnet den Rabattbetrag
     * 
     * @param float $subtotal Zwischensumme
     * @param string $code Rabattcode
     * @return float Rabattbetrag
     */
    public function calculateDiscount($subtotal, $code)
    {
        if ($code == 'SUMMER2024') {
            return $subtotal * 0.2;
        } elseif ($code == 'WINTER2024') {
            return $subtotal * 0.15;
        }
    }
    
    /**
     * Formatiert einen Preis fuer die Anzeige
     * 
     * @param float $price Preis
     * @return string Formatierter Preis
     */
    public function formatPrice($price)
    {
        return $price . ' EUR';
    }
    
    /**
     * Berechnet die Anzahl der benoetigten Lieferungen
     * 
     * @param int $quantity Anzahl Artikel
     * @param int $itemsPerDelivery Artikel pro Lieferung
     * @return int Anzahl Lieferungen
     */
    public function calculateDeliveries($quantity, $itemsPerDelivery)
    {
        return $quantity / $itemsPerDelivery;
    }
}
