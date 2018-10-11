<?php
require __DIR__.'/../../../../vendor/autoload.php';

$container = require __DIR__.'/../../../../config/container.php';

$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($container->get('config')['eloquent']);
$capsule->setAsGlobal();
$capsule->bootEloquent();