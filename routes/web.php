<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompleteProjectListUrlController;
use App\Http\Controllers\TodolistController;


Route::get('/', function () {
    return view('frontend.pages.home');
});

// Route::get('/dashboard', function () {
//     return view('backend.layouts.app');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// Route::get('/dashboard-page', [DashboardController::class, 'Dashboard'])
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard.page');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // about section route
    Route::get("/about-section", [AboutController::class, 'AboutSection'])->name('about.section');
    Route::get("/about-list", [AboutController::class, 'AboutList'])->name('about.list');
    Route::post("/about-save", [AboutController::class, 'AboutSave'])->name('about.save');

    // category section route
    Route::get("/category-section", [CategoryController::class, 'CategorySection'])->name('category.section');
    Route::get("/category-list", [CategoryController::class, 'CategoryList']);
    Route::post("/category-create", [CategoryController::class, 'CategoryCreate']);
    Route::post("/category-by-id", [CategoryController::class, 'CategoryById']);
    Route::post("/category-update", [CategoryController::class, 'CategoryUpdate']);
    Route::post("/category-delete", [CategoryController::class, 'CategoryDelete']);
    // project section route
    Route::get("/project-section", [ProjectController::class, 'ProjectSection'])->name('project.section');
    Route::get("/project-list", [ProjectController::class, 'ProjectList']);
    Route::post('/project-view-count/{id}', [ProjectController::class, 'ProjectViewCount']);
    Route::post("/project-create", [ProjectController::class, 'ProjectCreate']);
    Route::post("/project-by-id", [ProjectController::class, 'ProjectById']);
    Route::post("/project-update", [ProjectController::class, 'ProjectUpdate']);
    Route::post("/project-delete", [ProjectController::class, 'ProjectDelete']);
    // marketplace section route
    Route::get("/marketplace-section", [MarketplaceController::class, 'MarketplaceSection'])->name('marketplace.section');
    Route::get("/marketplace-list", [MarketplaceController::class, 'MarketplaceList']);
    Route::post("/marketplace-create", [MarketplaceController::class, 'MarketplaceCreate']);
    Route::post("/marketplace-by-id", [MarketplaceController::class, 'MarketplaceById']);
    Route::post("/marketplace-update", [MarketplaceController::class, 'MarketplaceUpdate']);
    Route::post("/marketplace-delete", [MarketplaceController::class, 'MarketplaceDelete']);
    // product section route
    Route::get("/product-section", [ProductController::class, 'ProductSection'])->name('product.section');
    Route::get("/product-list", [ProductController::class, 'ProductList']);
    Route::post("/product-create", [ProductController::class, 'ProductCreate']);
    Route::post("/product-by-id", [ProductController::class, 'ProductById']);
    Route::post("/product-update", [ProductController::class, 'ProductUpdate']);
    Route::post("/product-delete", [ProductController::class, 'ProductDelete']);

    // contact section route
    Route::get("/contact-section", [ContactController::class, 'ContactSection'])->name('contact.section');
    Route::get("/contact-list", [ContactController::class, 'ContactList']);
    Route::post('/contact-submit', [ContactController::class, 'store'])->name('contact.submit');
    Route::post("/contact-by-id", [ContactController::class, 'ContactById']);
    Route::post("/contact-update", [ContactController::class, 'ContactUpdate']);
    Route::post("/contact-delete", [ContactController::class, 'ContactDelete']);

       // settings section route
    Route::get("/settings-section", [SettingsController::class, 'SettingsSection'])->name('settings.section');
    Route::get("/settings-list", [SettingsController::class, 'SettingsList'])->name('settings.list');
    Route::post("/settings-save", [SettingsController::class, 'SettingsSave'])->name('settings.save');
 // Complete Project section route
    Route::get("/complete-project-section", [CompleteProjectListUrlController::class, 'CompleteProjectSection'])->name('complete-project.section');
    Route::get("/complete-project-list", [CompleteProjectListUrlController::class, 'CompleteProjectList']);
    Route::post('/complete-project-view-count/{id}', [CompleteProjectListUrlController::class, 'CompleteProjectViewCount']);
    Route::post("/complete-project-create", [CompleteProjectListUrlController::class, 'CompleteProjectCreate']);
    Route::post("/complete-project-by-id", [CompleteProjectListUrlController::class, 'CompleteProjectById']);
    Route::post("/complete-project-update", [CompleteProjectListUrlController::class, 'CompleteProjectUpdate']);
    Route::post("/complete-project-delete", [CompleteProjectListUrlController::class, 'CompleteProjectDelete']);

    // todolist section route
    Route::get("/todolist-section", [TodolistController::class, 'TodolistSection'])->name('todolist.section');
    Route::get("/todolist-list", [TodolistController::class, 'TodolistList']);
    Route::post("/todolist-create", [TodolistController::class, 'TodolistCreate']);
    Route::post("/todolist-by-id", [TodolistController::class, 'TodolistById']);
    Route::post("/todolist-update", [TodolistController::class, 'TodolistUpdate']);
    Route::post("/todolist-delete", [TodolistController::class, 'TodolistDelete']);
});

require __DIR__.'/auth.php';
