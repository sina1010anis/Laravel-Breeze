<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\TestCase;

class TestCaseTest extends TestCase
{
    /**
     * A basic test example.
     */

     public $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = new User();

    }
    public function test_in_file(): void
    {

        for ($i = 0 ; $i < 20 ; $i++) {

            $n1 = rand(1, 50);

            $n2 = rand(1, 50);

            $this->assertEquals($this->user->sumTowNumber($n1, $n2), $n1 + $n2);

        }

    }

    public function test_get_data_in_redis()
    {
        dd(Redis::executeRaw(['get', 'product:2:name']));

        // $this->assertEquals('Sumsung_S22_Ultra', Redis::executeRaw(['get', 'product:2:name']));

    }
}
