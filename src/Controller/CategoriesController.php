<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategorieType;
use App\Repository\CategoriesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController
{
    #[Route('/admin/categories/create', name: 'admin_categories_create')]
    public function create(Request $request,ManagerRegistry $doctrine): Response
    {
        $cat = new Categories();
        $form = $this->createForm(CategorieType::class,$cat);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {
            $cat =$form->getData();
            $em=$doctrine->getManager();
            $em->persist($cat);
            $em->flush();
            $this->addFlash('success', 'La catégorie est ajoutée avec succès.');
            return $this->redirectToRoute('app_categories');
        }
        return $this->render('Categories/create.html.twig',['form'=>$form]);
    }


    #[Route('/admin/categories', name: 'app_categories')]
    public function ListAll(CategoriesRepository $rep): Response
    {
        $categories=$rep->findAll(); 
        return $this->render('categories/listAll.html.twig',['categories'=>$categories]); 
    }

    #[Route('admin/categories/delete/{id}', name: 'admin_categories_id_delete')]
    public function delete(categories $categories,ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->remove($categories);
        $em->flush();
        //dd($livre);
        return $this->redirectToRoute('app_categories');
    }
}
