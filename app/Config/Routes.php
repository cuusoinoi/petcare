<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes - Redirect root to customer page
$routes->get('/', 'Home::index');

// Customer public routes (frontend)
$routes->group('customer', ['namespace' => 'App\Controllers'], function($routes) {
    // Public pages
    $routes->get('/', 'CustomerController::index');
    $routes->get('services', 'CustomerController::services');
    $routes->get('contact', 'CustomerController::contact');
    
    // Authentication
    $routes->get('login', 'CustomerAuthController::login');
    $routes->post('login', 'CustomerAuthController::login');
    $routes->get('register', 'CustomerAuthController::register');
    $routes->post('register', 'CustomerAuthController::register');
    $routes->get('logout', 'CustomerAuthController::logout');
    
    // Protected customer routes
    $routes->group('dashboard', function($routes) {
        $routes->get('/', 'CustomerDashboardController::index');
        $routes->get('profile', 'CustomerDashboardController::profile');
        $routes->post('profile', 'CustomerDashboardController::profile');
        $routes->get('pets', 'CustomerDashboardController::pets');
        $routes->get('pets/add', 'CustomerDashboardController::addPet');
        $routes->post('pets/add', 'CustomerDashboardController::addPet');
        $routes->get('medical-records', 'CustomerDashboardController::medicalRecords');
        $routes->get('prescriptions', 'CustomerDashboardController::prescriptions');
        $routes->get('vaccinations', 'CustomerDashboardController::vaccinations');
        $routes->get('invoices', 'CustomerDashboardController::invoices');
        $routes->get('invoices/view/(:num)', 'CustomerDashboardController::viewInvoice/$1');
    });
    
    // Booking
    $routes->group('booking', function($routes) {
        $routes->get('/', 'BookingController::index');
        $routes->post('create', 'BookingController::create');
        $routes->get('my-appointments', 'BookingController::myAppointments');
    });
});

// Admin authentication routes
$routes->group('admin', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'AuthController::index');
    $routes->post('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout');
});

// Admin protected routes
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'DashboardController::index');
    
    // Customers
    $routes->group('customers', function($routes) {
        $routes->get('/', 'CustomerController::index');
        $routes->get('add', 'CustomerController::add');
        $routes->post('store', 'CustomerController::store');
        $routes->get('edit/(:num)', 'CustomerController::edit/$1');
        $routes->post('update/(:num)', 'CustomerController::update/$1');
        $routes->get('delete/(:num)', 'CustomerController::delete/$1');
    });
    
    // Pets
    $routes->group('pets', function($routes) {
        $routes->get('/', 'PetController::index');
        $routes->get('add', 'PetController::add');
        $routes->post('store', 'PetController::store');
        $routes->get('edit/(:num)', 'PetController::edit/$1');
        $routes->post('update/(:num)', 'PetController::update/$1');
        $routes->get('delete/(:num)', 'PetController::delete/$1');
    });
    
    // Doctors
    $routes->group('doctors', function($routes) {
        $routes->get('/', 'DoctorController::index');
        $routes->get('add', 'DoctorController::add');
        $routes->post('store', 'DoctorController::store');
        $routes->get('edit/(:num)', 'DoctorController::edit/$1');
        $routes->post('update/(:num)', 'DoctorController::update/$1');
        $routes->get('delete/(:num)', 'DoctorController::delete/$1');
    });
    
    // Medical Records
    $routes->group('medical-records', function($routes) {
        $routes->get('/', 'MedicalRecordController::index');
        $routes->get('add', 'MedicalRecordController::add');
        $routes->post('store', 'MedicalRecordController::store');
        $routes->get('edit/(:num)', 'MedicalRecordController::edit/$1');
        $routes->post('update/(:num)', 'MedicalRecordController::update/$1');
        $routes->get('delete/(:num)', 'MedicalRecordController::delete/$1');
    });
    
    // Pet Enclosures
    $routes->group('pet-enclosures', function($routes) {
        $routes->get('/', 'PetEnclosureController::index');
        $routes->get('add', 'PetEnclosureController::add');
        $routes->post('store', 'PetEnclosureController::store');
        $routes->get('edit/(:num)', 'PetEnclosureController::edit/$1');
        $routes->post('update/(:num)', 'PetEnclosureController::update/$1');
        $routes->get('delete/(:num)', 'PetEnclosureController::delete/$1');
        $routes->get('checkout/(:num)', 'PetEnclosureController::checkout/$1');
        $routes->post('checkout/(:num)', 'PetEnclosureController::processCheckout/$1');
    });
    
    // Invoices
    $routes->group('invoices', function($routes) {
        $routes->get('/', 'InvoiceController::index');
        $routes->get('add', 'InvoiceController::add');
        $routes->post('store', 'InvoiceController::store');
        $routes->get('edit/(:num)', 'InvoiceController::edit/$1');
        $routes->post('update/(:num)', 'InvoiceController::update/$1');
        $routes->get('delete/(:num)', 'InvoiceController::delete/$1');
    });
    
    // Appointments
    $routes->group('appointments', function($routes) {
        $routes->get('/', 'AppointmentController::index');
        $routes->get('view/(:num)', 'AppointmentController::view/$1');
        $routes->post('update/(:num)', 'AppointmentController::update/$1');
        $routes->post('update-status/(:num)', 'AppointmentController::updateStatus/$1');
        $routes->get('delete/(:num)', 'AppointmentController::delete/$1');
    });
    
    // Service Types
    $routes->group('service-types', function($routes) {
        $routes->get('/', 'ServiceTypeController::index');
        $routes->get('add', 'ServiceTypeController::add');
        $routes->post('store', 'ServiceTypeController::store');
        $routes->get('edit/(:num)', 'ServiceTypeController::edit/$1');
        $routes->post('update/(:num)', 'ServiceTypeController::update/$1');
        $routes->get('delete/(:num)', 'ServiceTypeController::delete/$1');
    });
    
    // Users
    $routes->group('users', function($routes) {
        $routes->get('/', 'UserController::index');
        $routes->get('add', 'UserController::add');
        $routes->post('store', 'UserController::store');
        $routes->get('edit/(:num)', 'UserController::edit/$1');
        $routes->post('update/(:num)', 'UserController::update/$1');
        $routes->get('delete/(:num)', 'UserController::delete/$1');
        $routes->get('change-password', 'UserController::changePassword');
        $routes->post('update-password', 'UserController::updatePassword');
    });
    
    // Medicines
    $routes->group('medicines', function($routes) {
        $routes->get('/', 'MedicineController::index');
        $routes->get('add', 'MedicineController::add');
        $routes->post('store', 'MedicineController::store');
        $routes->get('edit/(:num)', 'MedicineController::edit/$1');
        $routes->post('update/(:num)', 'MedicineController::update/$1');
        $routes->get('delete/(:num)', 'MedicineController::delete/$1');
    });
    
    // Vaccines
    $routes->group('vaccines', function($routes) {
        $routes->get('/', 'VaccineController::index');
        $routes->get('add', 'VaccineController::add');
        $routes->post('store', 'VaccineController::store');
        $routes->get('edit/(:num)', 'VaccineController::edit/$1');
        $routes->post('update/(:num)', 'VaccineController::update/$1');
        $routes->get('delete/(:num)', 'VaccineController::delete/$1');
    });
    
    // Pet Vaccinations
    $routes->group('pet-vaccinations', function($routes) {
        $routes->get('/', 'PetVaccinationController::index');
        $routes->get('add', 'PetVaccinationController::add');
        $routes->post('store', 'PetVaccinationController::store');
        $routes->get('edit/(:num)', 'PetVaccinationController::edit/$1');
        $routes->post('update/(:num)', 'PetVaccinationController::update/$1');
        $routes->get('delete/(:num)', 'PetVaccinationController::delete/$1');
    });
    
    // Treatment Courses
    $routes->group('treatment-courses', function($routes) {
        $routes->get('/', 'TreatmentCourseController::index');
        $routes->get('add', 'TreatmentCourseController::add');
        $routes->post('store', 'TreatmentCourseController::store');
        $routes->get('edit/(:num)', 'TreatmentCourseController::edit/$1');
        $routes->post('update/(:num)', 'TreatmentCourseController::update/$1');
        $routes->get('delete/(:num)', 'TreatmentCourseController::delete/$1');
        $routes->get('complete/(:num)', 'TreatmentCourseController::complete/$1');
        
        // Treatment Sessions
        $routes->get('(:num)/sessions', 'TreatmentCourseController::sessions/$1');
        $routes->get('(:num)/sessions/add', 'TreatmentCourseController::addSession/$1');
        $routes->post('(:num)/sessions/store', 'TreatmentCourseController::storeSession/$1');
        $routes->get('(:num)/sessions/edit/(:num)', 'TreatmentCourseController::editSession/$1/$2');
        $routes->post('(:num)/sessions/update/(:num)', 'TreatmentCourseController::updateSession/$1/$2');
        $routes->get('(:num)/sessions/delete/(:num)', 'TreatmentCourseController::deleteSession/$1/$2');
        
        // Diagnosis
        $routes->get('(:num)/sessions/(:num)/diagnosis', 'TreatmentCourseController::diagnosis/$1/$2');
        $routes->post('(:num)/sessions/(:num)/diagnosis/save', 'TreatmentCourseController::saveDiagnosis/$1/$2');
        
        // Prescription
        $routes->get('(:num)/sessions/(:num)/prescription', 'TreatmentCourseController::prescription/$1/$2');
        $routes->post('(:num)/sessions/(:num)/prescription/add', 'TreatmentCourseController::addPrescription/$1/$2');
        $routes->get('(:num)/sessions/(:num)/prescription/delete/(:num)', 'TreatmentCourseController::deletePrescription/$1/$2/$3');
    });
    
    // General Settings
    $routes->get('settings', 'GeneralSettingController::index');
    $routes->post('settings/update', 'GeneralSettingController::update');
    
    // Print routes
    $routes->group('print', function($routes) {
        $routes->get('invoice/(:num)', 'PrintController::invoice/$1');
        $routes->get('medical-record/(:num)', 'PrintController::medicalRecord/$1');
        $routes->get('treatment-session/(:num)/(:num)', 'PrintController::treatmentSession/$1/$2');
        $routes->get('pet-enclosure/(:num)', 'PrintController::petEnclosure/$1');
    });
    
    // Printing Template (chọn hóa đơn và xem trước)
    $routes->group('printing-template', function($routes) {
        $routes->get('/', 'PrintingTemplateController::index');
        $routes->get('load-commit/(:num)', 'PrintingTemplateController::loadCommit/$1');
        $routes->get('load-invoice/(:num)', 'PrintingTemplateController::loadInvoice/$1');
    });
});
