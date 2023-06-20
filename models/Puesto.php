<?php
class Puesto {
    public const PUESTO_BARTENDER = 'bartender'; 
    public const PUESTO_PASTELERO = 'pastelero'; 
    public const PUESTO_COCINERO = 'cocinero';
    public const PUESTO_MOZO = 'mozo';
    public const PUESTO_CERVECERO = 'cervecero';
    public const PUESTO_SOCIO = 'socio';

    public static function getPuesto($puestoString) {
        // Verificar qué constante corresponde al string ingresado y retornarla
        switch (strtolower($puestoString)) {
            case 'bartender':
                return self::PUESTO_BARTENDER;
            case 'pastelero':
                return self::PUESTO_PASTELERO;
            case 'cocinero':
                return self::PUESTO_COCINERO;
            case 'mozo':
                return self::PUESTO_MOZO;
            case 'cervecero':
                return self::PUESTO_CERVECERO;
            case 'socio':
                return self::PUESTO_SOCIO;
            default:
                return null; 
        }
    }

}