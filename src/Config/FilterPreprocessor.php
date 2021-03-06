<?php

namespace SugaredRim\Sami\Config;

use stdClass;
use Schnittstabil\Get\Get;

/**
 * Config `filter` chain link.
 */
class FilterPreprocessor
{
    protected $next;

    public function __construct(callable $next)
    {
        $this->next = $next;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __invoke(stdClass $config)
    {
        $next = $this->next;
        $filter = Get::value('filter', $config);
        unset($config->filter);

        if (empty($filter)) {
            return $next($config);
        }

        $sami = $next($config);
        $sami['filter'] = function () use ($filter) {
            return new $filter();
        };

        return $sami;
    }
}
