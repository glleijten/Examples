<?php

namespace App;

use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['body', 'subject', 'sent_to_id', 'sender_id'];

    // A message belongs to a sender
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // A message also belongs to a receiver
    public function receiver()
    {
        return $this->belongsTo(User::class, 'sent_to_id');
    }

    // returns messages
    // expects sort (asc, desc etc), sender/recipient column (sent_to_id/sender_id) and the id of the sender/recipient
    public function getMessages($sort,$sender, $senderId) {
        return $this->orderBy('created_at', $order)->where($sender, '=', $senderId)->paginate(5);
    }
}
