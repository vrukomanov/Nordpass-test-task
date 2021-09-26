<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getByID($id): Item
    {
        $itemRepository = $this->entityManager->getRepository(Item::class);
        $item = $itemRepository->find($id);

        if(!($item instanceof Item)){
            return false;
        }

        return $item;
    }

    public function create(User $user, string $data): bool
    {
        if(!$data){
            return false;
        }

        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return true;
    }

    public function update(int $id, User $user, string $data): bool
    {
        $item = $this->getByID($id);

        if(!$item){
            return false;
        }

        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return true;
    }

    public function delete(int $id): bool
    {
        $item = $this->getByID($id);

        if(!$item){
            return false;
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();

        return true;
    }
} 