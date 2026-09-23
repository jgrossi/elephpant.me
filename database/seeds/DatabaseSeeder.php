<?php

use App\Message;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name'         => 'John Doe',
            'email'        => 'john@example.com',
            'x_handle'     => 'john',
            'country_code' => 'USA',
            'password'     => \Illuminate\Support\Facades\Hash::make('secret'),
            'mastodon'     => '@john@elephpant.me',
            'bluesky'      => '@john.bsky.social',
        ]);

        User::factory(50)->create();

        \Illuminate\Support\Facades\Artisan::call('elephpants:read');

        $me = \App\User::find(1);
        $elephpants = \App\Elephpant::inRandomOrder()->limit(15)->get();
        foreach ($elephpants as $elephpant) {
            $me->elephpants()->attach($elephpant->id, ['quantity' => rand(2, 4)]);
        }

        /** @var \Illuminate\Database\Eloquent\Collection $users */
        $users = \App\User::inRandomOrder()->where('id', '<>', 1)->limit(rand(10, 15))->get();

        foreach ($users as $user) {
            $elephpants = \App\Elephpant::inRandomOrder()->limit(rand(5, 10))->get();
            foreach ($elephpants as $elephpant) {
                $user->elephpants()->attach($elephpant->id, ['quantity' => rand(1, 3)]);
            }
        }

        $this->seedMessages($me);
    }

    private function seedMessages(User $me): void
    {
        $partners = User::query()->whereKeyNot($me->id)->inRandomOrder()->limit(8)->get();
        $now = Carbon::now()->startOfMinute();

        foreach ($partners as $index => $partner) {
            $day = $now->copy()->subDays(count($partners) - $index);

            // Burst of quick messages from John (same minute → one group).
            $this->createMessage($me, $partner, 'Hey, still interested in a trade?', $day->copy()->setTime(9, 0, 0));
            $this->createMessage($me, $partner, 'I have a couple of doubles if that helps.', $day->copy()->setTime(9, 0, 20));
            $this->createMessage(
                $me,
                $partner,
                "Happy to cover postage both ways. Just let me know which ones you're after and we can sort the rest.",
                $day->copy()->setTime(9, 0, 40),
            );

            // Reply from the partner (same minute → one group).
            $this->createMessage(
                $partner,
                $me,
                "Yes! I'd love to trade. Which ones are you missing from your collection?",
                $day->copy()->setTime(10, 15, 0),
            );
            $this->createMessage(
                $partner,
                $me,
                'I can send photos of condition if useful before we commit.',
                $day->copy()->setTime(10, 15, 30),
            );

            // Different minute from the same sender → new group with a fresh timestamp.
            $this->createMessage(
                $partner,
                $me,
                'Still keen if you are around — happy to wait either way.',
                $day->copy()->setTime(10, 16, 0),
            );

            // Closing reply from John later in the day.
            $this->createMessage(
                $me,
                $partner,
                "Perfect — let's do it. I'll put a parcel together this evening and share tracking once it's out.",
                $day->copy()->setTime(16, 45, 0),
            );
        }
    }

    private function createMessage(User $sender, User $receiver, string $body, Carbon $at): void
    {
        Message::query()->create([
            'sender_id'   => $sender->id,
            'receiver_id' => $receiver->id,
            'message'     => $body,
            'created_at'  => $at,
            'updated_at'  => $at,
        ]);
    }
}
