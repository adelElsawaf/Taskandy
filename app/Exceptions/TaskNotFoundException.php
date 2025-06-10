<?php
namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskNotFoundException extends NotFoundHttpException
{
    protected string $description;

    public function __construct(
        string $message = 'Task notfound',
        string $description = 'The requested task was not found in the system.',
    ) {
        parent::__construct($message);
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}