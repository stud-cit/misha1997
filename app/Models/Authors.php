<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authors extends Model
{
    protected $table = 'authors';

    protected $fillable = [
        'guid',
        'name',
        'scopus_id',
        'date_bth',
        'job',
        'job_type_id',
        'faculty_code',
        'department_code',
        'name_div',
        'country',
        'h_index',
        'scopus_autor_id',
        'scopus_researcher_id',
        'orcid',
        'academic_code',
        'roles_id',
        'categ_1',
        'categ_2',
        'five_publications',
        'forbes_fortune',
        'token',
        'without_self_citations_wos',
        'without_self_citations_scopus',
        'add_user_id',
        'custom_divisions',
        'test_data',
        'kod_level'
    ];

    protected $appends = ['position'];

    protected $hidden = ['created_at', 'updated_at', 'token', 'test_data'];

    public function getPositionAttribute() {
      $result = "";
      if($this->categ_1 == 1) {
        $result = "Студент";
      } elseif ($this->categ_1 == 2) {
        if($this->kod_level == 9) {
          $result = "Докторант";
        } else {
          $result = "Аспірант";
        }
      } elseif ($this->categ_1 == 3) {
          $result = "Випускник";
      } elseif ($this->categ_2 == 1) {
          $result = "Співробітник";
      } elseif ($this->categ_2 == 2) {
          $result = "Викладач";
      } elseif ($this->categ_2 == 3) {
          $result = "Менеджер";
      } elseif ($this->job_type_id == 6) {
          $result = "СумДУ (не працює)";
      } else {
        $result = $this->jobType ? $this->jobType['title'] : "";
      }
      return $result;
    }

    function role() {
      return $this->belongsTo('App\Models\Roles', 'roles_id');
    }

    function notifications() {
      return $this->hasMany('App\Models\Notifications', 'autors_id');
    }

    function publications() {
      return $this->HasMany('App\Models\AuthorsPublications', 'autors_id');
    }

    function user() {
      return $this->belongsTo('App\Models\Authors', 'add_user_id');
    }

    function jobType() {
      return $this->belongsTo('App\Models\JobType', 'job_type_id');
    }
}
