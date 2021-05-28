<?php

//CONFIGURAÇÕES 
require __DIR__.'/includes/app.php';

use App\Http\Router;

//INSTÂNCIA DO ROUTER 
$obRouter = new Router(URL);

//ROTAS DAS PÁGINAS 
require __DIR__.'/routes/pages.php';

// INCLUI ROTAS DO PAINEL
include __DIR__.'/routes/admin.php';

//RESPONSE DAS ROTAS
$obRouter->run()->sendResponse();