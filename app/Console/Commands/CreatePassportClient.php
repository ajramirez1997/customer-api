<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Passport\Client;

class CreatePassportClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-passport-client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Passport client for machine-to-machine communication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Create a new Passport client
            $client = new Client();
            $client->name = 'Personal'; // Name of your client
            $client->redirect = ''; // Redirect URI if needed (can be empty for machine-to-machine communication)
            $client->personal_access_client = false;
            $client->password_client = true;
            $client->revoked = false;
            $client->save();

            // Print out the client ID and secret
            $this->info("Client ID: " . $client->id);
            $this->info("Client Secret: " . $client->secret);
        } catch (\Exception $e) {
            // Handle any exceptions
            $this->error("An error occurred while creating the Passport client: {$e->getMessage()}");
        }
    }
}
