<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_wdt/styles' => [[['_route' => '_wdt_stylesheet', '_controller' => 'web_profiler.controller.profiler::toolbarStylesheetAction'], null, null, null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/auth/register' => [[['_route' => 'app_auth_register', '_controller' => 'App\\Controller\\AuthController::register'], null, ['POST' => 0], null, false, false, null]],
        '/auth/verify-email' => [[['_route' => 'app_auth_verify_email', '_controller' => 'App\\Controller\\AuthController::verifyEmail'], null, ['PATCH' => 0], null, false, false, null]],
        '/auth/login' => [[['_route' => 'app_auth_login', '_controller' => 'App\\Controller\\AuthController::login'], null, ['POST' => 0], null, false, false, null]],
        '/projects' => [
            [['_route' => 'app_project_create', '_controller' => 'App\\Controller\\ProjectController::create'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'app_project_list', '_controller' => 'App\\Controller\\ProjectController::list'], null, ['GET' => 0], null, false, false, null],
        ],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/a(?'
                    .'|pi(?'
                        .'|/(?'
                            .'|docs(?:\\.([^/]++))?(*:40)'
                            .'|\\.well\\-known/genid/([^/]++)(*:75)'
                            .'|validation_errors/([^/]++)(*:108)'
                        .')'
                        .'|(?:/(index)(?:\\.([^/]++))?)?(*:145)'
                        .'|/(?'
                            .'|contexts/([^.]+)(?:\\.(jsonld))?(*:188)'
                            .'|errors/(\\d+)(?:\\.([^/]++))?(*:223)'
                            .'|validation_errors/([^/]++)(?'
                                .'|(*:260)'
                            .')'
                            .'|project(?'
                                .'|s(?'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:309)'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:335)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:373)'
                                    .')'
                                .')'
                                .'|_statuses(?'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:421)'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:447)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:485)'
                                    .')'
                                .')'
                            .')'
                            .'|user(?'
                                .'|_types(?'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:538)'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:564)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:602)'
                                    .')'
                                .')'
                                .'|s(?'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:642)'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:668)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:706)'
                                    .')'
                                .')'
                            .')'
                        .')'
                    .')'
                    .'|dmin/projects/([^/]++)(*:741)'
                .')'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:781)'
                    .'|wdt/([^/]++)(*:801)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:843)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:880)'
                                .'|router(*:894)'
                                .'|exception(?'
                                    .'|(*:914)'
                                    .'|\\.css(*:927)'
                                .')'
                            .')'
                            .'|(*:937)'
                        .')'
                    .')'
                .')'
                .'|/projects/([^/]++)(?'
                    .'|(*:969)'
                    .'|/join(*:982)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        40 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => null, '_api_respond' => true], ['_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        75 => [[['_route' => 'api_genid', '_controller' => 'api_platform.action.not_exposed', '_api_respond' => true], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        108 => [[['_route' => 'api_validation_errors', '_controller' => 'api_platform.action.not_exposed'], ['id'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        145 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => null, '_api_respond' => true, 'index' => 'index'], ['index', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        188 => [[['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => true], ['shortName', '_format'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
        223 => [[['_route' => '_api_errors', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\State\\ApiResource\\Error', '_api_operation_name' => '_api_errors', '_format' => null], ['status', '_format'], ['GET' => 0], null, false, true, null]],
        260 => [
            [['_route' => '_api_validation_errors_problem', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_problem', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_hydra', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_hydra', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_jsonapi', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_jsonapi', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_validation_errors_xml', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => null, '_api_resource_class' => 'ApiPlatform\\Validator\\Exception\\ValidationException', '_api_operation_name' => '_api_validation_errors_xml', '_format' => null], ['id'], ['GET' => 0], null, false, true, null],
        ],
        309 => [[['_route' => '_api_/projects/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Project', '_api_operation_name' => '_api_/projects/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        335 => [
            [['_route' => '_api_/projects{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Project', '_api_operation_name' => '_api_/projects{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/projects{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Project', '_api_operation_name' => '_api_/projects{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        373 => [
            [['_route' => '_api_/projects/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Project', '_api_operation_name' => '_api_/projects/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/projects/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Project', '_api_operation_name' => '_api_/projects/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        421 => [[['_route' => '_api_/project_statuses/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        447 => [
            [['_route' => '_api_/project_statuses{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/project_statuses{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        485 => [
            [['_route' => '_api_/project_statuses/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/project_statuses/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        538 => [[['_route' => '_api_/user_types/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        564 => [
            [['_route' => '_api_/user_types{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/user_types{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        602 => [
            [['_route' => '_api_/user_types/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/user_types/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        642 => [[['_route' => '_api_/users/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        668 => [
            [['_route' => '_api_/users{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/users{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        706 => [
            [['_route' => '_api_/users/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/users/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        741 => [[['_route' => 'app_admin_projects_update', '_controller' => 'App\\Controller\\AdminController::update'], ['id'], ['PATCH' => 0], null, false, true, null]],
        781 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        801 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        843 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        880 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        894 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        914 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        927 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        937 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        969 => [
            [['_route' => 'app_project_update', '_controller' => 'App\\Controller\\ProjectController::update'], ['id'], ['PUT' => 0], null, false, true, null],
            [['_route' => 'app_project_delete', '_controller' => 'App\\Controller\\ProjectController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        982 => [
            [['_route' => 'app_project_join', '_controller' => 'App\\Controller\\ProjectController::join'], ['id'], ['POST' => 0], null, false, false, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
