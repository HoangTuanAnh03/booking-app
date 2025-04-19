<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Collection;

interface IUserRepository
{

    /**
     * Get a user by their ID.
     *
     * @param string $id The UUID of the user to retrieve
     * @return User|null Returns the user if found, or null otherwise
     */
    public function getById(string $id): ?User;

    /**
     * Get a user by their email address.
     *
     * @param string $email The email address of the user to retrieve
     * @return User|null Returns the user if found, or null otherwise
     */
    public function getByEmail(string $email): ?User;

    /**
     * Get an active user by their email address.
     *
     * @param string $email The email address of the active user to retrieve
     * @return User|null Returns the active user if found, or null otherwise
     */
    public function getByEmailAndActive(string $email): ?User;

    /**
     * Create a new user.
     *
     * @param array $data The data to create the user with
     * @return User Returns the newly created user
     */
    public function store(array $data): User;

    /**
     * Update an existing user.
     *
     * @param array $data The data to update the user with
     * @param string $id The UUID of the user to update
     * @return User Returns the updated user
     */
    public function update(array $data, string $id): User;

    /**
     * Delete a user.
     *
     * @param string $id The UUID of the user to delete
     * @return bool Returns true if the user was successfully deleted, false otherwise
     */
    public function delete(string $id): bool;
    public function getUsers(array $roles): Collection;
    public function upRole(string $userId): User;
}


