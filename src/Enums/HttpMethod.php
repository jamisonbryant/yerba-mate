<?php
declare(strict_types=1);

namespace CakeAttributes\Enums;

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

    /**
     * @return array
     */
    public static function verbs(): array
    {
        $reflection = new ReflectionClass(self::class);
        $cases = $reflection->getConstants();

        $verbs = [];
        array_walk($cases, function (mixed $value) use (&$verbs): void {
            $verbs[] = $value->value;
        });

        return $verbs;
    }
}
