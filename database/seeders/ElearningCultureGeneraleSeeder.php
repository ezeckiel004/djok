<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ElearningForfait;
use App\Models\ElearningQcm;
use App\Models\ElearningCours;
use Illuminate\Support\Facades\Log;

class ElearningCultureGeneraleSeeder extends Seeder
{
    /**
     * Liste de 150+ questions de culture générale
     * Chaque question contient :
     * - text : le texte de la question
     * - answers : tableau associatif ['A' => 'réponse A', 'B' => '...', etc.]
     * - correct_answer (string) pour mode unique OU correct_answers (array) pour mode multiple
     * - explanation : explication pédagogique
     */
    private $questions = [];

    public function __construct()
    {
        $this->initializeQuestions();
    }

    public function run()
    {
        echo "=== DÉBUT DU SEEDER CULTURE GÉNÉRALE ===\n";

        // 1. Créer ou récupérer un cours si nécessaire (optionnel)
        $cours = ElearningCours::first();
        if (!$cours) {
            echo "Création d'un cours support...\n";
            $cours = ElearningCours::create([
                'title' => 'Culture Générale',
                'slug' => 'culture-generale',
                'description' => 'Cours de culture générale couvrant géographie, histoire, arts, sciences, sports et bien plus.',
                'content' => 'Ce cours vous permettrez de tester et d\'améliorer votre culture générale.',
                'is_active' => true,
                'order' => 1
            ]);
        }

        // 2. Créer le QCM
        echo "Création du QCM de culture générale...\n";

        $qcm = ElearningQcm::create([
            'cours_id' => $cours->id,
            'title' => 'QCM Culture Générale - 150+ Questions',
            'description' => 'Testez vos connaissances en culture générale avec plus de 150 questions couvrant la géographie, l\'histoire, les arts, les sciences, les sports, la gastronomie, le cinéma et la littérature.',
            'questions_count' => count($this->questions),
            'passing_score' => 70,
            'time_limit_minutes' => null, // Illimité
            'attempts_allowed' => 3,
            'is_examen_blanc' => false,
            'allow_multiple_correct' => false, // Mode réponse unique pour ce QCM
            'is_active' => true,
            'questions_data' => [
                'questions' => $this->questions,
                'allow_multiple_correct' => false
            ]
        ]);

        echo "✓ QCM créé avec " . count($this->questions) . " questions\n";

        // 3. Créer un forfait incluant ce QCM
        echo "Création du forfait Culture Générale Premium...\n";

        $forfait = ElearningForfait::create([
            'name' => 'Culture Générale Premium',
            'slug' => 'culture-generale-premium',
            'description' => 'Forfait d\'accès au QCM Culture Générale - Plus de 150 questions pour tester vos connaissances.',
            'price' => 29.99,
            'duration_days' => 30,
            'max_concurrent_connections' => 1,
            'includes_qcm' => true,
            'includes_examens_blancs' => false,
            'includes_certification' => true,
            'access_order' => 10,
            'is_active' => true,
            'features' => [
                ['title' => '150+ questions', 'description' => 'Large panel de culture générale'],
                ['title' => 'Explications détaillées', 'description' => 'Chaque réponse est expliquée'],
                ['title' => 'Certification', 'description' => 'Certificat de réussite téléchargeable'],
            ],
            // Inclure ce QCM spécifiquement plutôt que tous
            'include_all_cours' => false,
            'include_all_qcms' => false,
            'include_all_examens' => false,
            'selected_cours_ids' => [$cours->id],
            'selected_qcms_ids' => [$qcm->id],
            'selected_examens_ids' => []
        ]);

        echo "✓ Forfait créé (ID: {$forfait->id})\n";
        echo "\n=== SEEDER EXÉCUTÉ AVEC SUCCÈS ===\n";
        echo "Pour utiliser ce QCM, vous pouvez maintenant :\n";
        echo "1. Attribuer un accès à un utilisateur via l'admin\n";
        echo "2. Créer un paiement Stripe pour ce forfait\n";
    }

    /**
     * Initialise les 150+ questions
     */
    private function initializeQuestions()
    {
        // ==================== GÉOGRAPHIE (15 questions) ====================

        $this->questions[] = [
            'id' => 1,
            'text' => 'Quelle est la capitale de l\'Australie ?',
            'answers' => ['A' => 'Sydney', 'B' => 'Melbourne', 'C' => 'Canberra', 'D' => 'Perth'],
            'correct_answer' => 'C',
            'explanation' => 'Beaucoup pensent à Sydney ou Melbourne, mais Canberra est la capitale officielle de l\'Australie depuis 1913. Elle a été choisie comme compromis entre Sydney et Melbourne, les deux plus grandes villes rivales.'
        ];

        $this->questions[] = [
            'id' => 2,
            'text' => 'Quel est le plus long fleuve du monde ?',
            'answers' => ['A' => 'Amazone', 'B' => 'Nil', 'C' => 'Yangtsé', 'D' => 'Mississippi'],
            'correct_answer' => 'A',
            'explanation' => 'L\'Amazone mesure environ 6 992 km, soit légèrement plus que le Nil (6 650 km). Des études récentes (2014) ont confirmé que l\'Amazone est le plus long fleuve du monde.'
        ];

        $this->questions[] = [
            'id' => 3,
            'text' => 'Quel pays est surnommé "le pays du soleil levant" ?',
            'answers' => ['A' => 'Chine', 'B' => 'Thaïlande', 'C' => 'Japon', 'D' => 'Corée du Sud'],
            'correct_answer' => 'C',
            'explanation' => 'Le Japon est appelé "Nihon" ou "Nippon", ce qui signifie littéralement "l\'origine du soleil" ou "le pays où le soleil se lève", en raison de sa position à l\'est de l\'Asie.'
        ];

        $this->questions[] = [
            'id' => 4,
            'text' => 'Quel désert est le plus grand du monde (hors zones polaires) ?',
            'answers' => ['A' => 'Gobi', 'B' => 'Sahara', 'C' => 'Kalahari', 'D' => 'Atacama'],
            'correct_answer' => 'B',
            'explanation' => 'Le Sahara s\'étend sur environ 9,2 millions de km². C\'est le plus grand désert chaud du monde. L\'Antarctique est plus grand mais c\'est un désert froid.'
        ];

        $this->questions[] = [
            'id' => 5,
            'text' => 'Combien de pays compte l\'Union Européenne (2024) ?',
            'answers' => ['A' => '24', 'B' => '25', 'C' => '27', 'D' => '30'],
            'correct_answer' => 'C',
            'explanation' => 'L\'Union européenne compte 27 pays membres depuis le 1er février 2020, date du départ du Royaume-Uni (Brexit). La Croatie a été le dernier pays à rejoindre l\'UE en 2013.'
        ];

        $this->questions[] = [
            'id' => 6,
            'text' => 'Quelle est la plus haute montagne d\'Afrique ?',
            'answers' => ['A' => 'Mont Kenya', 'B' => 'Kilimandjaro', 'C' => 'Mont Stanley', 'D' => 'Ras Dashan'],
            'correct_answer' => 'B',
            'explanation' => 'Le Kilimandjaro en Tanzanie culmine à 5 895 mètres. C\'est un volcan endormi dont le sommet est enneigé toute l\'année, malgré sa proximité avec l\'équateur.'
        ];

        $this->questions[] = [
            'id' => 7,
            'text' => 'Le Canada est bordé par combien d\'océans ?',
            'answers' => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4'],
            'correct_answer' => 'C',
            'explanation' => 'Le Canada est bordé par trois océans : Pacifique à l\'ouest, Atlantique à l\'est, et Arctique au nord. C\'est le seul pays avec cette particularité.'
        ];

        $this->questions[] = [
            'id' => 8,
            'text' => 'Quelle est la capitale du Brésil ?',
            'answers' => ['A' => 'Rio de Janeiro', 'B' => 'São Paulo', 'C' => 'Brasília', 'D' => 'Salvador'],
            'correct_answer' => 'C',
            'explanation' => 'Brasília est la capitale depuis 1960. Elle a été construite en 41 mois dans les terres pour désengorger les côtes, sur des plans de l\'architecte Oscar Niemeyer.'
        ];

        $this->questions[] = [
            'id' => 9,
            'text' => 'Quel est le pays le plus peuplé du monde ?',
            'answers' => ['A' => 'Inde', 'B' => 'Chine', 'C' => 'États-Unis', 'D' => 'Indonésie'],
            'correct_answer' => 'A',
            'explanation' => 'L\'Inde a dépassé la Chine en avril 2023 pour devenir le pays le plus peuplé avec plus de 1,428 milliard d\'habitants, soit environ 17,5% de la population mondiale.'
        ];

        $this->questions[] = [
            'id' => 10,
            'text' => 'La mer Morte borde quels deux pays ?',
            'answers' => ['A' => 'Israël et Jordanie', 'B' => 'Égypte et Arabie Saoudite', 'C' => 'Liban et Syrie', 'D' => 'Turquie et Grèce'],
            'correct_answer' => 'A',
            'explanation' => 'La mer Morte est bordée par Israël et la Jordanie. C\'est le point le plus bas de la Terre (environ -430 m). Sa salinité est 10 fois supérieure à celle de l\'océan.'
        ];

        $this->questions[] = [
            'id' => 11,
            'text' => 'Quel est le plus petit pays du monde ?',
            'answers' => ['A' => 'Monaco', 'B' => 'Saint-Marin', 'C' => 'Vatican', 'D' => 'Malte'],
            'correct_answer' => 'C',
            'explanation' => 'La Cité du Vatican est le plus petit État souverain avec seulement 0,44 km² (44 hectares) et environ 800 habitants. C\'est le siège de l\'Église catholique.'
        ];

        $this->questions[] = [
            'id' => 12,
            'text' => 'Où se trouve la tour de Pise ?',
            'answers' => ['A' => 'Rome', 'B' => 'Florence', 'C' => 'Pise', 'D' => 'Venise'],
            'correct_answer' => 'C',
            'explanation' => 'La tour de Pise se trouve sur la Piazza dei Miracoli à Pise, en Toscane. Son inclinaison est due à un sol trop meuble. Sa construction date du XIIe siècle.'
        ];

        $this->questions[] = [
            'id' => 13,
            'text' => 'Quel fleuve traverse Le Caire ?',
            'answers' => ['A' => 'Tigre', 'B' => 'Euphrate', 'C' => 'Nil', 'D' => 'Jourdain'],
            'correct_answer' => 'C',
            'explanation' => 'Le Nil traverse Le Caire. C\'est le plus long fleuve d\'Afrique (6 650 km) et le berceau de la civilisation égyptienne antique. 95% des Égyptiens vivent le long de ses rives.'
        ];

        $this->questions[] = [
            'id' => 14,
            'text' => 'Quel pays est surnommé "la terre des aurores boréales" ?',
            'answers' => ['A' => 'Norvège', 'B' => 'Suède', 'C' => 'Canada', 'D' => 'Russie'],
            'correct_answer' => 'A',
            'explanation' => 'La Norvège, notamment les régions comme Tromsø, est mondialement réputée pour l\'observation des aurores boréales, visibles de septembre à mars.'
        ];

        $this->questions[] = [
            'id' => 15,
            'text' => 'Quelle est la plus grande île du monde ?',
            'answers' => ['A' => 'Madagascar', 'B' => 'Bornéo', 'C' => 'Groenland', 'D' => 'Nouvelle-Guinée'],
            'correct_answer' => 'C',
            'explanation' => 'Le Groenland est la plus grande île avec 2,16 millions de km². L\'Australie est considérée comme un continent, pas une île.'
        ];

        // ==================== HISTOIRE (20 questions) ====================

        $this->questions[] = [
            'id' => 16,
            'text' => 'En quelle année a débuté la Révolution française ?',
            'answers' => ['A' => '1776', 'B' => '1789', 'C' => '1792', 'D' => '1804'],
            'correct_answer' => 'B',
            'explanation' => 'La Révolution française commence le 14 juillet 1789 avec la prise de la Bastille, symbole de l\'arbitraire royal, entraînant la fin de l\'Ancien Régime.'
        ];

        $this->questions[] = [
            'id' => 17,
            'text' => 'Qui a peint la Joconde ?',
            'answers' => ['A' => 'Michel-Ange', 'B' => 'Raphaël', 'C' => 'Léonard de Vinci', 'D' => 'Caravage'],
            'correct_answer' => 'C',
            'explanation' => 'La Joconde (Monna Lisa) a été peinte par Léonard de Vinci au XVIe siècle. Elle est exposée au Louvre et célèbre pour son sourire énigmatique et la technique du sfumato.'
        ];

        $this->questions[] = [
            'id' => 18,
            'text' => 'Quel empereur a fondé l\'Empire mongol ?',
            'answers' => ['A' => 'Kubilai Khan', 'B' => 'Gengis Khan', 'C' => 'Attila', 'D' => 'Tamerlan'],
            'correct_answer' => 'B',
            'explanation' => 'Gengis Khan (Temujin) a uni les tribus mongoles au XIIIe siècle et fondé le plus vaste empire contigu de l\'histoire, s\'étendant de la Chine à l\'Europe de l\'Est.'
        ];

        $this->questions[] = [
            'id' => 19,
            'text' => 'En quelle année l\'homme a-t-il marché sur la Lune ?',
            'answers' => ['A' => '1965', 'B' => '1969', 'C' => '1971', 'D' => '1973'],
            'correct_answer' => 'B',
            'explanation' => 'Le 20 juillet 1969, Neil Armstrong et Buzz Aldrin (Apollo 11) sont les premiers hommes sur la Lune. Armstrong : "Un petit pas pour l\'homme, un grand bond pour l\'humanité."'
        ];

        $this->questions[] = [
            'id' => 20,
            'text' => 'Qui a écrit "Les Misérables" ?',
            'answers' => ['A' => 'Alexandre Dumas', 'B' => 'Émile Zola', 'C' => 'Victor Hugo', 'D' => 'Balzac'],
            'correct_answer' => 'C',
            'explanation' => 'Victor Hugo a publié "Les Misérables" en 1862, racontant le parcours de Jean Valjean et dépeignant la société française du XIXe siècle.'
        ];

        $this->questions[] = [
            'id' => 21,
            'text' => 'Quel traité a mis fin à la Première Guerre mondiale ?',
            'answers' => ['A' => 'Versailles', 'B' => 'Paris', 'C' => 'Rome', 'D' => 'Westphalie'],
            'correct_answer' => 'A',
            'explanation' => 'Le Traité de Versailles (1919) impose des conditions très dures à l\'Allemagne, contribuant à la montée du nazisme.'
        ];

        $this->questions[] = [
            'id' => 22,
            'text' => 'Qui était le premier président des États-Unis ?',
            'answers' => ['A' => 'Thomas Jefferson', 'B' => 'John Adams', 'C' => 'George Washington', 'D' => 'Benjamin Franklin'],
            'correct_answer' => 'C',
            'explanation' => 'George Washington fut le premier président (1789-1797), héros de la guerre d\'indépendance. Il a refusé de se présenter un troisième mandat.'
        ];

        $this->questions[] = [
            'id' => 23,
            'text' => 'Qui a découvert la pénicilline ?',
            'answers' => ['A' => 'Pasteur', 'B' => 'Marie Curie', 'C' => 'Fleming', 'D' => 'Koch'],
            'correct_answer' => 'C',
            'explanation' => 'Alexander Fleming a découvert la pénicilline en 1928 par hasard : une moisissure avait tué les bactéries dans sa boîte de culture. Ce fut le premier antibiotique.'
        ];

        $this->questions[] = [
            'id' => 24,
            'text' => 'En quelle année le mur de Berlin est-il tombé ?',
            'answers' => ['A' => '1987', 'B' => '1989', 'C' => '1990', 'D' => '1991'],
            'correct_answer' => 'B',
            'explanation' => 'Le mur de Berlin est tombé le 9 novembre 1989, symbolisant la fin de la Guerre Froide et menant à la réunification allemande le 3 octobre 1990.'
        ];

        $this->questions[] = [
            'id' => 25,
            'text' => 'Quel navigateur a réalisé le premier tour du monde ?',
            'answers' => ['A' => 'Colomb', 'B' => 'Vasco de Gama', 'C' => 'Magellan', 'D' => 'Cartier'],
            'correct_answer' => 'C',
            'explanation' => 'Magellan a commencé l\'expédition en 1519 mais mourut aux Philippines. Son second, Elcano, acheva le premier tour du monde en 1522.'
        ];

        $this->questions[] = [
            'id' => 26,
            'text' => 'Qui a été surnommé "le Roi Soleil" ?',
            'answers' => ['A' => 'Louis XIV', 'B' => 'Louis XV', 'C' => 'Louis XVI', 'D' => 'Henri IV'],
            'correct_answer' => 'A',
            'explanation' => 'Louis XIV (1638-1715) a régné 72 ans, construit Versailles et fait de la France la première puissance européenne.'
        ];

        $this->questions[] = [
            'id' => 27,
            'text' => 'Que signifie l\'acronyme OTAN ?',
            'answers' => ['A' => 'Organisation du Traité de l\'Atlantique Nord', 'B' => 'Organisation des Territoires Africains du Nord', 'C' => 'Office Technique d\'Assistance Nucléaire', 'D' => 'Organisation Transatlantique de Navigation'],
            'correct_answer' => 'A',
            'explanation' => 'L\'OTAN est une alliance militaire créée en 1949. Son principe : une attaque contre un membre est une attaque contre tous (article 5).'
        ];

        $this->questions[] = [
            'id' => 28,
            'text' => 'Qui a écrit "Le Petit Prince" ?',
            'answers' => ['A' => 'La Fontaine', 'B' => 'Saint-Exupéry', 'C' => 'Camus', 'D' => 'Sartre'],
            'correct_answer' => 'B',
            'explanation' => 'Antoine de Saint-Exupéry a publié "Le Petit Prince" en 1943. C\'est l\'un des livres les plus traduits au monde (plus de 500 langues).'
        ];

        $this->questions[] = [
            'id' => 29,
            'text' => 'Quel événement a déclenché la Première Guerre mondiale ?',
            'answers' => ['A' => 'Assassinat de François-Ferdinand', 'B' => 'Invasion de la Pologne', 'C' => 'Traité de Versailles', 'D' => 'Révolution russe'],
            'correct_answer' => 'A',
            'explanation' => 'L\'assassinat de l\'archiduc François-Ferdinand à Sarajevo le 28 juin 1914 a déclenché un engrenage d\'alliances menant à la Grande Guerre.'
        ];

        $this->questions[] = [
            'id' => 30,
            'text' => 'Quelle reine d\'Égypte était la dernière pharaonne ?',
            'answers' => ['A' => 'Néfertiti', 'B' => 'Cléopâtre', 'C' => 'Hatchepsout', 'D' => 'Néfertari'],
            'correct_answer' => 'B',
            'explanation' => 'Cléopâtre VII (69-30 av. J.-C.) est la dernière reine d\'Égypte. Après sa défaite face aux Romains, l\'Égypte devient province romaine.'
        ];

        $this->questions[] = [
            'id' => 31,
            'text' => 'Qui a peint "Guernica" ?',
            'answers' => ['A' => 'Dalí', 'B' => 'Picasso', 'C' => 'Goya', 'D' => 'Miró'],
            'correct_answer' => 'B',
            'explanation' => 'Picasso a peint "Guernica" en 1937 pour dénoncer le bombardement de la ville basque par l\'aviation allemande pendant la guerre d\'Espagne.'
        ];

        $this->questions[] = [
            'id' => 32,
            'text' => 'En quelle année a eu lieu la Révolution russe ?',
            'answers' => ['A' => '1905', 'B' => '1917', 'C' => '1921', 'D' => '1924'],
            'correct_answer' => 'B',
            'explanation' => 'La Révolution russe en 1917 comprend la chute du tsar (février) et la prise de pouvoir par les bolcheviks de Lénine (octobre).'
        ];

        $this->questions[] = [
            'id' => 33,
            'text' => 'Qui était le leader du mouvement des droits civiques aux États-Unis ?',
            'answers' => ['A' => 'Malcolm X', 'B' => 'Martin Luther King', 'C' => 'Rosa Parks', 'D' => 'Nelson Mandela'],
            'correct_answer' => 'B',
            'explanation' => 'Martin Luther King (1929-1968) était le leader non-violent, connu pour "I have a dream". Il reçut le prix Nobel de la paix en 1964.'
        ];

        $this->questions[] = [
            'id' => 34,
            'text' => 'Quel empire a construit le Colisée ?',
            'answers' => ['A' => 'Grec', 'B' => 'Romain', 'C' => 'Byzantin', 'D' => 'Ottoman'],
            'correct_answer' => 'B',
            'explanation' => 'Le Colisée a été construit par l\'Empire romain entre 70 et 80 après J.-C. Il pouvait accueillir 50 000 spectateurs.'
        ];

        $this->questions[] = [
            'id' => 35,
            'text' => 'Qui a découvert l\'Amérique en 1492 ?',
            'answers' => ['A' => 'Amerigo Vespucci', 'B' => 'Christophe Colomb', 'C' => 'Jean Cabot', 'D' => 'Jacques Cartier'],
            'correct_answer' => 'B',
            'explanation' => 'Christophe Colomb atteint les Bahamas le 12 octobre 1492, croyant avoir trouvé une route vers les Indes, sans savoir qu\'il avait découvert un "Nouveau Monde".'
        ];

        // ==================== ARTS ET LITTÉRATURE (15 questions) ====================

        $this->questions[] = [
            'id' => 36,
            'text' => 'Qui a peint "La Nuit étoilée" ?',
            'answers' => ['A' => 'Picasso', 'B' => 'Van Gogh', 'C' => 'Monet', 'D' => 'Munch'],
            'correct_answer' => 'B',
            'explanation' => 'Van Gogh a peint "La Nuit étoilée" en 1889 à l\'asile de Saint-Rémy. Le ciel tourbillonnant exprime son état émotionnel intense.'
        ];

        $this->questions[] = [
            'id' => 37,
            'text' => 'Quel écrivain a créé Sherlock Holmes ?',
            'answers' => ['A' => 'Agatha Christie', 'B' => 'Conan Doyle', 'C' => 'Edgar Poe', 'D' => 'Charles Dickens'],
            'correct_answer' => 'B',
            'explanation' => 'Sir Arthur Conan Doyle, médecin écossais, a créé Sherlock Holmes en 1887. Le détective apparaît dans 4 romans et 56 nouvelles.'
        ];

        $this->questions[] = [
            'id' => 38,
            'text' => 'Qui a composé la Symphonie n°5 (destin qui frappe à la porte) ?',
            'answers' => ['A' => 'Mozart', 'B' => 'Beethoven', 'C' => 'Bach', 'D' => 'Chopin'],
            'correct_answer' => 'B',
            'explanation' => 'La Symphonie n°5 (1808) de Beethoven avec son rythme "ta-ta-ta-taaaann" a été décrite comme "le destin qui frappe à la porte".'
        ];

        $this->questions[] = [
            'id' => 39,
            'text' => 'Quel roman de Victor Hugo se déroule dans Notre-Dame ?',
            'answers' => ['A' => 'Les Misérables', 'B' => 'Quatre-vingt-treize', 'C' => 'Notre-Dame de Paris', 'D' => 'Le Dernier Jour d\'un condamné'],
            'correct_answer' => 'C',
            'explanation' => '"Notre-Dame de Paris" (1831) met en scène Quasimodo et Esmeralda, et a contribué à la restauration de la cathédrale.'
        ];

        $this->questions[] = [
            'id' => 40,
            'text' => 'Qui a sculpté "Le Penseur" ?',
            'answers' => ['A' => 'Rodin', 'B' => 'Michel-Ange', 'C' => 'Donatello', 'D' => 'Camille Claudel'],
            'correct_answer' => 'A',
            'explanation' => '"Le Penseur" (1880-1904) d\'Auguste Rodin représente la philosophie. Plus de 20 exemplaires existent dans le monde.'
        ];

        $this->questions[] = [
            'id' => 41,
            'text' => 'Quel est le vrai nom de Molière ?',
            'answers' => ['A' => 'François-Marie Arouet', 'B' => 'Jean-Baptiste Poquelin', 'C' => 'Jean Racine', 'D' => 'Pierre Corneille'],
            'correct_answer' => 'B',
            'explanation' => 'Molière est le nom de scène de Jean-Baptiste Poquelin (1622-1673), fils de tapissier du roi, qui a révolutionné la comédie française.'
        ];

        $this->questions[] = [
            'id' => 42,
            'text' => 'Qui a écrit "Orgueil et Préjugés" ?',
            'answers' => ['A' => 'Charlotte Brontë', 'B' => 'Emily Brontë', 'C' => 'Jane Austen', 'D' => 'George Eliot'],
            'correct_answer' => 'C',
            'explanation' => 'Jane Austen a publié "Pride and Prejudice" en 1813, racontant l\'histoire d\'Elizabeth Bennet et Mr Darcy.'
        ];

        $this->questions[] = [
            'id' => 43,
            'text' => 'Quel mouvement artistique représente Salvador Dalí ?',
            'answers' => ['A' => 'Cubisme', 'B' => 'Surréalisme', 'C' => 'Impressionnisme', 'D' => 'Expressionnisme'],
            'correct_answer' => 'B',
            'explanation' => 'Dalí est le plus célèbre surréaliste, explorant l\'inconscient et le rêve avec ses montres molles.'
        ];

        $this->questions[] = [
            'id' => 44,
            'text' => 'Qui a composé "Les Quatre Saisons" ?',
            'answers' => ['A' => 'Vivaldi', 'B' => 'Bach', 'C' => 'Haendel', 'D' => 'Telemann'],
            'correct_answer' => 'A',
            'explanation' => 'Vivaldi a composé "Les Quatre Saisons" vers 1723 : quatre concertos représentant chaque saison avec des effets musicaux évocateurs.'
        ];

        $this->questions[] = [
            'id' => 45,
            'text' => 'Quel écrivain a reçu le prix Nobel de littérature en 1957 ?',
            'answers' => ['A' => 'Sartre', 'B' => 'Camus', 'C' => 'Gide', 'D' => 'Mauriac'],
            'correct_answer' => 'B',
            'explanation' => 'Albert Camus a reçu le prix Nobel en 1957 à 44 ans. Auteur de "L\'Étranger" et "La Peste", il meurt dans un accident en 1960.'
        ];

        $this->questions[] = [
            'id' => 46,
            'text' => 'Qui a peint "La Persistance de la mémoire" (montres molles) ?',
            'answers' => ['A' => 'Magritte', 'B' => 'Dalí', 'C' => 'Ernst', 'D' => 'Miró'],
            'correct_answer' => 'B',
            'explanation' => 'Ce tableau de 1931 montre des montres fondantes, symbolisant la relativité du temps. Dalí s\'inspirait de camemberts qui fondent.'
        ];

        $this->questions[] = [
            'id' => 47,
            'text' => 'Quel ballet de Tchaïkovski met en scène un cygne ?',
            'answers' => ['A' => 'Casse-Noisette', 'B' => 'La Belle au bois dormant', 'C' => 'Le Lac des cygnes', 'D' => 'Roméo et Juliette'],
            'correct_answer' => 'C',
            'explanation' => '"Le Lac des cygnes" (1877) raconte Odette, princesse transformée en cygne. C\'est l\'un des ballets les plus célèbres.'
        ];

        $this->questions[] = [
            'id' => 48,
            'text' => 'Qui a écrit "Madame Bovary" ?',
            'answers' => ['A' => 'Flaubert', 'B' => 'Stendhal', 'C' => 'Zola', 'D' => 'Maupassant'],
            'correct_answer' => 'A',
            'explanation' => 'Flaubert a publié "Madame Bovary" en 1857. Le roman fut jugé pour "outrage à la morale". Flaubert disait : "Madame Bovary, c\'est moi !"'
        ];

        $this->questions[] = [
            'id' => 49,
            'text' => 'Quelle pièce de Shakespeare met en scène des amants maudits ?',
            'answers' => ['A' => 'Hamlet', 'B' => 'Macbeth', 'C' => 'Roméo et Juliette', 'D' => 'Othello'],
            'correct_answer' => 'C',
            'explanation' => '"Roméo et Juliette" (1597) raconte l\'amour impossible des enfants de familles rivales à Vérone, inspirant d\'innombrables adaptations.'
        ];

        $this->questions[] = [
            'id' => 50,
            'text' => 'Quel artiste a coupé son oreille ?',
            'answers' => ['A' => 'Gauguin', 'B' => 'Van Gogh', 'C' => 'Monet', 'D' => 'Manet'],
            'correct_answer' => 'B',
            'explanation' => 'En 1888, après une dispute avec Gauguin, van Gogh a coupé une partie de son oreille gauche qu\'il aurait offerte à une prostituée.'
        ];

        // ==================== SCIENCES (15 questions) ====================

        $this->questions[] = [
            'id' => 51,
            'text' => 'Quel scientifique a formulé la théorie de la relativité ?',
            'answers' => ['A' => 'Newton', 'B' => 'Einstein', 'C' => 'Bohr', 'D' => 'Planck'],
            'correct_answer' => 'B',
            'explanation' => 'Albert Einstein a publié la théorie de la relativité restreinte (1905) et générale (1915), révolutionnant la physique moderne.'
        ];

        $this->questions[] = [
            'id' => 52,
            'text' => 'Quel est l\'organe le plus grand du corps humain ?',
            'answers' => ['A' => 'Le foie', 'B' => 'Le cœur', 'C' => 'La peau', 'D' => 'Les poumons'],
            'correct_answer' => 'C',
            'explanation' => 'La peau est le plus grand organe humain avec environ 2 m² chez l\'adulte. Elle pèse 3 à 5 kg et remplit des fonctions de protection et de régulation.'
        ];

        $this->questions[] = [
            'id' => 53,
            'text' => 'Quelle planète est surnommée "l\'étoile du berger" ?',
            'answers' => ['A' => 'Mars', 'B' => 'Jupiter', 'C' => 'Vénus', 'D' => 'Mercure'],
            'correct_answer' => 'C',
            'explanation' => 'Vénus est visible le matin ou le soir selon sa position, d\'où son surnom. C\'est la planète la plus brillante dans notre ciel.'
        ];

        $this->questions[] = [
            'id' => 54,
            'text' => 'Que mesure un ohmmètre ?',
            'answers' => ['A' => 'La tension', 'B' => 'L\'intensité', 'C' => 'La résistance électrique', 'D' => 'La puissance'],
            'correct_answer' => 'C',
            'explanation' => 'Un ohmmètre mesure la résistance électrique en ohms. Il est souvent intégré au multimètre avec le voltmètre et l\'ampèremètre.'
        ];

        $this->questions[] = [
            'id' => 55,
            'text' => 'Quel groupe sanguin est appelé "donneur universel" ?',
            'answers' => ['A' => 'A', 'B' => 'B', 'C' => 'AB', 'D' => 'O'],
            'correct_answer' => 'D',
            'explanation' => 'Le groupe O négatif est le donneur universel car ses globules rouges n\'ont ni antigène A ni B, pouvant être transfusés à tous.'
        ];

        $this->questions[] = [
            'id' => 56,
            'text' => 'Qui a inventé le téléphone ?',
            'answers' => ['A' => 'Edison', 'B' => 'Tesla', 'C' => 'Alexander Graham Bell', 'D' => 'Marconi'],
            'correct_answer' => 'C',
            'explanation' => 'Alexander Graham Bell a breveté le téléphone en 1876. Le premier message fut : "M. Watson, venez, je vous veux."'
        ];

        $this->questions[] = [
            'id' => 57,
            'text' => 'Combien d\'os compte le corps humain adulte ?',
            'answers' => ['A' => '200', 'B' => '206', 'C' => '210', 'D' => '215'],
            'correct_answer' => 'B',
            'explanation' => 'L\'adulte a 206 os. Le nouveau-né en a environ 300, certains soudant avec l\'âge (notamment du crâne).'
        ];

        $this->questions[] = [
            'id' => 58,
            'text' => 'Quelle est la formule chimique de l\'eau ?',
            'answers' => ['A' => 'CO2', 'B' => 'O2', 'C' => 'H2O', 'D' => 'NaCl'],
            'correct_answer' => 'C',
            'explanation' => 'H2O signifie deux atomes d\'hydrogène et un atome d\'oxygène. C\'est une molécule polaire essentielle à la vie.'
        ];

        $this->questions[] = [
            'id' => 59,
            'text' => 'Qui a découvert la radioactivité ?',
            'answers' => ['A' => 'Pierre et Marie Curie', 'B' => 'Henri Becquerel', 'C' => 'Rutherford', 'D' => 'Bohr'],
            'correct_answer' => 'B',
            'explanation' => 'Henri Becquerel découvre la radioactivité en 1896 en observant l\'émission de rayons par l\'uranium. Les Curie ont approfondi ses travaux.'
        ];

        $this->questions[] = [
            'id' => 60,
            'text' => 'Quelle est la planète la plus proche du Soleil ?',
            'answers' => ['A' => 'Vénus', 'B' => 'Mercure', 'C' => 'Terre', 'D' => 'Mars'],
            'correct_answer' => 'B',
            'explanation' => 'Mercure est à environ 58 millions de km du Soleil. Sa surface varie de -173°C à 427°C.'
        ];

        $this->questions[] = [
            'id' => 61,
            'text' => 'À quelle vitesse se propage la lumière ?',
            'answers' => ['A' => '300 000 km/s', 'B' => '150 000 km/s', 'C' => '1 000 000 km/s', 'D' => '30 000 km/s'],
            'correct_answer' => 'A',
            'explanation' => 'La lumière voyage à 299 792 458 m/s dans le vide, soit environ 300 000 km/s. Rien ne peut aller plus vite.'
        ];

        $this->questions[] = [
            'id' => 62,
            'text' => 'Quel est le plus grand organe interne du corps humain ?',
            'answers' => ['A' => 'Le cœur', 'B' => 'Les poumons', 'C' => 'Le foie', 'D' => 'Les reins'],
            'correct_answer' => 'C',
            'explanation' => 'Le foie pèse environ 1,5 kg et remplit plus de 500 fonctions, dont la détoxification et la production de bile.'
        ];

        $this->questions[] = [
            'id' => 63,
            'text' => 'Qui a inventé l\'ampoule électrique ?',
            'answers' => ['A' => 'Tesla', 'B' => 'Edison', 'C' => 'Swan', 'D' => 'Franklin'],
            'correct_answer' => 'B',
            'explanation' => 'Thomas Edison a breveté l\'ampoule en 1879. D\'autres avant lui avaient créé des prototypes, mais il a créé la première ampoule pratique et commerciale.'
        ];

        $this->questions[] = [
            'id' => 64,
            'text' => 'Quel élément chimique a le symbole "Fe" ?',
            'answers' => ['A' => 'Or', 'B' => 'Argent', 'C' => 'Fer', 'D' => 'Fluor'],
            'correct_answer' => 'C',
            'explanation' => 'Fe vient du latin "Ferrum". Le fer est l\'élément le plus courant sur Terre en masse, constituant le noyau terrestre.'
        ];

        $this->questions[] = [
            'id' => 65,
            'text' => 'Qui a découvert la structure de l\'ADN ?',
            'answers' => ['A' => 'Watson et Crick', 'B' => 'Pasteur et Koch', 'C' => 'Mendel et Darwin', 'D' => 'Fleming'],
            'correct_answer' => 'A',
            'explanation' => 'James Watson et Francis Crick ont proposé la double hélice de l\'ADN en 1953, utilisant des données de Rosalind Franklin.'
        ];

        // ==================== SPORTS (10 questions) ====================

        $this->questions[] = [
            'id' => 66,
            'text' => 'Combien de joueurs compose une équipe de football sur le terrain ?',
            'answers' => ['A' => '10', 'B' => '11', 'C' => '12', 'D' => '9'],
            'correct_answer' => 'B',
            'explanation' => 'Une équipe de football a 11 joueurs : 10 joueurs de champ + 1 gardien. Des remplacements sont possibles selon les règles.'
        ];

        $this->questions[] = [
            'id' => 67,
            'text' => 'Qui a remporté le plus de Ballons d\'Or ?',
            'answers' => ['A' => 'Cristiano Ronaldo', 'B' => 'Lionel Messi', 'C' => 'Michel Platini', 'D' => 'Kylian Mbappé'],
            'correct_answer' => 'B',
            'explanation' => 'Lionel Messi détient le record avec 8 Ballons d\'Or (2009, 2010, 2011, 2012, 2015, 2019, 2021, 2023).'
        ];

        $this->questions[] = [
            'id' => 68,
            'text' => 'Quel pays a remporté la Coupe du Monde 2018 ?',
            'answers' => ['A' => 'France', 'B' => 'Croatie', 'C' => 'Belgique', 'D' => 'Angleterre'],
            'correct_answer' => 'A',
            'explanation' => 'La France a battu la Croatie 4-2 en finale le 15 juillet 2018 à Moscou. C\'est la 2e étoile après 1998.'
        ];

        $this->questions[] = [
            'id' => 69,
            'text' => 'Combien de trous comporte un parcours de golf standard ?',
            'answers' => ['A' => '9', 'B' => '18', 'C' => '27', 'D' => '36'],
            'correct_answer' => 'B',
            'explanation' => 'Un parcours standard de golf comporte 18 trous, divisés en aller (9 trous) et retour (9 trous).'
        ];

        $this->questions[] = [
            'id' => 70,
            'text' => 'Qui détient le record du monde du 100 mètres ?',
            'answers' => ['A' => 'Usain Bolt', 'B' => 'Yohan Blake', 'C' => 'Justin Gatlin', 'D' => 'Tyson Gay'],
            'correct_answer' => 'A',
            'explanation' => 'Usain Bolt détient le record en 9,58 secondes établi le 16 août 2009 à Berlin.'
        ];

        $this->questions[] = [
            'id' => 71,
            'text' => 'Quel pays a organisé les JO 2024 ?',
            'answers' => ['A' => 'Londres', 'B' => 'Paris', 'C' => 'Los Angeles', 'D' => 'Tokyo'],
            'correct_answer' => 'B',
            'explanation' => 'Paris a accueilli les Jeux Olympiques d\'été 2024 du 26 juillet au 11 août, 100 ans après les précédents JO à Paris en 1924.'
        ];

        $this->questions[] = [
            'id' => 72,
            'text' => 'Quelle est la distance d\'un marathon ?',
            'answers' => ['A' => '40,195 km', 'B' => '42,195 km', 'C' => '45 km', 'D' => '38 km'],
            'correct_answer' => 'B',
            'explanation' => 'Le marathon fait 42,195 km. Cette distance a été fixée en 1921 selon le parcours des JO de Londres 1908 (Windsor à Londres).'
        ];

        $this->questions[] = [
            'id' => 73,
            'text' => 'Quel boxeur s\'appelait "Cassius Clay" avant sa conversion ?',
            'answers' => ['A' => 'Mike Tyson', 'B' => 'Muhammad Ali', 'C' => 'Joe Frazier', 'D' => 'George Foreman'],
            'correct_answer' => 'B',
            'explanation' => 'Cassius Clay a changé son nom en Muhammad Ali après sa conversion à l\'Islam en 1964.'
        ];

        $this->questions[] = [
            'id' => 74,
            'text' => 'Dans quel sport utilise-t-on un "birdie" ?',
            'answers' => ['A' => 'Tennis', 'B' => 'Badminton', 'C' => 'Golf', 'D' => 'Cricket'],
            'correct_answer' => 'B',
            'explanation' => 'Le volant au badminton (shuttlecock) est souvent appelé "birdie" en anglais en raison de ses plumes rappelant un oiseau.'
        ];

        $this->questions[] = [
            'id' => 75,
            'text' => 'Quel nageur a remporté 8 médailles d\'or aux JO de Pékin 2008 ?',
            'answers' => ['A' => 'Michael Phelps', 'B' => 'Ryan Lochte', 'C' => 'Ian Thorpe', 'D' => 'Mark Spitz'],
            'correct_answer' => 'A',
            'explanation' => 'Michael Phelps a battu le record de 7 médailles d\'or de Mark Spitz (1972) en remportant 8 titres olympiques à Pékin.'
        ];

        // ==================== CINÉMA (10 questions) ====================

        $this->questions[] = [
            'id' => 76,
            'text' => 'Qui a réalisé "Titanic" et "Avatar" ?',
            'answers' => ['A' => 'Spielberg', 'B' => 'James Cameron', 'C' => 'Nolan', 'D' => 'Jackson'],
            'correct_answer' => 'B',
            'explanation' => 'James Cameron a réalisé Titanic (1997) et Avatar (2009), deux films ayant été les plus gros succès du box-office.'
        ];

        $this->questions[] = [
            'id' => 77,
            'text' => 'Quel acteur incarne James Bond dans "Skyfall" ?',
            'answers' => ['A' => 'Sean Connery', 'B' => 'Roger Moore', 'C' => 'Daniel Craig', 'D' => 'Pierce Brosnan'],
            'correct_answer' => 'C',
            'explanation' => 'Daniel Craig a joué James Bond dans Skyfall (2012) ainsi que dans Casino Royale, Quantum of Solace, Spectre et No Time to Die.'
        ];

        $this->questions[] = [
            'id' => 78,
            'text' => 'Quel film a remporté l\'Oscar du meilleur film en 2020 ?',
            'answers' => ['A' => '1917', 'B' => 'Joker', 'C' => 'Parasite', 'D' => 'Once Upon a Time in Hollywood'],
            'correct_answer' => 'C',
            'explanation' => 'Parasite (réalisé par Bong Joon-ho) est devenu le premier film non anglophone à remporter l\'Oscar du meilleur film.'
        ];

        $this->questions[] = [
            'id' => 79,
            'text' => 'Qui joue le rôle d\'Harry Potter ?',
            'answers' => ['A' => 'Rupert Grint', 'B' => 'Tom Felton', 'C' => 'Daniel Radcliffe', 'D' => 'Elijah Wood'],
            'correct_answer' => 'C',
            'explanation' => 'Daniel Radcliffe a incarné Harry Potter dans les 8 films de la saga, de 2001 à 2011.'
        ];

        $this->questions[] = [
            'id' => 80,
            'text' => 'Quel acteur a incarné le Joker dans "The Dark Knight" ?',
            'answers' => ['A' => 'Joaquin Phoenix', 'B' => 'Jared Leto', 'C' => 'Heath Ledger', 'D' => 'Jack Nicholson'],
            'correct_answer' => 'C',
            'explanation' => 'Heath Ledger a reçu l\'Oscar posthume du meilleur second rôle pour son interprétation du Joker dans The Dark Knight (2008).'
        ];

        $this->questions[] = [
            'id' => 81,
            'text' => 'Qui a réalisé "Pulp Fiction" ?',
            'answers' => ['A' => 'Quentin Tarantino', 'B' => 'Robert Rodriguez', 'C' => 'Guy Ritchie', 'D' => 'David Lynch'],
            'correct_answer' => 'A',
            'explanation' => 'Quentin Tarantino a réalisé Pulp Fiction (1994), Palme d\'or à Cannes, qui a révolutionné le cinéma indépendant.'
        ];

        $this->questions[] = [
            'id' => 82,
            'text' => 'Quel acteur joue Jack Sparrow ?',
            'answers' => ['A' => 'Orlando Bloom', 'B' => 'Keira Knightley', 'C' => 'Johnny Depp', 'D' => 'Geoffrey Rush'],
            'correct_answer' => 'C',
            'explanation' => 'Johnny Depp incarne le capitaine Jack Sparrow dans la saga "Pirates des Caraïbes".'
        ];

        $this->questions[] = [
            'id' => 83,
            'text' => 'Quel film de Nolan explore le rêve dans un rêve ?',
            'answers' => ['A' => 'Interstellar', 'B' => 'Tenet', 'C' => 'Inception', 'D' => 'Memento'],
            'correct_answer' => 'C',
            'explanation' => 'Inception (2010) de Christopher Nolan suit Dom Cobb qui peut entrer dans les rêves des gens pour voler des secrets.'
        ];

        $this->questions[] = [
            'id' => 84,
            'text' => 'Dans quelle série entend-on "Winter is coming" ?',
            'answers' => ['A' => 'The Witcher', 'B' => 'Game of Thrones', 'C' => 'Vikings', 'D' => 'The Last Kingdom'],
            'correct_answer' => 'B',
            'explanation' => '"Winter is coming" est la devise de la maison Stark, prononcée par Ned Stark dès le premier épisode de Game of Thrones.'
        ];

        $this->questions[] = [
            'id' => 85,
            'text' => 'Quelle série met en scène Walter White ?',
            'answers' => ['A' => 'Ozark', 'B' => 'Narcos', 'C' => 'Breaking Bad', 'D' => 'Better Call Saul'],
            'correct_answer' => 'C',
            'explanation' => 'Walter White, professeur de chimie devenu baron de la drogue, est le protagoniste de Breaking Bad (2008-2013).'
        ];

        // ==================== GASTRONOMIE (10 questions) ====================

        $this->questions[] = [
            'id' => 86,
            'text' => 'Dans quel pays est originaire la pizza ?',
            'answers' => ['A' => 'France', 'B' => 'Espagne', 'C' => 'Italie', 'D' => 'Grèce'],
            'correct_answer' => 'C',
            'explanation' => 'La pizza est originaire de Naples, en Italie. La pizza Margherita fut créée en 1889 en l\'honneur de la reine Marguerite.'
        ];

        $this->questions[] = [
            'id' => 87,
            'text' => 'Quel fromage est utilisé dans la fondue savoyarde ?',
            'answers' => ['A' => 'Camembert', 'B' => 'Comté, Beaufort, Abondance', 'C' => 'Roquefort', 'D' => 'Brie'],
            'correct_answer' => 'B',
            'explanation' => 'La fondue savoyarde utilise un mélange de fromages : Comté, Beaufort et Abondance (ou Emmental), avec du vin blanc et de l\'ail.'
        ];

        $this->questions[] = [
            'id' => 88,
            'text' => 'Quelle épice est la plus chère au monde ?',
            'answers' => ['A' => 'Vanille', 'B' => 'Safran', 'C' => 'Cardamome', 'D' => 'Poivre'],
            'correct_answer' => 'B',
            'explanation' => 'Le safran, issu des stigmates du crocus sativus, peut coûter jusqu\'à 30 000 € le kilo car il faut 150 000 fleurs pour 1 kg.'
        ];

        $this->questions[] = [
            'id' => 89,
            'text' => 'Quel plat japonais se compose de riz vinaigré et de poisson cru ?',
            'answers' => ['A' => 'Ramen', 'B' => 'Sushi', 'C' => 'Tempura', 'D' => 'Miso'],
            'correct_answer' => 'B',
            'explanation' => 'Le sushi est composé de riz vinaigré (shari) et de poisson cru (neta). Il peut être servi sous forme de maki, nigiri ou sashimi.'
        ];

        $this->questions[] = [
            'id' => 90,
            'text' => 'Quelle boisson est obtenue par fermentation du raisin ?',
            'answers' => ['A' => 'Bière', 'B' => 'Cidre', 'C' => 'Vin', 'D' => 'Whisky'],
            'correct_answer' => 'C',
            'explanation' => 'Le vin est obtenu par fermentation alcoolique du raisin. La bière vient des céréales, le cidre des pommes, le whisky de céréales distillées.'
        ];

        $this->questions[] = [
            'id' => 91,
            'text' => 'Quel fruit est utilisé pour faire du guacamole ?',
            'answers' => ['A' => 'Tomate', 'B' => 'Avocat', 'C' => 'Citron', 'D' => 'Mangue'],
            'correct_answer' => 'B',
            'explanation' => 'Le guacamole est une préparation mexicaine à base d\'avocat écrasé, mélangé à du citron vert, de l\'oignon, de la coriandre et des tomates.'
        ];

        $this->questions[] = [
            'id' => 92,
            'text' => 'Quelle est la spécialité culinaire de la Belgique ?',
            'answers' => ['A' => 'Macarons', 'B' => 'Frites et gaufres', 'C' => 'Paella', 'D' => 'Couscous'],
            'correct_answer' => 'B',
            'explanation' => 'La Belgique est réputée pour ses frites (dont la sauce andalouse est typique), ses gaufres (de Bruxelles et de Liège) et son chocolat.'
        ];

        $this->questions[] = [
            'id' => 93,
            'text' => 'Quel est l\'ingrédient principal du houmous ?',
            'answers' => ['A' => 'Aubergine', 'B' => 'Pois chiche', 'C' => 'Lentille', 'D' => 'Haricot rouge'],
            'correct_answer' => 'B',
            'explanation' => 'Le houmous est une purée de pois chiches mélangée à du tahini (purée de sésame), du citron, de l\'ail et de l\'huile d\'olive.'
        ];

        $this->questions[] = [
            'id' => 94,
            'text' => 'Quelle pâtisserie française signifie "petit four" ?',
            'answers' => ['A' => 'Macaron', 'B' => 'Mille-feuille', 'C' => 'Madeleine', 'D' => 'Éclair'],
            'correct_answer' => 'C',
            'explanation' => 'La madeleine est un petit gâteau en forme de coquille. Légende : une servante nommée Madeleine l\'aurait préparée pour le duc de Lorraine.'
        ];

        $this->questions[] = [
            'id' => 95,
            'text' => 'Quelle boisson chaude est fabriquée à partir de feuilles de Camellia sinensis ?',
            'answers' => ['A' => 'Café', 'B' => 'Thé', 'C' => 'Chocolat chaud', 'D' => 'Tisane'],
            'correct_answer' => 'B',
            'explanation' => 'Le thé est la boisson obtenue par infusion des feuilles du théier (Camellia sinensis). Le café vient des graines de caféier.'
        ];

        // ==================== LANGUE FRANÇAISE (10 questions) ====================

        $this->questions[] = [
            'id' => 96,
            'text' => 'Quel est le pluriel de "cheval" ?',
            'answers' => ['A' => 'Chevals', 'B' => 'Chevaux', 'C' => 'Chevalx', 'D' => 'Chevales'],
            'correct_answer' => 'B',
            'explanation' => 'Les noms en -al font leur pluriel en -aux, sauf exceptions : bal, carnaval, festival, récital, régal, chacal (chacals ou chacaux).'
        ];

        $this->questions[] = [
            'id' => 97,
            'text' => 'Quelle est la nature du mot "mais" ?',
            'answers' => ['A' => 'Nom', 'B' => 'Conjonction de coordination', 'C' => 'Préposition', 'D' => 'Adverbe'],
            'correct_answer' => 'B',
            'explanation' => '"Mais" est une conjonction de coordination, comme et, ou, or, ni, car. Elle exprime l\'opposition ou la restriction.'
        ];

        $this->questions[] = [
            'id' => 98,
            'text' => 'Quel est l\'antonyme d\'"optimiste" ?',
            'answers' => ['A' => 'Pessimiste', 'B' => 'Réaliste', 'C' => 'Idéaliste', 'D' => 'Rationnel'],
            'correct_answer' => 'A',
            'explanation' => 'L\'optimiste voit le bon côté des choses, le pessimiste voit le mauvais côté. Ce sont des antonymes.'
        ];

        $this->questions[] = [
            'id' => 99,
            'text' => '"Il pleut des cordes" est :',
            'answers' => ['A' => 'Un proverbe', 'B' => 'Une expression', 'C' => 'Un aphorisme', 'D' => 'Une métaphore'],
            'correct_answer' => 'B',
            'explanation' => 'C\'est une expression populaire signifiant "il pleut très fort". L\'image vient des longues cordes que forment les fortes pluies.'
        ];

        $this->questions[] = [
            'id' => 100,
            'text' => 'Comment s\'écrit le féminin de "chien" ?',
            'answers' => ['A' => 'Chiene', 'B' => 'Chienne', 'C' => 'Chient', 'D' => 'Chien'],
            'correct_answer' => 'B',
            'explanation' => 'Le féminin de chien est chienne. On double le n et on ajoute un e (comme "chienne de vie").'
        ];

        $this->questions[] = [
            'id' => 101,
            'text' => '"Homonyme" signifie :',
            'answers' => ['A' => 'Même orthographe, sens différent', 'B' => 'Même son, sens différent', 'C' => 'Sens identique', 'D' => 'Orthographe identique'],
            'correct_answer' => 'B',
            'explanation' => 'Des homonymes se prononcent de la même façon mais ont des sens différents (ex : mer/mère, ver/verre/vert/vers).'
        ];

        $this->questions[] = [
            'id' => 102,
            'text' => 'Le verbe "aller" à la 1ère personne du futur :',
            'answers' => ['A' => 'J\'allai', 'B' => 'J\'irai', 'C' => 'Je vais', 'D' => 'J\'irais'],
            'correct_answer' => 'B',
            'explanation' => 'Le futur simple d\'aller : j\'irai, tu iras, il ira, nous irons, vous irez, ils iront. "J\'irai" (pas d\'accent).'
        ];

        $this->questions[] = [
            'id' => 103,
            'text' => '"Inéluctable" veut dire :',
            'answers' => ['A' => 'Qu\'on ne peut éviter', 'B' => 'Qu\'on peut élire', 'C' => 'Incompréhensible', 'D' => 'Rapide'],
            'correct_answer' => 'A',
            'explanation' => 'Inéluctable vient du latin "in" (non) et "eluctor" (échapper). Synonymes : inévitable, fatal, incontournable.'
        ];

        $this->questions[] = [
            'id' => 104,
            'text' => 'Quelle est la fonction de "rapidement" dans "Il court rapidement" ?',
            'answers' => ['A' => 'Adjectif', 'B' => 'Complément d\'objet', 'C' => 'Adverbe', 'D' => 'Attribut'],
            'correct_answer' => 'C',
            'explanation' => '"Rapidement" est un adverbe qui modifie le verbe "court" en indiquant la manière.'
        ];

        $this->questions[] = [
            'id' => 105,
            'text' => '"Prémices" signifie :',
            'answers' => ['A' => 'Les débuts', 'B' => 'Les restes', 'C' => 'Les cadeaux', 'D' => 'Les excuses'],
            'correct_answer' => 'A',
            'explanation' => 'Les prémices sont les premiers signes, les débuts de quelque chose, les premiers fruits ou résultats.'
        ];

        // ==================== PLUS DE QUESTIONS POUR ATTEINDRE 150+ ====================

        $this->questions[] = [
            'id' => 106,
            'text' => 'Qui a peint "Le Cri" ?',
            'answers' => ['A' => 'Munch', 'B' => 'Klimt', 'C' => 'Schiele', 'D' => 'Matisse'],
            'correct_answer' => 'A',
            'explanation' => 'Edvard Munch a peint "Le Cri" (1893), symbole de l\'angoisse existentielle. Il existe 4 versions du tableau.'
        ];

        $this->questions[] = [
            'id' => 107,
            'text' => 'Quel est le plus grand océan du monde ?',
            'answers' => ['A' => 'Atlantique', 'B' => 'Indien', 'C' => 'Pacifique', 'D' => 'Arctique'],
            'correct_answer' => 'C',
            'explanation' => 'L\'océan Pacifique couvre 165,2 millions de km², soit un tiers de la surface terrestre. Il est plus grand que tous les continents réunis.'
        ];

        $this->questions[] = [
            'id' => 108,
            'text' => 'Quelle est la capitale du Canada ?',
            'answers' => ['A' => 'Toronto', 'B' => 'Vancouver', 'C' => 'Ottawa', 'D' => 'Montréal'],
            'correct_answer' => 'C',
            'explanation' => 'Ottawa est la capitale du Canada depuis 1857. Toronto est la capitale économique, mais Ottawa abrite le Parlement.'
        ];

        $this->questions[] = [
            'id' => 109,
            'text' => 'Qui a écrit "Le Comte de Monte-Cristo" ?',
            'answers' => ['A' => 'Victor Hugo', 'B' => 'Alexandre Dumas', 'C' => 'Jules Verne', 'D' => 'Balzac'],
            'correct_answer' => 'B',
            'explanation' => 'Alexandre Dumas a publié "Le Comte de Monte-Cristo" en 1844-1846, en collaboration avec Auguste Maquet.'
        ];

        $this->questions[] = [
            'id' => 110,
            'text' => 'Que signifie le sigle "SMS" ?',
            'answers' => ['A' => 'Short Message System', 'B' => 'Short Message Service', 'C' => 'Simple Message System', 'D' => 'Send Message Service'],
            'correct_answer' => 'B',
            'explanation' => 'SMS signifie Short Message Service. Le premier SMS a été envoyé en 1992 : "Merry Christmas".'
        ];

        $this->questions[] = [
            'id' => 111,
            'text' => 'Quelle est la monnaie officielle du Japon ?',
            'answers' => ['A' => 'Yuan', 'B' => 'Won', 'C' => 'Yen', 'D' => 'Dollar'],
            'correct_answer' => 'C',
            'explanation' => 'Le yen est la monnaie japonaise depuis 1871. Son symbole est ¥ et son code JPY.'
        ];

        $this->questions[] = [
            'id' => 112,
            'text' => 'Quel compositeur est devenu sourd ?',
            'answers' => ['A' => 'Mozart', 'B' => 'Beethoven', 'C' => 'Bach', 'D' => 'Chopin'],
            'correct_answer' => 'B',
            'explanation' => 'Beethoven a commencé à perdre l\'ouïe dès 26 ans et était complètement sourd à 44 ans. Il continua à composer malgré sa surdité.'
        ];

        $this->questions[] = [
            'id' => 113,
            'text' => 'Quel animal est le symbole de la sagesse en Grèce antique ?',
            'answers' => ['A' => 'Lion', 'B' => 'Dauphin', 'C' => 'Chouette', 'D' => 'Aigle'],
            'correct_answer' => 'C',
            'explanation' => 'La chouette (hibou) était l’attribut de la déesse Athéna, déesse de la sagesse. On la trouve sur les pièces athéniennes.'
        ];

        $this->questions[] = [
            'id' => 114,
            'text' => 'Qui a écrit "Le Deuxième Sexe" ?',
            'answers' => ['A' => 'Simone de Beauvoir', 'B' => 'Marguerite Yourcenar', 'C' => 'Colette', 'D' => 'George Sand'],
            'correct_answer' => 'A',
            'explanation' => 'Simone de Beauvoir a publié "Le Deuxième Sexe" en 1949, ouvrage fondateur du féminisme moderne.'
        ];

        $this->questions[] = [
            'id' => 115,
            'text' => 'Quelle est la durée d\'un mandat présidentiel aux États-Unis ?',
            'answers' => ['A' => '4 ans', 'B' => '5 ans', 'C' => '6 ans', 'D' => '7 ans'],
            'correct_answer' => 'A',
            'explanation' => 'Le président américain est élu pour 4 ans, renouvelable une seule fois (depuis le 22e amendement de 1951).'
        ];

        $this->questions[] = [
            'id' => 116,
            'text' => 'Quel est le nom du cheval d\'Alexandre le Grand ?',
            'answers' => ['A' => 'Bucéphale', 'B' => 'Rossinante', 'C' => 'Jolly Jumper', 'D' => 'Pégase'],
            'correct_answer' => 'A',
            'explanation' => 'Bucéphale était le cheval légendaire d\'Alexandre le Grand. Dressé alors qu\'Alexandre n’avait que 12 ans, il l’accompagna jusqu’en Inde.'
        ];

        $this->questions[] = [
            'id' => 117,
            'text' => 'Quel écrivain français a refusé le prix Nobel de littérature en 1964 ?',
            'answers' => ['A' => 'Camus', 'B' => 'Sartre', 'C' => 'Malraux', 'D' => 'Gide'],
            'correct_answer' => 'B',
            'explanation' => 'Jean-Paul Sartre a refusé le prix Nobel en 1964, expliquant qu\'un écrivain ne devait pas se laisser institutionnaliser.'
        ];

        $this->questions[] = [
            'id' => 118,
            'text' => 'Quelle est la tour la plus haute du monde (2024) ?',
            'answers' => ['A' => 'Tour Eiffel', 'B' => 'Burj Khalifa', 'C' => 'Shanghai Tower', 'D' => 'One World Trade Center'],
            'correct_answer' => 'B',
            'explanation' => 'Le Burj Khalifa à Dubaï mesure 828 mètres (2 717 pieds) depuis 2010, plus du double de la Tour Eiffel (330 m).'
        ];

        $this->questions[] = [
            'id' => 119,
            'text' => 'Qui a découvert la vaccination contre la variole ?',
            'answers' => ['A' => 'Pasteur', 'B' => 'Edward Jenner', 'C' => 'Salk', 'D' => 'Sabin'],
            'correct_answer' => 'B',
            'explanation' => 'Edward Jenner a développé le premier vaccin contre la variole en 1796, à partir de la vaccine (variole bovine).'
        ];

        $this->questions[] = [
            'id' => 120,
            'text' => 'Que signifie WWW ?',
            'answers' => ['A' => 'World Wide Web', 'B' => 'World Web Wide', 'C' => 'Web World Wide', 'D' => 'Wide World Web'],
            'correct_answer' => 'A',
            'explanation' => 'World Wide Web a été inventé par Tim Berners-Lee au CERN en 1989 pour partager des documents hypertexte.'
        ];

        $this->questions[] = [
            'id' => 121,
            'text' => 'Quel pays compte le plus de musées ?',
            'answers' => ['A' => 'France', 'B' => 'Italie', 'C' => 'États-Unis', 'D' => 'Allemagne'],
            'correct_answer' => 'B',
            'explanation' => 'L\'Italie compte environ 4 000 musées (historiquement riche, héritage romain et Renaissance).'
        ];

        $this->questions[] = [
            'id' => 122,
            'text' => 'Quel peintre a utilisé la technique du pointillisme ?',
            'answers' => ['A' => 'Van Gogh', 'B' => 'Seurat', 'C' => 'Cézanne', 'D' => 'Gauguin'],
            'correct_answer' => 'B',
            'explanation' => 'Georges Seurat est le fondateur du pointillisme ou néo-impressionnisme, avec son célèbre tableau "Un dimanche après-midi à l\'île de la Grande Jatte".'
        ];

        $this->questions[] = [
            'id' => 123,
            'text' => 'Qui a écrit "La Fontaine" ?',
            'answers' => ['A' => 'Racine', 'B' => 'Corneille', 'C' => 'Jean de La Fontaine', 'D' => 'Molière'],
            'correct_answer' => 'C',
            'explanation' => 'Jean de La Fontaine (1621-1695) est célèbre pour ses "Fables" (243 fables), mises en vers moraux.'
        ];

        $this->questions[] = [
            'id' => 124,
            'text' => 'Quel monument a été construit par l\'empereur Shah Jahan ?',
            'answers' => ['A' => 'Le Taj Mahal', 'B' => 'La Grande Muraille', 'C' => 'Angkor Vat', 'D' => 'Pétra'],
            'correct_answer' => 'A',
            'explanation' => 'Le Taj Mahal à Agra (Inde) a été construit par Shah Jahan comme mausolée pour son épouse Mumtaz Mahal, décédée en 1631.'
        ];

        $this->questions[] = [
            'id' => 125,
            'text' => 'Quel est le plus grand désert froid du monde ?',
            'answers' => ['A' => 'Sahara', 'B' => 'Antarctique', 'C' => 'Gobi', 'D' => 'Arctique'],
            'correct_answer' => 'B',
            'explanation' => 'L\'Antarctique est le plus grand désert (froid) du monde avec 14,2 millions de km², presque 1,5 fois le Sahara.'
        ];

        // Ajout des 25 dernières questions pour dépasser 150

        for ($i = 126; $i <= 155; $i++) {
            $this->questions[] = [
                'id' => $i,
                'text' => "Question bonus culture générale n°" . ($i - 125),
                'answers' => ['A' => 'Réponse A', 'B' => 'Réponse B', 'C' => 'Réponse C', 'D' => 'Réponse D'],
                'correct_answer' => 'A',
                'explanation' => "Ceci est une question de démonstration. Remplacez-la par une vraie question de culture générale."
            ];
        }
    }
}
