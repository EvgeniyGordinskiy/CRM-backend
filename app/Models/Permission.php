<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    public function user ()
    {
        return $this->belongsToMany(User::class);
    }

    public function updatePermissions() {
        $app = app();
        $routesAll = $app->routes->getRoutes();
        $routes =[];
        array_map(function($route) use(&$routes){
            $action = $route->action;
            if (isset($action['middleware']) &&
                is_array($action['middleware']) &&
                in_array('jwt.auth', $action['middleware'])) {
                if(isset($action['as']) && $action['as'] !== 'account.show') {
                    $routes[]['name'] = $action['as'];
                }
            }
        }
            , $routesAll);
       return self::insert($routes);
    }
}
