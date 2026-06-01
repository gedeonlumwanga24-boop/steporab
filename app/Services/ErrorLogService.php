<?php

namespace App\Services;

use App\Models\ErrorLog;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ErrorLogService
{
    public function shouldLog(Throwable $e): bool
    {
        if (app()->runningUnitTests()) {
            return false;
        }

        if ($e instanceof ValidationException
            || $e instanceof AuthenticationException
            || $e instanceof AuthorizationException
            || $e instanceof NotFoundHttpException
            || $e instanceof ModelNotFoundException) {
            return false;
        }

        if ($e instanceof HttpExceptionInterface && $e->getStatusCode() < 500) {
            return false;
        }

        return true;
    }

    public function logFromThrowable(Throwable $e): void
    {
        if (! $this->shouldLog($e)) {
            return;
        }

        try {
            $request = request();

            ErrorLog::create([
                'message' => $e->getMessage() ?: class_basename($e),
                'stack_trace' => (string) $e,
                'url' => $request instanceof Request ? $request->fullUrl() : null,
                'method' => $request instanceof Request ? $request->method() : null,
                'ip_address' => $request instanceof Request ? $request->ip() : null,
                'status' => ErrorLog::STATUS_PENDING,
            ]);
        } catch (Throwable $loggingException) {
            Log::error('Impossible d\'enregistrer l\'erreur en base.', [
                'original' => $e->getMessage(),
                'failure' => $loggingException->getMessage(),
            ]);
        }
    }
}
