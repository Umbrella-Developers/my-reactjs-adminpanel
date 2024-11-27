<?php
namespace App\Exceptions;

use Throwable;  // Use Throwable instead of Exception
use App\Models\ErrorLog;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Sentry\Laravel\Integration;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;




class Handler extends ExceptionHandler
{
    // Override the report method
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            $this->logErrorToDatabase($exception); 
        }
        parent::report($exception);
    }

    protected function logErrorToDatabase(Throwable $exception) 
    {
      // Extract error details
      $errorType = get_class($exception); // Get exception type
      // Get detailed information about the exception class
      $reflection = new \ReflectionClass($errorType);
      $log = 'Exception type: ' . $reflection->getName() . "\n" .
                  'Is a subclass of Exception: ' . ($reflection->isSubclassOf(\Exception::class) ? 'Yes' : 'No') . "\n" .
                  'Exception message: ' . $exception->getMessage() . "\n";

      $message = $exception->getMessage(); // Get error message
      // Find the correct controller and method in the stack trace
      $traceInfo = $exception->getTrace();
      $controller = null;
      $method = null;
      // Loop through the stack trace to find the first instance of a controller
      foreach ($traceInfo as $trace) {
          if (isset($trace['class']) && strpos($trace['class'], 'App\Http\Controllers') === 0) {
              $controller = $trace['class'];
              $method = $trace['function'];
              break; // Break once we find the first controller
          }
      }
      $file = $exception->getFile(); // Default file if no relevant file is found
      // Loop through the trace to find a file from Services or Controllers
      foreach ($traceInfo as $traceStep) {
          if (isset($traceStep['file'])) {
              // Prioritize files from Services and Controllers
              if (str_contains($traceStep['file'], 'App\Http\Services') || 
                  str_contains($traceStep['file'], 'App\Http\Controllers')) {
                  $file = $traceStep['file'];
                  break;
              }
          }
      }
      // Log error in database using your ErrorLog model
      $controller =  $controller ?? 'N/A';
      $method = $method ?? 'N/A';
      // log error into database
      errorLogs($controller, $log, $message, $method, $errorType, $getTraceAsString = $exception->getTraceAsString());             
    }

    protected function extractModelName(Throwable $exception)
    {
        // If the exception is related to a model, return the model name
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $exception->getModel();
        }

        return null;
    }
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    // $this->renderable(static function (\Throwable $exception, $request) {       
            
        //     // Get the type of the exception
        //     $exceptionType = get_class($exception);
        //     // Get detailed information about the exception class
        //     $reflection = new \ReflectionClass($exceptionType);
            
        //     $errorType = 'Exception type: ' . $reflection->getName() . "\n" .
        //                 'Is a subclass of Exception: ' . ($reflection->isSubclassOf(\Exception::class) ? 'Yes' : 'No') . "\n" .
        //                 'Exception message: ' . $exception->getMessage() . "\n";

        //     // Log the error
        //     errorLogs($model = "\App\Models\Role", $log = $exception->getMessage(), $module = 'role', $functionType = 'destroy', $errorType, $getTraceAsString = $exception->getTraceAsString());             

        //     // Check if the request expects a JSON response
        //     // if (request()->expectsJson()) {
        //     //     return new JsonResponse([
        //     //         'status' => false,
        //     //         'message' => $exception->getMessage(),
        //     //         'code' => $exception->getCode(),
        //     //     ], 500);
        //     // }

        //     // For non-API requests, redirect back to the previous page with an error message
        //     //return redirect()->back()->with('error', $exception->getMessage())->withInput();
        //      session()->flash('error_message', $exception->getMessage());

        //     // Redirect back with the error message
        //     return redirect()->back()->withInput($request->all())->withErrors($request->all());
        //     // return redirect()->back()->withErrors($request->all())->withInput()->throwResponse();
        // }); 
}