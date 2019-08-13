<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApiController extends Command
{
    private $db;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->db = Db::getConnection();
        parent::__construct();
    }


    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('get-users')
            ->setDescription('Get users from guthub.')
        ;
    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln([
            'Parse users...',
            '',
        ]);

        $json = $this->request();
        $user = new User();

        $result = $user->saveUser($this->db, json_decode($json, true));
        if ($result !== false) {
            $message = 'Users with IDs ' . json_encode($result) . ' has added to database.';
        } else {
            $message = 'Users with these IDs have already been added to the database.';
        }

        $output->write($message);

        $output->writeln([
            '',
            '',
            'Done!',
        ]);
    }

    /**
     * @return mixed
     */
    private function request(): string
    {
        $ch = curl_init('https://api.github.com/users');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36');
        curl_setopt($ch, CURLOPT_HEADER, false);
        $json = curl_exec($ch);
        curl_close($ch);

        return $json;
    }

}