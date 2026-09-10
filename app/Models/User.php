<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1) . Str::substr($initials, -1)
            : $initials;
    }

    public function progresMisis()
    {
        return $this->hasMany(ProgresMisi::class);
    }

    public function progresKontens()
    {
        return $this->hasMany(ProgresKonten::class);
    }

    public function poinLogs()
    {
        return $this->hasMany(PoinLog::class);
    }

    public function lencanas()
    {
        return $this->belongsToMany(Lencana::class, 'user_lencanas')->withTimestamps();
    }

    public function rapors()
    {
        return $this->hasMany(Rapor::class);
    }

    public function progresUjians()
    {
        return $this->hasMany(ProgresUjian::class);
    }
    public function jawabanUjians()
    {
        return $this->hasMany(JawabanUjian::class);
    }

    /** Tambah poin & catat ke ledger + update total_poin denormalized */
    public function tambahPoin(int $jumlah, string $sumber, ?int $misiId = null, ?string $keterangan = null): void
    {
        $this->poinLogs()->create([
            'misi_id' => $misiId,
            'jumlah' => $jumlah,
            'sumber' => $sumber,
            'keterangan' => $keterangan,
        ]);
        $this->increment('total_poin', $jumlah);
    }
}
