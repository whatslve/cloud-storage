<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilesMarkup extends Model
{
    use SoftDeletes;

    protected $table = 'files_markup';

    protected $fillable = ['file_id', 'user_id', 'folder_name'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(Files::class, 'folder_id');
    }

}
