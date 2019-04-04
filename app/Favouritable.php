<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 4/4/19
 * Time: 12:00 AM
 */

namespace App;


trait Favouritable
{

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourited');
    }

    public function favourite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favourites()->where($attributes)->exists()) {
            $this->favourites()->create($attributes);
        }
    }

    public function isFavourited()
    {
        return !!$this->favourites->where('user_id', auth()->id())->count();
    }

    public function getFavouritesCountAttribute()
    {
        return $this->favourites->count();
    }
}
