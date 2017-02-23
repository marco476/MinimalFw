<?php
use PHPUnit\Framework\TestCase;
use Kernel\Kernel;

class KernelTest extends TestCase
{
    private $istance = null;

    protected function getIstance()
    {
        if ($this->istance === null) {
            $this->istance = new Kernel();
        }

        return $this->istance;
    }

    public function testSetRoutes()
    {
        $kernel = $this->getIstance();

        $testRoutes = array(
            'first' => array(
                'route' => array(),
                'elemExpected' => 0
            ), //empty array
            'second' => array(  //single route
                'route' => array(
                    'homepage' => array(
                    'route' => '/^\/$/',
                    'controller' => 'IndexController',
                    'action' => 'showHomeAction',
                    'params' => []
                    )
                ),
                'elemExpected' => 1
            )
        );

        foreach ($testRoutes as $route) {
            $aRoute = $route['route'];
            $elemExpected = $route['elemExpected'];

            $result = $kernel->setRoutes($aRoute);
            $this->assertCount($elemExpected, $result);
        }
    }
}
