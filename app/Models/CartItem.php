<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
	use HasFactory;

	protected $fillable = [
		'product_id',
		'user_id',
		'quantity',
		'price_unit',
		'price_total',
	];


	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id', 'id');
	}

	public function file()
	{
		return $this->morphOne(File::class, 'fileable');
	}
}
