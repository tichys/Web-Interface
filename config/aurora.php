<?php
/**
 * Copyright (c) 2016 "Werner Maisl"
 *
 * This file is part of Aurorastation-Wi
 * Aurorastation-Wi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Register the phpbb auth provider
    |--------------------------------------------------------------------------
    |
    | If the phpbb auth provider should be registered.
    | This might need to be disabled for the initial migration of the database
    |
    */
    "registerPhpbbAuthProvider" => env('AURORA_REGISTER_PHPBB_AUTH_PROVIDER', true),

    /*
    |--------------------------------------------------------------------------
    | Enable the remember me token
    |--------------------------------------------------------------------------
    |
    | This is disabled by default, because it requires a additional
    | column in the phpbb_users table on the forum database.
    |
    */
    "enable_remember_me" => env('AURORA_ENABLE_REMEBER_ME', false),


    /*
    |--------------------------------------------------------------------------
    | Hash Passwords
    |--------------------------------------------------------------------------
    |
    | This needs to be disabled when testing the auth on php 7
    | Because phpbb 3.0 uses a hashing function that does not support php 7
    |
    */
    "hash_password" => env('AURORA_HASH_PASSWORD', false),


    /*
    |--------------------------------------------------------------------------
    | Token Expire Time
    |--------------------------------------------------------------------------
    |
    | The time in hours after which a sso login token should no longer be valid.
    |
    */
    "token_valid_time" => env('AURORA_TOKEN_VALID_TIME', 24),


    /*
    |--------------------------------------------------------------------------
    | Contract Update From-Name / From-Address
    |--------------------------------------------------------------------------
    |
    | The From Adress and Name that is shown in syndie contract updates
    |
    */
    "syndie_contract_from_name" => env('SYNDIE_CONTRACT_FROM_NAME', 'Aurora Station - Syndie Contracts'),
    "syndie_contract_from_address" => env('SYNDIE_CONTRACT_FROM_ADDRESS', 'syndiecontracts@aurorastation.org'),

    /*
    |--------------------------------------------------------------------------
    | Contract Moderator ID
    |--------------------------------------------------------------------------
    |
    | The id of the contract mod group
    |
    */
    "syndie_contract_mods_id" => env('SYNDIE_CONTRACT_MODS_ID', 5),
];