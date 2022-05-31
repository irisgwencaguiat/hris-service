<?php

namespace Database\Seeders\References;

use Illuminate\Database\Seeder;
use App\Models\Reference\Page;
use App\Models\UserTypePage;
use DB;
use Log;
use Illuminate\Database\QueryException;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {

            $pages = [
                // office
                [
                    'page_name' => "Office",
                    'page_icon' => "mdi-office-building-outline",
                    'sub_pages' => [
                        [
                            'route_name' => "offices-offices",
                            'page_name' => 'Office',
                            'page_icon' => 'mdi-domain'
                        ],
                        [
                            'route_name' => "offices-departments",
                            'page_name' => 'Department',
                            'page_icon' => 'mdi-home-city-outline'
                        ],
                        [
                            'route_name' => "offices-units",
                            'page_name' => 'Unit',
                            'page_icon' => 'mdi-office-building'
                        ]
                    ]
                ],

                [
                    'page_name' => 'Plantilla',
                    'page_icon' => 'mdi-badge-account',
                    'sub_pages' => [
                        [
                            'route_name' => 'plantilla-position',
                            'page_name' => 'Position',
                            'page_icon' => 'mdi-clipboard-account-outline',
                        ],
                        [
                            'route_name' => 'plantilla-salary-grade',
                            'page_name' => 'Salary Grade',
                            'page_icon' => 'mdi-clipboard-account-outline',
                        ],
                    ]
                ],
        
                [
                    'route_name' => 'employee/list',
                    'page_name' => 'Employee',
                    'page_icon' => 'mdi-folder-account-outline',
                ],

                // Employee Evaluation
                [
                    'page_name' => 'Evaluation',
                    'page_icon' => 'mdi-folder-edit-outline',
                    'sub_pages' =>  [
                        [
                            'route_name' => 'employee-evaluation-form',
                            'page_name' => 'Form',
                            'page_icon' => 'mdi-form-select',
                        ],
                        [
                            'route_name' => 'employee-evaluation-schedule',
                            'page_name' => 'Schedule',
                            'page_icon' => 'mdi-calendar-outline',
                        ],
                    ],
                ],
                // User Management
                [
                    'page_name' => 'User Mgmt',
                    'page_icon' => 'mdi-account-outline',
                    'sub_pages' =>  [
                        [
                            'route_name' => 'user-management-user-accounts',
                            'page_name' => 'User Account',
                            'page_icon' => 'mdi-account-key-outline',
                        ],
                        [
                            'route_name' => 'user-management-user-logs',
                            'page_name' => 'User Log',
                            'page_icon' => 'mdi-account-box-multiple',
                        ],
                    ],
                ],
        
                [
                    'page_name' => 'DTR',
                    'page_icon' => 'mdi-book-clock',
                    'sub_pages' =>  [
                        [
                            'route_name' => 'dtr/kiosks',
                            'page_name' => 'Kiosk',
                            'page_icon' => 'mdi-desktop-classic',
                        ],
                        [
                            'route_name' => 'dtr-dtrs',
                            'page_name' => 'DTR',
                            'page_icon' => 'mdi-clipboard-file',
                        ],
                    ],
                ],

                [
                    'page_name' => 'Page Mgmt',
                    'page_icon' => 'mdi-file-cog-outline',
                    'sub_pages' =>  [
                        [
                            'route_name' => 'page-mgmt/pages',
                            'page_name' => 'Pages',
                            'page_icon' => 'mdi-file-outline',
                        ],
                        [
                            'route_name' => 'page-mgmt/assign-pages',
                            'page_name' => 'Assign Pages',
                            'page_icon' => 'mdi-file-account',
                        ],
                    ],
                ],
            ];

            // add pages
            $userTypeId = 1; // Administrator
            $userClassificationId = 1; // Employee
            $pageTypeId = 1; // Module
            $rootOrder = 1;

            foreach ($pages as $page) {

                if (!array_key_exists('sub_pages', $page)) {
                    $page['sub_pages'] = [];
                }
                
                if (!array_key_exists('route_name', $page)) {
                    $page['route_name'] = '';
                }
                
                // $tempSubPages = $page['sub_pages'];
                // unset($page['sub_pages']);

                // create page
                $rootPage = Page::create([
                    'page_name' => $page['page_name'],
                    'page_type_id' => $pageTypeId,
                    'route_name' => $page['route_name'],
                    'page_icon' => $page['page_icon']
                ]);

                // $page['page_id'] = $rootPage->page_id;
                // $page['user_type_id'] = $userTypeId;
                // $page['user_classification_id'] = $userClassificationId;
                // $page['order_no'] = $rootOrder;

                // unset($page['page_name']);
                // unset($page['page_type_id']);

                // Add user type page
                $rootUserTypePage = UserTypePage::create([
                    'user_type_id' => $userTypeId,
                    'user_classification_id' => $userClassificationId,
                    'page_id' => $rootPage->page_id,
                    'order_no' => $rootOrder
                ]);

                // $page['sub_pages'] = $tempSubPages;

                if (
                    array_key_exists('sub_pages', $page) &&
                    count($page['sub_pages']) > 0
                ) {
                    
                    $rootUserTypePage->has_sub_pages = 1;
                    $rootUserTypePage->save();

                    $subOrder = 1;
                    
                    foreach ($page['sub_pages'] as $subPage) {
                        // $subPage['page_type_id'] = 1;

                        $sp = Page::create([
                            'page_name' => $subPage['page_name'],
                            'page_type_id' => $pageTypeId,
                            'route_name' => $subPage['route_name'],
                            'page_icon' => $subPage['page_icon']
                        ]);

                        // $subPage['user_type_id'] = $userTypeId;
                        // $subPage['user_classification_id'] = $userClassificationId;
                        // $subPage['order_no'] = $subOrder;
                        // $subPage['parent_user_type_page_id'] = $rootUserTypePage->user_type_page_id;

                        // unset($subPage['page_name']);
                        // unset($subPage['page_type_id']);

                        // $subPage['page_id'] = $subPageIns->page_id;
                        UserTypePage::create([
                            'user_type_id' => $userTypeId,
                            'user_classification_id' => $userClassificationId,
                            'page_id' => $sp->page_id,
                            'order_no' => $subOrder,
                            'parent_user_type_page_id' => $rootUserTypePage->user_type_page_id
                        ]);

                        $subOrder++;
                    }
                }

                $rootOrder++;
            }

            DB::commit();

        } catch (QueryException $e) {
            DB::rollback();
            Log::debug($e);
        }
    }
}
