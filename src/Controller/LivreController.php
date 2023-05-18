<?php

namespace App\Controller;

use DateTime;
use App\Entity\Livres;
use App\Form\LivreType;
use App\Repository\LivresRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivreController extends AbstractController
{//paramconverter
    #[Route('admin/livres/find/{id}', name: 'admin_livres_find_id')]
    public function find(Livres $livre): Response
    {
        return $this->render('livre/find.html.twig',['livre'=>$livre]);
    }

    #[Route('admin/livres', name: 'app_livres')]
    public function findAll(LivresRepository $rep, PaginatorInterface $paginator,Request $request): Response
    {
         $livres=$rep->findAll();
         $pagination = $paginator->paginate(
            $livres, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );
         return $this->render('livre/findAll.html.twig',['livres'=>$pagination]);
       
   }

   #[Route('admin/livres/add', name: 'admin_livres_add')]
   public function add(ManagerRegistry $doctrine): Response
   {    $dateedition=new \DateTime('2022-02-18');
       $livre = new Livres();
       $livre->setLibelle('bd')
             ->setPrix(80)
             ->setDescription('bla bla bla bla ')
             ->setImage('https://via.placeholder.com/300')
             ->setEditeur('messi')
             ->setDateEdition($dateedition);

       $em=$doctrine->getManager();
       $em->persist($livre);
       $em->flush();
       return new Response("Le livre est enregistré avec succés");    
    }
    #[Route('admin/livres/delete/{id}', name: 'admin_livres_id_delete')]
    public function delete(Livres $livre,ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->remove($livre);
        $em->flush();
        //dd($livre);
        return $this->redirectToRoute('app_livres');
    }

    #[Route('admin/livres/update/{id}', name: 'app_livres_id')]
    public function update($id,LivresRepository $rep,ManagerRegistry $doctrine): Response
    {
        $livre=$rep->find($id);
        	$livre->setPrix(75);
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_livres');
    }

    #[Route('admin/livres/create', name: 'admin_livres_create')]
    public function create(Request $request,ManagerRegistry $doctrine): Response
    {    
        $livre = new Livres();
        $form = $this->createForm(LivreType::class,$livre);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {
            $livre =$form->getData();
            $em=$doctrine->getManager();
            $em->persist($livre);
            $em->flush();
            $this->addFlash('success', 'Le livre est enregistré avec succés.');  
            return $this->redirectToRoute('app_livres');
        }
        return $this->render('livre/create.html.twig',['form'=>$form]);
}
}   
