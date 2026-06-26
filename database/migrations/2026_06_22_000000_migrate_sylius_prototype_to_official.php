<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateSyliusPrototypeToOfficial extends Migration
{
    private const PROTOTYPE_ELEPHPANT_ID = 55;

    private const OFFICIAL_ELEPHPANT_ID = 91;

    private const PROTOTYPE_OWNER_USERNAME = 'jacquesbh';

    /**
     * Move Sylius prototype (#55) to Sylius Green official (#91) for all users
     * except the prototype owner (username jacquesbh).
     *
     * @return void
     */
    public function up()
    {
        $ownerUserId = DB::table('users')
            ->where('username', self::PROTOTYPE_OWNER_USERNAME)
            ->value('id');

        DB::transaction(function () use ($ownerUserId) {
            $prototypeRows = DB::table('elephpant_user')
                ->where('elephpant_id', self::PROTOTYPE_ELEPHPANT_ID)
                ->when($ownerUserId, function ($query) use ($ownerUserId) {
                    return $query->where('user_id', '!=', $ownerUserId);
                })
                ->orderBy('user_id')
                ->get();

            $now = now();

            foreach ($prototypeRows as $row) {
                $existingOfficial = DB::table('elephpant_user')
                    ->where('user_id', $row->user_id)
                    ->where('elephpant_id', self::OFFICIAL_ELEPHPANT_ID)
                    ->first();

                if ($existingOfficial) {
                    DB::table('elephpant_user')
                        ->where('user_id', $row->user_id)
                        ->where('elephpant_id', self::OFFICIAL_ELEPHPANT_ID)
                        ->update([
                            'quantity' => $existingOfficial->quantity + $row->quantity,
                            'updated_at' => $now,
                        ]);
                } else {
                    DB::table('elephpant_user')->insert([
                        'user_id' => $row->user_id,
                        'elephpant_id' => self::OFFICIAL_ELEPHPANT_ID,
                        'quantity' => $row->quantity,
                        'created_at' => $row->created_at ?? $now,
                        'updated_at' => $now,
                    ]);
                }

                DB::table('elephpant_user')
                    ->where('user_id', $row->user_id)
                    ->where('elephpant_id', self::PROTOTYPE_ELEPHPANT_ID)
                    ->delete();
            }
        });
    }

    /**
     * Reversal is not possible without a database backup.
     *
     * @return void
     */
    public function down()
    {
    }
}
