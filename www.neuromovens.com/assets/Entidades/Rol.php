<?php

namespace Entidades;

enum Rol
{
    case jefe;
    case administrador;
    case visitante;

    public static function tryFrom(mixed $rol): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $rol) {
                return $case;
            }
        }
        return null;
    }


}
