<?php

namespace Voicecode\NavigationManager\Models;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'depth'
    ];

    /*
     * A navigation has many items.
     */
    public function items()
    {
        return $this->hasMany(NavigationItem::class);
    }
}
