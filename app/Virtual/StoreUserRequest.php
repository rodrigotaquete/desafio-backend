<?php

/**
 * @OA\Schema(
 *      title="Solicitação de cadastro de usuário",
 *      description="Armazenar dados do corpo da solicitação de usuário",
 *      type="object",
 *      required={"name","email","password"}
 * )
 */
class StoreUserRequest 
{

    /**
     * @OA\Property(
     *      title="Nome",
     *      description="Nome do usuário",
     *      example="Rodrigo Taquete"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Email de login do usuário",
     *      example="rodrigo@taquete.com.br"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Senha",
     *      description="Senha de login do usuário",
     * )
     *
     * @var string
     */
    public $password;

    /**
     * @OA\Property(
     *      title="Data de nascimento",
     *      description="Data de nascimento do usuário",
     *      example="1982-01-18",
     *      format="date",
     *      type="string"    
     * )
     *
     * @var string
     */
    private $birthday;

    /**
     * @OA\Property(
     *      title="Saldo Inicial",
     *      description="Valor do saldo inicial do usuário",
     * )
     *
     * @var number
     */
    private $opening_balance;
    
}