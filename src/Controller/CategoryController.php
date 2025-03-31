<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CategoryFormType;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

final class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_category')]
    public function index(CategoryRepository $category): Response
    {
        $category = $category->findAll();
        return $this->render('category/index.html.twig', ['category' => $category]);
    }
 #[Route('/admin/category/add', name: 'app_category_add')]
    public function new(EntityManagerInterface $em , Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class , $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            $this->addFlash('success' , 'Category Created Successfully');
            return $this->redirectToRoute('app_category' , ['category' => $category]);
        }

      return $this->render('category/add.html.twig' , ['form' => $form->createView()]); 


    }

    #[Route('/admin/category/update/{id}', name: 'app_category_update')]
    public function update(EntityManagerInterface $em , Request $request , Category $category): Response
    {
        $form = $this->createForm(CategoryFormType::class , $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('info' , 'Category Updated Successfully');
            return $this->redirectToRoute('app_category' , ['category' => $category]);
        }

      return $this->render('category/update.html.twig' , ['form' => $form->createView()]);
    }
    #[Route('/admin/category/delete/{id}', name: 'app_category_delete')]
    public function delete(EntityManagerInterface $em ,Category $category): Response
    {
            $em->remove($category);
            $em->flush();
            $this->addFlash('danger' , 'Category has Deleted');
            return $this->redirectToRoute('app_category' , ['category' => $category]);
    }
}