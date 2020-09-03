<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Str;

class User extends Authenticatable {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'locked', 'lock_message'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'total_cents' => 'integer',
        'locked' => 'boolean'
    ];

    public function refreshBalance()
    {
        // Good enough for demo data
        $new_total = $this->entries()->sum('amount_cents');
        $this->update(['total_cents' => $new_total]);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class)->latest('date_time');
    }

    public function avatar()
    {
        return 'https://secure.gravatar.com/avatar/' . md5(Str::lower($this->email)) . '?size=512';
    }

    public function deleteApiToken()
    {
        $this->regenerateApiToken();
    }

    public function regenerateApiToken()
    {
        do {
            $this->api_token = Str::random(60);
        } while ($this->where('api_token', $this->api_token)->exists());

        $this->save();
    }

    public function amountInDollars()
    {
        return $this->total_cents / 100.0;
    }

    public function ownsEntry(Entry $entry)
    {
        return intval($entry->user_id) === intval($this->id);
    }

    public function lock(string $message)
    {
        $this->update(['locked' => true, 'lock_message' => $message]);
    }

    public function unlock()
    {
        $this->update(['locked' => false, 'lock_message' => null]);
    }
}
