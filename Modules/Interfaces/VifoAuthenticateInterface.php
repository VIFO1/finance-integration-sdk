<?php

namespace Modules\Interfaces;

interface VifoAuthenticateInterface
{
    public function authenticateUser(array $headers, string $username, string $password): array;
}
