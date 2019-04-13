<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 4/13/19
 * Time: 6:51 AM
 */

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    protected $invalidKeywords = ['yahoo customer support'];

    public function detect($body)
    {
        $this->detectInvalidKeywords($body);
        return false;
    }

    protected function detectInvalidKeywords($body)
    {

        foreach ($this->invalidKeywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('your reply contains a spam');
            }
        }
    }
}
