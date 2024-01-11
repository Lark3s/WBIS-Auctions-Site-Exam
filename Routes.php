<?php

use App\Core\Route;

return [
    #user routes
    Route::get('|^user/register/?$|',                  'Main',                   'getRegister'),
    Route::post('|^user/register/?$|',                 'Main',                   'postRegister'),
    Route::get('|^user/login/?$|',                     'Main',                   'getLogin'),
    Route::post('|^user/login/?$|',                    'Main',                   'postLogin'),
    Route::get('|^user/logout/?$|',                    'Main',                   'getLogout'),

    #category routes
    Route::get('|^categories/?$|',                     'Categories',             'show'),
    Route::get('|^category/([0-9]+)/?$|',              'Category',               'show'),
    Route::get('|^category/([0-9]+)/delete/?$|',       'Category',               'delete'),

    #auction routes
    Route::get('|^auction/([0-9]+)/?$|',               'Auction',                'show'),
    Route::post('|^search/?$|',                        'Auction',                'postSearch'),

    #EventHandler routes
    Route::get('|^handle/([a-z]+)/?$|',                'EventHandler',           'handle'),

    #API routes:
    Route::get('|^api/auction/([0-9]+)/?$|',           'ApiAuction',             'show'),
    Route::get('|^api/bookmarks/?$|',                  'ApiBookmark',            'getBookmarks'),
    Route::get('|^api/bookmarks/add/([0-9]+)/?$|',     'ApiBookmark',            'addBookmark'),
    Route::get('|^api/bookmarks/clear/?$|',            'ApiBookmark',            'clear'),

    #Admin API routes - admin user not implemented
    Route::post('|^api/charts/year/?$|',               'ApiChart',               'chartByYears'),

    #User API routes
    Route::post('|^api/offer/make/?$|',                'ApiUserOffer',           'postMakeOffer'),

    #User role routes:
    Route::get('|^user/profile/?$|',                   'UserDashboard',          'index'),
    Route::get('|^user/profile/analytics/?$|',         'UserDashboard',          'analytics'),

    Route::get('|^user/categories/?$|',                'UserCategoryManagement', 'categories'),
    Route::get('|^user/categories/edit/([0-9]+)/?$|',  'UserCategoryManagement', 'getEdit'),
    Route::post('|^user/categories/edit/([0-9]+)/?$|', 'UserCategoryManagement', 'postEdit'),
    Route::get('|^user/categories/add/?$|',            'UserCategoryManagement', 'getAdd'),
    Route::post('|^user/categories/add/?$|',           'UserCategoryManagement', 'postAdd'),

    Route::get('|^user/auctions/?$|',                  'UserAuctionManagement',  'auctions'),
    Route::get('|^user/auctions/edit/([0-9]+)/?$|',    'UserAuctionManagement',  'getEdit'),
    Route::post('|^user/auctions/edit/([0-9]+)/?$|',   'UserAuctionManagement',  'postEdit'),
    Route::get('|^user/auctions/add/?$|',              'UserAuctionManagement',  'getAdd'),
    Route::post('|^user/auctions/add/?$|',             'UserAuctionManagement',  'postAdd'),

    #default
    Route::any('|^.*?$|',                               'Main',                   'home'),

    #404-not-found
//    Route::any('|^.*$|',                               'Main',                   'notFound')
];