<?php

/**
 * Copyright (c) 2016 'Werner Maisl', 'Sierra Brown'
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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\SiteRole;
use App\Models\SitePermission;

class CreatePermissionRolesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('wi')->create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::connection('wi')->create('permissions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::connection('wi')->create('permission_role', function(Blueprint $table)
        {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id','role_id']);
        });

        Schema::connection('wi')->create('role_user', function(Blueprint $table)
        {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id','user_id']);
        });

//        contract_moderate -> Allows to moderate Syndicate Contracts
        $contract_moderate = new SitePermission();
        $contract_moderate->name = "contract_moderate";
        $contract_moderate->label = "Allows to moderate Syndicate Contracts";
        $contract_moderate->save();

//        admin_menu_view -> Show the admin menu
        $admin_menu_view = new SitePermission();
        $admin_menu_view->name = "admin_menu_view";
        $admin_menu_view->label = "Show the admin menu";
        $admin_menu_view->save();

//        admin_whitelists_show -> Show the whitelists
        $admin_whitelists_show = new SitePermission();
        $admin_whitelists_show->name = "admin_whitelists_show";
        $admin_whitelists_show->label = "Show the whitelists";
        $admin_whitelists_show->save();

//        admin_whitelists_edit ->  Edit the whitelists
        $admin_whitelists_edit = new SitePermission();
        $admin_whitelists_edit->name = "admin_whitelists_edit";
        $admin_whitelists_edit->label = "Edit the whitelists";
        $admin_whitelists_edit->save();

//        admin_server_permissions_show -> Show the permissions on the server
        $admin_server_permissions_show = new SitePermission();
        $admin_server_permissions_show->name = "admin_server_permissions_show";
        $admin_server_permissions_show->label = "Show the permissions on the server";
        $admin_server_permissions_show->save();

//        admin_server_permissions_edit -> Edit the permissions on the server
        $admin_server_permissions_edit = new SitePermission();
        $admin_server_permissions_edit->name = "admin_server_permissions_edit";
        $admin_server_permissions_edit->label = "Edit the permissions on the server";
        $admin_server_permissions_edit->save();

//        admin_staff_roster_show -> Show the staff roster
        $admin_staff_roster_show = new SitePermission();
        $admin_staff_roster_show->name = "admin_staff_roster_show";
        $admin_staff_roster_show->label = "Show the staff roster";
        $admin_staff_roster_show->save();

//        admin_staff_roster_edit -> Edit the staff roster
        $admin_staff_roster_edit = new SitePermission();
        $admin_staff_roster_edit->name = "admin_staff_roster_edit";
        $admin_staff_roster_edit->label = "Edit the staff roster";
        $admin_staff_roster_edit->save();

//        admin_character_records_show -> Show the Character Records
        $admin_character_records_show = new SitePermission();
        $admin_character_records_show->name = "admin_character_records_show";
        $admin_character_records_show->label = "Show the Character Records";
        $admin_character_records_show->save();

//        admin_character_records_edit -> Edit the Character Records
        $admin_character_records_edit = new SitePermission();
        $admin_character_records_edit->name = "admin_character_records_edit";
        $admin_character_records_edit->label = "Allows to moderate Syndicate Contracts";
        $admin_character_records_edit->save();

//        admin_do_recorder_logs_show -> Show the DO recorder logs
        $admin_do_recorder_logs_show = new SitePermission();
        $admin_do_recorder_logs_show->name = "admin_do_recorder_logs_show";
        $admin_do_recorder_logs_show->label = "Show the DO recorder logs";
        $admin_do_recorder_logs_show->save();

//        admin_server_stats_show -> Show the admin stats
        $admin_server_stats_show = new SitePermission();
        $admin_server_stats_show->name = "admin_server_stats_show";
        $admin_server_stats_show->label = "Show the admin stats";
        $admin_server_stats_show->save();

//        admin_site_roles_show -> Show the site roles
        $admin_site_roles_show = new SitePermission();
        $admin_site_roles_show->name = "admin_site_roles_show";
        $admin_site_roles_show->label = "Show the site roles";
        $admin_site_roles_show->save();

//        admin_site_roles_edit -> Edit the site roles
        $admin_site_roles_edit = new SitePermission();
        $admin_site_roles_edit->name = "admin_site_roles_edit";
        $admin_site_roles_edit->label = "Edit the site roles";
        $admin_site_roles_edit->save();

//        admin_site_permissions_show -> Show the site permissions
        $admin_site_permissions_show = new SitePermission();
        $admin_site_permissions_show->name = "admin_site_permissions_show";
        $admin_site_permissions_show->label = "Show the site permissions";
        $admin_site_permissions_show->save();

//        admin_site_permissions_edit -> Edit the site permissions
        $admin_site_permissions_edit = new SitePermission();
        $admin_site_permissions_edit->name = "admin_site_permissions_edit";
        $admin_site_permissions_edit->label = "Edit the site permissions";
        $admin_site_permissions_edit->save();

//        admin_site_logs_show -> Show the site logs
        $admin_site_logs_show = new SitePermission();
        $admin_site_logs_show->name = "admin_site_logs_show";
        $admin_site_logs_show->label = "Show the site logs";
        $admin_site_logs_show->save();

//        admin_server_logs_show -> Show the server logs
        $admin_server_logs_show = new SitePermission();
        $admin_server_logs_show->name = "admin_server_logs_show";
        $admin_server_logs_show->label = "Show the server logs";
        $admin_server_logs_show->save();

//        admin_forms_show -> Show the corporate Form Database
        $admin_forms_show = new SitePermission();
        $admin_forms_show->name = "admin_forms_show";
        $admin_forms_show->label = "Show the corporate Form Database";
        $admin_forms_show->save();

//        admin_forms_edit -> Edit the corporate Form Database
        $admin_forms_edit = new SitePermission();
        $admin_forms_edit->name = "admin_forms_edit";
        $admin_forms_edit->label = "Edit the corporate Form Database";
        $admin_forms_edit->save();

//        admin_players_show -> Show the player page (Alone Useless -> Needs some of the perms below)
        $admin_players_show = new SitePermission();
        $admin_players_show->name = "admin_players_show";
        $admin_players_show->label = "Show the player page";
        $admin_players_show->save();

//        admin_warnings_show -> Show a players warnings
        $admin_warnings_show = new SitePermission();
        $admin_warnings_show->name = "admin_warnings_show";
        $admin_warnings_show->label = "Show a players warnings";
        $admin_warnings_show->save();

//        admin_warnings_edit -> Edit a players warnings
        $admin_warnings_edit = new SitePermission();
        $admin_warnings_edit->name = "admin_warnings_edit";
        $admin_warnings_edit->label = "Edit a players warnings";
        $admin_warnings_edit->save();

//        admin_notes_show -> Show a players notes
        $admin_notes_show = new SitePermission();
        $admin_notes_show->name = "admin_notes_show";
        $admin_notes_show->label = "Show a players notes";
        $admin_notes_show->save();

//        admin_notes_edit -> Edit a players notes
        $admin_notes_edit = new SitePermission();
        $admin_notes_edit->name = "admin_notes_edit";
        $admin_notes_edit->label = "Edit a players notes";
        $admin_notes_edit->save();

//        admin_do_actions_show -> Show the DO Actions
        $admin_do_actions_show = new SitePermission();
        $admin_do_actions_show->name = "admin_do_actions_show";
        $admin_do_actions_show->label = "Show the current DO Actions";
        $admin_do_actions_show->save();

//        admin_do_actions_edit -> Edit the DO Actions
        $admin_do_actions_edit = new SitePermission();
        $admin_do_actions_edit->name = "admin_do_actions_edit";
        $admin_do_actions_edit->label = "Edit the DO Actions";
        $admin_do_actions_edit->save();
        
        // ccia_general_notice_edit -> Edit the CCIA General Notice list
        $ccia_general_notice_edit = new SitePermission();
        $ccia_general_notice_edit->name = "ccia_general_notice_edit";
        $ccia_general_notice_edit->label = "Edit the CCIA General Notice list";
        $ccia_general_notice_edit->save();


        $administrators = new SiteRole();
        $administrators->name = "administrators";
        $administrators->label = "Administrators";
        $administrators->description = "Can do everything";
        $administrators->save();

        $administrators->givePermissionTo($contract_moderate);
        $administrators->givePermissionTo($admin_menu_view);
        $administrators->givePermissionTo($admin_server_permissions_show);
        $administrators->givePermissionTo($admin_server_permissions_edit);
        $administrators->givePermissionTo($admin_staff_roster_show);
        $administrators->givePermissionTo($admin_staff_roster_edit);
        $administrators->givePermissionTo($admin_character_records_show);
        $administrators->givePermissionTo($admin_character_records_edit);
        $administrators->givePermissionTo($admin_do_recorder_logs_show);
        $administrators->givePermissionTo($admin_server_stats_show);
        $administrators->givePermissionTo($admin_site_roles_show);
        $administrators->givePermissionTo($admin_site_roles_edit);
        $administrators->givePermissionTo($admin_site_permissions_show);
        $administrators->givePermissionTo($admin_site_permissions_edit);
        $administrators->givePermissionTo($admin_site_logs_show);
        $administrators->givePermissionTo($admin_server_logs_show);
        $administrators->givePermissionTo($admin_forms_show);
        $administrators->givePermissionTo($admin_forms_edit);
        $administrators->givePermissionTo($admin_players_show);
        $administrators->givePermissionTo($admin_whitelists_show);
        $administrators->givePermissionTo($admin_whitelists_edit);
        $administrators->givePermissionTo($admin_warnings_show);
        $administrators->givePermissionTo($admin_warnings_edit);
        $administrators->givePermissionTo($admin_notes_show);
        $administrators->givePermissionTo($admin_notes_edit);
        $administrators->givePermissionTo($admin_do_actions_show);
        $administrators->givePermissionTo($admin_do_actions_edit);
        $administrators->givePermissionTo($ccia_general_notice_edit);

        $moderators = new SiteRole();
        $moderators->name = "moderators";
        $moderators->label = "Moderators";
        $moderators->description = "Like a admin but not as powerful";
        $moderators->save();

        $moderators->givePermissionTo($contract_moderate);
        $moderators->givePermissionTo($admin_menu_view);
        $moderators->givePermissionTo($admin_staff_roster_show);
        $moderators->givePermissionTo($admin_character_records_show);
        $moderators->givePermissionTo($admin_server_stats_show);
        $moderators->givePermissionTo($admin_site_roles_show);
        $moderators->givePermissionTo($admin_site_permissions_show);
        $moderators->givePermissionTo($admin_forms_show);
        $moderators->givePermissionTo($admin_players_show);
        $moderators->givePermissionTo($admin_whitelists_show);
        $moderators->givePermissionTo($admin_warnings_show);
        $moderators->givePermissionTo($admin_warnings_edit);
        $moderators->givePermissionTo($admin_notes_show);
        $moderators->givePermissionTo($admin_notes_edit);

        $duty_offiers = new SiteRole();
        $duty_offiers->name = "duty_officers";
        $duty_offiers->label = "Duty Officers";
        $duty_offiers->description = "Something between a moderator and a paper pusher";
        $duty_offiers->save();

        $duty_offiers->givePermissionTo($admin_menu_view);
        $duty_offiers->givePermissionTo($admin_character_records_show);
        $duty_offiers->givePermissionTo($admin_character_records_edit);
        $duty_offiers->givePermissionTo($admin_do_recorder_logs_show);
        $duty_offiers->givePermissionTo($admin_forms_show);
        $duty_offiers->givePermissionTo($admin_forms_edit);
        $duty_offiers->givePermissionTo($admin_players_show);
        $duty_offiers->givePermissionTo($admin_whitelists_show);
        $duty_offiers->givePermissionTo($admin_do_actions_show);
        $duty_offiers->givePermissionTo($admin_do_actions_edit);
        $duty_offiers->givePermissionTo($ccia_general_notice_edit);

        $whitelist_managers = new SiteRole();
        $whitelist_managers->name = "whitelist_managers";
        $whitelist_managers->label = "Whitelist Managers";
        $whitelist_managers->description = "Manages the White-Lists";
        $whitelist_managers->save();

        $whitelist_managers->givePermissionTo($admin_menu_view);
        $whitelist_managers->givePermissionTo($admin_players_show);
        $whitelist_managers->givePermissionTo($admin_whitelists_show);
        $whitelist_managers->givePermissionTo($admin_whitelists_edit);

        $contract_managers = new Siterole();
        $contract_managers->name = "contract_managers";
        $contract_managers->label = "Contract Managers";
        $contract_managers->description = "Manages the Syndicate Contracts";
        $contract_managers->save();

        $contract_managers->givePermissionTo($contract_moderate);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('wi')->drop('permission_role');
        Schema::connection('wi')->drop('role_user');
        Schema::connection('wi')->drop('roles');
        Schema::connection('wi')->drop('permissions');
    }
}
