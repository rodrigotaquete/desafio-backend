<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'description', 'date_transaction', 'fl_operation', 'fl_reversal', 'value', 'transaction_id'
    ];

    // Dados do usuário proprietário
    public function user()
	{
		return $this->belongsTo(User::class);
	}
}
