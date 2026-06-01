<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\JudulKerjasamaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TemplateController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});




// User management routes moved into admin-only group below




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes accessible by both admin and staff
Route::middleware('role:admin,staff')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Mitra
        Route::get('/mitra', [MitraController::class, 'index'])->name('mitra');
        Route::get('/mitra/create', [MitraController::class, 'create'])->name('mitra.create');
        Route::post('/mitra', [MitraController::class, 'store'])->name('mitra.store');
        Route::get('/mitra/trashed', [MitraController::class, 'trashed'])->name('mitra.trashed');
        Route::put('/mitra/{mitra}/restore', [MitraController::class, 'restore'])->name('mitra.restore');
        Route::delete('/mitra/{mitra}/force-delete', [MitraController::class, 'forceDelete'])->name('mitra.force-delete');
        Route::get('/mitra/{mitra}', [MitraController::class, 'show'])->name('mitra.show');
        Route::get('/mitra/{mitra}/edit', [MitraController::class, 'edit'])->name('mitra.edit');
        Route::put('/mitra/{mitra}', [MitraController::class, 'update'])->name('mitra.update');
        Route::delete('/mitra/{mitra}', [MitraController::class, 'destroy'])->name('mitra.destroy');

        // Judul Kerjasama (Dokumen)
        Route::get('/judul-kerjasama', [JudulKerjasamaController::class, 'index'])->name('judul-kerjasama');
        Route::get('/judul-kerjasama/create', [JudulKerjasamaController::class, 'create'])->name('judul-kerjasama.create');
        Route::post('/judul-kerjasama', [JudulKerjasamaController::class, 'store'])->name('judul-kerjasama.store');
        Route::get('/judul-kerjasama/{judul_kerjasama}', [JudulKerjasamaController::class, 'show'])->name('judul-kerjasama.show');
        Route::get('/judul-kerjasama/{judul_kerjasama}/edit', [JudulKerjasamaController::class, 'edit'])->name('judul-kerjasama.edit');
        Route::put('/judul-kerjasama/{judul_kerjasama}', [JudulKerjasamaController::class, 'update'])->name('judul-kerjasama.update');
        Route::delete('/judul-kerjasama/{judul_kerjasama}', [JudulKerjasamaController::class, 'destroy'])->name('judul-kerjasama.destroy');

        // Templates (resource CRUD)
        Route::resource('templates', \App\Http\Controllers\TemplateController::class);

        // Documents indexes split by type (controller)
        Route::get('/documents/mou', [\App\Http\Controllers\DocumentController::class, 'index'])->name('documents.mou')->defaults('type', 'MoU')->defaults('slug', 'mou');
        Route::get('/documents/mou/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('documents.MoU.create')->defaults('type', 'MoU')->defaults('slug', 'mou');

        Route::get('/documents/pks', [\App\Http\Controllers\DocumentController::class, 'index'])->name('documents.PKS')->defaults('type', 'PKS')->defaults('slug', 'pks');
        Route::get('/documents/pks', [\App\Http\Controllers\DocumentController::class, 'index'])->name('documents.pks')->defaults('type', 'PKS')->defaults('slug', 'pks');
        Route::get('/documents/pks/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('documents.PKS.create')->defaults('type', 'PKS')->defaults('slug', 'pks');

        Route::get('/documents/berita-acara', [\App\Http\Controllers\DocumentController::class, 'index'])->name('documents.Berita Acara')->defaults('type', 'Berita Acara')->defaults('slug', 'berita-acara');
        Route::get('/documents/berita-acara/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('documents.Berita Acara.create')->defaults('type', 'Berita Acara')->defaults('slug', 'berita-acara');

        // Documents resource actions: store/show/edit/update/destroy
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');
        Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        // Pengajuan Dokumen
        Route::get('/pengajuan-dokumen', [\App\Http\Controllers\DocumentController::class, 'PengajuanDokumen'])->name('documents.pengajuan-dokumen');
        Route::put('/documents/{id}/status', [\App\Http\Controllers\DocumentController::class, 'StatusDokumen'])->name('documents.status');

        // Document Activities
        Route::get('/document-activities', [\App\Http\Controllers\DocumentActivityController::class, 'index'])->name('document-activities');

        // PDF export
        Route::get('/documents/{id}/pdf', [DocumentController::class, 'pdf'])->name('documents.pdf');

        // import
        Route::get('/templatesl', [\App\Http\Controllers\DocumentController::class, 'formImport'])->name('templates.import');
        Route::post('/templates/import-word', [DocumentController::class, 'importWord'])->name('templates.import-word');
    });

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


        Route::get('/documents/{id}/send-email', [\App\Http\Controllers\DocumentController::class, 'sendEmail'])->name('documents.send-email');

    });


    Route::middleware('role:staff')->group(function () {
         Route::get('user/documents/mou', [\App\Http\Controllers\DocumentController::class, 'userDocument'])->name('user.documents.mou')->defaults('type', 'MoU')->defaults('slug', 'mou');
        Route::get('user/documents/mou/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('user.documents.MoU.create')->defaults('type', 'MoU')->defaults('slug', 'mou');

        Route::get('user/documents/pks', [\App\Http\Controllers\DocumentController::class, 'userDocument'])->name('user.documents.PKS')->defaults('type', 'PKS')->defaults('slug', 'pks');
        Route::get('user/documents/pks', [\App\Http\Controllers\DocumentController::class, 'userDocument'])->name('user.documents.pks')->defaults('type', 'PKS')->defaults('slug', 'pks');
        Route::get('user/documents/pks/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('user.documents.PKS.create')->defaults('type', 'PKS')->defaults('slug', 'pks');

        Route::get('user/documents/berita-acara', [\App\Http\Controllers\DocumentController::class, 'userDocument'])->name('user.documents.Berita Acara')->defaults('type', 'Berita Acara')->defaults('slug', 'berita-acara');
        Route::get('user/documents/berita-acara/create', [\App\Http\Controllers\DocumentController::class, 'create'])->name('user.documents.Berita Acara.create')->defaults('type', 'Berita Acara')->defaults('slug', 'berita-acara');

    });

});


route::post('/filter-documents', [DashboardController::class, 'total'])->name('documents.filter');

require __DIR__.'/auth.php';
