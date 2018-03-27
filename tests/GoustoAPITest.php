<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GoustoAPITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function  testExample()
    {
        $this->get('v1/api/recipes/10')->seeJson();

        $this->get('v1/api/recipes/cuisine/asian')->seeJson();


    }
}
