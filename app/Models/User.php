<?php

namespace App\Models;

use App\Contracts\Likable;
use App\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'verified',
        'email_token',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_token',
        'email',
        'avatar_path',
    ];

    protected $appends = [
        'avatar'
    ];

    protected $casts = [
        'verified' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(
            function ($user) {
                $user->email_token = $user->generateToken();
            }
        );
    }

    /**
     * @param $username
     * @return \Illuminate\Database\Eloquent\Collection|Model|static
     */
    public static function findByUsernameOrIdOrFail($username)
    {
        if (is_numeric($username)) {
            return static::findOrFail($username);
        }

        return static::byUsername($username);
    }

    /**
     * Find User by username or fail
     *
     * @param $username
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return User
     */
    public static function byUsername($username)
    {
        return static::where('username', $username)->firstOrFail();
    }

    /**
     * Get User by Email Token or throw ModelNotFoundException if doesn't exist
     *
     * @param $token
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return User
     */
    public static function byEmailToken($token)
    {
        return static::where('email_token', $token)->firstOrFail();
    }

    /**
     * User's profile card
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Submit a Post
     *
     * @param $post
     * @return Model
     */
    public function addPost($post)
    {
        if ($post instanceof Post) {
            return $this->posts()->save($post);
        }

        return $this->posts()->create($post);
    }

    /**
     * Get User's posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get User's comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Like a content|Model
     *
     * @param Model $likable
     * @return Model
     */
    public function like(Likable $likable)
    {
        return $this->likes()->save($likable);
//        return $this->likes()->save(
//            new Like([
//                'likable_type' => (new \ReflectionClass($likable))->getName(),
//                'likable_id' => $likable->id,
//            ])
//        );
    }

    /**
     * Get User's Likes
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    /**
     * Follow a User
     *
     * @param $user
     * @return null|void
     */
    public function follow($user)
    {
        if ((is_numeric($user) && $user == $this->id) || ($user instanceof User && $user->id == $this->id)) {
            return null;
        }

        $this->followers()->attach($user);
    }

    public function follows($user)
    {
        return !! $this->following()->where('users.id', $user->id)->count();
    }

    /**
     * Get Users followed by the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    /**
     * Get Users who follow the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    /**
     * Alias to followers(). Get Users followed by the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function friends()
    {
        return $this->followers();
    }

    /**
     * Check if User owns a Model
     *
     * @param Model $model
     * @return bool
     */
    public function owns(Model $model)
    {
        return is_numeric($model->user_id) && $this->id === $model->user_id;
    }

    /**
     * Get all Adjustments made by User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    /**
     * Get User's activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Confirm User's email is verified
     *
     * @return $this
     */
    public function confirmEmail()
    {
        $this->verified = true;
        $this->email_token = null;
        $this->save();

        return $this;
    }

    /**
     * Generates random and unique Email Token for account activation
     *
     * @return string
     */
    private function generateToken()
    {
        $token = str_random(30);
        while (!!self::where('email_token', $token)->count()) {
            $token = str_random(30);
        }

        return $token;
    }

    /**
     * Get all Photos uploaded by User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Count of all Photos uploaded by User
     *
     * @return mixed
     */
    public function getPhotosCountAttribute()
    {
        return $this->photos()->count();
    }

    /**
     * Avatar property - URL to avatar image
     *
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        return action('ImagesController@avatar', ['user' => $this->username]);
    }

    /**
     * Check if User has uploaded avatar
     *
     * @return bool
     */
    public function hasAvatar()
    {
        return !!$this->avatar_path && \Storage::disk()->exists($this->avatar_path);
    }

    public function avatarBaseDir()
    {
        return storage_path('app/public/');
    }

    public function avatarFullPath()
    {
        return storage_path('app/'. $this->avatar_path);
    }

    public function setAvatar($path)
    {
        $this->avatar_path = 'public/'.$path;
        $this->save();

        return $this;
    }

    /**
     * URL to default avatar image
     *
     * @return mixed
     */
    public function defaultAvatar()
    {
        return asset('img/no-avatar.png');
    }

    /**
     * Check if this User is given User
     *
     * @param User $user
     * @return bool
     */
    public function is(User $user)
    {
        return $this->id === $user->id;
    }
}
