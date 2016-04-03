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
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SyndieContract extends Model
{
    use SoftDeletes;

    protected $connection = 'server';
    protected $table = 'syndie_contracts';
    protected $fillable = ['contractee_name', 'title', 'description', 'reward_credits', 'reward_other'];
    protected $primaryKey = 'contract_id';
    protected $dates = ['deleted_at'];

    /**
     * Returns an Array of all the users subscribed to the contract
     *
     * @return A Array with the user_id´s subscribed to the contract
     */
    public function get_subscribers()
    {
        $users = DB::connection($this->connection)->table('syndie_contracts_subscribers')->where('contract_id', $this->contract_id)->pluck('user_id');
        return $users;
    }

    /**
     * Checks weather a user is subscribed to the contract or not
     *
     * @param $user_id The userid the subscribed status should be returned for
     *
     * @return boolean true if the user is subscribed false if not
     */
    public function is_subscribed($user_id)
    {
        $count = DB::connection($this->connection)->table('syndie_contracts_subscribers')->where('contract_id', $this->contract_id)->where('user_id', $user_id)->count();
        if ($count > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Adds subscribers to the contract
     *
     * @param $subscribers A array or integer with the id of the users that should be subscribed to the contract
     */
    public function add_subscribers($subscribers)
    {
        //Check if its an array or a integer
        if (is_array($subscribers)) {
            //Generate array with insert data
            $query_data = array();
            foreach ($subscribers as $subscriber) {
                $query_data[] = ['contract_id' => $this->contract_id, 'user_id' => $subscriber];
            }
            DB::connection($this->connection)->table('syndie_contracts_subscribers')->insert($query_data);
        } else {
            //Insert directly into the db
            DB::connection($this->connection)->table('syndie_contracts_subscribers')->insert(['contract_id' => $this->contract_id, 'user_id' => $subscribers]);
        }
    }

    /**
     * Removes subscribers from the contract
     *
     * @param $subscribers A array or ineger with the id of the users that should be unsubscribed from the contract
     */
    public function remove_subscribers($subscribers)
    {
        //Check if its an array or a integer
        if (is_array($subscribers)) {
            DB::connection($this->connection)->table('syndie_contracts_subscribers')->where('contract_id', $this->contract_id)->whereIn('user_id', $subscribers)->delete();
        } else {
            //Insert directly into the db
            DB::connection($this->connection)->table('syndie_contracts_subscribers')->where('contract_id', $this->contract_id)->where('user_id', $subscribers)->delete();
        }
    }
}
