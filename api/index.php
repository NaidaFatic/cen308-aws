
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/services/UserService.php';

Flight::set('flight.log_errors', TRUE);

/* error handling for our API *
Flight::map('error', function(Exception $ex){
  Flight::json(["message" => $ex->getMessage()], $ex->getCode() ? $ex->getCode() : 500);
});
/* utility function for reading query parameters from URL */
Flight::map('query', function ($name, $default_value = NULL) {
    $request = Flight::request();
    $query_param = @$request->query->getData()[$name];
    $query_param = $query_param ? $query_param : $default_value;
    return $query_param;
});

/* utility function for getting header parameters */
Flight::map('header', function ($name) {
    $headers = getallheaders();
    return @$headers[$name];
});

/* utility function for generating JWT token */
Flight::map('jwt', function ($user) {
    $jwt = \Firebase\JWT\JWT::encode(["exp" => (time() + Config::JWT_TOKEN_TIME), "id" => $user["id"], "aid" => $user["account_id"], "r" => $user["role"]], Config::JWT_SECRET);
    return ["token" => $jwt];
});

/* Swagger documentation */
/**
 * @OA\Info(title="CEN 308 App API", version="0.2")
 * @OA\OpenApi(
 *    @OA\Server(url="http://localhost/cen308-app/api/", description="Development Environment" ),
 *    @OA\Server(url="http://157.230.119.60/cen308-app/api/", description="Production Environment" )
 * ),
 * @OA\SecurityScheme(securityScheme="ApiKeyAuth", type="apiKey", in="header", name="Authentication" )
 */
Flight::route('GET /swagger', function () {
    $openapi = @\OpenApi\scan([dirname(__FILE__) . "/routes", dirname(__FILE__) . "/index.php"]);
    echo $openapi->toJson();
});

Flight::route('GET /', function () {
    Flight::redirect('/docs');
});

/**
 * @OA\Get(path="/status/", tags={"status"},
 *     @OA\Response(response="200", description="Checking if the system is online.")
 * )
 */
Flight::route('GET /status', function () {
    Flight::json(['status' => 'online']);
});

/* register Business Logic layer services */
Flight::register('userService', 'UserService');

/* include all routes */
require_once dirname(__FILE__) . "/routes/middleware.php";
require_once dirname(__FILE__) . "/routes/users.php";

Flight::start();
