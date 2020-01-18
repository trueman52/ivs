<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;

class Filter
{
    /**
     * The filterable methods we allow.
     *
     * @var array
     */
    protected $filterables = [];

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The query builder.
     *
     * @var
     */
    protected $builder;

    /**
     * Filter constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply query scopes
     *
     * @param $builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilterables() as $scope => $value) {
            if (method_exists($this, $scope) && !is_null($value)) {
                $this->$scope($value);
            }
        }
    }

    /**
     * Filters created_at column
     *
     * @param $date
     *
     * @return mixed
     */
    public function createdFrom($date)
    {
        return $this->builder->whereDate('created_at', '>=', Carbon::createFromFormat('Y-m-d', $date));
    }

    /**
     * Filters created_at column
     *
     * @param $date
     *
     * @return mixed
     */
    public function createdTo($date)
    {
        return $this->builder->whereDate('created_at', '<=', Carbon::createFromFormat('Y-m-d', $date));
    }

    /**
     * Remove any request queries that's not whitelisted.
     *
     * @return array
     */
    protected function getFilterables()
    {
        return $this->request->only($this->filterables);
    }
}