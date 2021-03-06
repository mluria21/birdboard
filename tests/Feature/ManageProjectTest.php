<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Project;
use Tests\Setup\ProjectFactory;

class ManageProjectTest extends TestCase
{
    use WithFaker,RefreshDatabase;

    /** @test */
    public function guest_cannot_control_projects()
    {
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');

        $this->post('/projects',$project->toArray())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(factory('App\User')->create());

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects',$attributes);

        $this->assertDatabaseHas('projects',$attributes);

        $this->get('/projects')->assertSee($attributes['title']);

        $this->get('/projects/create')->assertStatus(200);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = app(ProjectFactory::class)->create();

        $this->actingAs($project->owner)
            ->patch($project->path(),$attributes = ['notes' => 'changed','title' => 'changed','description' => 'changed'])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects',$attributes);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function a_user_cannot_view_others_projects()
    {
        //$this->withoutExceptionHandling();
        $this->be(factory('App\User')->create());

        $project = factory('App\Project')->create();
        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Project')->raw(['title' => '']);
        $this->post('/projects',$attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->actingAs(factory('App\User')->create());
        $attributes = factory('App\Project')->raw(['description' => '']);
        $this->post('/projects',$attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function only_owner_of_project_may_update_project()
    {
        //$this->withoutExceptionHandling();
        $this->signIn();

        $project = factory(Project::class)->create();

        $this->patch($project->path(),[])
            ->assertStatus(403);
    }
}
