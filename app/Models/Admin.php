<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
class Admin extends Model
{
public function boot()
{
    Route::model('admin', Admin::class);
}

}
