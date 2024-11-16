<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('client.{clientId}', function ($user, $clientId) {
    return (int) $user->id === (int) $clientId;
});
