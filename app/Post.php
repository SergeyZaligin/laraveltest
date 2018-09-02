<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;
    protected $fillable = [
        'title',
        'content'
    ];
    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;
    const IS_FEATURED = 0;
    const IS_STANDART = 1;
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }
    public function author()
    {
        return $this->hasOne(User::class);
    }
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
    }
    /**
     * Delete images of uploads/
     */
    private function removeImage($image)
    {
        Storage::delete('uploads/', $image);
    }
    /**
     * Add Post
     */
    public static function add($fields)
    {
        $post = new static;
        $post = fill($fields);
        $post->user_id = 1;
        $post->save();

        return $post;
    }
    /**
     * Edit Post
     */
    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }
    /**
     * Delete Post
     */
    public function remove($fields)
    {
        $this->removeImage($this->image);
        $this->delete();
    }
    /**
     * Upload image
     */
    public function uploadImage($image)
    {
        if(null === $image) return;
        $this->removeImage($this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }
    /**
     * Add category for post
     */
    public function setCategory($id)
    {
        if(null === $id) return;
        $this->category_id = $id;
        $this->save();
    }
    /**
     * Add tags for post
     */
    public function setTags($ids)
    {
        if(null === $ids) return;
        $this->tags()->sync($ids);
    }
    /**
     * Set draft status post
     */
    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }
    /**
     * Set public status post
     */
    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }
    /**
     * Set featured post
     */
    public function setFeatured()
    {
        $this->is_featured = Post::IS_FEATURED;
        $this->save();
    }
    /**
     * Set standart post
     */
    public function setStandart()
    {
        $this->is_featured = Post::IS_STANDART;
        $this->save();
    }
    /**
     * Toggle featured post
     */
    public function toggleFeatured($value)
    {
        if(null === $value){
            return $this->status = setStandart();
        }else{
            return $this->status = setFeatured();
        }
    }
    /**
     * Toggle status post
     */
    public function toggleStatus($value)
    {
        if(null === $value){
            return $this->status = setDraft();
        }else{
            return $this->status = setPublic();
        }
    }
    /**
     * Get image post
     */
    public function getImage()
    {
        if(null === $this->image){
            return '/img/no-img.png';
        }
        return '/uploads/' . $this->image;
    }
}
