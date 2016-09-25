<?php
namespace Ovpn\Entity;

interface UsersInterface {

    /**
     * Get User Id
     *
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param $id
     * @return UsersInterface
     */
    public function getInstance($id);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return Roles
     */
    public function getRole();
    
}