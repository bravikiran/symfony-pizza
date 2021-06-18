<?php


namespace App\Tests\util;

use PHPUnit\Framework\TestCase;
use App\Controller\PizzasController;

class PizzaControllerTest extends  TestCase
{
    public function testFetchPizza()
    {
        $fetchPizzas = new PizzasController();

        $data = [
            'id' => 1,
            'name' => 'Test Pizza',
            'price' => 10.50,
        ];

        $results = $fetchPizzas->fetchPizza(1);

        $this->assertEquals($data['id'], $results['id']);
    }

}