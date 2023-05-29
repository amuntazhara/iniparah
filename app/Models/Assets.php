<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
  use HasFactory;

	protected $table = "assets";
	public static $tableName = "assets";
	public $timestamps = true;
	protected $fillable = ['background', 'wheel', 'pin', 'logo'];
}
