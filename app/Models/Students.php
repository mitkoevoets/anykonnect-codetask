<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'student';

    public $timestamps = false;


    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    public function address()
    {

        return $this->hasOne('StudentAddress', 'id');

    }

    public function getCourseNameAttribute()
    {
        if(!is_null($this->course)) return $this->course->course_name;
        else return '';
    }

    public function getUniversityNameAttribute()
    {
        if(!is_null($this->course)) return $this->course->university;
        else return '';
    }
}
