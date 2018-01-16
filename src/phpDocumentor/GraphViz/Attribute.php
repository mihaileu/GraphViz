<?php
declare(strict_types=1);

/**
 * phpDocumentor
 *
 * PHP Version 5
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\GraphViz;

/**
 * Class representing a single GraphViz attribute.
 *
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
class Attribute
{
    /** @var string The name of this attribute */
    protected $key = '';

    /** @var string The value of this attribute */
    protected $value = '';

    /**
     * Creating a new attribute.
     *
     * @param string $key   Id for the new attribute.
     * @param string $value Value for this attribute,
     */
    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Sets the key for this attribute.
     *
     * @param string $key The new name of this attribute.
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Returns the name for this attribute.
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Sets the value for this attribute.
     *
     * @param string $value The new value.
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Returns the value for this attribute.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the attribute definition as is requested by GraphViz.
     */
    public function __toString(): string
    {
        $key = $this->getKey();
        if ($key === 'url') {
            $key = 'URL';
        }

        $value = $this->getValue();
        if ($this->isValueContainingSpecials()) {
            $value = '"' . $this->encodeSpecials() . '"';
        } elseif (!$this->isValueInHtml()) {
            $value = '"' . addslashes($value) . '"';
        }

        return $key . '=' . $value;
    }

    /**
     * Returns whether the value contains HTML.
     */
    public function isValueInHtml(): bool
    {
        $value = $this->getValue();

        return isset($value[0]) && ($value[0] === '<');
    }

    /**
     * Checks whether the value contains any any special characters needing escaping.
     */
    public function isValueContainingSpecials(): bool
    {
        return strstr($this->getValue(), '\\') !== false;
    }

    /**
     * Encode special characters so the escape sequences aren't removed
     *
     * @see http://www.graphviz.org/doc/info/attrs.html#k:escString
     */
    protected function encodeSpecials(): string
    {
        $value = $this->getValue();
        $regex = '(\'|"|\\x00|\\\\(?![\\\\NGETHLnlr]))';
        return preg_replace($regex, '\\\\$0', $value);
    }
}
