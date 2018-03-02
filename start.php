<?php

require_once 'lib/functions.php';

/**
 * Initialize plugin
 */
function plugin_init()
{

    // Expose create function

    elgg_ws_expose_function(
        'user.create',
        'user_create',
        [
            'username' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Username of new user'
            ],
            'password' => [
                "type" => 'string',
                'required' => true,
                'description' => 'Password of new user'
            ],
            'displayName' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Name of new user'
            ],
            'email' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Email of new user'
            ],
            'language' => [
                'type' => 'string',
                'default' => 'en',
                'description' => 'Language for new user'
            ],
            'profileManagerJson' => [
                'type' => 'string',
                'required' => false,
                'description' => 'A JSON encoded object with values for fields of the profile_manager plugin'
            ],
            'notifyUser' => [
                'type' => 'boolean',
                'default' => false,
                'description' => 'Notify the new user upon registration'
            ],
            'avatar' => [
                'type' => 'string',
                'required' => false,
                'description' => 'The base64-encoded avatar of the user'
            ]
        ],
        'Create a new user',
        'POST',
        false,
        true,
        true
    );

    /**
     * Expose delete function
     */
    elgg_ws_expose_function(
        'user.delete',
        'user_delete',
        [
            'username' => [
                'type' => 'string',
                'required' => true,
                'description' => 'Username of the user to delete'
            ]
        ],
        'Delete a user',
        'POST',
        false,
        true,
        false
    );
}

elgg_register_event_handler('init', 'system', 'plugin_init');
