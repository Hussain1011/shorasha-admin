<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\AdvsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/',[\App\Http\Controllers\WebsiteController::class, 'index'])->name('index');

Route::get('/', [WebsiteController::class, 'index'])->name('index');
Route::get('/terms', [WebsiteController::class, 'terms']);
Route::get('/privacy', [WebsiteController::class, 'privacy']);
Route::get('/ar/terms', [WebsiteController::class, 'arTerms']);
Route::get('/ar/privacy', [WebsiteController::class, 'arPrivacy']);

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode .= Artisan::call('config:clear');
    $exitCode .= Artisan::call('route:clear');
    $exitCode .= Artisan::call('view:clear');
    $exitCode .= Artisan::call('event:clear'); // For Laravel 8+

    echo 'All caches have been cleared! '.$exitCode;
});
Route::get('/storage-link', function () {
    try {
        Artisan::call('storage:link');
        return 'Storage has been linked successfully!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});


Route::middleware('guest')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/login', [\App\Http\Controllers\AuthController::class, 'loginView'])->name('loginView');
        Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
        Route::get('/register', [\App\Http\Controllers\AuthController::class, 'registerView'])->name('registerView');
        Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        // ahmed shawky code start
        Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('admin.logout');
        Route::post('user/delete', [UserController::class, 'delete'])->name('user/delete');
        Route::get('user/create', [UserController::class, 'create'])->name('users.create');
        Route::post('user/store', [UserController::class, 'store'])->name('admin.user.create');
        Route::get('/users/list', [UserController::class, 'index'])->name('/users/list');
        Route::get('/users/deleted', [UserController::class, 'deletedUsers'])->name('admin.deleted.users');
        Route::get('/users/restore/{id}', [UserController::class, 'restoreUser'])->name('admin.user.restore');
        Route::post('/users/update', [UserController::class, 'update'])->name('admin.user.update');
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');


        Route::post('consultant/transferred_amount', [UserController::class, 'transferAmount'])->name('admin.doctor.transferred_amount');
        Route::post('consultant/store', [UserController::class, 'consultantStore'])->name('admin.consultant.store');
        Route::get('consultant/create', [UserController::class, 'Consultantcreate'])->name('admin.consultant.create');
        Route::get('consultant/edit/{id}', [UserController::class, 'ConsultantEdit'])->name('admin.consultant.edit');
        Route::post('consultant/update', [UserController::class, 'consultantUpdate'])->name('admin.consultant.update');

        Route::get('users/{id}', [UserController::class, 'show'])->name('user/show');
        Route::get('/consultant/list', [UserController::class, 'consultantIndex'])->name('/consultant/list');
        Route::get('/consultant/new/list', [UserController::class, 'newConsultantIndex'])->name('admin.consultant.index.new');
        Route::get('/consultant/deleted/list', [UserController::class, 'deletedConsultantIndex'])->name('admin.consultant.index.deleted');
        Route::get('/consultant/change-fees/list', [UserController::class, 'ConsultantChangeRequestIndex'])->name('admin.consultant.change.list');
        Route::post('/consultant/change-fees/approve', [UserController::class, 'ConsultantChangeRequestApprove'])->name('/consultant/change-fees/approve');
        Route::post('consultant/change-fees/reject', [UserController::class, 'ConsultantChangeRequestReject'])->name('/consultant/change-fees/reject');


        Route::get('/consultant/action', [UserController::class, 'action'])->name('/consultant/action');
        Route::get('/consultant/medal_assign', [UserController::class, 'medal_assign'])->name('/consultant/medal_assign');
        Route::get('consultant/{id}', [UserController::class, 'consultantShow'])->name('admin.consultant.show');

        Route::get('settings/list', [\App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings.list');
        Route::post('settings/update', [\App\Http\Controllers\SettingController::class, 'update'])->name('admin.setting.update');
        Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('index');
        Route::get('/set-locale',  [\App\Http\Controllers\DashboardController::class, 'setlocale'])->name('setLocale');

        // Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('index');


        Route::get('specialist/list', [\App\Http\Controllers\SpecialistController::class, 'index'])->name('/specialist/list');
        Route::get('specialist/create', [\App\Http\Controllers\SpecialistController::class, 'create'])->name('admin.specialist.create');
        Route::post('specialist/store', [\App\Http\Controllers\SpecialistController::class, 'store'])->name('specialist.store');
        Route::get('specialist/edit/{id}', [\App\Http\Controllers\SpecialistController::class, 'edit'])->name('specialist/edit');
        Route::post('specialist/delete', [\App\Http\Controllers\SpecialistController::class, 'delete'])->name('specialist/delete');
        Route::post('specialist/update', [\App\Http\Controllers\SpecialistController::class, 'update'])->name('specialist/update');


        Route::get('category/list', [\App\Http\Controllers\CategoryController::class, 'index'])->name('/category/list');
        Route::get('category/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('admin.category.create');
        Route::post('category/store', [\App\Http\Controllers\CategoryController::class, 'store'])->name('category.store');
        Route::get('category/edit/{id}', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('category/edit');
        Route::post('category/delete', [\App\Http\Controllers\CategoryController::class, 'delete'])->name('category/delete');
        Route::post('category/update', [\App\Http\Controllers\CategoryController::class, 'update'])->name('category/update');


        Route::get('language/list', [\App\Http\Controllers\LanguageController::class, 'index'])->name('/language/list');
        Route::get('language/create', [\App\Http\Controllers\LanguageController::class, 'create'])->name('admin.language.create');
        Route::post('language/store', [\App\Http\Controllers\LanguageController::class, 'store'])->name('language.store');
        Route::get('language/edit/{id}', [\App\Http\Controllers\LanguageController::class, 'edit'])->name('language/edit');
        Route::post('language/delete', [\App\Http\Controllers\LanguageController::class, 'delete'])->name('language/delete');
        Route::post('language/update', [\App\Http\Controllers\LanguageController::class, 'update'])->name('language/update');

        Route::get('accent/list', [\App\Http\Controllers\AccentController::class, 'index'])->name('/accent/list');
        Route::get('accent/create', [\App\Http\Controllers\AccentController::class, 'create'])->name('admin.accent.create');
        Route::post('accent/store', [\App\Http\Controllers\AccentController::class, 'store'])->name('accent.store');
        Route::get('accent/edit/{id}', [\App\Http\Controllers\AccentController::class, 'edit'])->name('accent/edit');
        Route::post('accent/delete', [\App\Http\Controllers\AccentController::class, 'delete'])->name('accent/delete');
        Route::post('accent/update', [\App\Http\Controllers\AccentController::class, 'update'])->name('accent/update');

        Route::get('revenue/{from?}/{to?}', [\App\Http\Controllers\RevenueController::class, 'index'])->name('revenue');


        Route::get('/appointments/list', [\App\Http\Controllers\AppointmentsBookingController::class, 'index'])->name('/appointments/list');
        Route::post('/appointments/update', [\App\Http\Controllers\AppointmentsBookingController::class, 'update'])->name('admin.appointments.update');
        Route::get('/appointments/{id}', [\App\Http\Controllers\AppointmentsBookingController::class, 'show'])->name('admin.appointments.show');
        Route::get('/appointments/edit/{id}', [\App\Http\Controllers\AppointmentsBookingController::class, 'edit'])->name('admin.appointments.edit');
        Route::post('appointments-booking/delete', [\App\Http\Controllers\AppointmentsBookingController::class, 'delete'])->name('appointments-booking/delete');



        Route::get('/collect-request/list', [\App\Http\Controllers\DoctorCollectRequestController::class, 'index'])->name('admin.collect.list');
        Route::post('/collect/reject', [\App\Http\Controllers\DoctorCollectRequestController::class, 'reject'])->name('/collect/reject');
        Route::post('/collect/approve', [\App\Http\Controllers\DoctorCollectRequestController::class, 'approve'])->name('/collect/approve');


        Route::get('/collect-user-request/list', [\App\Http\Controllers\UserCollectRequestController::class, 'index'])->name('admin.collect-user.list');
        Route::post('/collect-user/reject', [\App\Http\Controllers\UserCollectRequestController::class, 'reject'])->name('/collect-user/reject');
        Route::post('/collect-user/approve', [\App\Http\Controllers\UserCollectRequestController::class, 'approve'])->name('/collect-user/approve');

        // Route::get('/main-banners/list', [\App\Http\Controllers\BannersController::class, 'main_banners'])->name('admin.main-banners.list');

        Route::post('banner/delete', [BannersController::class, 'delete'])->name('banner/delete');
        Route::get('banner/create', [BannersController::class, 'create'])->name('banners.create');
        Route::post('banner/store', [BannersController::class, 'store'])->name('admin.banner.create');
        Route::get('/banners/list', [BannersController::class, 'index'])->name('banners.list');
        Route::get('/banners/deleted', [BannersController::class, 'deletedbanners'])->name('admin.deleted.banners');
        Route::get('/banners/restore/{id}', [BannersController::class, 'restorebanner'])->name('admin.banner.restore');
        Route::post('/banners/update', [BannersController::class, 'update'])->name('admin.banner.update');
        Route::get('/banners/edit/{id}', [BannersController::class, 'edit'])->name('admin.banner.edit');

        Route::post('adv/delete', [AdvsController::class, 'delete'])->name('adv/delete');
        Route::get('adv/create', [AdvsController::class, 'create'])->name('advs.create');
        Route::post('adv/store', [AdvsController::class, 'store'])->name('admin.adv.create');
        Route::get('/advs/list', [AdvsController::class, 'index'])->name('advs.list');
        Route::get('/advs/deleted', [AdvsController::class, 'deletedadvs'])->name('admin.deleted.advs');
        Route::get('/advs/restore/{id}', [AdvsController::class, 'restoreadv'])->name('admin.adv.restore');
        Route::post('/advs/update', [AdvsController::class, 'update'])->name('admin.adv.update');
        Route::get('/advs/edit/{id}', [AdvsController::class, 'edit'])->name('admin.adv.edit');
    });
});




        // ahmed shawky code end
/*
    Route::get('/elements/avatar', [PagesController::class, 'elementsAvatar'])->name('elements/avatar');
    Route::get('/elements/alert', [PagesController::class, 'elementsAlert'])->name('elements/alert');
    Route::get('/elements/button', [PagesController::class, 'elementsButton'])->name('elements/button');
    Route::get('/elements/button-group', [PagesController::class, 'elementsButtonGroup'])->name('elements/button-group');
    Route::get('/elements/badge', [PagesController::class, 'elementsBadge'])->name('elements/badge');
    Route::get('/elements/breadcrumb', [PagesController::class, 'elementsBreadcrumb'])->name('elements/breadcrumb');
    Route::get('/elements/card', [PagesController::class, 'elementsCard'])->name('elements/card');
    Route::get('/elements/divider', [PagesController::class, 'elementsDivider'])->name('elements/divider');
    Route::get('/elements/mask', [PagesController::class, 'elementsMask'])->name('elements/mask');
    Route::get('/elements/progress', [PagesController::class, 'elementsProgress'])->name('elements/progress');
    Route::get('/elements/skeleton', [PagesController::class, 'elementsSkeleton'])->name('elements/skeleton');
    Route::get('/elements/spinner', [PagesController::class, 'elementsSpinner'])->name('elements/spinner');
    Route::get('/elements/tag', [PagesController::class, 'elementsTag'])->name('elements/tag');
    Route::get('/elements/tooltip', [PagesController::class, 'elementsTooltip'])->name('elements/tooltip');
    Route::get('/elements/typography', [PagesController::class, 'elementsTypography'])->name('elements/typography');

    Route::get('/components/accordion', [PagesController::class, 'componentsAccordion'])->name('components/accordion');
    Route::get('/components/collapse', [PagesController::class, 'componentsCollapse'])->name('components/collapse');
    Route::get('/components/tab', [PagesController::class, 'componentsTab'])->name('components/tab');
    Route::get('/components/dropdown', [PagesController::class, 'componentsDropdown'])->name('components/dropdown');
    Route::get('/components/popover', [PagesController::class, 'componentsPopover'])->name('components/popover');
    Route::get('/components/modal', [PagesController::class, 'componentsModal'])->name('components/modal');
    Route::get('/components/drawer', [PagesController::class, 'componentsDrawer'])->name('components/drawer');
    Route::get('/components/steps', [PagesController::class, 'componentsSteps'])->name('components/steps');
    Route::get('/components/timeline', [PagesController::class, 'componentsTimeline'])->name('components/timeline');
    Route::get('/components/pagination', [PagesController::class, 'componentsPagination'])->name('components/pagination');
    Route::get('/components/menu-list', [PagesController::class, 'componentsMenuList'])->name('components/menu-list');
    Route::get('/components/treeview', [PagesController::class, 'componentsTreeview'])->name('components/treeview');
    Route::get('/components/table', [PagesController::class, 'componentsTable'])->name('components/table');
    Route::get('/components/table-advanced', [PagesController::class, 'componentsTableAdvanced'])->name('components/table-advanced');
    Route::get('/components/table-gridjs', [PagesController::class, 'componentsTableGridjs'])->name('components/gridjs');
    Route::get('/components/apexchart', [PagesController::class, 'componentsApexchart'])->name('components/apexchart');
    Route::get('/components/carousel', [PagesController::class, 'componentsCarousel'])->name('components/carousel');
    Route::get('/components/notification', [PagesController::class, 'componentsNotification'])->name('components/notification');
    Route::get('/components/extension-clipboard', [PagesController::class, 'componentsExtensionClipboard'])->name('components/extension-clipboard');
    Route::get('/components/extension-persist', [PagesController::class, 'componentsExtensionPersist'])->name('components/extension-persist');
    Route::get('/components/extension-monochrome', [PagesController::class, 'componentsExtensionMonochrome'])->name('components/extension-monochrome');

    Route::get('/forms/layout-v1', [PagesController::class, 'formsLayoutV1'])->name('forms/layout-v1');
    Route::get('/forms/layout-v2', [PagesController::class, 'formsLayoutV2'])->name('forms/layout-v2');
    Route::get('/forms/layout-v3', [PagesController::class, 'formsLayoutV3'])->name('forms/layout-v3');
    Route::get('/forms/layout-v4', [PagesController::class, 'formsLayoutV4'])->name('forms/layout-v4');
    Route::get('/forms/layout-v5', [PagesController::class, 'formsLayoutV5'])->name('forms/layout-v5');
    Route::get('/forms/input-text', [PagesController::class, 'formsInputText'])->name('forms/input-text');
    Route::get('/forms/input-group', [PagesController::class, 'formsInputGroup'])->name('forms/input-group');
    Route::get('/forms/input-mask', [PagesController::class, 'formsInputMask'])->name('forms/input-mask');
    Route::get('/forms/checkbox', [PagesController::class, 'formsCheckbox'])->name('forms/checkbox');
    Route::get('/forms/radio', [PagesController::class, 'formsRadio'])->name('forms/radio');
    Route::get('/forms/switch', [PagesController::class, 'formsSwitch'])->name('forms/switch');
    Route::get('/forms/select', [PagesController::class, 'formsSelect'])->name('forms/select');
    Route::get('/forms/tom-select', [PagesController::class, 'formsTomSelect'])->name('forms/tom-select');
    Route::get('/forms/textarea', [PagesController::class, 'formsTextarea'])->name('forms/textarea');
    Route::get('/forms/range', [PagesController::class, 'formsRange'])->name('forms/range');
    Route::get('/forms/datepicker', [PagesController::class, 'formsDatepicker'])->name('forms/datepicker');
    Route::get('/forms/timepicker', [PagesController::class, 'formsTimepicker'])->name('forms/timepicker');
    Route::get('/forms/datetimepicker', [PagesController::class, 'formsDatetimepicker'])->name('forms/datetimepicker');
    Route::get('/forms/text-editor', [PagesController::class, 'formsTextEditor'])->name('forms/text-editor');
    Route::get('/forms/upload', [PagesController::class, 'formsUpload'])->name('forms/upload');
    Route::get('/forms/validation', [PagesController::class, 'formsValidation'])->name('forms/validation');

    Route::get('/layouts/onboarding-1', [PagesController::class, 'layoutsOnboarding1'])->name('layouts/onboarding-1');
    Route::get('/layouts/onboarding-2', [PagesController::class, 'layoutsOnboarding2'])->name('layouts/onboarding-2');
    Route::get('/layouts/user-card-1', [PagesController::class, 'layoutsUserCard1'])->name('layouts/user-card-1');
    Route::get('/layouts/user-card-2', [PagesController::class, 'layoutsUserCard2'])->name('layouts/user-card-2');
    Route::get('/layouts/user-card-3', [PagesController::class, 'layoutsUserCard3'])->name('layouts/user-card-3');
    Route::get('/layouts/user-card-4', [PagesController::class, 'layoutsUserCard4'])->name('layouts/user-card-4');
    Route::get('/layouts/user-card-5', [PagesController::class, 'layoutsUserCard5'])->name('layouts/user-card-5');
    Route::get('/layouts/user-card-6', [PagesController::class, 'layoutsUserCard6'])->name('layouts/user-card-6');
    Route::get('/layouts/user-card-7', [PagesController::class, 'layoutsUserCard7'])->name('layouts/user-card-7');
    Route::get('/layouts/blog-card-1', [PagesController::class, 'layoutsBlogCard1'])->name('layouts/blog-card-1');
    Route::get('/layouts/blog-card-2', [PagesController::class, 'layoutsBlogCard2'])->name('layouts/blog-card-2');
    Route::get('/layouts/blog-card-3', [PagesController::class, 'layoutsBlogCard3'])->name('layouts/blog-card-3');
    Route::get('/layouts/blog-card-4', [PagesController::class, 'layoutsBlogCard4'])->name('layouts/blog-card-4');
    Route::get('/layouts/blog-card-5', [PagesController::class, 'layoutsBlogCard5'])->name('layouts/blog-card-5');
    Route::get('/layouts/blog-card-6', [PagesController::class, 'layoutsBlogCard6'])->name('layouts/blog-card-6');
    Route::get('/layouts/blog-card-7', [PagesController::class, 'layoutsBlogCard7'])->name('layouts/blog-card-7');
    Route::get('/layouts/blog-card-8', [PagesController::class, 'layoutsBlogCard8'])->name('layouts/blog-card-8');
    Route::get('/layouts/blog-details', [PagesController::class, 'layoutsBlogDetails'])->name('layouts/blog-details');
    Route::get('/layouts/help-1', [PagesController::class, 'layoutsHelp1'])->name('layouts/help-1');
    Route::get('/layouts/help-2', [PagesController::class, 'layoutsHelp2'])->name('layouts/help-2');
    Route::get('/layouts/help-3', [PagesController::class, 'layoutsHelp3'])->name('layouts/help-3');
    Route::get('/layouts/price-list-1', [PagesController::class, 'layoutsPriceList1'])->name('layouts/price-list-1');
    Route::get('/layouts/price-list-2', [PagesController::class, 'layoutsPriceList2'])->name('layouts/price-list-2');
    Route::get('/layouts/price-list-3', [PagesController::class, 'layoutsPriceList3'])->name('layouts/price-list-3');
    Route::get('/layouts/invoice-1', [PagesController::class, 'layoutsInvoice1'])->name('layouts/invoice-1');
    Route::get('/layouts/invoice-2', [PagesController::class, 'layoutsInvoice2'])->name('layouts/invoice-2');
    Route::get('/layouts/sign-in-1', [PagesController::class, 'layoutsSignIn1'])->name('layouts/sign-in-1');
    Route::get('/layouts/sign-in-2', [PagesController::class, 'layoutsSignIn2'])->name('layouts/sign-in-2');
    Route::get('/layouts/sign-up-1', [PagesController::class, 'layoutsSignUp1'])->name('layouts/sign-up-1');
    Route::get('/layouts/sign-up-2', [PagesController::class, 'layoutsSignUp2'])->name('layouts/sign-up-2');
    Route::get('/layouts/error-404-1', [PagesController::class, 'layoutsError4041'])->name('layouts/error-404-1');
    Route::get('/layouts/error-404-2', [PagesController::class, 'layoutsError4042'])->name('layouts/error-404-2');
    Route::get('/layouts/error-404-3', [PagesController::class, 'layoutsError4043'])->name('layouts/error-404-3');
    Route::get('/layouts/error-404-4', [PagesController::class, 'layoutsError4044'])->name('layouts/error-404-4');
    Route::get('/layouts/error-401', [PagesController::class, 'layoutsError401'])->name('layouts/error-401');
    Route::get('/layouts/error-429', [PagesController::class, 'layoutsError429'])->name('layouts/error-429');
    Route::get('/layouts/error-500', [PagesController::class, 'layoutsError500'])->name('layouts/error-500');
    Route::get('/layouts/starter-blurred-header', [PagesController::class, 'layoutsStarterBlurredHeader'])->name('layouts/starter-blurred-header');
    Route::get('/layouts/starter-unblurred-header', [PagesController::class, 'layoutsStarterUnblurredHeader'])->name('layouts/starter-unblurred-header');
    Route::get('/layouts/starter-centered-link', [PagesController::class, 'layoutsStarterCenteredLink'])->name('layouts/starter-centered-link');
    Route::get('/layouts/starter-minimal-sidebar', [PagesController::class, 'layoutsStarterMinimalSidebar'])->name('layouts/starter-minimal-sidebar');
    Route::get('/layouts/starter-sideblock', [PagesController::class, 'layoutsStarterSideblock'])->name('layouts/starter-sideblock');

    Route::get('/apps/chat', [PagesController::class, 'appsChat'])->name('apps/chat');
    Route::get('/apps/filemanager', [PagesController::class, 'appsFilemanager'])->name('apps/filemanager');
    Route::get('/apps/kanban', [PagesController::class, 'appsKanban'])->name('apps/kanban');
    Route::get('/apps/list', [PagesController::class, 'appsList'])->name('apps/list');
    Route::get('/apps/mail', [PagesController::class, 'appsMail'])->name('apps/mail');
    Route::get('/apps/nft-1', [PagesController::class, 'appsNft1'])->name('apps/nft1');
    Route::get('/apps/nft-2', [PagesController::class, 'appsNft2'])->name('apps/nft2');
    Route::get('/apps/pos', [PagesController::class, 'appsPos'])->name('apps/pos');
    Route::get('/apps/todo', [PagesController::class, 'appsTodo'])->name('apps/todo');
    Route::get('/apps/travel', [PagesController::class, 'appsTravel'])->name('apps/travel');

    Route::get('/dashboards/crm-analytics', [PagesController::class, 'dashboardsCrmAnalytics'])->name('dashboards/crm-analytics');
    Route::get('/dashboards/orders', [PagesController::class, 'dashboardsOrders'])->name('dashboards/orders');
    Route::get('/dashboards/crypto-1', [PagesController::class, 'dashboardsCrypto1'])->name('dashboards/crypto-1');
    Route::get('/dashboards/crypto-2', [PagesController::class, 'dashboardsCrypto2'])->name('dashboards/crypto-2');
    Route::get('/dashboards/banking-1', [PagesController::class, 'dashboardsBanking1'])->name('dashboards/banking-1');
    Route::get('/dashboards/banking-2', [PagesController::class, 'dashboardsBanking2'])->name('dashboards/banking-2');
    Route::get('/dashboards/personal', [PagesController::class, 'dashboardsPersonal'])->name('dashboards/personal');
    Route::get('/dashboards/cms-analytics', [PagesController::class, 'dashboardsCmsAnalytics'])->name('dashboards/cms-analytics');
    Route::get('/dashboards/influencer', [PagesController::class, 'dashboardsInfluencer'])->name('dashboards/influencer');
    Route::get('/dashboards/travel', [PagesController::class, 'dashboardsTravel'])->name('dashboards/travel');
    Route::get('/dashboards/teacher', [PagesController::class, 'dashboardsTeacher'])->name('dashboards/teacher');
    Route::get('/dashboards/education', [PagesController::class, 'dashboardsEducation'])->name('dashboards/education');
    Route::get('/dashboards/authors', [PagesController::class, 'dashboardsAuthors'])->name('dashboards/authors');
    Route::get('/dashboards/doctor', [PagesController::class, 'dashboardsDoctor'])->name('dashboards/doctor');
    Route::get('/dashboards/employees', [PagesController::class, 'dashboardsEmployees'])->name('dashboards/employees');
    Route::get('/dashboards/workspaces', [PagesController::class, 'dashboardsWorkspaces'])->name('dashboards/workspaces');
    Route::get('/dashboards/meetings', [PagesController::class, 'dashboardsMeetings'])->name('dashboards/meetings');
    Route::get('/dashboards/project-boards', [PagesController::class, 'dashboardsProjectBoards'])->name('dashboards/project-boards');
    Route::get('/dashboards/widget-ui', [PagesController::class, 'dashboardsWidgetUi'])->name('dashboards/widget-ui');
    Route::get('/dashboards/widget-contacts', [PagesController::class, 'dashboardsWidgetContacts'])->name('dashboards/widget-contacts');
    */

