<?php

namespace Modules\User\Repositories;

use Modules\User\Entities\ConfigFilter;
use Modules\User\Entities\User;

class UserRepository implements Contracts\UserRepositoryInterface
{
    public function __construct(
        protected User $user,
        protected ConfigFilter $configFilter
    ) {}
    public function create(array $data)
    {
        return $this->user->create([
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'password' => data_get($data, 'password')
        ]);
    }

    public function updatePreferenceSettings(int $id, string $type, string $item)
    {
        $exists = $this->configFilter->where('value', $item)->where('customer_id', $id)->first();

        if (!$exists) {
            $this->configFilter->create([
                'customer_id' => $id,
                'type' => $type,
                'value' => $item
            ]);
        } else {
            $exists->delete();
        }
    }

    public function getByEmail(string $email)
    {
        return $this->user->where('email', $email)->first();
    }

    public function getById(int $id)
    {
        return $this->user->find($id);
    }

    public function getUserPreferences(int $id, string $type)
    {
        $config = $this->configFilter->where('customer_id', $id);

        if ($type) {
            $config->where('type', $type);
        }

        return $config->get();
    }
}
