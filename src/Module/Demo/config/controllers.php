<?php declare(strict_types=1);

use Interop\Container\ContainerInterface;
use Simplex\Quickstart\Module\Demo\Controller\DemoController;
use Simplex\Quickstart\Module\Demo\Repository\PersonRepository;

return [

    DemoController::class => function(ContainerInterface $c) {
        return new DemoController($c->get(PersonRepository::class));
    },

];
