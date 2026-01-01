<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Group;
use App\Models\Level;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'type' => 'course',
            'quizlet_url' => null,
            'dark_version' => false,
            'cards' => false,
            'thanks' => 0,
            'published' => true,
            'pinned' => false,
            'slug' => $this->faker->unique()->slug(),
            'public' => 'yes',
            'course_id' => 1,
            'level_id' => 1,
            'user_id' => 1,
            'type_id' => 1,
            'group_id' => null,
            'school_id' => null,
            'downloads_total' => 0,
        ];
    }

    /**
     * Indicate that the post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published' => true,
        ]);
    }

    /**
     * Indicate that the post is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'published' => false,
        ]);
    }

    /**
     * Indicate that the post is pinned.
     */
    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'pinned' => true,
        ]);
    }
}
