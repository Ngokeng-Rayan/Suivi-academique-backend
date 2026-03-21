<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Ue;
use App\Models\Ec;
use App\Models\Personnel;
use App\Models\Salle;
use App\Models\Enseigne;
use App\Models\Programmation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer des filières de base
        Filiere::insert([
            [
                'code_filiere' => 'INF',
                'label_filiere' => 'Informatique',
                'desc_filiere' => 'Filière spécialisée en Informatique et Développement',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_filiere' => 'GES',
                'label_filiere' => 'Gestion',
                'desc_filiere' => 'Filière de Gestion d\'entreprise',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_filiere' => 'COM',
                'label_filiere' => 'Commerce',
                'desc_filiere' => 'Filière de Commerce et Marketing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Créer des niveaux
        Niveau::insert([
            ['label_niveau' => 'Licence 1', 'desc_niveau' => 'Première année', 'code_filiere' => 'INF', 'created_at' => now(), 'updated_at' => now()],
            ['label_niveau' => 'Licence 2', 'desc_niveau' => 'Deuxième année', 'code_filiere' => 'INF', 'created_at' => now(), 'updated_at' => now()],
            ['label_niveau' => 'Licence 3', 'desc_niveau' => 'Troisième année', 'code_filiere' => 'INF', 'created_at' => now(), 'updated_at' => now()],
            ['label_niveau' => 'Master 1', 'desc_niveau' => 'Première année Master', 'code_filiere' => 'GES', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Créer des UEs
        $niveau1 = Niveau::first()->code_niveau;
        Ue::insert([
            ['code_ue' => 'UE-001', 'label_ue' => 'Programmation Web', 'desc_ue' => 'Introduction au développement web', 'code_niveau' => $niveau1, 'created_at' => now(), 'updated_at' => now()],
            ['code_ue' => 'UE-002', 'label_ue' => 'Base de données', 'desc_ue' => 'Conception de bases de données', 'code_niveau' => $niveau1, 'created_at' => now(), 'updated_at' => now()],
            ['code_ue' => 'UE-003', 'label_ue' => 'Algorithmique', 'desc_ue' => 'Algorithmes et structures de données', 'code_niveau' => $niveau1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Créer des ECs
        Ec::insert([
            ['label_ec' => 'HTML/CSS', 'desc_ec' => 'Bases du web', 'code_ue' => 'UE-001', 'created_at' => now(), 'updated_at' => now()],
            ['label_ec' => 'JavaScript', 'desc_ec' => 'Programmation côté client', 'code_ue' => 'UE-001', 'created_at' => now(), 'updated_at' => now()],
            ['label_ec' => 'SQL', 'desc_ec' => 'Langage de requêtes', 'code_ue' => 'UE-002', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Créer du personnel
        Personnel::insert([
            ['code_pers' => 'PERS-001', 'nom_pers' => 'Dupont Jean', 'sexe_pers' => 'M', 'phone_pers' => '0612345678', 'login_pers' => 'jdupont', 'pwd_pers' => bcrypt('password'), 'type_pers' => 'ENSEIGNANT', 'created_at' => now(), 'updated_at' => now()],
            ['code_pers' => 'PERS-002', 'nom_pers' => 'Martin Marie', 'sexe_pers' => 'F', 'phone_pers' => '0623456789', 'login_pers' => 'mmartin', 'pwd_pers' => bcrypt('password'), 'type_pers' => 'ENSEIGNANT', 'created_at' => now(), 'updated_at' => now()],
            ['code_pers' => 'PERS-003', 'nom_pers' => 'Bernard Paul', 'sexe_pers' => 'M', 'phone_pers' => '0634567890', 'login_pers' => 'pbernard', 'pwd_pers' => bcrypt('password'), 'type_pers' => 'RESPONSABLE ACADEMIQUE', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Créer des salles
        Salle::insert([
            ['num_sale' => 'A101', 'contenance' => 30, 'statut' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
            ['num_sale' => 'A102', 'contenance' => 40, 'statut' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
            ['num_sale' => 'B201', 'contenance' => 50, 'statut' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

