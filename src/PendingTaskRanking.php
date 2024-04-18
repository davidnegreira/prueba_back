<?php

namespace App;

use App\Entity\UserTaskRanking;
use Doctrine\ORM\EntityManagerInterface;

class PendingTaskRanking
{

    private $base_url = "https://jsonplaceholder.typicode.com";
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function connectAndSaveRanking() {
        $ranking = $this->getPendingTasksRanking();
        $this->saveRankingToDatabase($ranking);
    }

    private function getTasks()
    {
        $url = $this->base_url . "/todos";
        return $this->makeRequest($url);
    }

    private function getUsers()
    {
        $url = $this->base_url . "/users";
        return $this->makeRequest($url);
    }

    private function makeRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error al realizar la solicitud: ' . curl_error($ch);
            exit;
        }
        $data = json_decode($response, true);
        curl_close($ch);
        return $data;
    }

    private function getPendingTasksRanking()
    {
        $users = $this->getUsers();
        $tasks = $this->getTasks();

        $pendingTasksCount = array();
        foreach ($users as $user) {
            $pendingTasksCount[$user['id']] = 0;
        }

        foreach ($tasks as $task) {
            if (!$task['completed']) {
                $pendingTasksCount[$task['userId']]++;
            }
        }

        arsort($pendingTasksCount);

        $ranking = array();
        foreach ($pendingTasksCount as $userId => $count) {
            $user = $this->getUserById($userId, $users);
            $ranking[] = array(
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'pending_tasks' => $count
            );
        }

        return $ranking;
    }

    private function getUserById($userId, $users)
    {
        foreach ($users as $user) {
            if ($user['id'] == $userId) {
                return $user;
            }
        }
        return null;
    }

    private function saveRankingToDatabase($ranking)
    {
        foreach ($ranking as $userData) {
            $userRanking = new UserTaskRanking();

            $userRanking->setName($userData['name']);
            $userRanking->setUsername($userData['username']);
            $userRanking->setEmail($userData['email']);
            $userRanking->setPendingTasks($userData['pending_tasks']);

            $this->entityManager->persist($userRanking);
        }

        $this->entityManager->flush();
    }
}
?>
