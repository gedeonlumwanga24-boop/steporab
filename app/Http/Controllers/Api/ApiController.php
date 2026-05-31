<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Base controller for all API V1 responses.
 * Provides helpers for consistent JSON structure.
 */
abstract class ApiController extends Controller
{
    protected function success(mixed $data, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function created(mixed $data, string $message = 'Créé avec succès.'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    protected function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    protected function notFound(string $message = 'Ressource introuvable.'): JsonResponse
    {
        return $this->error($message, 404);
    }

    protected function forbidden(string $message = 'Action non autorisée.'): JsonResponse
    {
        return $this->error($message, 403);
    }
}
