<?php
require_once 'Autoloader.php';
Autoloader::register();
new Api();

class Api
{
    private static $db;

    public static function getDb()
    {
        return self::$db;
    }

    public function __construct()
    {
        self::$db = (new Database())->init();

        $uri = strtolower(trim((string)$_SERVER['PATH_INFO'], '/'));
        $httpVerb = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';

        $wildcards = [
            ':any' => '[^/]+',
            ':num' => '[0-9]+',
        ];
        $routes = [
            'get constructionStages' => [
                'class' => 'ConstructionStages',
                'method' => 'getAll',
            ],
            'get constructionStages/(:num)' => [
                'class' => 'ConstructionStages',
                'method' => 'getSingle',
            ],
            'post constructionStages' => [
                'class' => 'ConstructionStages',
                'method' => 'post',
                'bodyType' => 'ConstructionStagesCreate',
            ],
            'patch constructionStages/(:num)' => [
                'class' => 'ConstructionStages',
                'method' => 'patch',
            ],
            'delete constructionStages/(:num)' => [
                'class' => 'ConstructionStages',
                'method' => 'delete',
            ],
        ];


		$response = [
			'error' => 'No such route',
		];

        if ($uri) {
            foreach ($routes as $pattern => $target) {
                $pattern = str_replace(array_keys($wildcards), array_values($wildcards), $pattern);
                if (preg_match('#^'.$pattern.'$#i', "{$httpVerb} {$uri}", $matches)) {
                    $params = [];
                    array_shift($matches);
                    preg_replace_callback('#'.implode('|', array_keys($wildcards)).'#', function ($matches) use (&$params) {
                        if (isset($matches[1])) {
                            $params[] = $matches[1];
                        }
                    }, $pattern);

                    if (isset($target['bodyType'])) {
                        $data = json_decode(file_get_contents('php://input'));
                        if (!$data instanceof $target['bodyType']) {
                            http_response_code(400);
                            echo json_encode(array('message' => 'Geçersiz istek veri yapısı'));
                            return;
                        }
                        $params[] = $data;
                    }

                    $class = $target['class'];
                    $method = $target['method'];

                    if (class_exists($class)) {
                        $instance = new $class();
                        if (method_exists($instance, $method)) {
                            call_user_func_array([$instance, $method], $params);
                            return;
                        }
                    }
                }
            }
        }

        echo json_encode($response);
    }
}

