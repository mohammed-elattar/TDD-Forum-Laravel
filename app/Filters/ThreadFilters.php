<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 4/1/19
 * Time: 11:27 AM
 */

namespace App\Filters;
use App\User;
class ThreadFilters extends Filters
{

    protected $filters = ['by','popular'];

    //we apply or filters to the builder

    /**
     * @param $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }
    protected function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }

}
