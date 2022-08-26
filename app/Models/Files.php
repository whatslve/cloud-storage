<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Files extends Model
{

    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = ['user_id', 'file_name', 'file_path', 'folder_id'];
    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
}
