<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Livres;
use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LivresFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create('fr_FR');
        for($j= 1; $j<=3; $j++)
        {
            $cat = new Categories();
            $cat->setLibelle($faker->name);
            $cat->setDescription($faker->text);
            $manager->persist($cat);
            for($i = 1; $i <= random_int(10, 15); $i++)
            {
                $livre =new Livres();
                $livre->setLibelle($faker->name);
                $livre->setImage('https://picsum.photos/300/?random=' . $i);
                $livre->setDescription($faker->text);
                $livre->setPrix($faker->numberBetween(10,200));
                $livre->setEditeur($faker->company);
                $livre->setDateEdition(new \DateTime($faker->date()));
                $livre->setCategorie($cat);
                $manager->persist($livre);
            }
        }
        $manager->flush();
    }
}
