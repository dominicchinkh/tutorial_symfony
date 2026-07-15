<?php

namespace App\Extension;

use App\Dto\Notification;
use Symfony\UX\LiveComponent\Hydration\HydrationExtensionInterface;

class NotificationHydration implements HydrationExtensionInterface
{
    public function supports(string $className): bool
    {
        return is_subclass_of($className, Notification::class);
    }

    public function hydrate(mixed $value, string $className): ?object
    {
        return new Notification($value['message'], $value['type']);
    }

    public function dehydrate(object $object): mixed
    {
        return [
            'message' => $object->message,
            'type'    => $object->type,
        ];
    }
}
