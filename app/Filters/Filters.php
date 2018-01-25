<?php
/**
 * Created by PhpStorm.
 * User: Anna
 * Date: 20.01.2018
 * Time: 18:51
 */

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request, $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        // We apply our filters to the builder
        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->builder;
    }

    protected function getFilters()
    {
        return $this->request->only($this->filters);
    }

}