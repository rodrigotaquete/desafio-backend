<?php

/**
 * @OA\Schema(
 *     title="Usuário",
 *     description="Modelo de Usuário",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="Cõdigo do usuário",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

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
     *     title="Data de nascimento",
     *     description="Data de nascimento do usuário",
     *     example="1982-01-18",
     *     format="date",
     *     type="string"    
     * )
     *
     * @var \DateTime
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

    /**
     * @OA\Property(
     *     title="Data de cadastro",
     *     description="Data de cadastro do registro",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"    
     * )
     *
     * @var \DateTime
     */
    private $created_at;

    /**
     * @OA\Property(
     *     title="Data de alteração",
     *     description="Data de alteração do registro",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $updated_at;

}