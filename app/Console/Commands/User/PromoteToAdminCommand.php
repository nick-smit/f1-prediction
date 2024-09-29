<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Actions\Authorization\PromoteUserToAdmin;
use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Prompts\SearchPrompt;

class PromoteToAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:promote-to-admin {user-id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PromoteUserToAdmin $action): int
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            $this->error('The specified user was not found.');
            return Command::FAILURE;
        }

        if (!$this->confirm(sprintf('Are you sure you want to make %s <%s> an administrator?', $user->name, $user->email))) {
            $this->error('Action cancelled due to user input');
            return Command::FAILURE;
        }

        $action->handle($user);

        $this->info('User is successfully promoted to an administrator!');

        return Command::SUCCESS;
    }

    private function getUser(): ?User
    {
        $userId = $this->argument('user-id');

        if ($userId === null) {
            $search = new SearchPrompt(
                'Please select a user',
                function (string $value) {
                    if ($value === '') {
                        // @codeCoverageIgnoreStart
                        return [];
                        // @codeCoverageIgnoreEnd
                    }

                    return User::query()->whereLike('email', sprintf('%%%s%%', $value))
                        ->orWhereLike('name', sprintf('%%%s%%', $value))
                        ->get()
                        ->mapWithKeys(function (User $user) {
                            $concat = sprintf('%s <%s>', $user->name, $user->email);
                            return [$user->id => $concat];
                        })->all();
                },
                scroll: 10
            );

            $userId = $search->prompt();
        }

        return User::whereId($userId)->first();
    }
}
