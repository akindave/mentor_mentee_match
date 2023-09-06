<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SkillUser extends Model
{
    use HasFactory;


    public function skills()
    {
        return $this->hasMany(Skill::class,'id');
    }
}
