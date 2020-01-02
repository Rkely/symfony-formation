<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $rep)
    {
        $comment = $rep->findAll();
        return $this->render('admin/comment/comment.html.twig', [
            'comments' => $comment
        ]);
    }
    /**
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     */
    public function edit(Comment $comment, ObjectManager $manager, Request $request)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                'success',
                "Modification effectuée"
            );
            return $this->redirectToRoute('admin_comment_index');
        }
        return $this->render('admin/comment/edit.html.twig', [
            'comments' => $comment,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     */
    public function delele(Comment $comment, ObjectManager $manager)
    {
            $manager->remove($comment);
            $manager->flush();
            $this->addFlash(
                'success',
                "Suppression effectuée"
            );
            return $this->redirectToRoute('admin_comment_index');
       
    }
}
