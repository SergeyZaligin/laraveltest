<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    const IS_ADMIN = 1;
    const IS_REGISTER = 0;
    const IS_UNBANNED = 0;
    const IS_BANNED = 1;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * Delete images of uploads/
     */
    private function removeImage($image)
    {
        Storage::delete('uploads/', $image);
    }
    /**
     * Add user
     */
    public static function add($fields)
    {
        $user = new static;
        $user = fill($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();

        return $user;
    }
    /**
     * Edit user
     */
    public function edit($fields)
    {
        $this->fill($fields);
        $user->password = bcrypt($fields['password']);
        $this->save();
    }
    /**
     * Delete user
     */
    public function remove($fields)
    {
        removeImage($this->image);
        $this->delete();
    }
    /**
     * Upload image
     */
    public function uploadAvatar($image)
    {
        if(null === $image) return;
        $this->removeImage($this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }
    /**
     * Get image post
     */
    public function getAvatar()
    {
        if(null === $this->image){
            return '/img/no-avatar.png';
        }
        return '/uploads/' . $this->image;
    }
    /**
     * Set user is admin
     */
    public function makeAdmin()
    {
        $this->is_admin = User::IS_ADMIN;
        $this->save();
    }
    /**
     * Set user is admin
     */
    public function makeRegister()
    {
        $this->is_admin = User::IS_REGISTER;
        $this->save();
    }
    /**
     * Toggle user role
     */
    public function toggleRole($value)
    {
        if(null === $value){
            return $this->makeRegister();
        }else{
            return $this->makeAdmin();
        }
    }
    /**
     * Set ban status user
     */
    public function ban()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }
    /**
     * Set unban status user
     */
    public function unban()
    {
        $this->status = User::IS_UNBANNED;
        $this->save();
    }
    /**
     * Toggle status user
     */
    public function toggleStatus($value)
    {
        if(null === $value){
            return $this->status = unban();
        }else{
            return $this->status = ban();
        }
    }
}
