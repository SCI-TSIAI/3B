<?php namespace App\Router;

use App\Controller\UserController;
use App\Helpers\ReflectionUtils;
use Bramus\Router\Router;
use ReflectionClass;
use zpt\anno\Annotations;

class RestRouter {

    const CONTROLLER_ANNOTATION_NAME = "Controller";
    const ACTION_ANNOTATION_NAME = "Action";
    const PATH_PARAMETER_NAME = "path";
    const METHOD_PARAMETER_NAME = "method";

    private static $router;

    /**
     * @throws \ReflectionException
     * @throws \zpt\anno\ReflectorNotCommentedException
     */
    public static function run() {
        if (empty(self::$router)) {
            self::$router = new Router();

            self::$router->before('GET|POST|PUT|DELETE', '/.*', function () {
                header('Content-type: application/json');
            });

            self::registerRoutes(UserController::class);

            self::$router->run();
        }
    }

    /**
     * @param $classname
     * @throws \ReflectionException
     * @throws \zpt\anno\ReflectorNotCommentedException
     */
    private static function registerRoutes($classname) {

        $classReflector = new ReflectionClass($classname);
        $classAnnotations = new Annotations($classReflector);

        if (!$classAnnotations->hasAnnotation(self::CONTROLLER_ANNOTATION_NAME)) {
            return;
        }

        $controllerPath = $classAnnotations[self::CONTROLLER_ANNOTATION_NAME][self::PATH_PARAMETER_NAME];

        foreach ($classReflector->getMethods() as $methodReflector) {
            self::registerRoute($controllerPath, $methodReflector);
        }
    }

    /**
     * @param $controllerPath
     * @param \ReflectionMethod $methodReflector
     * @throws \Exception
     */
    private static function registerRoute($controllerPath, \ReflectionMethod $methodReflector) {

        $methodAnnotations = new Annotations($methodReflector);

        if (!$methodAnnotations->hasAnnotation(self::ACTION_ANNOTATION_NAME)) {
            return;
        }

        $actionMethod = strtoupper($methodAnnotations[self::ACTION_ANNOTATION_NAME][self::METHOD_PARAMETER_NAME]);

        $actionPath = $methodAnnotations[self::PATH_PARAMETER_NAME];

        $path = !empty($actionPath) ? $controllerPath . $actionPath : $controllerPath;

        switch ($actionMethod) {
            case "GET":
                self::$router->get($path, ReflectionUtils::getFullMethodIdentifier($methodReflector));
                break;
            case "POST":
                self::$router->post($path, ReflectionUtils::getFullMethodIdentifier($methodReflector));
                break;
            case "PUT":
                self::$router->put($path, ReflectionUtils::getFullMethodIdentifier($methodReflector));
                break;
            case "DELETE":
                self::$router->delete($path, ReflectionUtils::getFullMethodIdentifier($methodReflector));
                break;
            default:
                throw new \Exception("Unhandled action method!");
        }
    }
}