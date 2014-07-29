<?php namespace Hook\Http;

use Respect\Rest\Router as RestRouter;

use Hook\Middlewares;

class Router {
    protected static $instance;

    public static function mount($app)
    {
        static::$instance = $app;

        //
        // Setup middlewares
        //
        $app->add(new Middlewares\ResponseTypeMiddleware());
        $app->add(new Middlewares\LogMiddleware());
        $app->add(new Middlewares\AuthMiddleware());
        $app->add(new Middlewares\AppMiddleware());

        // System
        $app->get('/system/time', 'Hook\\Controllers\\SystemController:time');
        $app->get('/system/ip', 'Hook\\Controllers\\SystemController:ip');

        // Collections
        $app->get('/collection/:name', 'Hook\\Controllers\\CollectionController:index');
        $app->post('/collection/:name', 'Hook\\Controllers\\CollectionController:store');
        $app->put('/collection/:name', 'Hook\\Controllers\\CollectionController:put');
        $app->put('/collection/:name/:id', 'Hook\\Controllers\\CollectionController:put');
        $app->post('/collection/:name/:id', 'Hook\\Controllers\\CollectionController:post');

        // Auth
        $app->get('/auth', 'Hook\\Controllers\\AuthController:show');
        $app->post('/auth/:provider(/:method', 'Hook\\Controllers\\AuthController:execute');

        // Push Notifications
        $app->post('/push', 'Hook\\Controllers\\PushNotificationController:store');
        $app->delete('/push', 'Hook\\Controllers\\PushNotificationController:delete');
        $app->get('/push/notify', 'Hook\\Controllers\\PushNotificationController:notify');

        // App management
        $app->get('/apps', 'Hook\\Controllers\\AppsController:index');
        $app->post('/apps', 'Hook\\Controllers\\AppsController:create');
        $app->delete('/apps', 'Hook\\Controllers\\AppsController:delete');
        $app->delete('/apps/cache', 'Hook\\Controllers\\AppsController:delete_cache');
        $app->get('/apps/logs', 'Hook\\Controllers\\AppsController:logs');
        $app->get('/apps/tasks', 'Hook\\Controllers\\AppsController:tasks');
        $app->post('/apps/tasks', 'Hook\\Controllers\\AppsController:recreate_tasks');
        $app->get('/apps/deploy', 'Hook\\Controllers\\AppsController:dump_deploy');
        $app->post('/apps/deploy', 'Hook\\Controllers\\AppsController:deploy');
        $app->get('/apps/configs', 'Hook\\Controllers\\AppsController:configs');
        $app->get('/apps/modules', 'Hook\\Controllers\\AppsController:modules');
        $app->get('/apps/schema', 'Hook\\Controllers\\AppsController:schema');
        $app->post('/apps/schema', 'Hook\\Controllers\\AppsController:upload_schema');

        //
        // Output exceptions as JSON {'error':'message'}
        //
        $app->error(function($e) use ($app) {
            return json_encode(array('error' => $e->getMessage()));
        });

        return $app;
    }

    public static function getInstance() {
        return static::$instance;
    }

}
