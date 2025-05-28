<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

abstract class Controller
{
    /**
     * Generate a standardized error response.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success_response($message, $data = null)
    {
        return Redirect::back()->with([
            'success' => $message,
            'data' => $data,
        ]);
    }

    public function error_response($message = '')
    {
        return back()->with('error', $message);
    }
}
