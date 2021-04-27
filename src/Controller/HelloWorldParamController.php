<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HelloWorldParamController
{
    #[Route('/hello-param', name: 'hello_param', methods: ['GET'])]
    public function __invoke(string $environment, string $environmentAlias): Response
    {
        $environment = strtoupper($environment);

        return new Response(
            <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hello World!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <h1 class="mr-3 pr-3 align-top border-right inline-block align-content-center">$environment</h1>
    <div class="inline-block align-middle">
        <h2 class="font-weight-normal lead" id="desc" style="font-size: 2rem;">Hello world from <strong><u>$environmentAlias</u></strong> environment</h2>
    </div>
</div>
</body>
</html>
HTML
        );
    }
}
