<?php
/**
 * Copyright (c) 2016-2018 "Werner Maisl"
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
    | The id of the contract mod role
    |
    */
    "role_syndie_contract_mods_id" => env('SYNDIE_CONTRACT_MODS_ID', 5),


    /*
    |--------------------------------------------------------------------------
    | Game Server Address
    |--------------------------------------------------------------------------
    |
    | Address of the Server
    |
    */
    "gameserver_address" => env('GAMESERVER_ADDRESS', NULL),

    /*
    |--------------------------------------------------------------------------
    | Game Server Port
    |--------------------------------------------------------------------------
    |
    | Address of the Server
    |
    */
    "gameserver_port" => env('GAMESERVER_PORT', NULL),

    /*
    |--------------------------------------------------------------------------
    | Game Server Auth Key
    |--------------------------------------------------------------------------
    |
    | Auth Key for the Game Server
    |
    */
    "gameserver_auth" => env('GAMESERVER_AUTH', NULL),

    /*
    |--------------------------------------------------------------------------
    | Log Server Address
    |--------------------------------------------------------------------------
    |
    | Auth Key for the Game Server
    |
    */
    "logserver_address" => env('LOGSERVER_ADDRESS', NULL),

    /*
    |--------------------------------------------------------------------------
    | Group Mapping
    |--------------------------------------------------------------------------
    |
    | Maps a IPB Group to a WI Role
    |
    */
    "group_mappings" => [
        4=>1,   //Forum: Forum Admins,            WI: administrators
        8=>1,   //Forum: Server Administrators,   WI: administrators
        5=>2,   //Forum: Moderators,   WI: moderators
        13=>3,   //Forum: CCIA Agents,   WI: duty officers
        16=>3,   //Forum: CCIA Leader,   WI: duty officers
        11=>4,  //Forum: Server Administrators,   WI: whitelist_managers
        10=>6,   //Forum: Server Administrators,   WI: lore_writers
        17=>6,   //Forum: Server Administrators,   WI: lore_writers
        9=>8,   //Forum: Server Administrators,   WI: developers
    ],
];