<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\HasExceptionFormatHandles;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class BaseExceptionHandler extends Exception implements HttpExceptionInterface
{
    use HasExceptionFormatHandles;

    protected array $headers = [];

    public string $scope = 'E_COMMON' {
        get {
            return $this->scope;
        }
    }

    public string $textCode = 'E_SOMETHING_WENT_WRONG' {
        get {
            return $this->textCode;
        }
    }

    protected ?string $text = null;

    public function getText(): ?string
    {
        return __($this->text);
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): Exception
    {
        $this->headers = $headers;

        return $this;
    }
}
