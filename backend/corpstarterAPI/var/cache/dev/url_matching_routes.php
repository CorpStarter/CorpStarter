<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/auth/signup' => [[['_route' => 'auth_signup', '_controller' => 'App\\Controller\\AuthController::signup'], null, ['POST' => 0], null, false, false, null]],
        '/auth/signin' => [[['_route' => 'auth_signin', '_controller' => 'App\\Controller\\AuthController::signin'], null, ['POST' => 0], null, false, false, null]],
        '/auth/verify-email' => [[['_route' => 'auth_verify_email', '_controller' => 'App\\Controller\\AuthController::verifyEmail'], null, ['POST' => 0], null, false, false, null]],
        '/projects' => [
            [['_route' => 'projects_list', '_controller' => 'App\\Controller\\ProjectController::list'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'projects_create', '_controller' => 'App\\Controller\\ProjectController::create'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/auth/signup' => [[['_route' => 'api_auth_signup', '_controller' => 'App\\Controller\\AuthController::signup'], null, ['POST' => 0], null, false, false, null]],
        '/api/auth/signin' => [[['_route' => 'api_auth_signin', '_controller' => 'App\\Controller\\AuthController::signin'], null, ['POST' => 0], null, false, false, null]],
        '/api/auth/verify-email' => [[['_route' => 'api_auth_verify_email', '_controller' => 'App\\Controller\\AuthController::verifyEmail'], null, ['POST' => 0], null, false, false, null]],
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
                                    .'|/(?'
                                        .'|([^/\\.]++)(?:\\.([^/]++))?(?'
                                            .'|(*:376)'
                                        .')'
                                        .'|([^/]++)(?'
                                            .'|(*:396)'
                                            .'|/join(*:409)'
                                        .')'
                                    .')'
                                    .'|(*:419)'
                                .')'
                                .'|_statuses(?'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:466)'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:492)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:530)'
                                    .')'
                                .')'
                            .')'
                            .'|user(?'
                                .'|_types(?'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:583)'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:609)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:647)'
                                    .')'
                                .')'
                                .'|s(?'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(*:687)'
                                    .'|(?:\\.([^/]++))?(?'
                                        .'|(*:713)'
                                    .')'
                                    .'|/([^/\\.]++)(?:\\.([^/]++))?(?'
                                        .'|(*:751)'
                                    .')'
                                .')'
                            .')'
                            .'|admin/projects/([^/]++)/(?'
                                .'|status(*:795)'
                                .'|budget(*:809)'
                            .')'
                        .')'
                    .')'
                    .'|dmin/projects/(?'
                        .'|(\\d+)/status(*:849)'
                        .'|(\\d+)/budget(*:869)'
                    .')'
                .')'
                .'|/projects/(?'
                    .'|(\\d+)(?'
                        .'|(*:900)'
                    .')'
                    .'|(\\d+)/join(*:919)'
                .')'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:956)'
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
        376 => [
            [['_route' => '_api_/projects/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Project', '_api_operation_name' => '_api_/projects/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/projects/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Project', '_api_operation_name' => '_api_/projects/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        396 => [
            [['_route' => 'api_projects_edit', '_controller' => 'App\\Controller\\ProjectController::edit'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'api_projects_delete', '_controller' => 'App\\Controller\\ProjectController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        409 => [[['_route' => 'api_projects_join', '_controller' => 'App\\Controller\\ProjectController::join'], ['id'], ['POST' => 0], null, false, false, null]],
        419 => [
            [['_route' => 'api_projects_list', '_controller' => 'App\\Controller\\ProjectController::list'], [], ['GET' => 0], null, false, false, null],
            [['_route' => 'api_projects_create', '_controller' => 'App\\Controller\\ProjectController::create'], [], ['POST' => 0], null, false, false, null],
        ],
        466 => [[['_route' => '_api_/project_statuses/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        492 => [
            [['_route' => '_api_/project_statuses{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/project_statuses{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        530 => [
            [['_route' => '_api_/project_statuses/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/project_statuses/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\ProjectStatus', '_api_operation_name' => '_api_/project_statuses/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        583 => [[['_route' => '_api_/user_types/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        609 => [
            [['_route' => '_api_/user_types{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/user_types{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        647 => [
            [['_route' => '_api_/user_types/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/user_types/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\UserTypes', '_api_operation_name' => '_api_/user_types/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        687 => [[['_route' => '_api_/users/{id}{._format}_get', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users/{id}{._format}_get', '_format' => null], ['id', '_format'], ['GET' => 0], null, false, true, null]],
        713 => [
            [['_route' => '_api_/users{._format}_get_collection', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users{._format}_get_collection', '_format' => null], ['_format'], ['GET' => 0], null, false, true, null],
            [['_route' => '_api_/users{._format}_post', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users{._format}_post', '_format' => null], ['_format'], ['POST' => 0], null, false, true, null],
        ],
        751 => [
            [['_route' => '_api_/users/{id}{._format}_patch', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users/{id}{._format}_patch', '_format' => null], ['id', '_format'], ['PATCH' => 0], null, false, true, null],
            [['_route' => '_api_/users/{id}{._format}_delete', '_controller' => 'api_platform.symfony.main_controller', '_stateless' => true, '_api_resource_class' => 'App\\Entity\\Users', '_api_operation_name' => '_api_/users/{id}{._format}_delete', '_format' => null], ['id', '_format'], ['DELETE' => 0], null, false, true, null],
        ],
        795 => [[['_route' => 'api_admin_change_project_status', '_controller' => 'App\\Controller\\AdminController::changeProjectStatus'], ['id'], ['PATCH' => 0], null, false, false, null]],
        809 => [[['_route' => 'api_admin_allocate_budget', '_controller' => 'App\\Controller\\AdminController::allocateBudget'], ['id'], ['PATCH' => 0], null, false, false, null]],
        849 => [[['_route' => 'admin_change_project_status', '_controller' => 'App\\Controller\\AdminController::changeProjectStatus'], ['id'], ['PATCH' => 0], null, false, false, null]],
        869 => [[['_route' => 'admin_allocate_budget', '_controller' => 'App\\Controller\\AdminController::allocateBudget'], ['id'], ['PATCH' => 0], null, false, false, null]],
        900 => [
            [['_route' => 'projects_edit', '_controller' => 'App\\Controller\\ProjectController::edit'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null],
            [['_route' => 'projects_delete', '_controller' => 'App\\Controller\\ProjectController::delete'], ['id'], ['DELETE' => 0], null, false, true, null],
        ],
        919 => [[['_route' => 'projects_join', '_controller' => 'App\\Controller\\ProjectController::join'], ['id'], ['POST' => 0], null, false, false, null]],
        956 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
