<?php

/*
*
https://github.com/barryvdh/laravel-cors
https://fractal.thephpleague.com/installation/
*/

namespace App\ModelSample;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class ModelSample extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schema.table';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];
    

}
