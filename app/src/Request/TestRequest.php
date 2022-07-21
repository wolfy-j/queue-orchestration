<?php

/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Request;

use Spiral\Filters\Filter;

class TestRequest extends Filter
{
    protected const SCHEMA = [
        'name' => 'query:name'
    ];

    protected const VALIDATES = [
        'name' => [
            'notEmpty'
        ]
    ];

    protected const SETTERS = [];
}
