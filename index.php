<?php
require_once 'Autoloader.php';
Autoloader::register();
new Api();

/**
 * Class Api
 *
 * This class represents the API handler.
 */
class Api
{
    /**
     * @var mixed The database instance.
     */
    private static $db;

    /**
     * Get the database instance.
     *
     * @return mixed The database instance.
     */
    public static function getDb()
    {
        return self::$db;
    }

    /**
     * Api constructor.
     *
     * Initializes the API handler and processes the requests.
     */
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
                'bodyType' => 'ConstructionStagesUpdate',
            ],
            'delete constructionStages/(:num)' => [
                'class' => 'ConstructionStages',
                'method' => 'delete',
            ],
        ];

        $response = [
            'error' => 'Route not found',
        ];

        if ($uri) {
            foreach ($routes as $pattern => $target) {
                $pattern = str_replace(array_keys($wildcards), array_values($wildcards), $pattern);
                if (preg_match('#^'.$pattern.'$#i', "{$httpVerb} {$uri}", $matches)) {
                    $params = [];
                    array_shift($matches);
                    if ($httpVerb === 'post' || $httpVerb === 'patch') {
                        $data = json_decode(file_get_contents('php://input'));
                        $params = [new $target['bodyType']($data)];
                        var_dump($params);
                    }
                    $params = array_merge($params, $matches);
                    $response = call_user_func_array([new $target['class'], $target['method']], $params);
                    break;
                }
            }

            echo json_encode($response, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }

        echo json_encode($response);
    }
}
