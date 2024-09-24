<?php

namespace App\Helpers;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{

    /**
     * Generate a JSON response for a successful request with data.
     * @param array $data    The data to include in the response.
     * @param string $message An optional message to include in the response.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function successWithData($data = [], $message = '')
    {
        return response()->json([
            'message' => $message,
            'data'    => $data
        ], Response::HTTP_OK);
    }

    /**
     * Generate a JSON response for an error scenario.
     * @param string $message An optional message describing the error.
     * @param array $errors An optional array of detailed error information.
     * @param int $code The HTTP status code associated with the error (default: 400 Bad Request).
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = '', array $errors = [], $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}