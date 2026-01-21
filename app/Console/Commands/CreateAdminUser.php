<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {--name= : Le nom de l\'administrateur} 
                                      {--email= : L\'adresse email de l\'administrateur} 
                                      {--password= : Le mot de passe (optionnel, généré automatiquement si non fourni)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un nouvel utilisateur administrateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?: $this->ask('Entrez le nom de l\'administrateur');
        $email = $this->option('email') ?: $this->ask('Entrez l\'adresse email de l\'administrateur');
        $password = $this->option('password') ?: $this->generateRandomPassword();

        // Validation des entrées
        $validator = Validator::make(
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );

        if ($validator->fails()) {
            $this->error('Erreur de validation :');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- ' . $error);
            }
            return 1;
        }

        // Création de l'utilisateur admin
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->info('\n✅ Compte administrateur créé avec succès !');
        $this->line('Nom: ' . $user->name);
        $this->line('Email: ' . $user->email);
        $this->line('Mot de passe: ' . $password);
        $this->info('\n⚠️ Notez bien ce mot de passe, il ne sera plus affiché !');
        
        return 0;
    }

    /**
     * Génère un mot de passe aléatoire sécurisé
     */
    protected function generateRandomPassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        $all = $uppercase . $lowercase . $numbers . $special;
        $password = '';
        
        // Au moins un caractère de chaque type
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $special[rand(0, strlen($special) - 1)];
        
        // Remplir le reste du mot de passe
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $all[rand(0, strlen($all) - 1)];
        }
        
        // Mélanger pour plus de sécurité
        return str_shuffle($password);
    }
}
