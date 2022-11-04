<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('staffs', StaffsController::class);
    $router->resource('customers', CustomersController::class);
    $router->resource('processes', ProcessesController::class);
    $router->resource('vendors', VendorsController::class);
    $router->resource('tools', ToolsController::class);
    $router->resource('steps', StepsController::class);
    $router->resource('products', ProductsController::class);
    $router->resource('models', ModelsController::class);
    $router->resource('prod-processes-lists', ProdProcessesListController::class);

    $router->resource('runs', RunsController::class);
    $router->resource('batches', BatchesController::class);

    $router->resource('orders', OrdersController::class);
});
