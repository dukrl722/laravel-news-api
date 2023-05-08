<?php

namespace Modules\User\Services;

use Modules\User\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function create(array $data) {
        $data['password'] = bcrypt(data_get($data, 'password'));

        return $this->userRepository->create($data);
    }

    public function updateProfilePicture($user, $img) {
        return storeMedia($user, $img, $user->name, 'avatar', true);
    }

    public function updatePreferenceSettings(int $id, string $type, object $data) {
        foreach ($data as $item) {
            $this->userRepository->updatePreferenceSettings($id, $type, $item);
        }
    }

    public function getByEmail(string $email) {
        return $this->userRepository->getByEmail($email);
    }

    public function getById(int $id) {
        return $this->userRepository->getById($id);
    }

    public function getUserPreferences(int $id, string $type) {
        return $this->userRepository->getUserPreferences($id, $type);
    }
}
