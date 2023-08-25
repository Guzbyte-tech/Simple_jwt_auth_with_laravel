<?php

namespace App\Exceptions;

use Throwable;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function (TokenBlacklistedException $e) {
            return response()->json([
                "status" => 401,
                "error" => true,
                "message" => "Token Expired"
            ], 401);
            // return response()->json("Too many attempt", 429);
        });
    }
}
