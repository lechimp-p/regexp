<?php
/******************************************************************************
 * A basic wrapper around PHP's regexps.
 *
 * Copyright (c) 2021 Richard Klees <richard.klees@rwth-aachen.de>
 *
 * This software is licensed under GPLv3. You should have received
 * a copy of the license along with the code.
 */

namespace Lechimp\Regexp\Tests;

use Lechimp\Regexp\Regexp;

class RegexpTest extends \PHPUnit\Framework\TestCase
{
    public function regexp($str)
    {
        return new Regexp($str);
    }

    public function test_raw()
    {
        $re = $this->regexp("ab");
        $this->assertEquals("ab", $re->raw());
    }

    public function test_throws_on_delim()
    {
        try {
            $this->regexp("%");
            $this->assertFalse("This should not happen.");
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    public function test_match()
    {
        $re = $this->regexp("ab");
        $this->assertTrue($re->match("ab"));
        $this->assertFalse($re->match("abc"));
        $this->assertFalse($re->match("0abc"));
        $this->assertFalse($re->match("0ab"));
        $this->assertFalse($re->match("cd"));
    }

    public function test_match_beginning()
    {
        $re = $this->regexp("ab");
        $this->assertTrue($re->matchBeginning("ab"));
        $this->assertTrue($re->matchBeginning("abc"));
        $this->assertFalse($re->matchBeginning("0abc"));
        $this->assertFalse($re->matchBeginning("0ab"));
        $this->assertFalse($re->matchBeginning("cd"));
    }

    public function test_search()
    {
        $re = $this->regexp("ab");
        $this->assertTrue($re->search("ab"));
        $this->assertTrue($re->search("abc"));
        $this->assertTrue($re->search("0abc"));
        $this->assertTrue($re->search("0ab"));
        $this->assertFalse($re->search("cd"));
    }

    public function test_matches()
    {
        $re = $this->regexp("(a)(b)");

        $matches = array();
        $re->match("ab", false, $matches);
        $this->assertEquals(["ab", "a", "b"], $matches);

        $matches = array();
        $re->matchBeginning("ab", false, $matches);
        $this->assertEquals(["ab", "a", "b"], $matches);

        $matches = array();
        $re->search("ab", false, $matches);
        $this->assertEquals(["ab", "a", "b"], $matches);
    }

    public function test_dotall()
    {
        $re = $this->regexp("(a).(b)");

        $this->assertFalse($re->match("a\nb"));
        $this->assertFalse($re->matchBeginning("a\nb"));
        $this->assertFalse($re->search("a\nb"));

        $this->assertTrue($re->match("a\nb", true));
        $this->assertTrue($re->matchBeginning("a\nb", true));
        $this->assertTrue($re->search("a\nb", true));
    }

    public function test_match_backslash()
    {
        $re = $this->regexp("[\\\\]");

        $this->assertTrue($re->match("\\"));
        $this->assertTrue($re->matchBeginning("\\a"));
        $this->assertTrue($re->search("a\\b"));
    }

    public function test_match_namespacelike()
    {
        $re = $this->regexp("A[\\\\]B");

        $this->assertTrue($re->match("A\\B"));
        $this->assertTrue($re->matchBeginning("A\\Ba"));
        $this->assertTrue($re->search("aA\\Bb"));
    }
}
