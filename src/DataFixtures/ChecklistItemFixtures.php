<?php


namespace App\DataFixtures;

use App\Entity\ChecklistItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ChecklistItemFixtures extends Fixture
{

    const ITEMS = [
        '0' => [
            'name' => 'Signer et renvoyer mon contrat de travail',
            'category' => ChecklistItem::TODO,
        ],
        '1' => [
            'name' => 'Retourner le dossier d\'embauche avec l\'ensemble des documents demandés',
            'category' => ChecklistItem::TODO,
        ],
        '2' => [
            'name' => 'Si j\'ai un logement de fonction,
         remplir l\'état des lieux d\'entrée avec mon responsable de Zone ou mon Référent Métier',
            'category' => ChecklistItem::TODO,
        ],
        '3' => [
            'name' => 'Retourner le formulaire d\'état des lieux d\'entrée au service RH 
            accompagné du chèque de caution',
            'category' => ChecklistItem::TODO,
        ],
        '4' => [
            'name' => 'Welcome Pack',
            'category' => ChecklistItem::DOC,
        ],
        '5' => [
            'name' => 'Contrat de travail',
            'category' => ChecklistItem::DOC,
        ],
        '6' => [
            'name' => 'Dossier d\'embauche',
            'category' => ChecklistItem::DOC,
        ],
        '7' => [
            'name' => 'Clé USB',
            'category' => ChecklistItem::DOC,
        ],
        '8' => [
            'name' => 'Book Outils (Horsys, Iresa, Progidoc...)',
            'category' => ChecklistItem::DOC,
        ],
        '9' => [
            'name' => 'Carnet de route',
            'category' => ChecklistItem::DOC,
        ],


    ];

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::ITEMS as $data) {
            $item = new ChecklistItem();
            $item->setName($data['name']);
            $item->setCategory($data['category']);
            $manager->persist($item);
        }
        $manager->flush();
    }
}
