<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;

	protected $table = "prizes";
	public static $tableName = "prizes";
	public $timestamps = false;
	protected $fillable = ['hadiah'];
}
