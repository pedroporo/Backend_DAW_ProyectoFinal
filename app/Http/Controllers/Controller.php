<?php

namespace App\Http\Controllers;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *    title="API Documentation",
 *    version="1.0.0",
 * )
 * @OA\PathItem(path="/api")
**  
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller {
    
}
