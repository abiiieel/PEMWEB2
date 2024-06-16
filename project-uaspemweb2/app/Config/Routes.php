<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('/kategori/hapus/(:any)', 'Kategori::index');
$routes->delete('/kategori/hapus/(:any)', 'Kategori::hapus/$1');
$routes->get('/barang/hapus/(:any)', 'Barang::index');
$routes->get('/barangkeluar/edit/*', 'BarangKeluar::edit/$1');
$routes->delete('/barang/hapus/(:any)', 'Barang::hapus/$1');
