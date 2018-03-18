<?php

namespace App\Http\Controllers;

use App\Traits\Restable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Restable;

    /**
     * @var int The default pagination size.
     */
    protected $pagination = 5;

    /**
     * @var int The minimum pagination size.
     */
    protected $minLimit = 1;

    /**
     * Getter for the pagination.
     *
     * @return int The pagination size.
     */
    public function getPagination() : int
    {
        
        return $this->pagination;
    }

    /**
     * Sets and checks the pagination.
     *
     * @param int $pagination The given pagination.
     */
    public function setPagination($pagination)
    {
        $this->pagination = (int) $this->checkPagination($pagination);
    }

    /**
     * Checks the pagination.
     *
     * @param * $pagination The pagination.
     *
     * @return int The corrected pagination.
     */
    private function checkPagination($pagination) : int
    {
        // Pagination should be numeric
        if (!is_numeric($pagination)) {
            return $this->pagination;
        }
        // Pagination should not be less than the minimum limitation
        if ($pagination < $this->minLimit) {
            return $this->minLimit;
        }
        // If the pagination is between the min limit and the max limit, return the pagination
        if (!($pagination < $this->minLimit)) {
            return $pagination;
        }

        // If all fails, return the default pagination
        return $this->pagination;
    }

    /**
     * Paginate a given collection.
     *
     * @param Collection $collection The collection.
     * @param int $page The page number.
     * @return void
     */
    public function paginateCollection(Collection $collection, $page)
    {   
        
        return  array_values(
            $collection->slice(($page - 1) * $this->getPagination())
                ->take($this->getPagination())->all()
        );
    }
}
