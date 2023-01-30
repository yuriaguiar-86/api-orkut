<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'username' => 'Lorem ipsum dolor sit amet',
                'name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'personal_phone' => 'Lorem ipsum d',
                'password' => 'Lorem ipsum dolor sit amet',
                'token_password_forget' => 'Lorem ipsum dolor sit amet',
                'created' => '2023-01-25 14:55:32',
                'modified' => '2023-01-25 14:55:32',
            ],
        ];
        parent::init();
    }
}
