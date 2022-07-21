<?php

/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Request;

use Spiral\Filters\Filter;

/**
 * @property string $n
 */
class AbcRequest extends Filter
{
    protected const SCHEMA = [
        'n' => 'query:name'
    ];

    protected const VALIDATES = [
        'n' => ['notEmpty']
    ];

    protected const SETTERS = [];
}
