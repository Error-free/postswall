<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $appends = ['decodedMessage', 'username'];

	public static function boot()
	{
		parent::boot();

		Post::saving(function ($post) {
			if($post->is_private) {
				$post->message = encrypt($post->message);
			}
		});
	}

	public function getDecodedMessageAttribute()
	{
		$message = $this->message;
		if($this->is_private) {
			$message = decrypt($this->message);
		}
		return $message;
	}

	public function getUsernameAttribute()
	{
		return $this->user->name;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
	  return $this->belongsTo(User::class);
	}
}
