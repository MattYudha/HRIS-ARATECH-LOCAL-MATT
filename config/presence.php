<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Presence Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for the presence/attendance system
    |
    */

    // Office location coordinates
    'office_latitude' => env('PRESENCE_OFFICE_LAT', -6.3623194),
    'office_longitude' => env('PRESENCE_OFFICE_LON', 106.6476751),

    // Location radius in meters (default: 1000m)
    'location_radius' => env('PRESENCE_LOCATION_RADIUS', 1000),

    // Allowed WiFi SSIDs for WFO mode
    'allowed_ssids' => [
        'UNPAM VIKTOR',
        'Serhan 2',
        'Serhan',
        'S53s',
    ],

    // Standard work hours
    'work_start_time' => env('PRESENCE_WORK_START', '08:00'),
    'work_end_time' => env('PRESENCE_WORK_END', '17:00'),

    // Late check-in threshold in minutes (default: 15 minutes)
    'late_threshold_minutes' => env('PRESENCE_LATE_THRESHOLD', 15),

    // Check-in/check-out validation
    'require_check_in_before_checkout' => env('PRESENCE_REQUIRE_CHECKIN', true),
];

