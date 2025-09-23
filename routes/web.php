<?php

use App\Http\Controllers\Admin\QrExportController;
use App\Http\Controllers\Dealer\ExportController;
use App\Livewire\Admin\AttachProducts;
use App\Livewire\Admin\CampaignManager;
use App\Livewire\Admin\CampaignPerformanceReport;
use App\Livewire\Admin\ClaimsReport;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\AgentManager;
use App\Livewire\Admin\AgentPerformanceReport;
use App\Livewire\Admin\LocationReport;
use App\Livewire\Admin\PrizeInventoryReport as AdminPrizeInventory;
use App\Livewire\Admin\PrizeManager;
use App\Livewire\Admin\ProductManager;
use App\Livewire\Admin\ProductSalesReport;
use App\Livewire\Admin\QrBatchManager;
use App\Livewire\Admin\WinnersMap;
use App\Livewire\Agent\Dashboard as AgentDashboard;
use App\Livewire\Agent\DetailedPrizeReport;
use App\Livewire\Agent\DailyReport;
use App\Livewire\Agent\PrizeInventory as AgentPrizeInventory;
use App\Livewire\Agent\RedemptionList;
use App\Livewire\Profile\UpdateProfile;
use App\Livewire\Profile\UpdatePassword;
use App\Livewire\SpinPage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Publicly accessible routes
Route::get('/', function () {
    return view('welcome');
});
Route::get('/spin/{token}', SpinPage::class)
    ->name('spin');
    // ->middleware('throttle:spin_attempts,10,5');


// --- Routes for ANY authenticated user ---
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', UpdateProfile::class)->name('profile.edit');
    Route::get('/profile/password', UpdatePassword::class)->name('profile.password');
});


// --- Admin Portal Routes ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Core
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/products', ProductManager::class)->name('products.index');
    Route::get('/campaigns', CampaignManager::class)->name('campaigns.index');
    Route::get('/campaigns/{campaign}/attach-products', AttachProducts::class)->name('campaigns.products');
    Route::get('/campaigns/{campaign}/prizes', PrizeManager::class)->name('prizes.index');
    Route::get('/agents', AgentManager::class)->name('agents.index');
    Route::get('/qr-batches', QrBatchManager::class)->name('qr-batches.index');

    // Reports
    Route::get('/reports/claims', ClaimsReport::class)->name('reports.claims');
    Route::get('/reports/winners-map', WinnersMap::class)->name('reports.winners-map');
    Route::get('/reports/campaign-performance', CampaignPerformanceReport::class)->name('reports.campaign-performance');
    Route::get('/reports/prize-inventory', AdminPrizeInventory::class)->name('reports.prize-inventory');
    Route::get('/reports/agent-performance', AgentPerformanceReport::class)->name('reports.agent-performance');
    Route::get('/reports/location-performance', LocationReport::class)->name('reports.location-performance');
    Route::get('/reports/product-sales', ProductSalesReport::class)->name('reports.product-sales');
    Route::get('/reports/time-analytics', \App\Livewire\Admin\TimeAnalyticsReport::class)->name('reports.time-analytics');

    // Exports
    Route::get('/qr-batches/{batch}/export/csv', [QrExportController::class, 'exportCsv'])->name('qr.export.csv');
    Route::get('/qr-batches/{batch}/export/pdf', [QrExportController::class, 'exportPdf'])->name('qr.export.pdf');
    Route::get('/qr-batches/{batch}/download/pdf', [QrExportController::class, 'downloadPdf'])->name('qr.download.pdf');
    Route::get('/qr-batches/{batch}/export/zip', [QrExportController::class, 'exportSvgZip'])->name('qr.export.zip');
});


// --- Dealer Portal Routes ---
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', AgentDashboard::class)->name('dashboard');
    Route::get('/redemptions', RedemptionList::class)->name('redemptions.index');

    // Reports
    Route::get('/reports/daily', DailyReport::class)->name('reports.daily');
    Route::get('/reports/prize-inventory', AgentPrizeInventory::class)->name('reports.prize-inventory');
    Route::get('/reports/detailed-prize-report', DetailedPrizeReport::class)->name('reports.detailed-prize');

    // Exports
    Route::get('/redemptions/export', [ExportController::class, 'exportRedemptions'])->name('redemptions.export');
});


// --- Post-Login Dashboard Redirect ---
Route::get('/dashboard', function () {
    if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::user()->isAgent()) {
        return redirect()->route('agent.dashboard');
    }
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__ . '/auth.php';
