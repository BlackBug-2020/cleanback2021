<?php

namespace App\Exceptions;

use Exception;

class NotBelongsToUSer extends Exception
{
    public function render()
    {
        return ['error' => 'Not belongs to user'];
    }
}
