<?php
$post_type = get_post_type();

switch ($post_type) {
        // case 'spirits':
        //     Timber::render('templates/spirit-page.twig', Timber::get_context());
        //     break;
        // case 'cocktails':
        //     Timber::render('templates/cocktail-page.twig', Timber::get_context());
        //     break;
    default:
        Timber::render('views/templates/page_builder.twig', Timber::get_context());
}
