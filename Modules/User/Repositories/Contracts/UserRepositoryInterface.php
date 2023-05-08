<?php

namespace Modules\User\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function updatePreferenceSettings(int $id, string $type, string $item);
    public function getByEmail(string $email);
    public function getById(int $id);
    public function getUserPreferences(int $id, string $type);
}
