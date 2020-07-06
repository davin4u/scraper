<?php

use Illuminate\Database\Seeder;

class InitialDomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $domains = [
            'dns-shop.ru',
            'eldorado.ru',
            'poiskhome.ru',
            'citilink.ru',
            'mvideo.ru',
            'www.notik.ru'
        ];

        foreach ($domains as $domain) {
            \App\Domain::query()->create(['name' => $domain]);
        }
    }
}
