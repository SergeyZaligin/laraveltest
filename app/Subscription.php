<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * Add subscribe
     */
    public static function add($email)
    {
        $subscribe = new static;
        $subscribe->email = $email;
        $subscribe->token = str_random(100);
        $subscribe->save();

        return $subscribe;
    }
    /**
     * Delete subscribe
     */
    public function remove()
    {
        $this->delete();
    }
}
