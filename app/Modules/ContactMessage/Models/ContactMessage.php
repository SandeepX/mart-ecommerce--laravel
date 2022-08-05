<?php

namespace App\Modules\ContactMessage\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages';
    protected $fillable = [
        'name',
        'email',
        'subject',
        'phone',
        'message'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }
}
