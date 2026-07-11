<?php

namespace App\Generator;

class CsrfToken
{
    /**
     * Generates a cryptographically secure CSRF token.
     *
     * @param int $length The number of random bytes to generate.
     * @return string A hex-encoded string of the random bytes.
     */
    public function generate(int $length = 32): string
    {
        // random_bytes() generates raw binary data.
        // bin2hex() converts it to a readable string safe for HTML/JSON.
        return bin2hex(random_bytes($length));
    }
}
