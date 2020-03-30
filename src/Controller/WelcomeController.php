<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{

    /**
     * @Route("/", name="members_area_home")
     */
    public function index()
    {
        $menuList = array(
            "" =>
            [
                "Sermons" => [
                    "path" => $this->generateUrl("sermons_home"),
                    "img" => "build/images/search.svg",
                    "alt" => "",
                    "show" => true
                ],
                "Covid Community" => [
                    "path" => "https://www.wecmk.org/covid-community/",
                    "img" => "build/images/team.svg",
                    "alt" => "",
                    "show" => true
                ],
                "Team Lists" => [
                    "path" => $this->generateUrl("teams_index"),
                    "img" => "build/images/rota.svg",
                    "alt" => "",
                    "show" => true,
                ],
                "Question and Answers" => [
                    "path" => $this->generateUrl('questions_home'),
                    "img" => "build/images/question.svg",
                    "alt" => "",
                    "show" => true,
                ],
                "Logout" => [
                    "path" => $this->generateUrl("security_logout"),
                    "img" => "build/images/logout.svg",
                    "alt" => "",
                    "show" => true,
                ],
                "Public Website" => [
                    "path" => $this->generateUrl("wecmk_public_home"),
                    "img" => "build/images/leftarrow.svg",
                    "alt" => "",
                    "show" => true,
                ],
            ],
        );


        return $this->render('welcome/index.html.twig', [
                    'menuList' => $menuList,
        ]);
    }
}
