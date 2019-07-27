<?php

return [

    'admin_number'       => env('SEEDER_ADMIN_NUMBER', 2),
    'admin_default_user' => env('SEEDER_ADMIN_DEFAULT_USER', ''),

    'user_number'                => env('SEEDER_USER_NUMBER', 100),
    'default_user'               => env('SEEDER_DEFAULT_USER', ''),
    'seminar_entry_min_number'   => env('SEEDER_SEMINAR_ENTRY_MIN_NUMBER', 0),
    'seminar_entry_max_number'   => env('SEEDER_SEMINAR_ENTRY_MAX_NUMBER', 10),
    'user_assessment_min_number' => env('SEEDER_USER_ASSESSMENT_MIN_NUMBER', 0),
    'user_assessment_max_number' => env('SEEDER_USER_ASSESSMENT_MAX_NUMBER', 10),
    'user_pre_entry_min_number'  => env('SEEDER_USER_PRE_ENTRY_MIN_NUMBER', 0),
    'user_pre_entry_max_number'  => env('SEEDER_USER_PRE_ENTRY_MAX_NUMBER', 6),

    'company_number'                => env('SEEDER_COMPANY_NUMBER', 21),
    'company_profile_min_number'    => env('SEEDER_COMPANY_PROFILE_MIN_NUMBER', 6),
    'company_profile_max_number'    => env('SEEDER_COMPANY_PROFILE_MAX_NUMBER', 6),
    'seminar_min_number'            => env('SEEDER_SEMINAR_MIN_NUMBER', 0),
    'seminar_max_number'            => env('SEEDER_SEMINAR_MAX_NUMBER', 15),
    'seminar_tag_min_number'        => env('SEEDER_SEMINAR_TAG_MIN_NUMBER', 0),
    'seminar_tag_max_number'        => env('SEEDER_SEMINAR_TAG_MAX_NUMBER', 5),
    'seminar_jobtryout_min_number'  => env('SEEDER_SEMINAR_JOBTRYOUT_MIN_NUMBER', 1),
    'seminar_jobtryout_max_number'  => env('SEEDER_SEMINAR_JOBTRYOUT_MAX_NUMBER', 6),
    'seminar_detail_min_number'     => env('SEEDER_SEMINAR_DETAIL_MIN_NUMBER', 1),
    'seminar_detail_max_number'     => env('SEEDER_SEMINAR_DETAIL_MAX_NUMBER', 3),
    'seminar_detail_url_min_number' => env('SEEDER_SEMINAR_DETAIL_URL_MIN_NUMBER', 0),
    'seminar_detail_url_max_number' => env('SEEDER_SEMINAR_DETAIL_URL_MAX_NUMBER', 3),

    'content_min_number'         => env('SEEDER_CONTENT_MIN_NUMBER', 1),
    'content_max_number'         => env('SEEDER_CONTENT_MAX_NUMBER', 20),
    'content_tag_min_number'     => env('SEEDER_CONTENT_TAG_MIN_NUMBER', 0),
    'content_tag_max_number'     => env('SEEDER_CONTENT_TAG_MAX_NUMBER', 5),
    'feature_article_min_number' => env('SEEDER_FEATURE_ARTICLE_MIN_NUMBER', 0),
    'feature_article_max_number' => env('SEEDER_FEATURE_ARTICLE_MAX_NUMBER', 5),

    'template_min_number'     => env('SEEDER_TEMPLATE_MIN_NUMBER', 5),
    'template_max_number'     => env('SEEDER_TEMPLATE_MAX_NUMBER', 10),
    'notification_min_number' => env('SEEDER_NOTIFICATION_MIN_NUMBER', 0),
    'notification_max_number' => env('SEEDER_NOTIFICATION_MAX_NUMBER', 3),

];
