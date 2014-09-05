<?php
namespace models;

/**
 * ChannelMessage
 *
 * @uses Collection
 * @author Endel Dreyer <endel.dreyer@gmail.com>
 */
class ChannelMessage extends Collection {
	const EVENT_CONNECTED = 'connected';

	protected $table = 'channel_messages';

	public static function boot() {
		parent::boot();
		static::creating(function($model) { $model->beforeCreate(); });
	}

	public function app() {
		return $this->belongsTo('models\App');
	}

	public function beforeCreate() {
		// Check if a CONNECT message is being created, to
		// generate a unique client_id.
		if ($this->getAttribute('event') && $this->event == self::EVENT_CONNECTED) {
			$this->setAttribute('client_id', uniqid());
			$this->beforeSave();
		}
	}

	public function beforeSave() {
		//
		// Fill auth_id on message if there is a user authenticated.
		//
		$auth_token = AuthToken::current();
		if ($auth_token) {
			$this->setAttribute('auth_id', $auth_token->auth_id);
		}

		parent::beforeSave();
	}

}