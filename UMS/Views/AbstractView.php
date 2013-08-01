<?php
namespace UMS\Views;

/**
 * Abstract view
 *
 * @author Jasper Stafleu
 */
abstract class AbstractView implements \Iterator
{
    /**
     * If an object is passed to the constructor, this is where the object is
     * held in.
     *
     * @var mixed
     */
    private $_context = null;

    /**
     * Holder for the iterator of the object, if any
     *
     * @var Iterator
     */
    private $_iterator = null;

    /**
     * Holder for the template this view is using
     *
     * @var string
     */
    protected $_template = null;

    /**
     * Holder for whether the view is in template mode. If it is, __toString()
     * behaves differently.
     *
     * @var boolean
     */
    private $_templateMode = false;

    /**
     * Constructor. Shows the $template, using $context to build with
     *
     * @param string $template
     * @param mixed $context
     */
    public function __construct($template, $context = null)
    {
        $this->_context = $context;

        if ( is_array($context) ) {
            $this->_iterator = new \ArrayIterator($this->_context);
        } else if ( $this->_context instanceof Iterator ) {
            $this->_iterator = $this->_context;
        }

        $this->_template = $template;
    } // show();

    /**
     * Retrieves the current object
     */
    public function getObject()
    {
        return $this->_context;
    } // getObject();

    /**
     * Magic get function
     *
     * @param string $what
     */
    public function __get($what)
    {
        if ( is_array($this->_context) ) {
            $ret = isset($this->_context[$what]) ? $this->_context[$what] : '';
        } else {
            $ret = $this->_context->$what ?: '';
        }

        return $ret;
    } // __get();

    /**
     * (non-PHPdoc)
     * @see Iterator::current()
     */
    public function current()
    {
        return $this->_iterator->current();
    } // current();

    /**
     * (non-PHPdoc)
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->_iterator->key();
    } // key();

    /**
     * (non-PHPdoc)
     * @see Iterator::next()
     */
    public function next()
    {
        $this->_iterator->next();
    } // next();

    /**
     * (non-PHPdoc)
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->_iterator->rewind();
    } // rewind();

    /**
     * (non-PHPdoc)
     * @see Iterator::valid()
     */
    public function valid()
    {
        return $this->_iterator->valid();
    } // valid();

    /**
     * Setting the toString method to return the context allows for scalars to
     * be retrieved through calling $this;
     * @return \UMS\Views\mixed
     */
    public function __toString()
    {
        if ( $this->_templateMode ) {
            return @(string) $this->_context;
        } else {
            $this->_templateMode = true;
            $ret = $this->show();
            $this->_templateMode = false;
            return $ret;
        }
    } // __toString();

    /**
     * Requires the template of the view
     */
    public function show()
    {
        ob_start();
        require TMPLDIR . $this->_template;
        return ob_get_clean();
    } // show();

} // end class UMS\Views\Standard