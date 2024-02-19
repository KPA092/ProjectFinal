<?php

namespace App\Models;

use App\Models\CartItem;
use App\Models\Purchase;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use HasRoles, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

	protected $fillable = [
		'number_id',
		'name',
		'last_name',
		'email',
		'password',
	];

	protected $appends = ['full_name'];

	protected $hidden = [
		'password',
		'remember_token',
	];


	protected $casts = [
		'created_at' => 'datetime:Y-m-d',
		'updated_at' => 'datetime:Y-m-d',
		// 'is_enable' => 'boolean' //0-1:true,false
	];

	/*
	* Accessor (get)
	*/
	public function getFullNameAttribute()
	{
		return "{$this->name} {$this->last_name}"; // Kevin Pidrahita
	}

	/*
	* Mutadores
	 */
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = bcrypt($value);
	}

	public function setRememberTokenAttribute()
	{
		$this->attributes['remember_token'] =  Str::random(30);
	}

	public function purchases()
	{
		return $this->hasMany(Purchase::class, 'user_id', 'id');
	}

	public function cartItems()
	{
		return $this->hasMany(CartItem::class);
	}
}
