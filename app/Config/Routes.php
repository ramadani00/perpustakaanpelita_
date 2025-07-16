<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/user/login', 'User::getLogin');
$routes->post('/user/login', 'User::postLogin');
$routes->get('/user/logout', 'User::logout');

$routes->get('/anggota/login', 'Anggota::getLogin');
$routes->post('/anggota/login', 'Anggota::postLogin');
$routes->get('/anggota/logout', 'Anggota::logout');

$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
$routes->get('/page/tos', 'Page::tos');
$routes->get('/buku/(:num)', 'Buku::detail/$1');

$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/kategori/(:any)', 'Artikel::index/$1');
$routes->get('/artikel/artikelterkini', 'Artikel::terkini');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

// Admin area (protected)
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('ajax', 'AjaxController::dashboard_admin');
    $routes->get('ajax/admin_index', 'AjaxController::admin_index');
    $routes->get('ajax/get-data', 'AjaxController::getData');
    $routes->get('ajax/view/(:num)', 'AjaxController::view/$1');
    $routes->post('ajax/add', 'AjaxController::add');
    $routes->get('ajax/add', 'AjaxController::add');
    $routes->get('ajax/edit/(:num)', 'AjaxController::edit/$1'); 
    $routes->post('ajax/update/(:num)', 'AjaxController::update/$1');
    $routes->post('ajax/delete/(:num)', 'AjaxController::delete/$1');

    $routes->get('buku', 'Buku::index');
    $routes->get('buku/get-data', 'BukuAjaxController::getData');
    $routes->get('buku/kelola_buku', 'BukuAjaxController::kelola_buku');
    $routes->get('buku/add', 'BukuAjaxController::create');
    $routes->post('buku/add', 'BukuAjaxController::store');
    $routes->get('buku/edit/(:num)', 'BukuAjaxController::edit/$1');
    $routes->post('buku/edit/(:num)', 'BukuAjaxController::update/$1');
    $routes->post('buku/delete/(:num)', 'BukuAjaxController::delete/$1');
    $routes->get('buku/view/(:num)', 'BukuAjaxController::view/$1');

    $routes->get('peminjaman/get-data', 'PeminjamanAjaxController::getData');
    $routes->get('peminjaman/kelola_peminjaman', 'PeminjamanAjaxController::kelola_peminjaman');
    $routes->get('peminjaman/get-data', 'PeminjamanAjaxController::getData');
    $routes->get('peminjaman/add', 'PeminjamanAjaxController::add');
    $routes->post('peminjaman/add', 'PeminjamanAjaxController::add');
    $routes->get('peminjaman/edit/(:num)', 'PeminjamanAjaxController::edit/$1');
    $routes->post('peminjaman/edit/(:num)', 'PeminjamanAjaxController::edit_post/$1');
    $routes->delete('peminjaman/hapus/(:num)', 'PeminjamanAjaxController::hapus/$1');
});

// Area anggota (protected)
$routes->group('anggota', ['filter' => 'anggotaauth'], function($routes) {
    $routes->get('dashboard', 'Anggota::dashboard');
    $routes->get('laporan', 'Laporan::index');
    $routes->get('peminjaman', 'Peminjaman::index');
    $routes->get('getAnggota/(:num)', 'Anggota::getAnggota/$1');
});