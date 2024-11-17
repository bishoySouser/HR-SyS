<?php

namespace App\Services\V1;

use App\Services\ApiFilter;

class EvaluationFilter extends ApiFilter
{
    protected $safaParms = [
        'evaluationType' => ['eq'],
        'employeeId' => ['eq'],
        'year' => ['eq']
    ];

    protected $columnMap = [
        'evaluationType' => 'evaluation_type',
        'employeeId' => 'employee.id',
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
