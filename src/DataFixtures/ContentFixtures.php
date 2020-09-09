<?php


namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ContentFixtures extends Fixture implements DependentFixtureInterface
{
    const TOOLBOX = [
        [
            'title' => 'HORSYS',
            'content' => 'La solution Horsys est accessible depuis une page web. Elle vous permet de :
            Consulter et gérer les demandes des collaborateurs (congés, abscences...)
            Plannifier les plannings horaires de votre équipe
            Visualiser les fins de contrat
            Valider et visualiser les feuilles de temps de vos collaborateurs
    
            Vous trouverez le guide d\'utilisation et toutes les informations dont vous avez besoin sur votre clé USB ainsi que dans le Book d\'outils'
        ],
        [
            'title' => 'EURECIA',
            'content' => 'La solution Eurecia est accessible depuis une page web ou bien de votre Smartphone. Elle vous permet de :

            Soumettre à validation vos notes de frais en temps réel en y joignant vos différents justificatifs en PJ
            Catégoriser vos dépenses par thème et/ou par déplacement
            Suivre l\'état d\'avancement du traitement de vos saisies en temps réel
        
            Vous trouverez le guide d\'utilisation et toutes les informations dont vous avez besoin sur votre clé USB ainsi que dans le Book d\'outils'
        ],
        [
            'title' => 'IRESIA',
            'content' => 'Iresa est un logiciel de gestion de suivi des réservations, interne au Groupe Nemea.
            Il s\'agit d\'une solution sur laquelle vous pourrez retrouver un suivi en temps réel des réservations effectuées sur votre résidence.
            Iresa vous permettra également d\'avoir une visibilité sur les différentes prestations réservées par séjour.',
        ],
        [
            'title' => 'SILAE',
            'content' => 'La solution Silae est un logiciel de gestion des contrats Extras.
            Elle vous permettra la génération des Déclarations Préalables à l\'Embauche (DPAE) et de renseigner le nombre d\'heures effectuées dans le mois par le salarié et par contrat, afin que le service paie puisse générer les bulletins de salaires.
            Vous trouverez le guide d\'utilisation et toutes les informations dont vous avez besoin sur votre clé USB ainsi que dans le Book d\'outils',
        ],
        [
            'title' => 'PROGIDOC',
            'content' => 'Progidoc est un logiciel de dématérialisation des factures fournisseurs et des demandes d\'achats. Cet outil permet d\'intégrer les devis pour les soumettre à validation au manager.
            Une fois ces derniers validés, Progidoc permettra d\'avoir un suivi des dépenses effectuées et de suivre la facturation des différents achats.',
        ],
    ];

    const CHECKLIST_MANAGER = [
        [
            'title' => 'Mettre toutes les chances de réussite de son côté',
            'content' => 'Relire la défintion du poste et des responsabilités.
            Décrire le style et les attentes du responsable.
            Préciser les objectifs de performance.
            Prévoir des rendez-vous avec les "acteurs" clés susceptibles de collaborer avec la nouvelle recrue.
            Présenter les outils usuels.
            Expliquer le mode de réservation des salles de réunion.
            Fournir l\'annuaire des salariés.
            Expliquer l\'installation du bureau et la procédure de demande de fournitures.
            Prévoir un entretien individuel chaque semaine.
            Inclure le nouveau collaborateur aux réunions de routine de l\'équipe.
            Confirmer que le salarié ait bien reçu et lu les différentes politiques et procédures.',
        ],

        [
            'title' => "Présenter l'environnement de travail",
            'content' => 'Salle de pause
            Toilettes
            Salles de réunion
            Photocopieuse et fax
            Politique d\'achat des fournitures
            Accés et stationnement',
        ],

        [
            'title' => 'Créer un accueil chaleureux',
            'content' => 'Préparer le planning pour la première semaine.
            Organiser le déjeuner du premier jour (plateau repas).
            Envoyer un e-mail d\'accueil au personnel.
            Présenter le salarié à ses collègues.
            Présenter les responsables des différents services et la direction.
            Échanger avec le collaborateur.
            Proposer une partie de baby-foot en salle de pause.',
        ],

        [
            'title' => "Faire preuve d'investissement",
            'content' => 'Identifier de potentielles actions de formations et de développement à prévoir pour le salarie au cours des prochains mois.
            Identifier et établir des objectifs de carrière quantifiables pour les mois ou années à venir.'
        ],
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::TOOLBOX as $data) {
            $toolbox = new Content();
            $toolbox->setUser($this->getReference('admin'));
            $toolbox->setTitle($data['title']);
            $toolbox->setContent($data['content']);
            $toolbox->setCategory($this->getReference('category_0'));
            $manager->persist($toolbox);
        }
        foreach (self::CHECKLIST_MANAGER as $data) {
            $listManager = new Content();
            $listManager->setUser($this->getReference('admin'));
            $listManager->setTitle($data['title']);
            $listManager->setContent($data['content']);
            $listManager->setCategory($this->getReference('category_1'));
            $manager->persist($listManager);
        }
        $manager->flush();
    }
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [CategoryFixtures::class, UserFixtures::class];
    }
}
