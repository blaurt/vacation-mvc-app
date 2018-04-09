<?php
declare(strict_types=1);

namespace app\core;

use app\core\service\Request;
use app\core\service\Response;
use app\core\service\Router;
use app\core\service\Session;
use app\core\service\View;
use app\models\dayoffs\DayOffTypes;
use app\models\users\{
    Guest, User
};
use \Monolog\Logger;
use \Monolog\Formatter\LineFormatter;
use \Monolog\Handler\StreamHandler;

/**
 * Core class of this framework
 * @package core
 */
class AppCore
{
    /**
     * @var self, singleton object
     */
    protected static $instance = null;

    /**
     * Routing class
     *
     * @var Router
     */
    public static $router;

    /**
     * contains array with params, defined in config/ folder
     *
     * @var array
     */
    public static $config;

    /**
     * @var \PDO stores pdo connection
     */
    public static $dbConnection;

    /**
     * Contains object with user's params and permissions
     *
     * @var User
     */
    public static $user;

    /**
     * Monolog logger
     *
     * @var Logger
     */
    public static $logger;

    /**
     *Class contains request params
     *
     * @var Request
     */
    public static $request;

    /**
     * Simple responser
     *
     * @var Response
     */
    public static $response;

    /**
     * Handles session operations
     *
     * @var Session
     */
    public static $session;

    /**
     * Pointer to current controller
     *
     * @var object child of AbstractController
     */
    public static $controller;

    /**
     * Handles the procces of rendering full page
     *
     * @var View
     */
    public static $view;

    /**
     * Initialize framework components:
     *
     * @param $config array is main app config
     */
    protected function __construct(array $config)
    {
        session_start();
        self::$logger = $this->getLogger();
        self::$config = $config;
        self::$request = new Request();
        self::$response = new Response();
        self::$session = new Session();
        self::$dbConnection = new \PDO($config['db']['dsn'],
            $config['db']['user'], $config['db']['pass']);
        self::$view = new View();
        self::$router = new Router();
        DayOffTypes::init();
        if (self::$session->isInSession('user_data')) {
            self::$user = new User(self::$session->getFromSession('user_data'));
        } else {
            self::$user = new Guest();
        }
        self::$logger->debug('self initialized ');
    }

    /**
     * Private for singleton purpose
     */
    private function __clone()
    {
    }

    /**
     * Private for singleton purpose
     */
    private function __wakeup()
    {
    }


    /**
     * Processes the request
     * Returns response to user
     *
     * for AJAX responses we can ignore disable layout,
     * responding with $content only
     */
    public function run(): void
    {
        try {
            $content = $this->handleRequest();
        } catch (\Exception $e) {
            if (DEBUG_MODE) {
                ob_start();
                include __DIR__ . '/../views/error/error.php';
                $content = ob_get_clean();
            } else {
                $content = self::$config['error_message'];
            }
        }

        echo self::$view->renderPage($content);
    }

    /**
     * @return string rendered content of response
     */
    protected function handleRequest(): string
    {
        list($controllerName, $action) = self::$router->parseRequest();
        self::$logger->debug('creating controller: ' . $controllerName);
        $controller = new $controllerName();
        self::$controller = $controller;
        self::$logger->debug('trying to call action: ' . $action);
        $returnData = $controller->$action();

        return $returnData;
    }


    public static function getInstance($config)
    {
        if (self::$instance == null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    protected function getLogger()
    {
        $log = new Logger('files');
        $formatter = new LineFormatter(null, null, false, true);
        $debugHandler = new StreamHandler('debug.log', Logger::DEBUG);
        $debugHandler->setFormatter($formatter);
        $log->pushHandler($debugHandler);

        $log->debug('');
        $log->debug("Logger initiated");

        return $log;
    }
}