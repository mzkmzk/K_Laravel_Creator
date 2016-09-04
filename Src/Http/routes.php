<?php

Route::group(['prefix' => 'v1/{Entity_Controller}'], function (){
    //
    Route::get('query',function($Entity_Controller,\Illuminate\Http\Request $request){
        $controller_string = "App\\Http\\Controllers\\" . $Entity_Controller;
        $controller = new $controller_string($request);
        return $controller->query();
    });

    Route::post('insert',function($Entity_Controller,\Illuminate\Http\Request $request){
        $controller_string = "App\\Http\\Controllers\\" . $Entity_Controller;
        $controller = new $controller_string($request);
        return $controller->insert();
    });

    Route::post('update',function($Entity_Controller,\Illuminate\Http\Request $request){
        $controller_string = "App\\Http\\Controllers\\" . $Entity_Controller;
        $controller = new $controller_string($request);
        return $controller->update();
    });

    Route::post('delete',function($Entity_Controller,\Illuminate\Http\Request $request){
        $controller_string = "App\\Http\\Controllers\\" . $Entity_Controller;
        $controller = new $controller_string($request);
        return $controller->delete();
    });

    Route::post('restore',function($Entity_Controller,\Illuminate\Http\Request $request){
        $controller_string = "App\\Http\\Controllers\\" . $Entity_Controller;
        $controller = new $controller_string($request);
        return $controller->restore();
    });
         

});