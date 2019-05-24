<?php

namespace Voicecode\NavigationManager\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'navigation_id', 'parent_id', 'name', 'url', 'target', 
        'order', 'draggable', 'editable',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @return array
     */
    protected $casts = [
        'draggable' => 'boolean',
        'editable' => 'boolean',
    ];
    
    /*
     * A navigation has many items.
     */
    public function items()
    {
        return $this->belongsTo(Navigation::class);
    }

    /**
     * A navigation item can have many children.
     */
    public function children()
    {
        return $this->hasMany(NavigationItem::class, 'parent_id', 'id');
    }
}
