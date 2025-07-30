<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QrExportController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Dealer\Dashboard as DealerDashboard;
use App\Livewire\SpinPage;
use App\Livewire\Admin\ClaimsReport;
use App\Livewire\Admin\QrBatchManager;
use App\Livewire\Admin\ProductManager;
use App\Livewire\Admin\AttachProducts;
use App\Livewire\Admin\WinnersMap;
use App\Livewire\Admin\CampaignManager;
use App\Livewire\Admin\PrizeManager;
use App\Livewire\Admin\DealerManager;
use App\Livewire\Admin\RetailShopManager;
use App\Livewire\Dealer\RedemptionList;
use App\Http\Controllers\Dealer\ExportController;
use App\Livewire\Profile\UpdatePassword;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/spin/{token}', SpinPage::class)->name('spin');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/qr-manager', QrBatchManager::class)->name('qr.manager');
    Route::get('/claims-report', ClaimsReport::class)->name('claims.report');
    Route::get('/winners-map', WinnersMap::class)->name('winners.map');
    Route::get('/products', ProductManager::class)->name('products.index');
    Route::get('/campaigns/{campaign}/attach-products', AttachProducts::class)->name('campaigns.products');
    Route::get('/campaigns', CampaignManager::class)->name('campaigns.index');
    Route::get('/campaigns/{campaign}/prizes', PrizeManager::class)->name('prizes.index');
    Route::get('/dealers', DealerManager::class)->name('dealers.index');
    Route::get('/dealers/{dealer}/shops', RetailShopManager::class)->name('shops.index');

    Route::get('/qr-manager/{batch}/export/csv', [QrExportController::class, 'exportCsv'])->name('qr.export.csv');
    Route::get('/qr-manager/{batch}/export/pdf', [QrExportController::class, 'exportPdf'])->name('qr.export.pdf');
    Route::get('/qr-manager/{batch}/download/pdf', [QrExportController::class, 'downloadPdf'])->name('qr.download.pdf');
    Route::get('/qr-manager/{batch}/export/zip', [QrExportController::class, 'exportSvgZip'])->name('qr.export.zip');

    Route::get('/profile/password', UpdatePassword::class)->name('profile.password');
});

Route::middleware(['auth', 'role:dealer'])->prefix('dealer')->name('dealer.')->group(function () {
    Route::get('/dashboard', DealerDashboard::class)->name('dashboard');
    Route::get('/redemptions', RedemptionList::class)->name('redemptions.index');
    Route::get('/redemptions/export', [ExportController::class, 'exportRedemptions'])->name('redemptions.export');
});

require __DIR__ . '/auth.php';
