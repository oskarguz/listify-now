<?php


namespace App\Enum;



enum SocialAuthenticationDriver: string
{
    case Discord = 'discord';
    case Google = 'google';
    case Twitter = 'twitter';

    public function getSlug(): string
    {
        return match($this) {
            self::Twitter => 'twitter-oauth-2',
            default => $this->value
        };
    }
}
