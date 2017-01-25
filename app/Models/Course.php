<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'course';

    public $timestamps = false;

    public function students()
    {
        return $this->hasMany('App\Models\Students');
    }

    public function getTotalStudentsAmountAttribute()
    {
        if(!is_null($this->students)) return $this->students->count();
        else return 0;
    }
}
