<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function(){

    Route::prefix('auth')->group(function(){

        Route::post('login', 'App\Http\Controllers\Admin\AuthController@login');

        Route::middleware(['auth:sanctum', 'admin'])->group(function(){
            Route::post('unlock', 'App\Http\Controllers\Admin\AuthController@unlock');
            Route::post('logout', 'App\Http\Controllers\Admin\AuthController@logout');
        });

    });
    Route::middleware(['auth:sanctum', 'admin'])->group(function(){

        Route::prefix('account')->group(function(){
            Route::post('', 'App\Http\Controllers\Admin\AccountController@index');
            Route::post('save', 'App\Http\Controllers\Admin\AccountController@save');
            Route::post('password', 'App\Http\Controllers\Admin\AccountController@password');
        });
        Route::middleware('mails')->group(function(){

            Route::prefix('mail')->group(function(){
                Route::post('', 'App\Http\Controllers\Admin\MailController@index');
                Route::post('send', 'App\Http\Controllers\Admin\MailController@send');
                Route::post('active', 'App\Http\Controllers\Admin\MailController@active');
                Route::post('unactive', 'App\Http\Controllers\Admin\MailController@unactive');
                Route::post('archive', 'App\Http\Controllers\Admin\MailController@archive');
                Route::post('star', 'App\Http\Controllers\Admin\MailController@star');
                Route::post('important', 'App\Http\Controllers\Admin\MailController@important');
                Route::post('delete', 'App\Http\Controllers\Admin\MailController@delete');
            });

        });
        Route::middleware('messages')->group(function(){

            Route::prefix('chat')->group(function(){

                Route::prefix('friends')->group(function(){

                    Route::post('', 'App\Http\Controllers\Admin\MessageController@relations');

                    Route::prefix('{user}')->group(function(){
                        Route::post('', 'App\Http\Controllers\Admin\MessageController@messages');
                        Route::post('send', 'App\Http\Controllers\Admin\MessageController@send');
                        Route::post('active', 'App\Http\Controllers\Admin\MessageController@active');
                        Route::post('delete', 'App\Http\Controllers\Admin\MessageController@delete');
                        Route::post('archive', 'App\Http\Controllers\Admin\MessageController@archive');
                        Route::post('unarchive', 'App\Http\Controllers\Admin\MessageController@unarchive');
                    });

                });
                Route::prefix('messages')->group(function(){

                    Route::prefix('{message}')->group(function(){
                        Route::post('star', 'App\Http\Controllers\Admin\MessageController@star_message');
                        Route::post('unstar', 'App\Http\Controllers\Admin\MessageController@unstar_message');
                        Route::post('delete', 'App\Http\Controllers\Admin\MessageController@delete_message');
                    });

                });

            });

        });
        Route::middleware('statistics')->group(function(){
            Route::prefix('statistic')->group(function(){
                Route::post('', 'App\Http\Controllers\Admin\StatisticController@index');
            });
        });
        Route::middleware('categories')->group(function(){

            Route::prefix('category')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\CategoryController@index');
                Route::post('store', 'App\Http\Controllers\Admin\CategoryController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\CategoryController@delete_group');

                Route::prefix('{category}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\CategoryController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\CategoryController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\CategoryController@delete');
                    Route::post('products', 'App\Http\Controllers\Admin\CategoryController@products');
                });

            });

        });
        Route::middleware('products')->group(function(){

            Route::prefix('product')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\ProductController@index');
                Route::post('default', 'App\Http\Controllers\Admin\ProductController@default');
                Route::post('store', 'App\Http\Controllers\Admin\ProductController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\ProductController@delete_group');

                Route::prefix('{product}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\ProductController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\ProductController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\ProductController@delete');
                    Route::post('orders', 'App\Http\Controllers\Admin\ProductController@orders');
                    Route::post('reviews', 'App\Http\Controllers\Admin\ProductController@reviews');
                });

            });

        });
        Route::middleware('coupons')->group(function(){

            Route::prefix('coupon')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\CouponController@index');
                Route::post('store', 'App\Http\Controllers\Admin\CouponController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\CouponController@delete_group');

                Route::prefix('{coupon}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\CouponController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\CouponController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\CouponController@delete');
                    Route::post('orders', 'App\Http\Controllers\Admin\CouponController@orders');
                });

            });

        });
        Route::middleware('orders')->group(function(){

            Route::prefix('order')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\OrderController@index');
                Route::post('default', 'App\Http\Controllers\Admin\OrderController@default');
                Route::post('store', 'App\Http\Controllers\Admin\OrderController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\OrderController@delete_group');

                Route::prefix('{order}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\OrderController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\OrderController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\OrderController@delete');
                });

            });

        });
        Route::middleware('reviews')->group(function(){

            Route::prefix('review')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\ReviewController@index');
                Route::post('store', 'App\Http\Controllers\Admin\ReviewController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\ReviewController@delete_group');

                Route::prefix('{review}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\ReviewController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\ReviewController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\ReviewController@delete');
                });

            });

        });
        Route::middleware('blogs')->group(function(){

            Route::prefix('blog')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\BlogController@index');
                Route::post('store', 'App\Http\Controllers\Admin\BlogController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\BlogController@delete_group');

                Route::prefix('{blog}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\BlogController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\BlogController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\BlogController@delete');
                    Route::post('comments', 'App\Http\Controllers\Admin\BlogController@comments');
                });

            });
            Route::prefix('comment')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\CommentController@index');
                Route::post('store', 'App\Http\Controllers\Admin\CommentController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\CommentController@delete_group');

                Route::prefix('{comment}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\CommentController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\CommentController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\CommentController@delete');
                    Route::post('replies', 'App\Http\Controllers\Admin\CommentController@replies');
                });

            });
            Route::prefix('reply')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\ReplyController@index');
                Route::post('store', 'App\Http\Controllers\Admin\ReplyController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\ReplyController@delete_group');

                Route::prefix('{reply}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\ReplyController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\ReplyController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\ReplyController@delete');
                });

            });

        });
        Route::middleware('contacts')->group(function(){

            Route::prefix('contact')->group(function(){
                Route::post('', 'App\Http\Controllers\Admin\ContactController@index');
                Route::post('delete', 'App\Http\Controllers\Admin\ContactController@delete');
            });

        });
        Route::middleware('reports')->group(function(){

            Route::prefix('report')->group(function(){
                Route::post('', 'App\Http\Controllers\Admin\ReportController@index');
                Route::post('delete', 'App\Http\Controllers\Admin\ReportController@delete');
            });

        });
        Route::middleware('clients')->group(function(){

            Route::prefix('client')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\ClientController@index');
                Route::post('store', 'App\Http\Controllers\Admin\ClientController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\ClientController@delete_group');

                Route::prefix('{user}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\ClientController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\ClientController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\ClientController@delete');
                    Route::post('orders', 'App\Http\Controllers\Admin\ClientController@orders');
                    Route::post('reviews', 'App\Http\Controllers\Admin\ClientController@reviews');
                    Route::post('comments', 'App\Http\Controllers\Admin\ClientController@comments');
                    Route::post('replies', 'App\Http\Controllers\Admin\ClientController@replies');
                });

            });

        });
        Route::middleware('vendors')->group(function(){

            Route::prefix('vendor')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\VendorController@index');
                Route::post('store', 'App\Http\Controllers\Admin\VendorController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\VendorController@delete_group');

                Route::prefix('{user}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\VendorController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\VendorController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\VendorController@delete');
                });

            });

        });
        Route::middleware('supervisor')->group(function(){

            Route::prefix('admin')->group(function(){

                Route::post('', 'App\Http\Controllers\Admin\AdminController@index');
                Route::post('store', 'App\Http\Controllers\Admin\AdminController@store');
                Route::post('delete', 'App\Http\Controllers\Admin\AdminController@delete_group');

                Route::prefix('{user}')->group(function(){
                    Route::post('', 'App\Http\Controllers\Admin\AdminController@show');
                    Route::post('update', 'App\Http\Controllers\Admin\AdminController@update');
                    Route::post('delete', 'App\Http\Controllers\Admin\AdminController@delete');
                });

            });

        });
        Route::middleware('super')->group(function(){

            Route::prefix('setting')->group(function(){
                Route::post('', 'App\Http\Controllers\Admin\SettingController@index');
                Route::post('update', 'App\Http\Controllers\Admin\SettingController@update');
                Route::post('option', 'App\Http\Controllers\Admin\SettingController@option');
                Route::post('delete', 'App\Http\Controllers\Admin\SettingController@delete');
            });

        });

    });

});

// try this

//  Route::middleware('mails')->prefix('mail')->controller('App\Http\Controllers\Admin\MailController')->group(function(){
//      Route::post('', 'index');
//  })
