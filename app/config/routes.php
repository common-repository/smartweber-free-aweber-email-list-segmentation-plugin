<?php

$route = new ActionPHPRapid_Route;

$route->get ('links', 'links@LinksController' );
$route->post( 'create', 'create@LinksController');
$route->put ( 'link', 'update@LinksController');
$route->delete( 'link/{id}', 'delete@LinksController');

$route->post('aweber', "connect@AweberController");