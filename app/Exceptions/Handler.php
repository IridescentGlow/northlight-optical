<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        'new_password',
        'new_password_confirmation',
        'card_number',
        'cvv',
    ];

    /**
     * The framework's redirect back with the errors, except that only text goes back
     * into the form. A list such as message[]=x cannot fill a field, and printing it
     * turned the page the visitor landed on into an error page.
     */
    protected function invalid($request, ValidationException $exception)
    {
        $input = array_filter(
            Arr::except($request->input(), $this->dontFlash),
            fn ($value) => is_scalar($value) || $value === null
        );

        return redirect($exception->redirectTo ?? url()->previous())
                    ->withInput($input)
                    ->withErrors($exception->errors(), $request->input('_error_bag', $exception->errorBag));
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
