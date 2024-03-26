<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $srid = config("database.srid");

        DB::table('regions')->insert([
            [
                'name' => 'Volcano',
                'parent_id' => '0',
                'geometry' => DB::raw('ST_GeometryFromText("POLYGON((-38.202355487134994 145.2807425562172, -38.1764309412612 145.28648978318526, -38.18075233966343 145.3147261591588, -38.198624492215316 145.3099784499243, -38.202355487134994 145.2807425562172))", '.$srid.')')
            ],

            [
                'name' => 'Cinnabar Gym',
                'parent_id' => 0,
                'geometry' => DB::raw('ST_GeometryFromText("POLYGON((-38.2025518499925 145.23501461990614, -38.17112705659141 145.2407618468742, -38.17702023795262 145.28499050658493, -38.201570030408774 145.27874352075006, -38.20530087438675 145.25350569797726, -38.2025518499925 145.23501461990614))", '.$srid.')')
            ],
            [
                'name' => 'Mansion',
                'parent_id' => 0,
                'geometry' => DB::raw('ST_GeometryFromText("POLYGON((-37.924870366933646 145.21387930649396, -37.90325329029293 145.21646050227287, -37.8998065026202 145.22288039946656, -37.89959760120943 145.23241096849637, -37.90487218029982 145.23677914596834, -37.92784613357728 145.23241096849637, -37.924870366933646 145.21387930649396))", '.$srid.')')
            ],
            [
                'name' => 'Cinnabar Lab - Kanto',
                'parent_id' => 0,
                'geometry' => DB::raw('ST_GeometryFromText("POLYGON((-38.147627585080286 145.2455240836383, -38.10968321360177 145.25299135340674, -38.113262439667125 145.2821737869116, -38.132506213794365 145.29487672855493, -38.153094809601086 145.2909285168103, -38.147627585080286 145.2455240836383))", '.$srid.')')
            ],
            [
                'name' => 'Hoenn',
                'parent_id' => 0,
                'geometry' => DB::raw('ST_GeometryFromText("POLYGON((-38.109023969567815 145.25274359823985, -38.08030455619973 145.25750350461396, -38.08498783923175 145.2953844261746, -38.11339332568866 145.2896328726392, -38.109023969567815 145.25274359823985))", '.$srid.')')
            ],
            [
                'name' => 'Homestead Park - Peet',
                'parent_id' => 5,
                'geometry' => DB::raw('ST_GeometryFromText("POLYGON((-38.14187581201284 145.26604154289805, -38.12639938484 145.2686135574738, -38.128928437107945 145.28642475806632, -38.14293779948054 145.28385274352766, -38.14187581201284 145.26604154289805))", '.$srid.')')
            ],
            [
                'name' => 'Mansion park',
                'parent_id' => 3,
                'geometry' => DB::raw('ST_GeometryFromText("POLYGON((-37.92476102121046 145.2148388542881, -37.909800680226915 145.22009192205323, -37.90428820856246 145.23390749042838, -37.907479689759924 145.23537834940612, -37.91980911216249 145.22169410781723, -37.92511323631065 145.2212738623707, -37.92476102121046 145.2148388542881))", '.$srid.')')
            ],
            [
                'name' => 'Leisure area',
                'parent_id' => 7,
                'geometry' => DB::raw('ST_GeometryFromText("POLYGON((-37.923840373199376 145.2172604364925, -37.921915670024596 145.21732930519343, -37.922668483312975 145.22053661898084, -37.9241275221772 145.22021195224775, -37.923840373199376 145.2172604364925))", '.$srid.')')
            ]

        ]);

        DB::table('pokemones')->insert([
            [
                'name' => 'Bulbasaur',
                'image' => 'images/pokemon_portrait/Bulbasaur.png',
                'region_id' => 1
            ],
            [
                'name' => 'Clefairy',
                'image' => 'images/pokemon_portrait/Clefairy.png',
                'region_id' => 2
            ],[
                'name' => 'Golem',
                'image' => 'images/pokemon_portrait/Golem.png',
                'region_id' => 3
            ],[
                'name' => 'Meowth',
                'image' => 'images/pokemon_portrait/Meowth.png',
                'region_id' => 4
            ],[
                'name' => 'Pikachu',
                'image' => 'images/pokemon_portrait/Pikachu.png',
                'region_id' => 5
            ],[
                'name' => 'Poliwag',
                'image' => 'images/pokemon_portrait/Poliwag.png',
                'region_id' => 6
            ],[
                'name' => 'Tangela',
                'image' => 'images/pokemon_portrait/Tangela.png',
                'region_id' => 7
            ],[
                'name' => 'Venonat',
                'image' => 'images/pokemon_portrait/Venonat.png',
                'region_id' => 8
            ],
        ]);



        DB::table('shapes')->insert([
            ['name' => 'head'],
            ['name' => 'head_legs'],
            ['name' => 'fins'],
            ['name' => 'wings']
        ]);
        DB::table('pokemon_shape')->insert([
            'pokemon_id' => 1,
            'shape_id' => 1
        ]);



        DB::table('abilities')->insert([
            [
                'name' => 'immobilize',
                'name_lang_ru' => 'паралич',
                'image' => 'images/pokemon_abilities/immobilize.jpg'
            ]
        ]);

        DB::table('ability_pokemon')->insert([
            [
                'pokemon_id' => 1,
                'ability_id' => 1
            ]
        ]);


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
