<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 4/13/19
 * Time: 6:51 AM
 */

namespace App\Inspections;

use Exception;

class KeyHeldDown
{

    public function detect($body)
    {
        $this->detectHelddownKey($body);
        return false;
    }

    protected function detectHelddownKey($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new Exception('your reply contains a spam');
        }
    }
}
