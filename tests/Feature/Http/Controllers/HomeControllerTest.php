<?php

namespace Tests\Feature\Http\Controllers;

use App\Elephpant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_elephpants_on_the_home_page(): void
    {
        $elephpants = factory(Elephpant::class, 32)->create();

        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertViewHas('elephpants');

        $elephpantsInView = $response->viewData('elephpants');

        $this->assertContainsOnlyInstancesOf(Elephpant::class, $elephpantsInView);

        $this->assertCount(32, $elephpantsInView);

        foreach ($elephpants as $elephpant) {
            $response->assertSee($elephpant->image);
        }
    }
}
