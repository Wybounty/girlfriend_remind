<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Girlfriend extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prenom',
        'nom',
        'surnom',
        'date_anniversaire',
        'signe_astro',
        'allergie',
        'date_rencontre',
        'nom_doudou',
        'plat_prefere',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_anniversaire' => 'date',
        'date_rencontre' => 'date',
    ];

    /**
     * Calculer le signe astrologique basé sur la date d'anniversaire.
     *
     * @param string $date
     * @return string
     */
    public static function calculerSigneAstro(string $date): string
    {
        $date = Carbon::parse($date);
        $jour = $date->day;
        $mois = $date->month;

        return match (true) {
            ($mois == 1 && $jour >= 20) || ($mois == 2 && $jour <= 18) => 'Verseau',
            ($mois == 2 && $jour >= 19) || ($mois == 3 && $jour <= 20) => 'Poissons',
            ($mois == 3 && $jour >= 21) || ($mois == 4 && $jour <= 19) => 'Bélier',
            ($mois == 4 && $jour >= 20) || ($mois == 5 && $jour <= 20) => 'Taureau',
            ($mois == 5 && $jour >= 21) || ($mois == 6 && $jour <= 20) => 'Gémeaux',
            ($mois == 6 && $jour >= 21) || ($mois == 7 && $jour <= 22) => 'Cancer',
            ($mois == 7 && $jour >= 23) || ($mois == 8 && $jour <= 22) => 'Lion',
            ($mois == 8 && $jour >= 23) || ($mois == 9 && $jour <= 22) => 'Vierge',
            ($mois == 9 && $jour >= 23) || ($mois == 10 && $jour <= 22) => 'Balance',
            ($mois == 10 && $jour >= 23) || ($mois == 11 && $jour <= 21) => 'Scorpion',
            ($mois == 11 && $jour >= 22) || ($mois == 12 && $jour <= 21) => 'Sagittaire',
            default => 'Capricorne',
        };
    }

    /**
     * Accessor pour obtenir le nom complet.
     *
     * @return string
     */
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Calculer l'âge basé sur la date d'anniversaire.
     *
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return $this->date_anniversaire->age;
    }

    /**
     * Calculer le nombre de jours depuis la rencontre.
     *
     * @return int
     */
    public function getJoursEnsembleAttribute(): int
    {
        return $this->date_rencontre->diffInDays(now());
    }

    /**
     * Relation avec les informations supplémentaires.
     */
    public function infos(): HasMany
    {
        return $this->hasMany(GirlfriendInfo::class);
    }

    /**
     * Relation avec les avatars pixel art.
     */
    public function pixelAvatars(): HasMany
    {
        return $this->hasMany(PixelAvatar::class);
    }
}
