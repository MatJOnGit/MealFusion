<?php

namespace Api\Models;

use PDO;

final class User {
    public function selectPermissions(object $db, string $token): ?array
    {
        $selectPermissionsQuery = "SELECT api_permissions FROM api_tokens WHERE token = ?";
        $selectPermissionsStatement = $db->prepare($selectPermissionsQuery);
        $selectPermissionsStatement->execute([$token]);
        
        return $selectPermissionsStatement->fetch(PDO::FETCH_ASSOC);
    }
}