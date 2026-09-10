<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoinLog extends Model
{
    protected $fillable = ['user_id', 'misi_id', 'jumlah', 'sumber', 'keterangan'];
}