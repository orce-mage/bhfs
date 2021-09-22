<?php

/**
 * @see       https://github.com/laminas/laminas-mvc for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Mvc\Router\Http;

use Laminas\Mvc\Router\Exception;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Stdlib\RequestInterface as Request;
use Traversable;

/**
 * Query route.
 *
 * @deprecated
 */
class Query implements RouteInterface
{
    /**
     * Default values.
     *
     * @var array
     */
    protected $defaults;

    /**
     * List of assembled parameters.
     *
     * @var array
     */
    protected $assembledParams = [];

    /**
     * Create a new wildcard route.
     *
     * @param array $defaults
     */
    public function __construct(array $defaults = [])
    {
        /**
         * Legacy purposes only, to prevent code that uses it from breaking.
         */
        trigger_error('Query route deprecated as of Laminas 2.1.4; use the "query" option of the HTTP router\'s assembling method instead', E_USER_DEPRECATED);
        $this->defaults = $defaults;
    }

    /**
     * factory(): defined by RouteInterface interface.
     *
     * @see    \Laminas\Mvc\Router\RouteInterface::factory()
     * @param  array|Traversable $options
     * @return Query
     * @throws Exception\InvalidArgumentException
     */
    public static function factory($options = [])
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = [];
        }

        return new static($options['defaults']);
    }

    /**
     * match(): defined by RouteInterface interface.
     *
     * @see    \Laminas\Mvc\Router\RouteInterface::match()
     * @param  Request $request
     * @return RouteMatch
     */
    public function match(Request $request)
    {
        // We don't merge the query parameters into the rotue match here because
        // of possible security problems. Use the Query object instead which is
        // included in the Request object.
        return new RouteMatch($this->defaults);
    }

    /**
     * Recursively urldecodes keys and values from an array
     *
     * @param  array $array
     * @return array
     */
    protected function recursiveUrldecode(array $array)
    {
        $matches = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $matches[urldecode($key)] = $this->recursiveUrldecode($value);
            } else {
                $matches[urldecode($key)] = urldecode($value);
            }
        }

        return $matches;
    }

    /**
     * assemble(): Defined by RouteInterface interface.
     *
     * @see    \Laminas\Mvc\Router\RouteInterface::assemble()
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = [], array $options = [])
    {
        $mergedParams          = array_merge($this->defaults, $params);
        $this->assembledParams = [];

        if (isset($options['uri']) && count($mergedParams)) {
            foreach ($mergedParams as $key => $value) {
                $this->assembledParams[] = $key;
            }

            $options['uri']->setQuery($mergedParams);
        }

        // A query does not contribute to the path, thus nothing is returned.
        return '';
    }

    /**
     * getAssembledParams(): defined by RouteInterface interface.
     *
     * @see    RouteInterface::getAssembledParams
     * @return array
     */
    public function getAssembledParams()
    {
        return $this->assembledParams;
    }
}