<?php

namespace Cake\Attributes\Enums;

use ReflectionClass;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
    case OPTIONS = 'OPTIONS';
    case HEAD = 'HEAD';

    public static function verbs(): array
    {
        $reflection = new ReflectionClass(self::class);
        $cases = $reflection->getConstants();

        $verbs = [];
        array_walk($cases, static function($value) use (&$verbs) {
            $verbs[] = $value->value;
        });

        return $verbs;
    }
}
