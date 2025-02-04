<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\ShortUrl;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if authenticated user can create a short URL
     *
     * @test
     */
    public function authenticated_user_can_create_short_url()
    {
        // Create a user and log them in
        $user = User::factory()->create();
        $this->actingAs($user);

        // Define the long URL to shorten
        $longUrl = 'https://www.example.com';

        // Make the POST request to create the short URL
        $response = $this->post(route('shorturl.store'), [
            'long_url' => $longUrl
        ]);

        // Assert that the short URL was created in the database
        $this->assertDatabaseHas('short_urls', [
            'long_url' => $longUrl,
            'user_id' => $user->id,
        ]);

        // Assert the response is a redirect back with a success message
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * Test if user cannot create a duplicate short URL
     *
     * @test
     */
    public function user_cannot_create_duplicate_short_url()
    {
        // Create a user and log them in
        $user = User::factory()->create();
        $this->actingAs($user);

        // Define the long URL to shorten
        $longUrl = 'https://www.example.com';

        // Create the first short URL
        $this->post(route('shorturl.store'), [
            'long_url' => $longUrl
        ]);

        // Try creating the same short URL again
        $response = $this->post(route('shorturl.store'), [
            'long_url' => $longUrl
        ]);

        // Assert that the database still contains only one short URL
        $this->assertDatabaseCount('short_urls', 1);

        // Assert that the response contains the error message
        $response->assertSessionHas('error', 'Short URL already exists: ' . url('/s/' . ShortUrl::first()->short_code));
    }

    /**
     * Test if short URL redirects to the long URL
     *
     * @test
     */
    public function short_url_redirects_to_long_url()
    {
        // Create a user and log them in
        $user = User::factory()->create();
        $this->actingAs($user);

        // Define the long URL to shorten
        $longUrl = 'https://www.example.com';

        // Create the short URL
        $response = $this->post(route('shorturl.store'), [
            'long_url' => $longUrl
        ]);

        // Get the short URL
        $shortUrl = ShortUrl::first();

        // Make a GET request to the short URL route
        $response = $this->get('/s/' . $shortUrl->short_code);

        // Assert that it redirects to the original long URL
        $response->assertRedirect($longUrl);
    }

    /**
     * Test that guest users cannot create short URLs
     *
     * @test
     */
    public function guest_user_cannot_create_short_url()
    {
        // Make a POST request without being authenticated
        $response = $this->post(route('shorturl.store'), [
            'long_url' => 'https://www.example.com'
        ]);

        // Assert that the user is redirected to the login page
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that admins can view their short URLs
     *
     * @test
     */
    public function admin_can_view_their_short_urls()
    {
        // Create an admin user and log them in
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create a short URL for the admin user
        $longUrl = 'https://www.example.com';
        $this->post(route('shorturl.store'), [
            'long_url' => $longUrl
        ]);

        // Make a GET request to the dashboard
        $response = $this->get(route('dashboard'));

        // Assert that the response contains the short URL
        $response->assertSee(url('/s/' . ShortUrl::first()->short_code));
    }
}
