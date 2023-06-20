<?php
require_once './models/Producto.php';
class Sector {
    public $colaProductosPorPreparar;
    public $sector;
    public $empleadosActivos;

    public const SECTOR_BARRA_TRAGOS_VINOS = 'barra_tragos_vinos';
    public const SECTOR_COCINA = 'cocina';
    public const SECTOR_BARRA_CERVEZA = 'barra_cerveza';
    public const SECTOR_CANDYBAR = 'candybar';
    public const SECTOR_GENERAL_SOCIOS = 'socio';
    public const SECTOR_SALON = 'salon';


    public static function getSector($sectorString) {
        switch (strtolower($sectorString)) {
            case 'barra_tragos_vinos':
                return self::SECTOR_BARRA_TRAGOS_VINOS;
            case 'cocina':
                return self::SECTOR_COCINA;
            case 'salon':
                return self::SECTOR_COCINA;
            case 'barra_cerveza':
                return self::SECTOR_BARRA_CERVEZA;
            case 'candybar':
                return self::SECTOR_CANDYBAR;
            case 'socio':
                return self::SECTOR_GENERAL_SOCIOS;
            default:
                return null; 
        }
    }

    public static function CalcularTiempoDePreparacion($pedido){
        $empleados=Usuario::obtenerTodos();
        $cantidadEmpleadosEnSector=0;
        $productoAPreparar =Producto::obtenerProducto($pedido->id_producto);
        foreach($empleados as $empleado){
            if($empleado->sector==$productoAPreparar->sectorDePreparacion){
                $cantidadEmpleadosEnSector++;
            }
        }
        $tiempoBruto = $pedido->cantidadProducto*$productoAPreparar->tiempoPreparacion;
        $tiempoFinal = $tiempoBruto/$cantidadEmpleadosEnSector;
        return $tiempoFinal;
    }
}