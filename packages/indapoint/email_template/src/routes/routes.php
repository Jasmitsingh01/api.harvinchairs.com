<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register routes for email template module
  |
 */

// if not otherwise configured, setup the dashboard routes
if (config('email_template.setup_email_template_routes')) {
    $router->group(['namespace' => '\Indapoint\EmailTemplate'], function ($router) {

// Login required routes
        $router->group(['prefix' => 'email_template', 'middleware' => 'auth:admin_api'], function ($router) {

            // Email Template
            $router->get('list', ['as' => 'email_template.index', 'uses' => 'EmailTemplateController@index']);
            $router->get('show/{id}', ['as' => 'email_template.show', 'uses' => 'EmailTemplateController@show']);
            $router->post('create', ['as' => 'email_template.create', 'uses' => 'EmailTemplateController@store']);
            $router->delete('delete/{id}', ['as' => 'email_template.delete', 'uses' => 'EmailTemplateController@destroy']);
            $router->put('update/{id}', ['as' => 'email_template.update', 'uses' => 'EmailTemplateController@update']);
            $router->put('change_status', ['as' => 'email_template.status', 'uses' => 'EmailTemplateController@changeStatus']);
        });
    });
}
