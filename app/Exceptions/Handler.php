<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        $class = get_class($exception);

        if ($class == 'League\OAuth2\Server\Exception\OAuthServerException' ){
            return response()->json([
              'code'=>$exception->getHttpStatusCode(),
              'error'=>$exception->getMessage(),
              'success'=>false
            ],
            $exception->getHttpStatusCode());
        }


            if ($exception instanceof MethodNotAllowedHttpException) {

                return response()->json(['success' => 0, 'error' => ['MethodNotAllowed']], 404);

            }
            elseif ($exception instanceof ModelNotFoundException or $exception instanceof NotFoundHttpException) {

                    return response()->json(['success' => 0,'error' => ['Not Found']], 404);

            }
          /*   elseif ($exception instanceof QueryException) {

                    return response()->json(['success' => 0, 'error' => ['query error']], 408);

            } */
            elseif ($exception instanceof \Illuminate\Validation\ValidationException) {

                return response()->json(['success' => 0,'error'=>$exception->errors()],422);
            }
            elseif ($exception instanceof AuthenticationException) {

                return response()->json(['success'=>0 ,'error' => 'Unauthenticated.'], 401);
            }

                return parent::render($request, $exception);
    }



}
