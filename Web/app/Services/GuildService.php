<?php

namespace App\Services;

class GuildService
{
    /**
     * 
     * @return array[]
     */
    public function mockGuilds()
    {
        return [
            [
                'name'   => 'Sixamo',
                'area'   => 'Japan',
                'language'   => 'Japanese',
                'members'   => '33,958',
                'leader'   => 'Sixamo',
                'type'   => 'Game Guild',
                'description'   => 'GameFi',
                'avatar' => asset('img/admin/demo/guild/sixamo.png'),
            ],
            [
                'name'   => 'Master Guild',
                'area'   => 'Vietnam',
                'language'   => 'Vietnamese',
                'members'   => '56,888',
                'leader'   => 'Chen',
                'type'   => 'Game Guild',
                'description'   => 'Axie Infinity',
                'avatar' => asset('img/admin/demo/guild/master_guild.png'),
            ],
            [
                'name'   => 'Chimsedinang',
                'area'   => 'Vietnam',
                'language'   => 'Vietnamese',
                'members'   => '123,875',
                'leader'   => 'Binh',
                'type'   => 'Game Guild',
                'description'   => 'The biggest Age of Empires Fanclub in Vietnam',
                'avatar' => asset('img/admin/demo/guild/chimsedinang.png'),
            ],
            [
                'name'   => 'Makanan',
                'area'   => 'Malaysia',
                'language'   => 'Malaysian',
                'members'   => '153,259',
                'leader'   => 'Fung',
                'type'   => 'Group',
                'description'   => 'Traditional Foods of Malaysia',
                'avatar' => asset('img/admin/demo/guild/makanan.png'),
            ],
            [
                'name'   => 'Guardians',
                'area'   => 'Phillipines',
                'language'   => 'Phillipines',
                'members'   => '143,993',
                'leader'   => 'Justine',
                'type'   => 'Game Guild',
                'description'   => 'GameFi',
                'avatar' => asset('img/admin/demo/guild/guardians.png'),
            ],
            [
                'name'   => 'Tech Turtles',
                'area'   => 'Global',
                'language'   => 'English',
                'members'   => '153,399',
                'leader'   => 'Limp',
                'type'   => 'Group',
                'description'   => 'Technology Lovers',
                'avatar' => asset('img/admin/demo/guild/tech_turtles.png'),
            ],
        ];
    }
}
