<?php

namespace App\Models\NFT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;
    // protected $guarded = [];
    protected $fillable = ['user_id', 'email','amount','description','due_date','invoice_number','paid','link','stripe_payment_id'];


    public function user() {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }

    public function storeData($data) {
        return static::create($data);
    }

    public function updateData($input, $slug) {
        return static::where('link', $slug)->update($input);
    }
}
