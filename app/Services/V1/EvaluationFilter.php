<?php

namespace App\Services\V1;

use App\Services\ApiFilter;

class EvaluationFilter extends ApiFilter
{
    protected $safaParms = [
        'id' => ['eq'],
        'evaluationType' => ['eq'],
        'year' => ['eq']
    ];

    protected $columnMap = [
        'evaluationId' => 'customer_id',
        'evaluationType' => 'evaluation_type',
        'year' => 'year',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!=',
    ];

}
