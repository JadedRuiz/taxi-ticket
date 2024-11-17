<?php

Broadcast::channel('client.{clientId}', function ($user, $clientId) {
    // Obtener el usuario desde la sesión
    $user = session('user');
    Log::info('Usuario en sesión', ['user' => $user]);
    // Verificar si el usuario está en la sesión y si tiene acceso al canal
    if ($user && (int) $user['caja_id'] === (int) $clientId) {
        return true; // El usuario está autorizado para acceder al canal
    }
    
    return false; // Si no está autenticado o no tiene permisos
});
