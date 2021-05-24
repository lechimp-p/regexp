<?php
/******************************************************************************
 * A basic wrapper around PHP's regexps.
 *
 * Copyright (c) 2021 Richard Klees <richard.klees@rwth-aachen.de>
 *
 * This software is licensed under GPLv3. You should have received
 * a copy of the license along with the code.
 */

namespace Lechimp\Regexp;

/**
 * Small wrapper around preg.
 */
class Regexp
{
    /**
     * @var string
     */
    protected $regexp;

    /**
     * @var string
     */
    protected $delim = "%";

    public function __construct(string $regexp)
    {
        if (@preg_match($this->delim . $regexp . $this->delim, "") === false) {
            throw new \InvalidArgumentException("Invalid regexp '$regexp'");
        }
        $this->regexp = $regexp;
    }

    public function raw() : string
    {
        return $this->regexp;
    }

    /**
     * Match a string with the regexp.
     */
    public function match(string $str, bool $dotall = false, array &$matches = null) : bool
    {
        if (!$dotall) {
            return preg_match($this->delim . "^" . $this->regexp . '$' . $this->delim, $str, $matches) === 1;
        } else {
            return preg_match($this->delim . "^" . $this->regexp . '$' . $this->delim . "s", $str, $matches) === 1;
        }
    }

    /**
     * Match the beginning of a string with the regexp.
     */
    public function match_beginning(string $str, bool $dotall = false, array &$matches = null)
    {
        if (!$dotall) {
            return preg_match($this->delim . "^" . $this->regexp . $this->delim, $str, $matches) === 1;
        } else {
            return preg_match($this->delim . "^" . $this->regexp . $this->delim . "s", $str, $matches) === 1;
        }
    }

    /**
     * Search a string with the regexp.
     */
    public function search(string $str, bool $dotall = false, array &$matches = null) : bool
    {
        if (!$dotall) {
            return preg_match($this->delim . $this->regexp . $this->delim, $str, $matches) === 1;
        } else {
            return preg_match($this->delim . $this->regexp . $this->delim . "s", $str, $matches) === 1;
        }
    }
}
