<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Biblioteca
Breadcrumbs::for('posts', function (BreadcrumbTrail $trail) {
    $trail->push('Biblioteca', route('biblioteca.index'));
});

// Biblioteca > Crear post
Breadcrumbs::for('crearPost', function (BreadcrumbTrail $trail) {
    $trail->parent('posts');
    $trail->push('Crear post', route('biblioteca.create'));
});

// Biblioteca > Editar post
Breadcrumbs::for('editarPost', function (BreadcrumbTrail $trail) {
    $trail->parent('posts');
    $trail->push('Editar post', route('biblioteca.create'));
});

// Buzon
Breadcrumbs::for('buzon', function (BreadcrumbTrail $trail) {
    $trail->push('Buzón', route('buzon.index'));
});

// Buzon > Ver mensaje
Breadcrumbs::for('verMensaje', function (BreadcrumbTrail $trail) {
    $trail->parent('buzon');
    $trail->push('Ver mensaje');
});
