<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    //$app->route('/vendedores', App\Action\VendedoresAction::class, ['GET', 'POST', 'PUT'], 'vendedores');
    $app->route(
        '/vendedores[/ativo/{ativo}]',
        App\Action\VendedoresAction::class,
        ['GET'],
        'vendedores.list'
    );
    
    $app->route(
        '/vendedores/{id}',
        App\Action\VendedoresAction::class,
        ['GET'],
        'vendedores.get'
    );
    
    $app->route(
        '/vendedores',
        App\Action\VendedoresAction::class,
        ['POST'],
        'vendedores.create'
    );
    
    $app->route(
        '/vendedores/{id}',
        App\Action\VendedoresAction::class,
        ['PUT'],
        'vendedores.update'
    );
    
    $app->route(
        '/vendas',
        App\Action\VendasAction::class,
        ['POST'],
        'vendas.create'
    );
    
    $app->route(
        '/vendas/pagina/{pagina}[/vendedor/{vendedor}]',
        App\Action\VendasAction::class,
        ['GET'],
        'vendas.list'
    );
    
    $app->route(
        '/vendas/{id}',
        App\Action\VendasAction::class,
        ['DELETE'],
        'vendas.delete'
    );
    
    $app->route(
        '/dashboard/{data}',
        App\Action\DashboardAction::class,
        ['GET'],
        'dashboard'
    );
};
