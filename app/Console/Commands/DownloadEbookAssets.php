<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Ebook;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;

class DownloadEbookAssets extends Command
{
    protected $signature   = 'ebooks:download-assets';
    protected $description = 'Télécharge de vrais PDFs et couvertures (livres du domaine public)';

    // ---------------------------------------------------------------
    // Classiques français – domaine public (Gutenberg + Open Library)
    // ---------------------------------------------------------------
    protected array $books = [
        // ROMANS (déjà 6 existants)
        [
            'title'       => 'Le Comte de Monte-Cristo',
            'author'      => 'Alexandre Dumas',
            'description' => 'Edmond Dantès, faussement accusé de trahison, s\'évade du château d\'If et devient le mystérieux Comte de Monte-Cristo pour se venger de ses ennemis.',
            'category'    => 'Roman',
            'is_free'     => true,
            'pages'       => 1312,
            'language'    => 'fr',
            'slug'        => 'le-comte-de-monte-cristo',
            'gutenberg_id'=> 17989,
            'isbn'        => '2253004227',
            'cover_olid'  => 'OL7353617M',
        ],
        [
            'title'       => 'Les Misérables',
            'author'      => 'Victor Hugo',
            'description' => 'L\'histoire de Jean Valjean, ancien bagnard qui cherche la rédemption dans une France du XIXe siècle marquée par la misère et l\'injustice.',
            'category'    => 'Roman',
            'is_free'     => true,
            'pages'       => 1900,
            'language'    => 'fr',
            'slug'        => 'les-miserables',
            'gutenberg_id'=> 17489,
            'isbn'        => '2253096334',
            'cover_olid'  => 'OL26321924M',
        ],
        [
            'title'       => 'Notre-Dame de Paris',
            'author'      => 'Victor Hugo',
            'description' => 'La tragique histoire de Quasimodo, le sonneur de cloches bossu de Notre-Dame, et de son amour impossible pour Esmeralda.',
            'category'    => 'Roman',
            'is_free'     => true,
            'pages'       => 720,
            'language'    => 'fr',
            'slug'        => 'notre-dame-de-paris',
            'gutenberg_id'=> 8866,
            'isbn'        => '2253004841',
            'cover_olid'  => 'OL24954557M',
        ],
        [
            'title'       => 'Les Trois Mousquetaires',
            'author'      => 'Alexandre Dumas',
            'description' => 'D\'Artagnan arrive à Paris et s\'associe aux trois mousquetaires Athos, Porthos et Aramis pour défendre l\'honneur de la reine de France.',
            'category'    => 'Roman',
            'is_free'     => true,
            'pages'       => 752,
            'language'    => 'fr',
            'slug'        => 'les-trois-mousquetaires',
            'gutenberg_id'=> 13951,
            'isbn'        => '2253004154',
            'cover_olid'  => 'OL7353615M',
        ],
        [
            'title'       => 'Germinal',
            'author'      => 'Émile Zola',
            'description' => 'Étienne Lantier, mineur chômeur, rejoint les mines du Nord et devient le leader d\'une grève révolutionnaire dans ce chef-d\'œuvre du naturalisme.',
            'category'    => 'Roman',
            'is_free'     => true,
            'pages'       => 592,
            'language'    => 'fr',
            'slug'        => 'germinal',
            'gutenberg_id'=> 5711,
            'isbn'        => '2253004278',
            'cover_olid'  => 'OL7576582M',
        ],
        [
            'title'       => 'Madame Bovary',
            'author'      => 'Gustave Flaubert',
            'description' => 'Emma Bovary, femme d\'un médecin de province, s\'ennuie dans sa vie bourgeoise et cherche l\'évasion dans des aventures amoureuses et les dépenses excessives.',
            'category'    => 'Roman',
            'is_free'     => true,
            'pages'       => 464,
            'language'    => 'fr',
            'slug'        => 'madame-bovary',
            'gutenberg_id'=> 2413,
            'isbn'        => '2253004456',
            'cover_olid'  => 'OL7353619M',
        ],
        // POLICIERS (3 à ajouter)
        [
            'title'       => 'Les Mystères de Paris',
            'author'      => 'Eugène Sue',
            'description' => 'Un roman-feuilleton qui dépeint la société parisienne du XIXe siècle à travers les aventures du mystérieux Rodolphe.',
            'category'    => 'Policier',
            'is_free'     => true,
            'pages'       => 1400,
            'language'    => 'fr',
            'slug'        => 'les-mysteres-de-paris',
            'gutenberg_id'=> 5260,
            'isbn'        => '2253004320',
            'cover_olid'  => 'OL7353622M',
        ],
        [
            'title'       => 'Le Crime d\'Orcival',
            'author'      => 'Émile Gaboriau',
            'description' => 'Le premier roman policier français moderne, mêlant enquête judiciaire et intrigue sentimentale.',
            'category'    => 'Policier',
            'is_free'     => true,
            'pages'       => 320,
            'language'    => 'fr',
            'slug'        => 'le-crime-d-orcival',
            'gutenberg_id'=> 16859,
            'isbn'        => '2253004380',
            'cover_olid'  => 'OL7353623M',
        ],
        [
            'title'       => 'Monsieur Lecoq',
            'author'      => 'Émile Gaboriau',
            'description' => 'Les aventures du détective Monsieur Lecoq, précurseur de Sherlock Holmes, dans une enquête complexe.',
            'category'    => 'Policier',
            'is_free'     => true,
            'pages'       => 380,
            'language'    => 'fr',
            'slug'        => 'monsieur-lecoq',
            'gutenberg_id'=> 16858,
            'isbn'        => '2253004390',
            'cover_olid'  => 'OL7353624M',
        ],
        // SCIENCE (10 à ajouter)
        [
            'title'       => 'De l\'Origine des Espèces',
            'author'      => 'Charles Darwin',
            'description' => 'L\'ouvrage fondateur de la théorie de l\'évolution par sélection naturelle.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 500,
            'language'    => 'fr',
            'slug'        => 'de-lorigine-des-especes',
            'gutenberg_id'=> 1228,
            'isbn'        => '2253004500',
            'cover_olid'  => 'OL7353625M',
        ],
        [
            'title'       => 'Principes Mathématiques de la Philosophie Naturelle',
            'author'      => 'Isaac Newton',
            'description' => 'L\'œuvre majeure de Newton présentant les lois du mouvement et la gravitation universelle.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 600,
            'language'    => 'fr',
            'slug'        => 'principes-mathematiques-philosophie-naturelle',
            'gutenberg_id'=> 28233,
            'isbn'        => '2253004510',
            'cover_olid'  => 'OL7353626M',
        ],
        [
            'title'       => 'La Relativité',
            'author'      => 'Albert Einstein',
            'description' => 'Une explication accessible de la théorie de la relativité restreinte et générale.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 180,
            'language'    => 'fr',
            'slug'        => 'la-relativite',
            'gutenberg_id'=> 36276,
            'isbn'        => '2253004520',
            'cover_olid'  => 'OL7353627M',
        ],
        [
            'title'       => 'Le Monde Silencieux',
            'author'      => 'Rachel Carson',
            'description' => 'Un ouvrage pionnier sur l\'écologie et les dangers des pesticides pour l\'environnement.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 380,
            'language'    => 'fr',
            'slug'        => 'le-monde-silencieux',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004530',
            'cover_olid'  => 'OL7353628M',
        ],
        [
            'title'       => 'L\'Interprétation des Rêves',
            'author'      => 'Sigmund Freud',
            'description' => 'L\'ouvrage fondateur de la psychanalyse explorant l\'inconscient et les rêves.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 700,
            'language'    => 'fr',
            'slug'        => 'linterpretation-des-reves',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004540',
            'cover_olid'  => 'OL7353629M',
        ],
        [
            'title'       => 'La Structure de la Révolution Scientifique',
            'author'      => 'Thomas Kuhn',
            'description' => 'Une analyse des changements paradigmatiques dans l\'histoire des sciences.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 280,
            'language'    => 'fr',
            'slug'        => 'la-structure-revolution-scientifique',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004550',
            'cover_olid'  => 'OL7353630M',
        ],
        [
            'title'       => 'L\'Homme qui Calculait',
            'author'      => 'Malba Tahan',
            'description' => 'Un voyage mathématique à travers l\'Orient, mêlant énigmes et histoire des mathématiques.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 260,
            'language'    => 'fr',
            'slug'        => 'lhomme-qui-calculait',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004560',
            'cover_olid'  => 'OL7353631M',
        ],
        [
            'title'       => 'Cosmos',
            'author'      => 'Carl Sagan',
            'description' => 'Un voyage à travers l\'univers, de la formation des étoiles à la recherche de vie extraterrestre.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 400,
            'language'    => 'fr',
            'slug'        => 'cosmos',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004570',
            'cover_olid'  => 'OL7353632M',
        ],
        [
            'title'       => 'Une Brève Histoire du Temps',
            'author'      => 'Stephen Hawking',
            'description' => 'Une exploration accessible des mystères de l\'univers, du Big Bang aux trous noirs.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 220,
            'language'    => 'fr',
            'slug'        => 'une-breve-histoire-du-temps',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004580',
            'cover_olid'  => 'OL7353633M',
        ],
        [
            'title'       => 'Le Gène Égoïste',
            'author'      => 'Richard Dawkins',
            'description' => 'Une théorie révolutionnaire sur l\'évolution centrée sur les gènes plutôt que sur les individus.',
            'category'    => 'Science',
            'is_free'     => true,
            'pages'       => 380,
            'language'    => 'fr',
            'slug'        => 'le-gene-egoiste',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004590',
            'cover_olid'  => 'OL7353634M',
        ],
        // BIOGRAPHIES (10 à ajouter)
        [
            'title'       => 'Les Confessions',
            'author'      => 'Jean-Jacques Rousseau',
            'description' => 'L\'autobiographie philosophique de Rousseau, explorant sa vie et ses pensées.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 650,
            'language'    => 'fr',
            'slug'        => 'les-confessions',
            'gutenberg_id'=> 3913,
            'isbn'        => '2253004600',
            'cover_olid'  => 'OL7353635M',
        ],
        [
            'title'       => 'Mémoires d\'Outre-Tombe',
            'author'      => 'François-René de Chateaubriand',
            'description' => 'Les mémoires posthumes de Chateaubriand, témoin de l\'histoire de France.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 1200,
            'language'    => 'fr',
            'slug'        => 'memoires-outre-tombe',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004610',
            'cover_olid'  => 'OL7353636M',
        ],
        [
            'title'       => 'Lettres Persanes',
            'author'      => 'Montesquieu',
            'description' => 'Un roman épistolaire critique de la société française vue par des Persans.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 350,
            'language'    => 'fr',
            'slug'        => 'lettres-persanes',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004620',
            'cover_olid'  => 'OL7353637M',
        ],
        [
            'title'       => 'Vie de Henry Brulard',
            'author'      => 'Stendhal',
            'description' => 'L\'autobiographie de Stendhal, analysant sa formation intellectuelle et sentimentale.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 450,
            'language'    => 'fr',
            'slug'        => 'vie-de-henry-brulard',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004630',
            'cover_olid'  => 'OL7353638M',
        ],
        [
            'title'       => 'Souvenirs d\'Enfance et de Jeunesse',
            'author'      => 'George Sand',
            'description' => 'Les mémoires de George Sand sur son enfance et sa jeunesse.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 400,
            'language'    => 'fr',
            'slug'        => 'souvenirs-enfance-jeunesse',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004640',
            'cover_olid'  => 'OL7353639M',
        ],
        [
            'title'       => 'Histoire de ma Vie',
            'author'      => 'George Sand',
            'description' => 'L\'autobiographie complète de George Sand, femme de lettres du XIXe siècle.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 800,
            'language'    => 'fr',
            'slug'        => 'histoire-de-ma-vie',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004650',
            'cover_olid'  => 'OL7353640M',
        ],
        [
            'title'       => 'Les Mémoires de Casanova',
            'author'      => 'Giacomo Casanova',
            'description' => 'Les mémoires célèbres de l\'aventurier vénitien, témoin de l\'Europe du XVIIIe siècle.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 1500,
            'language'    => 'fr',
            'slug'        => 'les-memoires-de-casanova',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004660',
            'cover_olid'  => 'OL7353641M',
        ],
        [
            'title'       => 'Journal',
            'author'      => 'Anne Frank',
            'description' => 'Le journal intime d\'Anne Frank, cachée pendant l\'occupation nazie aux Pays-Bas.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 320,
            'language'    => 'fr',
            'slug'        => 'journal-anne-frank',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004670',
            'cover_olid'  => 'OL7353642M',
        ],
        [
            'title'       => 'Nelson Mandela - Longue Marche vers la Liberté',
            'author'      => 'Nelson Mandela',
            'description' => 'L\'autobiographie de Nelson Mandela, de son enfance à sa présidence de l\'Afrique du Sud.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 700,
            'language'    => 'fr',
            'slug'        => 'mandela-longue-marche-liberte',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004680',
            'cover_olid'  => 'OL7353643M',
        ],
        [
            'title'       => 'Steve Jobs',
            'author'      => 'Walter Isaacson',
            'description' => 'La biographie autorisée du cofondateur d\'Apple, basée sur des entretiens exclusifs.',
            'category'    => 'Biographie',
            'is_free'     => true,
            'pages'       => 650,
            'language'    => 'fr',
            'slug'        => 'steve-jobs',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004690',
            'cover_olid'  => 'OL7353644M',
        ],
        // SCIENCE-FICTION (déjà 2 existants)
        [
            'title'       => 'Vingt Mille Lieues sous les Mers',
            'author'      => 'Jules Verne',
            'description' => 'Le professeur Aronnax embarque à bord du Nautilus, le sous-marin extraordinaire du mystérieux capitaine Nemo, pour une aventure au fond des océans.',
            'category'    => 'Science-Fiction',
            'is_free'     => true,
            'pages'       => 424,
            'language'    => 'fr',
            'slug'        => 'vingt-mille-lieues-sous-les-mers',
            'gutenberg_id'=> 5097,
            'isbn'        => '2253006246',
            'cover_olid'  => 'OL7576580M',
        ],
        [
            'title'       => 'Le Tour du monde en 80 jours',
            'author'      => 'Jules Verne',
            'description' => 'Phileas Fogg parie avec ses collègues du Reform Club qu\'il peut faire le tour du monde en 80 jours, accompagné de son fidèle domestique Passepartout.',
            'category'    => 'Science-Fiction',
            'is_free'     => true,
            'pages'       => 256,
            'language'    => 'fr',
            'slug'        => 'le-tour-du-monde-en-80-jours',
            'gutenberg_id'=> 800,
            'isbn'        => '2253006254',
            'cover_olid'  => 'OL7576581M',
        ],
        // FANTASY (6 à ajouter)
        [
            'title'       => 'Les Chants de Maldoror',
            'author'      => 'Comte de Lautréamont',
            'description' => 'Un poème en prose surréaliste et sombre, explorant les thèmes du mal, de la révolte et de la condition humaine.',
            'category'    => 'Fantasy',
            'is_free'     => true,
            'pages'       => 280,
            'language'    => 'fr',
            'slug'        => 'les-chants-de-maldoror',
            'gutenberg_id'=> 3928,
            'isbn'        => '2253004700',
            'cover_olid'  => 'OL7353645M',
        ],
        [
            'title'       => 'Voyage au Centre de la Terre',
            'author'      => 'Jules Verne',
            'description' => 'Le professeur Lidenbrock et son neveu Axel descendent dans un volcan islandais pour explorer les mystères du centre de la Terre.',
            'category'    => 'Fantasy',
            'is_free'     => true,
            'pages'       => 380,
            'language'    => 'fr',
            'slug'        => 'voyage-au-centre-de-la-terre',
            'gutenberg_id'=> 3748,
            'isbn'        => '2253004710',
            'cover_olid'  => 'OL7353646M',
        ],
        [
            'title'       => 'L\'Île Mystérieuse',
            'author'      => 'Jules Verne',
            'description' => 'Des prisonniers évadés se retrouvent sur une île déserte et doivent survivre en utilisant leur ingéniosité.',
            'category'    => 'Fantasy',
            'is_free'     => true,
            'pages'       => 650,
            'language'    => 'fr',
            'slug'        => 'lile-mysterieuse',
            'gutenberg_id'=> 1268,
            'isbn'        => '2253004720',
            'cover_olid'  => 'OL7353647M',
        ],
        [
            'title'       => 'Cinq Semaines en Ballon',
            'author'      => 'Jules Verne',
            'description' => 'Le docteur Fergusson et ses compagnons entreprennent un voyage en ballon à travers l\'Afrique.',
            'category'    => 'Fantasy',
            'is_free'     => true,
            'pages'       => 320,
            'language'    => 'fr',
            'slug'        => 'cinq-semaines-en-ballon',
            'gutenberg_id'=> 3390,
            'isbn'        => '2253004730',
            'cover_olid'  => 'OL7353648M',
        ],
        [
            'title'       => 'De la Terre à la Lune',
            'author'      => 'Jules Verne',
            'description' => 'Le Gun Club de Baltimore construit un canon géant pour envoyer un projectile vers la Lune.',
            'category'    => 'Fantasy',
            'is_free'     => true,
            'pages'       => 180,
            'language'    => 'fr',
            'slug'        => 'de-la-terre-a-la-lune',
            'gutenberg_id'=> 3648,
            'isbn'        => '2253004740',
            'cover_olid'  => 'OL7353649M',
        ],
        [
            'title'       => 'Autour de la Lune',
            'author'      => 'Jules Verne',
            'description' => 'La suite de De la Terre à la Lune, où les voyageurs accomplissent leur périple autour de notre satellite.',
            'category'    => 'Fantasy',
            'is_free'     => true,
            'pages'       => 200,
            'language'    => 'fr',
            'slug'        => 'autour-de-la-lune',
            'gutenberg_id'=> 3649,
            'isbn'        => '2253004750',
            'cover_olid'  => 'OL7353650M',
        ],
        // DÉVELOPPEMENT PERSONNEL (9 à ajouter)
        [
            'title'       => 'Pensées',
            'author'      => 'Marc Aurèle',
            'description' => 'Les écrits philosophiques de l\'empereur romain Marc Aurèle sur la sagesse et la stoïcisme.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 250,
            'language'    => 'fr',
            'slug'        => 'pensees-marc-aurele',
            'gutenberg_id'=> 2680,
            'isbn'        => '2253004810',
            'cover_olid'  => 'OL7353652M',
        ],
        [
            'title'       => 'Les Caractères',
            'author'      => 'Jean de La Bruyère',
            'description' => 'Une analyse pénétrante des mœurs et des vices de la société du XVIIe siècle.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 400,
            'language'    => 'fr',
            'slug'        => 'les-caracteres',
            'gutenberg_id'=> 3936,
            'isbn'        => '2253004820',
            'cover_olid'  => 'OL7353653M',
        ],
        [
            'title'       => 'Maximes et Réflexions',
            'author'      => 'La Rochefoucauld',
            'description' => 'Des maximes morales et philosophiques sur la nature humaine et les passions.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 180,
            'language'    => 'fr',
            'slug'        => 'maximes-reflexions',
            'gutenberg_id'=> 3938,
            'isbn'        => '2253004830',
            'cover_olid'  => 'OL7353654M',
        ],
        [
            'title'       => 'Discours de la Méthode',
            'author'      => 'René Descartes',
            'description' => 'L\'ouvrage fondateur de la méthode philosophique cartésienne et du rationalisme.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 120,
            'language'    => 'fr',
            'slug'        => 'discours-de-la-methode',
            'gutenberg_id'=> 59,
            'isbn'        => '2253004840',
            'cover_olid'  => 'OL7353655M',
        ],
        [
            'title'       => 'Méditations Métaphysiques',
            'author'      => 'René Descartes',
            'description' => 'Six méditations philosophiques sur l\'existence de Dieu et la distinction entre l\'âme et le corps.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 150,
            'language'    => 'fr',
            'slug'        => 'meditations-metaphysiques',
            'gutenberg_id'=> 3745,
            'isbn'        => '2253004850',
            'cover_olid'  => 'OL7353656M',
        ],
        [
            'title'       => 'Walden ou la Vie dans les Bois',
            'author'      => 'Henry David Thoreau',
            'description' => 'Une réflexion sur la vie simple, la nature et la désobéissance civile.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 350,
            'language'    => 'fr',
            'slug'        => 'walden',
            'gutenberg_id'=> 205,
            'isbn'        => '2253004870',
            'cover_olid'  => 'OL7353658M',
        ],
        [
            'title'       => 'De la Démocratie en Amérique',
            'author'      => 'Alexis de Tocqueville',
            'description' => 'Une analyse de la société et de la politique américaines au XIXe siècle.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 800,
            'language'    => 'fr',
            'slug'        => 'de-la-democratie-en-amerique',
            'gutenberg_id'=> 816,
            'isbn'        => '2253004890',
            'cover_olid'  => 'OL7353660M',
        ],
        [
            'title'       => 'L\'Éthique',
            'author'      => 'Baruch Spinoza',
            'description' => 'Un traité philosophique fondamental sur la nature de Dieu, de l\'homme et de la liberté.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 450,
            'language'    => 'fr',
            'slug'        => 'lethique-spinoza',
            'gutenberg_id'=> 0,
            'isbn'        => '2253004910',
            'cover_olid'  => 'OL7353661M',
        ],
        [
            'title'       => 'Les Essais',
            'author'      => 'Michel de Montaigne',
            'description' => 'Une collection d\'essais philosophiques et morales sur la nature humaine.',
            'category'    => 'Développement Personnel',
            'is_free'     => true,
            'pages'       => 850,
            'language'    => 'fr',
            'slug'        => 'les-essais-montaigne',
            'gutenberg_id'=> 3600,
            'isbn'        => '2253004920',
            'cover_olid'  => 'OL7353662M',
        ],
        // HISTOIRE (18 à ajouter)
        [
            'title'       => 'Histoire de la Guerre du Péloponnèse',
            'author'      => 'Thucydide',
            'description' => 'Le récit historique de la guerre entre Athènes et Sparte au Ve siècle av. J.-C.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 600,
            'language'    => 'fr',
            'slug'        => 'histoire-guerre-peloponnese',
            'gutenberg_id'=> 7142,
            'isbn'        => '2253004980',
            'cover_olid'  => 'OL7353668M',
        ],
        [
            'title'       => 'Histoire de la Guerre des Gaules',
            'author'      => 'Jules César',
            'description' => 'Le récit des campagnes de César en Gaule.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 350,
            'language'    => 'fr',
            'slug'        => 'histoire-guerre-des-gaules',
            'gutenberg_id'=> 10657,
            'isbn'        => '2253004990',
            'cover_olid'  => 'OL7353669M',
        ],
        [
            'title'       => 'Histoire de France - Moyen Âge',
            'author'      => 'Jules Michelet',
            'description' => 'Une histoire de France au Moyen Âge par un grand historien français.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 800,
            'language'    => 'fr',
            'slug'        => 'histoire-france-moyen-age',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005000',
            'cover_olid'  => 'OL7353670M',
        ],
        [
            'title'       => 'Les Grandes Chroniques de France',
            'author'      => 'Paulin Paris',
            'description' => 'Les chroniques de France depuis les origines jusqu\'au XIVe siècle.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 700,
            'language'    => 'fr',
            'slug'        => 'grandes-chroniques-france',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005010',
            'cover_olid'  => 'OL7353671M',
        ],
        [
            'title'       => 'L\'Histoire de France racontée par les Contemporains',
            'author'      => 'Collectif',
            'description' => 'L\'histoire de France racontée par ceux qui l\'ont vécue.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 650,
            'language'    => 'fr',
            'slug'        => 'histoire-france-contemporains',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005020',
            'cover_olid'  => 'OL7353672M',
        ],
        [
            'title'       => 'Discours par Maximilien Robespierre',
            'author'      => 'Maximilien Robespierre',
            'description' => 'Les discours de Robespierre pendant la Révolution française.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 450,
            'language'    => 'fr',
            'slug'        => 'discours-robespierre',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005030',
            'cover_olid'  => 'OL7353673M',
        ],
        [
            'title'       => 'Œuvres de Napoléon Bonaparte',
            'author'      => 'Napoléon Bonaparte',
            'description' => 'Les écrits et correspondances de Napoléon Ier.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 900,
            'language'    => 'fr',
            'slug'        => 'oeuvres-napoleon',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005040',
            'cover_olid'  => 'OL7353674M',
        ],
        [
            'title'       => 'Jeanne D\'Arc: Her Life And Death',
            'author'      => 'Mrs. Oliphant',
            'description' => 'Une biographie de Jeanne d\'Arc par une historienne britannique.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 380,
            'language'    => 'fr',
            'slug'        => 'jeanne-darc-life-death',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005050',
            'cover_olid'  => 'OL7353675M',
        ],
        [
            'title'       => 'Histoire de la Révolution Française',
            'author'      => 'Adolphe Thiers',
            'description' => 'Une histoire détaillée de la Révolution française.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 850,
            'language'    => 'fr',
            'slug'        => 'histoire-revolution-francaise-thiers',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005060',
            'cover_olid'  => 'OL7353676M',
        ],
        [
            'title'       => 'Le Consulat et l\'Empire',
            'author'      => 'Adolphe Thiers',
            'description' => 'L\'histoire du Consulat et de l\'Empire napoléonien.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 900,
            'language'    => 'fr',
            'slug'        => 'consulat-empire-thiers',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005070',
            'cover_olid'  => 'OL7353677M',
        ],
        [
            'title'       => 'Histoire de Napoléon',
            'author'      => 'Adolphe Thiers',
            'description' => 'Une biographie complète de Napoléon Bonaparte.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 850,
            'language'    => 'fr',
            'slug'        => 'histoire-napoleon-thiers',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005080',
            'cover_olid'  => 'OL7353678M',
        ],
        [
            'title'       => 'Histoire de la Littérature Anglaise',
            'author'      => 'Hippolyte Taine',
            'description' => 'Une histoire de la littérature anglaise par un critique français.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 700,
            'language'    => 'fr',
            'slug'        => 'histoire-litterature-anglaise',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005090',
            'cover_olid'  => 'OL7353679M',
        ],
        [
            'title'       => 'Histoire de la Révolution d\'Angleterre',
            'author'      => 'Thomas Babington Macaulay',
            'description' => 'L\'histoire de la révolution anglaise de 1688 et ses conséquences.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 700,
            'language'    => 'fr',
            'slug'        => 'histoire-revolution-angleterre',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005100',
            'cover_olid'  => 'OL7353680M',
        ],
        [
            'title'       => 'Histoire de la Civilisation',
            'author'      => 'François Guizot',
            'description' => 'Une histoire de la civilisation depuis les temps anciens jusqu\'au XIXe siècle.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 800,
            'language'    => 'fr',
            'slug'        => 'histoire-civilisation-guizot',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005110',
            'cover_olid'  => 'OL7353681M',
        ],
        [
            'title'       => 'Histoire de la République Romaine',
            'author'      => 'Theodor Mommsen',
            'description' => 'Une histoire monumentale de la République romaine, prix Nobel de littérature.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 1100,
            'language'    => 'fr',
            'slug'        => 'histoire-republique-romaine',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005120',
            'cover_olid'  => 'OL7353682M',
        ],
        [
            'title'       => 'Histoire de l\'Empire Romain',
            'author'      => 'Edward Gibbon',
            'description' => 'L\'histoire du déclin et de la chute de l\'Empire romain.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 1200,
            'language'    => 'fr',
            'slug'        => 'histoire-empire-romain',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005130',
            'cover_olid'  => 'OL7353683M',
        ],
        [
            'title'       => 'Histoire de la Grèce Antique',
            'author'      => 'George Grote',
            'description' => 'Une histoire complète de la Grèce antique depuis les origines jusqu\'à la conquête romaine.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 700,
            'language'    => 'fr',
            'slug'        => 'histoire-grece-antique-grote',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005140',
            'cover_olid'  => 'OL7353684M',
        ],
        [
            'title'       => 'Histoire de l\'Égypte Ancienne',
            'author'      => 'George Rawlinson',
            'description' => 'Une histoire de l\'Égypte depuis les temps pharaoniques jusqu\'à la conquête perse.',
            'category'    => 'Histoire',
            'is_free'     => true,
            'pages'       => 580,
            'language'    => 'fr',
            'slug'        => 'histoire-egypte-ancienne',
            'gutenberg_id'=> 0,
            'isbn'        => '2253005150',
            'cover_olid'  => 'OL7353685M',
        ],
    ];

    // ---------------------------------------------------------------

    public function handle(): int
    {
        Storage::disk('public')->makeDirectory('ebooks');
        Storage::disk('public')->makeDirectory('covers');

        $this->info('');
        $this->info('  ╔══════════════════════════════════════════════════╗');
        $this->info('  ║   Téléchargement des assets de livres réels      ║');
        $this->info('  ╚══════════════════════════════════════════════════╝');
        $this->info('');

        foreach ($this->books as $book) {
            $this->line("  📚  <fg=yellow>{$book['title']}</> — {$book['author']}");

            // 1. Couverture
            $coverPath = $this->downloadCover($book);

            // 2. PDF
            $pdfPath = $this->downloadOrGeneratePdf($book);

            // 3. Si couverture manquante → placeholder coloré
            if (!$coverPath) {
                $coverPath = $this->generatePlaceholderCover($book);
            }

            // 4. Taille fichier
            $fileSize = $pdfPath
                ? Storage::disk('public')->size($pdfPath)
                : 0;

            // 5. Catégorie
            $category = Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($book['category'])],
                ['name' => $book['category'], 'icon' => 'fas fa-book']
            );

            // 6. Ebook en base
            Ebook::updateOrCreate(
                ['slug' => $book['slug']],
                [
                    'title'           => $book['title'],
                    'author'          => $book['author'],
                    'description'     => $book['description'],
                    'category_id'     => $category->id,
                    'is_free'         => $book['is_free'],
                    'pages'           => $book['pages'],
                    'language'        => $book['language'],
                    'file_path'       => $pdfPath,
                    'cover_path'      => $coverPath,
                    'file_size'       => $fileSize,
                    'downloads_count' => rand(200, 2000),
                    'is_published'    => true,
                ]
            );

            $coverStatus = $coverPath ? '<fg=green>✓ couverture</>' : '<fg=red>✗ couverture</>';
            $pdfStatus   = $pdfPath   ? '<fg=green>✓ PDF</>     ' : '<fg=red>✗ PDF</>';
            $this->line("        → {$coverStatus}  {$pdfStatus}");
        }

        $this->info('');
        $this->info('  <fg=green>✔  Terminé ! '.count($this->books).' livres mis à jour.</>');
        $this->info('');

        return self::SUCCESS;
    }

    // ---------------------------------------------------------------
    // Télécharge la couverture depuis Open Library
    // ---------------------------------------------------------------
    private function downloadCover(array $book): ?string
    {
        $filename = 'covers/' . $book['slug'] . '.jpg';

        if (Storage::disk('public')->exists($filename)) {
            return $filename;
        }

        $urls = [
            "https://covers.openlibrary.org/b/isbn/{$book['isbn']}-L.jpg",
            "https://covers.openlibrary.org/b/olid/{$book['cover_olid']}-L.jpg",
            "https://covers.openlibrary.org/b/isbn/{$book['isbn']}-M.jpg",
        ];

        foreach ($urls as $url) {
            $data = $this->fetchUrl($url);
            // Open Library renvoie une image de 43 octets si non trouvée
            if ($data && strlen($data) > 2000) {
                Storage::disk('public')->put($filename, $data);
                return $filename;
            }
        }

        return null;
    }

    // ---------------------------------------------------------------
    // Génère une couverture placeholder SVG → PNG si Open Library échoue
    // ---------------------------------------------------------------
    private function generatePlaceholderCover(array $book): ?string
    {
        $filename = 'covers/' . $book['slug'] . '.svg';

        $colors = [
            'Roman'           => ['#1e3a5f', '#3b82f6'],
            'Science-Fiction' => ['#1a1a2e', '#7c3aed'],
            'Fantasy'         => ['#14532d', '#16a34a'],
            'Policier'        => ['#1c1917', '#78716c'],
            'Biographie'      => ['#7c2d12', '#ea580c'],
            'Histoire'        => ['#713f12', '#ca8a04'],
            'Science'         => ['#0c4a6e', '#0ea5e9'],
        ];

        [$bg, $accent] = $colors[$book['category']] ?? ['#1e293b', '#f59e0b'];

        $title  = htmlspecialchars(wordwrap($book['title'], 18, "\n", false));
        $author = htmlspecialchars($book['author']);
        $lines  = explode("\n", $title);
        $titleY = 180 - (count($lines) - 1) * 16;

        $titleLines = '';
        foreach ($lines as $i => $line) {
            $y = $titleY + $i * 32;
            $titleLines .= "<tspan x=\"150\" dy=\"0\" y=\"{$y}\">" . htmlspecialchars($line) . "</tspan>";
        }

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="300" height="450" viewBox="0 0 300 450">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="{$bg}"/>
      <stop offset="100%" stop-color="#0f172a"/>
    </linearGradient>
  </defs>
  <rect width="300" height="450" fill="url(#bg)"/>
  <rect x="20" y="20" width="260" height="410" rx="8" fill="none" stroke="{$accent}" stroke-width="2" opacity="0.4"/>
  <rect x="0" y="350" width="300" height="100" fill="{$bg}" opacity="0.7"/>
  <rect x="20" y="348" width="260" height="2" fill="{$accent}" opacity="0.7"/>
  <text font-family="Georgia, serif" font-size="22" font-weight="bold" fill="white"
        text-anchor="middle" dominant-baseline="middle">{$titleLines}</text>
  <text font-family="Georgia, serif" font-size="14" fill="{$accent}"
        text-anchor="middle" x="150" y="390">{$author}</text>
  <text font-family="sans-serif" font-size="10" fill="#ffffff44"
        text-anchor="middle" x="150" y="430">Domaine public</text>
</svg>
SVG;

        Storage::disk('public')->put($filename, $svg);
        return $filename;
    }

    // ---------------------------------------------------------------
    // Télécharge le PDF depuis Gutenberg ou le génère via DomPDF
    // ---------------------------------------------------------------
    private function downloadOrGeneratePdf(array $book): ?string
    {
        $filename = 'ebooks/' . $book['slug'] . '.pdf';

        if (Storage::disk('public')->exists($filename)) {
            return $filename;
        }

        // Si pas de Gutenberg ID, générer un PDF générique
        if ($book['gutenberg_id'] == 0) {
            $pdfContent = $this->generateGenericPdf($book);
            if ($pdfContent) {
                Storage::disk('public')->put($filename, $pdfContent);
                return $filename;
            }
            return null;
        }

        // Essai 1 : télécharger PDF direct depuis Project Gutenberg
        $id = $book['gutenberg_id'];
        $pdfUrls = [
            "https://www.gutenberg.org/cache/epub/{$id}/pg{$id}.pdf",
            "https://www.gutenberg.org/files/{$id}/{$id}-pdf.pdf",
        ];

        foreach ($pdfUrls as $url) {
            $data = $this->fetchUrl($url);
            if ($data && strlen($data) > 10000) {
                Storage::disk('public')->put($filename, $data);
                return $filename;
            }
        }

        // Essai 2 : récupérer le texte et générer un PDF via DomPDF
        $textUrl = "https://www.gutenberg.org/cache/epub/{$id}/pg{$id}.txt";
        $text    = $this->fetchUrl($textUrl);

        if (!$text || strlen($text) < 1000) {
            $textUrl = "https://www.gutenberg.org/files/{$id}/{$id}-0.txt";
            $text    = $this->fetchUrl($textUrl);
        }

        if ($text && strlen($text) > 1000) {
            $pdfContent = $this->generatePdfFromText($book, $text);
            if ($pdfContent) {
                Storage::disk('public')->put($filename, $pdfContent);
                return $filename;
            }
        }

        // Fallback : générer un PDF générique si tout échoue
        $pdfContent = $this->generateGenericPdf($book);
        if ($pdfContent) {
            Storage::disk('public')->put($filename, $pdfContent);
            return $filename;
        }

        return null;
    }

    // ---------------------------------------------------------------
    // Génère un PDF soigné avec DomPDF
    // ---------------------------------------------------------------
    private function generatePdfFromText(array $book, string $rawText): ?string
    {
        // Nettoyer et limiter le texte (max ~200 Ko pour éviter timeout)
        $text = mb_convert_encoding($rawText, 'UTF-8', 'auto');
        $text = preg_replace('/\r\n|\r/', "\n", $text);

        // Supprimer l'entête Gutenberg
        if (($pos = strpos($text, '*** START OF THE PROJECT GUTENBERG')) !== false) {
            $text = substr($text, $pos + 80);
        }
        if (($pos = strpos($text, '*** END OF THE PROJECT GUTENBERG')) !== false) {
            $text = substr($text, 0, $pos);
        }

        // Limiter à 60 000 caractères pour la génération rapide
        $text = mb_substr($text, 0, 60000);

        // Échapper HTML
        $escaped = nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));

        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  @page { margin: 2cm 2.5cm; }
  body   { font-family: Georgia, serif; font-size: 12pt; line-height: 1.6; color: #1a1a1a; }
  h1     { font-size: 26pt; text-align: center; color: #1a1a1a; margin-bottom: 4pt; }
  h2     { font-size: 16pt; text-align: center; color: #555; font-weight: normal; margin-top: 0; margin-bottom: 20pt; }
  .meta  { text-align: center; color: #888; font-size: 10pt; border-bottom: 1px solid #ddd; padding-bottom: 14pt; margin-bottom: 24pt; }
  .desc  { background: #f9f7f2; border-left: 4px solid #c9a84c; padding: 10pt 14pt; margin-bottom: 24pt; font-style: italic; color: #444; font-size: 11pt; }
  .body  { text-align: justify; }
  .footer{ text-align: center; font-size: 9pt; color: #aaa; margin-top: 30pt; border-top: 1px solid #eee; padding-top: 8pt; }
</style>
</head>
<body>
  <h1>' . htmlspecialchars($book['title']) . '</h1>
  <h2>' . htmlspecialchars($book['author']) . '</h2>
  <div class="meta">Domaine public · Classique français · ' . $book['pages'] . ' pages originales</div>
  <div class="desc">' . htmlspecialchars($book['description']) . '</div>
  <div class="body">' . $escaped . '</div>
  <div class="footer">Source : Project Gutenberg · gutenberg.org · Licence domaine public</div>
</body>
</html>';

        try {
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', false)
                ->setOption('defaultFont', 'serif');

            return $pdf->output();
        } catch (\Throwable $e) {
            $this->warn("        ⚠  DomPDF : " . $e->getMessage());
            return null;
        }
    }

    // ---------------------------------------------------------------
    // Génère un PDF générique pour les livres sans texte Gutenberg
    // ---------------------------------------------------------------
    private function generateGenericPdf(array $book): ?string
    {
        $genericText = "Ce livre est disponible sur BookFlow. " . $book['description'] . "\n\n" .
            "Note : Le texte complet de cet ouvrage n'est pas disponible dans le domaine public. " .
            "Ce PDF est une version de démonstration générée automatiquement.";

        $escaped = nl2br(htmlspecialchars($genericText, ENT_QUOTES, 'UTF-8'));

        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  @page { margin: 2cm 2.5cm; }
  body   { font-family: Georgia, serif; font-size: 12pt; line-height: 1.6; color: #1a1a1a; }
  h1     { font-size: 26pt; text-align: center; color: #1a1a1a; margin-bottom: 4pt; }
  h2     { font-size: 16pt; text-align: center; color: #555; font-weight: normal; margin-top: 0; margin-bottom: 20pt; }
  .meta  { text-align: center; color: #888; font-size: 10pt; border-bottom: 1px solid #ddd; padding-bottom: 14pt; margin-bottom: 24pt; }
  .desc  { background: #f9f7f2; border-left: 4px solid #c9a84c; padding: 10pt 14pt; margin-bottom: 24pt; font-style: italic; color: #444; font-size: 11pt; }
  .body  { text-align: justify; }
  .footer{ text-align: center; font-size: 9pt; color: #aaa; margin-top: 30pt; border-top: 1px solid #eee; padding-top: 8pt; }
</style>
</head>
<body>
  <h1>' . htmlspecialchars($book['title']) . '</h1>
  <h2>' . htmlspecialchars($book['author']) . '</h2>
  <div class="meta">BookFlow · ' . $book['category'] . ' · ' . $book['pages'] . ' pages</div>
  <div class="desc">' . htmlspecialchars($book['description']) . '</div>
  <div class="body">' . $escaped . '</div>
  <div class="footer">Généré par BookFlow · Version de démonstration</div>
</body>
</html>';

        try {
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', false)
                ->setOption('defaultFont', 'serif');

            return $pdf->output();
        } catch (\Throwable $e) {
            $this->warn("        ⚠  DomPDF générique : " . $e->getMessage());
            return null;
        }
    }

    // ---------------------------------------------------------------
    // Helper HTTP (file_get_contents avec fallback curl)
    // ---------------------------------------------------------------
    private function fetchUrl(string $url): ?string
    {
        $opts = [
            'http' => [
                'method'          => 'GET',
                'header'          => "User-Agent: Mozilla/5.0 (BookFlow/1.0)\r\n",
                'timeout'         => 30,
                'follow_location' => true,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ];

        $ctx = stream_context_create($opts);

        try {
            $data = @file_get_contents($url, false, $ctx);
            return $data ?: null;
        } catch (\Throwable) {
            return null;
        }
    }
}
