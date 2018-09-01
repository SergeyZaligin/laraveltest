<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    const IS_ALLOW = 1;
    const IS_DISALLOW = 0;

    public function post()
    {
        return $this->hasOne(Post::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }
    /**
     * Delete comment
     */
    public function remove($fields)
    {
        $this->delete();
    }
    /**
     * Status comment allow
     */
    public function allow($value='')
    {
        $this->status = Comment::IS_ALLOW;
        $this->save();
    }
    /**
     * Status comment disallow
     */
    public function disallow($value='')
    {
        $this->status = Comment::IS_DISALLOW;
        $this->save();
    }
    /**
     * Toggle status comment published
     */
    public function toggleStatus()
    {
        if(0 === $this->status){
            return $this->allow();
        }else{
            return $this->disallow();
        }
    }
}
