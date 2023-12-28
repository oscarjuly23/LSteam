<?php
declare(strict_types=1);

namespace SallePW\SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

final class VisitsController
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function showVisits(Request $request, Response $response): Response
    {
        if (empty($_SESSION['counter'])) {
            $_SESSION['counter'] = 1;
        } else {
            $_SESSION['counter']++;
        }

        return $this->twig->render(
            $response,
            'visits.twig',
            [
                'visits' => $_SESSION['counter'],
            ]
        );
    }
}