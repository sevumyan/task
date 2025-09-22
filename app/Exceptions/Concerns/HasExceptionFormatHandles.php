<?php

namespace App\Exceptions\Concerns;

use App\Exceptions\BaseExceptionHandler;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait HasExceptionFormatHandles
{
    /**
     * HTTP status code mapping for common exceptions
     */
    private static array $statusCodeMap = [
        AuthenticationException::class => 401,
        ModelNotFoundException::class => 404,
        NotFoundHttpException::class => 404,
        MethodNotAllowedHttpException::class => 405,
        ValidationException::class => 422,
    ];

    /**
     * Convenience method to register the exception format handler with Laravel 11.0 and up
     */
    public static function handles(Exceptions $exceptions, string $wildcard): void
    {
        $exceptions->render(function (Exception $exception, Request $request) use ($wildcard): ?JsonResponse {
            if (!$request->is($wildcard)) {
                return null;
            }

            return self::formatExceptionResponse($exception);
        });
    }

    /**
     * Format exception into standardized JSON response
     */
    private static function formatExceptionResponse(Exception $exception): JsonResponse
    {
        $responseData = self::buildResponseData($exception);
        $statusCode = self::determineStatusCode($exception);

        return response()->json($responseData, $statusCode);
    }

    /**
     * Build the response data array
     */
    private static function buildResponseData(Exception $exception): array
    {
        $data = [
            'message' => self::getExceptionMessage($exception),
            'type' => self::getExceptionType($exception),
        ];

        if ($exception instanceof ValidationException) {
            $data = self::buildValidationResponse($exception, $data);
        }

        if ($exception instanceof BaseExceptionHandler && $exception->textCode) {
            $data['code'] = $exception->textCode;
        }

        if (config('app.debug', false)) {
            $data['debug'] = self::buildDebugInfo($exception);
        }

        return $data;
    }

    /**
     * Build validation-specific response data
     */
    private static function buildValidationResponse(ValidationException $exception, array $data): array
    {
        $errors = $exception->errors();

        $data['errors'] = $errors;

        if (!empty($errors)) {
            $data['total_errors'] = array_sum(array_map('count', $errors));
        }

        $firstError = self::getFirstValidationError($errors);
        if ($firstError) {
            $data['message'] = $firstError;
        }

        return $data;
    }

    /**
     * Get the first validation error message
     */
    private static function getFirstValidationError(array $errors): ?string
    {
        if (empty($errors)) {
            return null;
        }

        $firstField = array_key_first($errors);
        $firstFieldErrors = $errors[$firstField];

        return is_array($firstFieldErrors) && !empty($firstFieldErrors)
            ? $firstFieldErrors[0]
            : null;
    }

    /**
     * Get appropriate message from exception
     */
    private static function getExceptionMessage(Exception $exception): string
    {
        if ($exception instanceof BaseExceptionHandler) {
            return $exception->getText() ?: $exception->getMessage();
        }

        if ($exception instanceof ValidationException) {
            return $exception->getMessage() ?: 'Validation failed';
        }

        return $exception->getMessage() ?: 'An error occurred';
    }

    /**
     * Get exception type (class name without namespace)
     */
    private static function getExceptionType(Exception $exception): string
    {
        if ($exception instanceof BaseExceptionHandler && $exception->scope) {
            return $exception->scope;
        }

        if ($exception instanceof ValidationException) {
            return 'validation_error';
        }

        return class_basename($exception);
    }

    /**
     * Determine HTTP status code for exception
     */
    private static function determineStatusCode(Exception $exception): int
    {
        foreach (self::$statusCodeMap as $exceptionClass => $statusCode) {
            if ($exception instanceof $exceptionClass) {
                return $statusCode;
            }
        }

        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    /**
     * Build debug information array
     */
    private static function buildDebugInfo(Exception $exception): array
    {
        $debug = [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => self::sanitizeStackTrace($exception->getTrace()),
        ];

        if ($exception instanceof ValidationException) {
            $debug['validator'] = [
                'rules' => $exception->validator?->getRules() ?? [],
                'data_keys' => array_keys($exception->validator?->getData() ?? []),
            ];
        }

        return $debug;
    }

    /**
     * Sanitize stack trace by removing arguments and limiting depth
     */
    private static function sanitizeStackTrace(array $trace, int $maxDepth = 10): array
    {
        return collect($trace)
            ->take($maxDepth)
            ->map(fn(array $item): array => Arr::except($item, ['args', 'object']))
            ->filter(fn(array $item): bool => !empty($item['file']))
            ->values()
            ->all();
    }
}
