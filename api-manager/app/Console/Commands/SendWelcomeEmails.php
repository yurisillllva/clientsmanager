<?php

namespace App\Console\Commands;

use App\Models\Client;
use Illuminate\Console\Command;
use App\Console\Commands\Mail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail as FacadesMail;

class SendWelcomeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:welcome-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Client::where('created_at', '>=', now()->subMinutes(30))
        ->where('welcome_email_sent', false)
        ->chunk(200, function ($clients) { 
            foreach ($clients as $client) {
                FacadesMail::to($client->email)->send(new WelcomeEmail($client->name, $client->email));
                $client->update(['welcome_email_sent' => true]);
            }
        });
    }
}
