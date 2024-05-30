<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','bug_images','tester_id','status','priority','project_id'];
    protected $attributes = [
        'status' => 'open',
    ];
    public function developers()
    {
        return $this->belongsToMany(User::class, 'bug_developer', 'bug_id', 'developer_id');
    }

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    protected $casts = [
        'bug_images' => 'array',
    ];
}
