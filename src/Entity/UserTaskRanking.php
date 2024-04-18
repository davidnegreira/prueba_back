<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="UserTaskRanking")
 */
class UserTaskRanking
{
/**
* @ORM\Id()
* @ORM\GeneratedValue()
* @ORM\Column(type="integer")
*/
private $id;

/**
* @ORM\Column(type="string", length=255)
*/
private $name;

/**
* @ORM\Column(type="string", length=255)
*/
private $username;

/**
* @ORM\Column(type="string", length=255)
*/
private $email;

/**
* @ORM\Column(type="integer")
*/
private $pendingTasks;

// Getters and setters
public function getId()
{
return $this->id;
}

public function getName()
{
return $this->name;
}

public function setName($name)
{
$this->name = $name;

return $this;
}

public function getUsername()
{
return $this->username;
}

public function setUsername($username)
{
$this->username = $username;

return $this;
}

public function getEmail()
{
return $this->email;
}

public function setEmail($email)
{
$this->email = $email;

return $this;
}

public function getPendingTasks()
{
return $this->pendingTasks;
}

public function setPendingTasks($pendingTasks)
{
$this->pendingTasks = $pendingTasks;

return $this;
}
}
