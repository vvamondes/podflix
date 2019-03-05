<?php

use Illuminate\Database\Seeder;
use App\Catalog;

class CatalogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $catalogs[] = (object)array('name' => 'Lançamentos', 'slug' => 'lancamentos');
        $catalogs[] = (object)array('name' => 'Novos Projetos', 'slug' => 'novos-projetos');
        $catalogs[] = (object)array('name' => 'Filmes', 'slug' => 'filmes');
        $catalogs[] = (object)array('name' => 'Jogos', 'slug' => 'jogos');
        $catalogs[] = (object)array('name' => 'Livros e HQs', 'slug' => 'livros-e-hqs');
        $catalogs[] = (object)array('name' => 'Tecnologia', 'slug' => 'tecnologia');
        $catalogs[] = (object)array('name' => 'Variedades', 'slug' => 'variedades');
        $catalogs[] = (object)array('name' => 'Entrevistas', 'slug' => 'entrevistas');
        $catalogs[] = (object)array('name' => 'Música', 'slug' => 'musica');
        $catalogs[] = (object)array('name' => 'Entretenimento', 'slug' => 'entretenimento');
        $catalogs[] = (object)array('name' => 'Esporte', 'slug' => 'esporte');
        $catalogs[] = (object)array('name' => 'Culturas e Tradições Religiosas', 'slug' => 'cultura-e-tradicoes-religiosas');
        $catalogs[] = (object)array('name' => 'História Geral', 'slug' => 'historia-geral');
        $catalogs[] = (object)array('name' => 'Podcasts de Rádios', 'slug' => 'podcasts-de-radios');

        foreach ($catalogs as $catalog) {
            Catalog::create([
                'name' => $catalog->name,
                'slug' => $catalog->slug
            ]);
        }

    }
}
