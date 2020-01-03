<?php
namespace App\Service;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService{

    private $entityClass;
    private $limit = 10;
    private $currentpage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatepath;
    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatepath)
    {
        $this->manager     = $manager;
        $this->twig        = $twig;
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatepath = $templatepath;

    }
    public function setRoute($route){
        $this->route = $route;
        return $this;
    }
    public function getRoute($route){
        return $this->route;
    }
    public function setTemplatepath($templatepath){
        $this->templatepath = $templatepath;
        return $this;
    }
    public function getTemplatepath($templatepath){
        return $this->templatepath;
    }
    public function display(){
        $this->twig->display($this->templatepath,[
            'page' => $this->currentpage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }
    public function getPages(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas specifier l'entite sur laquelle nous devons paginer !
            Utiliser la methode setEntity() de votre objet PaginationService !");
        }
        $rep = $this->manager->getRepository($this->entityClass);
        $total = count($rep->findAll());
        $pages = ceil($total/ $this->limit);
        return $pages;
    }
    public function getData(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas specifier l'entite sur laquelle nous devons paginer !
            Utiliser la methode setEntity() de votre objet PaginationService !");
        }
        //1 )Calculer l'objet
        $offset = $this->currentpage * $this->limit -$this->limit;
        //2 ) Demader au repository de trouver les elements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([],[],$this->limit, $offset);
        //3) Renvoyer les elements en question
        return $data;

    }

    public function setPage($page){
        $this->currentpage = $page;
        return $this;
    }
    public function getPage(){
        return $this->currentpage;
    }
    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }
    public function getLimti(){
        return $this->limit;
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;
        return $this;
    }
    public function getEntityClass(){
        return $this->entityClass;
    }




}