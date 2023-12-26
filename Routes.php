<?php

use App\Core\Route;

return [
    #user routes
    Route::get('|^user/register/?$|',                  'Main',                   'getRegister'),
    Route::post('|^user/register/?$|',                 'Main',                   'postRegister'),
    Route::get('|^user/login/?$|',                     'Main',                   'getLogin'),
    Route::post('|^user/login/?$|',                    'Main',                   'postLogin'),

    #category routes
    Route::get('|^category/([0-9]+)/?$|',              'Category',               'show'),
    Route::get('|^category/([0-9]+)/delete/?$|',       'Category',               'delete'),

    #auction routes
    Route::get('|^auction/([0-9]+)/?$|',               'Auction',                'show'),

    #API routes:
    Route::get('|^api/auction/([0-9]+)/?$|',           'ApiAuction',             'show'),
    Route::get('|^api/bookmarks/?$|',                  'ApiBookmark',            'getBookmarks'),
    Route::get('|^api/bookmarks/add/([0-9]+)/?$|',     'ApiBookmark',            'addBookmark'),
    Route::get('|^api/bookmarks/clear/?$|',            'ApiBookmark',            'clear'),

    #User role routes:
    Route::get('|^user/profile/?$|',                   'UserDashboard',          'index'),
    Route::get('|^user/categories/?$|',                'UserCategoryManagement', 'categories'),
    Route::get('|^user/categories/edit/([0-9]+)/?$|',  'UserCategoryManagement', 'getEdit'),
    Route::post('|^user/categories/edit/([0-9]+)/?$|', 'UserCategoryManagement', 'postEdit'),
    Route::get('|^user/categories/add/?$|',            'UserCategoryManagement', 'getAdd'),
    Route::post('|^user/categories/add/?$|',           'UserCategoryManagement', 'postAdd'),

    #default
    Route::any('|^.*$|',                               'Main',                   'home')
];